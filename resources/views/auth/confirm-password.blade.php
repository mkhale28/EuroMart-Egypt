@extends('layouts.guest')

@section('content')
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">{{ __('Confirm Password') }}</h2>
    <p class="text-sm text-gray-500 text-center mb-6">{{ __('Please confirm your password before continuing.') }}</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
            <input type="password" name="password" id="password" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500 @error('password') border-red-300 @enderror">
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="w-full bg-primary-600 text-white py-2.5 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Confirm') }}</button>
    </form>
@endsection
