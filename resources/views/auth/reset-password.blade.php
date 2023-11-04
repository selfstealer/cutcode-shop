@extends('layouts.auth')

@section('title', __('Восстановление пароля'))

@section('content')
    <x-forms.auth-forms
        title="{{ __('Восстановление пароля') }}"
        action="{{ route('password.update') }}"
        method="POST"
    >
        @csrf

        <input
            name="token"
            type="hidden"
            value="{{ request('token') }}"
        >

        <x-forms.text-input
            name="email"
            type="email"
            placeholder="{{ __('E-mail') }}"
            value="{{ request('email') }}"
            required="true"
            :isError="$errors->has('email')"
        />
        @error('email')
        <x-forms.error>
            {{ $message  }}
        </x-forms.error>
        @enderror

        <x-forms.text-input
            name="password"
            type="password"
            placeholder="{{ __('Пароль') }}"
            required="true"
            :isError="$errors->has('password')"
        />
        @error('password')
        <x-forms.error>
            {{ $message  }}
        </x-forms.error>
        @enderror

        <x-forms.text-input
            name="password_confirmation"
            type="password"
            placeholder="{{ __('Повторите пароль') }}"
            required="true"
            :isError="$errors->has('password_confirmation')"
        />
        @error('password_confirmation')
        <x-forms.error>
            {{ $message  }}
        </x-forms.error>
        @enderror

        <x-forms.primary-button>
            {{ __('Обновить пароль') }}
        </x-forms.primary-button>

        <x-slot:socialAuth></x-slot:socialAuth>
        <x-slot:buttons></x-slot:buttons>
    </x-forms.auth-forms>
@endsection
