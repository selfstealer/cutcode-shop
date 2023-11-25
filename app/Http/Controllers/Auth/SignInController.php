<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInFormRequest;
use Illuminate\Http\RedirectResponse;
use Support\SessionRegenerator;

class SignInController extends Controller
{
    public function page(): mixed
    {
        return view('auth.login');
    }

    public function handle(SignInFormRequest $request): RedirectResponse
    {
        if (!auth()->attempt($request->validated())) {
            return back()->withErrors([
                'email' => __('Неизвестное сочетание e-mail и пароля'),
            ])->onlyInput('email');
        }

        SessionRegenerator::run();

        return redirect()->intended(route('home'));
    }

    public function logout(): RedirectResponse
    {
        SessionRegenerator::run(fn() => auth()->logout());

        return redirect()->route('home');
    }
}
