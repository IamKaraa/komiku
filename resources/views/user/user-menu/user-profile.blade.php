@extends('user.layout.user-app')

@section('title', 'KOMIKU - User Profile')

@push('styles')
@vite(['resources/css/dashboard.css'])
@vite(['resources/css/header.css'])
@vite(['resources/css/profile.css'])
@endpush

@section('content')
<div class="w-full px-0 py-8">
    <!-- Header Section -->
    <section class="section mb-8 mx-4">
        <div class="text-center">
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">User Profile</h1>
            <p class="text-lg text-gray-600">Manage your account information</p>
        </div>
    </section>

    <!-- Profile Box -->
    <section class="section mb-16 mx-4">
        <div class="profile-box">
            <!-- Avatar Section -->
            <div class="avatar-section">
                @if(Auth::user()->avatar)
                    <div class="avatar-circle">
                        <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar User" />
                    </div>
                @else
                    <div class="avatar-circle">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                @endif
                <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
            </div>

            <!-- Profile Information -->
            <div class="profile-row">
                <span><strong>User Name</strong><br>{{ Auth::user()->name }}</span>
                <a href="{{ route('profile.edit') }}" class="edit-btn">Edit</a>
            </div>

            <div class="profile-row">
                <span><strong>Email</strong><br>{{ Auth::user()->email }}</span>
                <a href="{{ route('profile.edit') }}" class="edit-btn">Edit</a>
            </div>

            <div class="profile-row">
                <span><strong>Bio</strong><br>{{ Auth::user()->bio ?? 'No bio available' }}</span>
                <a href="{{ route('profile.edit') }}" class="edit-btn">Edit</a>
            </div>

            <div class="profile-row">
                <span><strong>Member Since</strong><br>{{ Auth::user()->created_at->format('M d, Y') }}</span>
            </div>

            <!-- Delete Account -->
            <div style="margin-top: 30px; text-align: center;">
                <strong>Delete Account?</strong><br>
                <button class="delete-btn" onclick="confirmDelete()">Delete account</button>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
            // Add delete functionality here
            alert('Account deletion functionality to be implemented');
        }
    }
</script>
@endpush
@endsection
