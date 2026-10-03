@extends('layouts.guest')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">{{ __('Reset Password') }}</h2>
    <p class="text-sm text-gray-500 text-center mb-6">{{ __('Enter your email and we\'ll send you a reset link') }}</p>

    @if(session('status'))
        <div class="bg-green-50 text-green-700 p-3 rounded-lg text-sm mb-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus class="w-full border-gray-200 rounded-lg focus:ring-primary-500 @error('email') border-red-300 @enderror">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="w-full bg-primary-600 text-white py-2.5 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Send Reset Link') }}</button>
        <p class="text-center text-sm mt-4"><a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700">{{ __('Back to login') }}</a></p>
    </form>
@endsection
