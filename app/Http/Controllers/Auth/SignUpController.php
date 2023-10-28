<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;

class SignUpController extends Controller
{
    public function page(): mixed
    {
        return view('auth.sign-up');
    }

    public function handle(SignUpFormRequest $request, RegisterNewUserContract $action): mixed
    {
        // TODO make DTOs
        $action(
            $request->get('name'),
            $request->get('email'),
            $request->get('password')
        );

        return redirect()->intended(route('home'));
    }
}
