<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class KeycloakLoginController extends Controller
{
    /**
     * Redirect the user to Keycloak's authentication page.
     */
    public function redirectToProvider()
    {
        
        // dd(config('services.keycloak'));
        return Socialite::driver('keycloak')->redirect();
    }

    public function redirectToRegister()
{
    $config   = config('services.keycloak');
    $base     = rtrim($config['base_url'], '/');
    $realm    = $config['realms'];
    $clientId = $config['client_id'];
    // same redirect URI you use for login callback
    $redirect = urlencode($config['redirect']);  

    $url = "{$base}/realms/{$realm}/protocol/openid-connect/registrations"
         . "?client_id={$clientId}"
         . "&redirect_uri={$redirect}";

    return redirect()->away($url);
}

    /**
     * Obtain the user information from Keycloak.
     */
    public function handleProviderCallback()
    {
        try {
            $keycloakUser = Socialite::driver('keycloak')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Unable to login using Keycloak. Please try again.');
        }

        
        $user = User::updateOrCreate(
            ['email' => $keycloakUser->getEmail()],
            [
                'name'  => $keycloakUser->getName(),
                'keycloak_id' => $keycloakUser->getId(),
                'username' => $keycloakUser->user['preferred_username'] ?? null,
            ]
        );

        // Log the user in locally
        Auth::login($user, true);

        return redirect('/dashboard'); // Redirect to your desired page.
    }
}
