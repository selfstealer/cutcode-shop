<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotFormRequest;
use App\Http\Requests\ResetFormRequest;
use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Models\User;
use http\Encoding\Stream;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function index(): mixed
    {
        return view('auth.index');
    }

    public function signIn(SignInFormRequest $request) : RedirectResponse
    {
        if(!auth()->attempt($request->validated())) {
            return back()->withErrors([
                'email' => __('Неизвестное сочетание e-mail и пароля'),
            ])->onlyInput('email');
        }
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function signUp(): mixed
    {
        return view('auth.sign-up');
    }

    public function store(SignUpFormRequest $request): mixed
    {
        /** @var User $user */
        $user = User::query()->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        event(new Registered($user));

        auth()->login($user);

        return view('auth.sign-up');
    }

    public function forgotPassword(): mixed
    {
        return view('auth.forgot-password');
    }

    public function forgot(ForgotFormRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            flash()->info(__($status));
            return back();
        }

        // НЕБЕЗОПАСНО можно получить информацию о наличии в системе
        return back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(string $token): mixed
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(ResetFormRequest $request) : RedirectResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(str()->random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            flash()->info(__($status));
            return redirect()->route('login');
        }

        // НЕБЕЗОПАСНО можно получить информацию о наличии в системе
        return back()->withErrors(['email' => __($status)]);
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function github(): RedirectResponse
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubCallback(): RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();

        /** @var User $user */
        $user = User::query()->updateOrCreate([
            'github_id' => $githubUser->id,
        ], [
            'name' => $githubUser->name ?? $githubUser->email,
            'email' => $githubUser->email,
//            'github_token' => $githubUser->token,
//            'github_refresh_token' => $githubUser->refreshToken,
            'password' => bcrypt(str()->random(20))
        ]);

        auth()->login($user);

        return redirect()->intended(route('home'));
    }
}
