@extends('layouts.guest')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">{{ __('Create Account') }}</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full Name') }}</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus class="w-full border-gray-200 rounded-lg focus:ring-primary-500 @error('name') border-red-300 @enderror">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 @error('email') border-red-300 @enderror">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone Number') }}</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="01012345678" class="w-full border-gray-200 rounded-lg focus:ring-primary-500 @error('phone') border-red-300 @enderror">
            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
            <input type="password" name="password" id="password" required minlength="8" class="w-full border-gray-200 rounded-lg focus:ring-primary-500 @error('password') border-red-300 @enderror">
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
        </div>
        <button type="submit" class="w-full bg-primary-600 text-white py-2.5 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Register') }}</button>
        <p class="text-center text-sm text-gray-500 mt-4">{{ __('Already have an account?') }} <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('Login') }}</a></p>
    </form>
@endsection
