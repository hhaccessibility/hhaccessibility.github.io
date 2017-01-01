<?php namespace App\Http\Controllers;

use App\BaseUser;
use App\QuestionCategory;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller {

    /**
     * Either shows profile view or redirects browser to sign in.
	 *
     * @return Response
     */
    public function index(Request $request)
    {
		
        if (BaseUser::isSignedIn())
        {
			$user = BaseUser::getDbUser();
			$question_categories = QuestionCategory::with('questions')->get();
            
            return view('pages.profile', ['user' => $user, 'question_categories' => $question_categories]);
        }
        else
        {
            return redirect()->intended('signin');
        }
    }

}