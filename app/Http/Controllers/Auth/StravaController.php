<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class StravaController extends Controller
{
    public function handleStravaRedirect(){
        return Socialite::driver('strava')->redirect();
    }
    public function handleStravaCallback(){
        try {

            $auth_strava = Socialite::driver('strava')->stateless()->user();
            //refresh_token access_token expires_at expires_in
            //working: refreshToken  access_token==token expiresIn expiresAt

            $auth_user = auth()->user();
            
            //print_r($auth_strava->expiresIn);

            
            User::where('oauth_id', $auth_user->oauth_id)->update(['strava_name' => $auth_strava->name, 'strava_id' => $auth_strava->id, 'strava_refresh_token' => $auth_strava->refreshToken, 'strava_access_token' => $auth_strava->token, 'strava_expires_at' => $auth_strava->accessTokenResponseBody["expires_at"]]);
            
            $idExisted = User::where('oauth_id', $auth_user->oauth_id)->where('oauth_type', 'strava')->first();
            
            if( $idExisted ){
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('dashboard');
            }

        } catch (Exception $e) {
            dd($e);
        }
    }
}
