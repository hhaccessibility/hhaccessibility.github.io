<?php namespace App\Http\Controllers;

use App\BaseUser;
use App\QuestionCategory;
use App\Country;
use App\AnswerRepository;
use App\Region;
use App\Http\Controllers\ProfilePhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;

class ProfileController extends \Illuminate\Routing\Controller
{
    private static function getHomeAddressTextFor($user)
    {
        $user_home_address_text = '';
        if (!empty($user->home_city)) {
            $user_home_address_text = trim($user->home_city);
        }
        if (!empty($user->home_region)) {
            $user_home_address_text .= ', '.trim($user->home_region);
        }
        if ($user->home_country_id) {
            $user_home_address_text .= ', '.$user->homeCountry()->first()->name;
        }
        // Remove leading ', ', if there is one.
        if (strpos($user_home_address_text, ', ') === 0) {
            $user_home_address_text = substr($user_home_address_text, 2);
        }
        return $user_home_address_text;
    }

    public static function getProfileView($validator = null)
    {
        $user = BaseUser::getDbUser();
        $question_categories = QuestionCategory::with('questions')->orderBy('name', 'ASC')->get();
        $required_questions = $user->requiredQuestions()->get();
        $num_locations_added_by_me = DB::table('location')->where('creator_user_id', '=', $user->id)->count();

        $view_data = [
            'user' => $user,
            'question_categories' => $question_categories,
            'address_default' => BaseUser::getDefaultAddress(),
            'required_questions' => $required_questions,
            'has_profile_photo' => ProfilePhotoController::hasProfilePhoto(),
            'num_reviews' => count(AnswerRepository::getReviewedLocations()['location_ids']),
            'num_locations_added_by_me' => $num_locations_added_by_me,
            'is_internal_user' => BaseUser::isInternal(),
            'user_home_address_text' => ProfileController::getHomeAddressTextFor($user)
            ];

        if ($validator === null) {
            return view('pages.profile.profile', $view_data);
        } else {
            return view('pages.profile.profile', $view_data)->withErrors($validator);
        }
    }

    public function save(Request $request)
    {
        if (!BaseUser::isSignedIn()) {
            return redirect()->intended('signin');
        }
        $user = BaseUser::getDbUser();
        $user->uses_screen_reader = isset($request->uses_screen_reader) ? 1 : 0;

        $current_user_questions = $user->requiredQuestions()->get();
        
        // questions/accessibility needs that aren't required yet by $user.
        $questions_to_add = [];
        
        // Existing questions/accessibility needs that are required so no update needed on.
        $questions_matched = [];
        
        // Save the user_question data.
        foreach (Input::all() as $name => $value) {
            if (strpos($name, 'question_') === 0) {
                $question_id = substr($name, strlen('question_'));
                if (is_numeric($question_id)) {
                    $question_id = intval($question_id);
                    if ($user->isQuestionRequired($current_user_questions, $question_id)) {
                        $questions_matched []= $question_id;
                    } else {
                        $questions_to_add []= $question_id;
                    }
                }
            }
        }
        $questions_updated = !empty($questions_to_add);
        foreach ($questions_to_add as $new_question_id) {
            $user->requiredQuestions()->attach($new_question_id);
        }
        foreach ($current_user_questions as $existing_question) {
            if (!in_array($existing_question->id, $questions_matched)) {
                $user->requiredQuestions()->detach($existing_question->id);
                $questions_updated = true;
            }
        }

        $user->save();

        return redirect()->intended('/');
    }

    /**
     * Either shows profile view or redirects browser to sign in.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (BaseUser::isSignedIn()) {
            return ProfileController::getProfileView();
        } else {
            return redirect()->intended('signin');
        }
    }
}
