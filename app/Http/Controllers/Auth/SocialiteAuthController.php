<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Support\SessionRegenerator;
use Throwable;

class SocialiteAuthController extends Controller
{
    public function redirect(string $diver): mixed
    {
        try {
            return Socialite::driver($diver)->redirect();
        } catch (Throwable $e) {
            throw new DomainException(__('Произошла ошибка или драйвер не поддерживается'));
        }
    }

    public function callback(string $diver): RedirectResponse
    {
        if (strtolower($diver) !== 'github') {
            throw new DomainException(__('Драйвер не поддерживается'));
        }

        $driverUser = Socialite::driver($diver)->user();

        /** @var User $user */
        $user = User::query()->updateOrCreate([
            $diver.'_id' => $driverUser->getId(),
        ], [
            'email' => ($email = $driverUser->getEmail()),
            'name' => $driverUser->getName() ?? $email,
//            'github_token' => $driverUser->token,
//            'github_refresh_token' => $driverUser->refreshToken,
            'password' => bcrypt(str()->random(20))
        ]);

        SessionRegenerator::run(fn() => auth()->login($user));

        return redirect()->intended(route('home'));
    }
}
