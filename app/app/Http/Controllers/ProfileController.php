<?php namespace App\Http\Controllers;

use App\BaseUser;
use App\QuestionCategory;
use App\Country;
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
			$countries = Country::orderBy('name')->get();
   
            return view('pages.profile.profile', [
				'user' => $user,
				'question_categories' => $question_categories,
				'address_default' => BaseUser::getDefaultAddress(),
				'countries' => $countries
				]);
        }
        else
        {
            return redirect()->intended('signin');
        }
    }

}