<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Jetstream\Events\AddingTeam;
use App\Models\User;


class DiscordController extends Controller
{
    public function handleDiscordRedirect(){
        return Socialite::driver('discord')->redirect();
    }
    public function handleDiscordCallback(){
        try {
            $user = Socialite::driver('discord')->stateless()->user();
            $idExisted = User::where('oauth_id', $user->id)->where('oauth_type', 'discord')->first();

            if( $idExisted ){
                Auth::login($idExisted);

                return redirect()->route('dashboard');

            } else {
                $userNew = User::create([
                    'discord_name' => $user->name,
                    'name' => $user->name,
                    'email' => $user->email,
                    'oauth_id' => $user->id,
                    'oauth_type' => 'discord',
                    'password' => bcrypt($user->id),
                ]);

                AddingTeam::dispatch($userNew);

                $userNew -> switchTeam($team = $userNew->ownedTeams()->create([
                    'name' => $userNew->name . "'s Team",
                    'personal_team' => false,
                ]));

                $userNew -> update([
                    'current_team_id' => $userNew->id,
                ]);

                Auth::login($userNew);

                return redirect()->route('dashboard');
            }
        } catch (Exception $e) {
            dd($e);
        }
    }
}
