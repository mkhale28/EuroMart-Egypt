@extends('layouts.app')

@section('title', __('My Profile') . ' - ' . config('app.name'))

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ __('My Profile') }}</h1>

        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="font-bold text-gray-900 text-lg mb-6">{{ __('Profile Information') }}</h2>
            <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone') }}</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Avatar') }}</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="w-full border-gray-200 rounded-lg focus:ring-primary-500 text-sm">
                    </div>
                </div>
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Update Profile') }}</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-6">{{ __('Change Password') }}</h2>
            <form action="{{ route('dashboard.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Current Password') }}</label>
                        <input type="password" name="current_password" id="current_password" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('New Password') }}</label>
                        <input type="password" name="password" id="password" required minlength="8" class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm Password') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full border-gray-200 rounded-lg focus:ring-primary-500">
                    </div>
                </div>
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-700 transition">{{ __('Update Password') }}</button>
            </form>
        </div>
    </div>
@endsection
