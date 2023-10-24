@extends('layouts.app')

@section('content')
    @auth
    <form
        action="{{ route('logout') }}"
        method="POST"
    >
        @csrf
        @method('DELETE')

        <button type="submit">{{ __('Выйти') }}</button>
    </form>
    @endauth
@endsection
