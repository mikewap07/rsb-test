<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function userLogin(Request $request){
        $findUser = User::query()
            ->where('email', $request->email)
            ->first();

        if ($findUser && Hash::check($request->password, $findUser->password)) {
            Auth::login($findUser);

            return Redirect::to('user/dashboard');
        }else{
            return Controller::showMessagePage('Login failed! Info mismatched.', 'login');
        }
    }

    public function userLogout(Request $request){
        Auth::logout();
        Session::flush();

        return Redirect::to('/login');
    }
}
