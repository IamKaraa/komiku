@extends('user.layout.user-app')

@section('title', 'Checkout - ' . $comic->title)

@push('styles')
@vite(['resources/css/dashboard.css'])
@vite(['resources/css/header.css'])
<style>
    .checkout-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
    }
    .comic-summary {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    .payment-form {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .payment-method {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .payment-method:hover {
        border-color: #3b82f6;
    }
    .payment-method.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    .comic-thumbnail {
        width: 80px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="checkout-container">
    <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Checkout</h1>

    <div class="comic-summary">
        <h2 class="text-xl font-semibold mb-4">Comic Details</h2>
        <div class="flex items-start space-x-4">
            @if($comic->thumbnail_path)
                <img src="{{ asset('storage/' . $comic->thumbnail_path) }}" alt="{{ $comic->title }}" class="comic-thumbnail">
            @else
                <div class="comic-thumbnail bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500 text-sm">No Image</span>
                </div>
            @endif
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">{{ $comic->title }}</h3>
                <p class="text-gray-600 mt-1">{{ Str::limit($comic->description, 150) }}</p>
                <div class="mt-2">
                    <span class="text-sm text-gray-500">By {{ $comic->user->name }}</span>
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-900">
                    Rp {{ number_format($comic->price ?? 50000, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('payment.process', $comic->id) }}" method="POST" class="payment-form">
        @csrf
        <h2 class="text-xl font-semibold mb-4">Payment Method</h2>

        <div class="space-y-3">
            <div class="payment-method" data-method="credit_card">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="payment_type" value="credit_card" class="mr-3" required>
                    <div>
                        <div class="font-medium">Credit/Debit Card</div>
                        <div class="text-sm text-gray-600">Visa, Mastercard, JCB</div>
                    </div>
                </label>
            </div>

            <div class="payment-method" data-method="bank_transfer">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="payment_type" value="bank_transfer" class="mr-3">
                    <div>
                        <div class="font-medium">Bank Transfer</div>
                        <div class="text-sm text-gray-600">BCA, Mandiri, BNI, BRI</div>
                    </div>
                </label>
            </div>

            <div class="payment-method" data-method="e_wallet">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="payment_type" value="e_wallet" class="mr-3">
                    <div>
                        <div class="font-medium">E-Wallet</div>
                        <div class="text-sm text-gray-600">GoPay, OVO, Dana, LinkAja</div>
                    </div>
                </label>
            </div>
        </div>

        <div class="mt-8 border-t pt-6">
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-medium">Total Amount:</span>
                <span class="text-2xl font-bold text-gray-900">
                    Rp {{ number_format($comic->price ?? 50000, 0, ',', '.') }}
                </span>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                Proceed to Payment
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('.payment-method');

    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Remove selected class from all methods
            paymentMethods.forEach(m => m.classList.remove('selected'));

            // Add selected class to clicked method
            this.classList.add('selected');

            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });
});
</script>
@endsection
