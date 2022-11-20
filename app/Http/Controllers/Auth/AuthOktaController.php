<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthOktaController extends Controller
{
    public function okta(){
        return Socialite::driver('okta')->redirect();
    }

    public function store(){
        $user = Socialite::driver('okta')->user();
        dd($user);
    }
}
