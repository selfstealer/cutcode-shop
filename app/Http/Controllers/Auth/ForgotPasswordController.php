<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;


class ForgotPasswordController extends Controller
{
    public function page(): mixed
    {
        return view('auth.forgot-password');
    }

    public function handle(ForgotFormRequest $request): RedirectResponse
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
}
