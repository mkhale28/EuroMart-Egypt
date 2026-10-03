@extends('layouts.guest')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">{{ __('Reset Password') }}</h2>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
            <input type="email" name="email" id="email" value="{{ old('email', $email) }}" required readonly class="w-full border-gray-200 rounded-lg bg-gray-50">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('New Password') }}</label>
            <input type="password" name="password" id="password" required minlength="8" class="w-full border-gray-200 rounded-lg focus:ring-primary-500 @error('password') border-red-300 @enderror">
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
        </div>
        <button type="submit" class="w-full bg-primary-600 text-white py-2.5 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Reset Password') }}</button>
    </form>
@endsection
