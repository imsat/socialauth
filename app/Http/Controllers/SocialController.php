<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $userSocial =   Socialite::driver($provider)->stateless()->user();
        $user_exist      =   User::where(['email' => $userSocial->getEmail()])->first();
        if($user_exist){
            Auth::login($user_exist, true);
            return redirect('/home');
        }else{
//            dd($userSocial);
            $user = User::create([
                'name'          => $userSocial->getName(),
                'email'         => $userSocial->getEmail(),
                'image'         => $userSocial->getAvatar(),
                'provider_id'   => $userSocial->getId(),
                'provider'      => $provider,
            ]);
            Auth::login($user, true);
            return redirect()->route('home');
        }
    }

}
