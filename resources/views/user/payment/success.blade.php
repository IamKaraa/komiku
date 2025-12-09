@extends('user.layout.user-app')

@section('title', 'Payment Successful')

@push('styles')
@vite(['resources/css/dashboard.css'])
@vite(['resources/css/header.css'])
<style>
    .success-container {
        max-width: 600px;
        margin: 4rem auto;
        padding: 3rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    .success-icon {
        width: 80px;
        height: 80px;
        background: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
    }
    .purchase-details {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 2rem 0;
        text-align: left;
    }
</style>
@endpush

@section('content')
<div class="success-container">
    <div class="success-icon">
        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Successful!</h1>

    <p class="text-gray-600 mb-6">
        Thank you for your purchase. You now have access to read this premium comic.
    </p>

    @if(isset($purchase))
    <div class="purchase-details">
        <h3 class="font-semibold text-gray-900 mb-3">Purchase Details</h3>
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Comic:</span>
                <span class="font-medium">{{ $purchase->comic->title }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Order ID:</span>
                <span class="font-medium">{{ $purchase->order_id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Amount:</span>
                <span class="font-medium">Rp {{ number_format($purchase->amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Payment Method:</span>
                <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $purchase->payment_type)) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Purchase Date:</span>
                <span class="font-medium">{{ $purchase->paid_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="space-y-4">
        <a href="{{ route('comic.read', [$purchase->comic_id ?? $comic->id ?? 1]) }}"
           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
            Start Reading Now
        </a>

        <br>

        <a href="{{ route('home') }}"
           class="inline-block text-blue-600 hover:text-blue-800 font-medium">
            Back to Home
        </a>
    </div>
</div>
@endsection
