@extends('creator.layout.creator-app')

@section('title', 'Mulai Perjalanan Kreatormu')

@push('styles')
{{-- Font Awesome untuk ikon --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .onboarding-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 120px); /* Adjust based on header/footer height */
        background-color: #f8f9fa;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        text-align: center;
        padding: 2rem;
    }
    .onboarding-card {
        background-color: white;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        padding: 3rem 4rem;
        max-width: 600px;
        width: 100%;
        transform: translateY(-20px);
    }
    .onboarding-icon {
        font-size: 4rem;
        color: #0d6efd;
        margin-bottom: 1.5rem;
    }
    .btn-primary-start {
        background-color: #0d6efd;
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        display: inline-block;
        text-decoration: none;
    }
    .btn-primary-start:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }
</style>
@endpush

@section('content')
<div class="onboarding-container">
    <div class="onboarding-card">
        <div class="onboarding-icon">
            <i class="fas fa-rocket"></i>
        </div>
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Selamat Datang di Dunia Kreator!</h1>
        <p class="text-lg text-gray-600 mb-8">Anda selangkah lebih dekat untuk membagikan cerita luar biasa Anda kepada dunia. Mari kita mulai perjalanan ini bersama.</p>
        <div class="space-y-4">
            <a href="{{ route('creator.comics.create') }}" class="btn-primary-start">Buat Komik Pertamamu</a>
            <p class="text-sm text-gray-500">atau <a href="{{ route('creator.dashboard') }}" class="text-blue-600 hover:underline">kembali ke Dashboard</a></p>
        </div>
    </div>
</div>
@endsection