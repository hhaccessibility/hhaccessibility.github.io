<?php namespace App\Http\Controllers;

use App\BaseUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;

class UserNamesController extends \Illuminate\Routing\Controller
{
    public static function getView($validator = null)
    {
        $user = BaseUser::getDbUser();
        $view_data = [
            'user' => $user
            ];

        if ($validator === null) {
            return view('pages.profile.user_names_form', $view_data);
        } else {
            return view('pages.profile.user_names_form', $view_data)->withErrors($validator);
        }
    }

    public function save(Request $request)
    {
        if (!BaseUser::isSignedIn()) {
            return redirect()->intended('signin');
        }

        $validation_rules = array(
            'first_name'           => 'required|max:255',
            'last_name'            => 'required|max:255'
        );
        $validator = Validator::make(Input::all(), $validation_rules);
        
        if ($validator->fails()) {
            return UserNamesController::getView($validator);
        }
        
        $user = BaseUser::getDbUser();
        
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        
        $user->save();

        return redirect()->intended('/profile');
    }

    /**
     * Either shows user names form view or redirects browser to sign in.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (BaseUser::isSignedIn()) {
            return UserNamesController::getView();
        } else {
            return redirect()->intended('signin');
        }
    }
}
