@extends('user.layout.user-app')

@section('title', 'Payment Failed')

@push('styles')
@vite(['resources/css/dashboard.css'])
@vite(['resources/css/header.css'])
<style>
    .error-container {
        max-width: 600px;
        margin: 4rem auto;
        padding: 3rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    .error-icon {
        width: 80px;
        height: 80px;
        background: #ef4444;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
    }
</style>
@endpush

@section('content')
<div class="error-container">
    <div class="error-icon">
        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Failed</h1>

    <p class="text-gray-600 mb-6">
        Unfortunately, your payment could not be processed. Please try again or contact support if the problem persists.
    </p>

    <div class="space-y-4">
        <a href="{{ url()->previous() }}"
           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
            Try Again
        </a>

        <br>

        <a href="{{ route('home') }}"
           class="inline-block text-blue-600 hover:text-blue-800 font-medium">
            Back to Home
        </a>
    </div>
</div>
@endsection
