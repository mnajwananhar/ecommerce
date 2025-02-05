<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
  {
    try {
      $user = Socialite::driver('google')->user();
      $findUser = User::where('email', $user->getEmail())->first();

      if ($findUser) {
        Auth::login($findUser);
        return redirect()->intended('/');
      } else {
        $newUser = User::create([
          'name' => $user->getName(),
          'email' => $user->getEmail(),
          'google_id' => $user->getId(),
          'password' => encrypt('my-google')
        ]);

        Auth::login($newUser);
        return redirect()->intended('/');
      }
    } catch (Exception $e) {
      return redirect('auth/google');
    }
  }
}
