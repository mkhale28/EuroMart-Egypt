@extends('layouts.guest')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">{{ __('Verify Email') }}</h2>
    <p class="text-sm text-gray-500 text-center mb-6">{{ __('Thanks for signing up! Please verify your email address.') }}</p>

    @if(session('status') === 'verification-link-sent')
        <div class="bg-green-50 text-green-700 p-3 rounded-lg text-sm mb-4">{{ __('A new verification link has been sent to your email.') }}</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
        @csrf
        <button type="submit" class="w-full bg-primary-600 text-white py-2.5 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Resend Verification Email') }}</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-center text-sm text-gray-500 hover:text-gray-700">{{ __('Logout') }}</button>
    </form>
@endsection
