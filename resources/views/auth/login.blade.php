@extends('layouts.guest')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">{{ __('Login') }}</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-300 @enderror">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
            <input type="password" name="password" id="password" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-300 @enderror">
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                <span>{{ __('Remember me') }}</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700">{{ __('Forgot password?') }}</a>
        </div>
        <button type="submit" class="w-full bg-primary-600 text-white py-2.5 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Login') }}</button>
        <p class="text-center text-sm text-gray-500 mt-4">{{ __('Don\'t have an account?') }} <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('Register') }}</a></p>
    </form>
@endsection
