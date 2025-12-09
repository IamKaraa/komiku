@extends('user.layout.user-app')

@section('title', 'Profil - KOMIKU')

@push('styles')
@vite(['resources/css/dashboard.css'])
@vite(['resources/css/header.css'])
@vite(['resources/css/profile.css'])
<style>
    .profile-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .profile-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .profile-header p {
        font-size: 1.1rem;
        color: #6b7280;
    }

    .profile-content {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 30px;
        margin-bottom: 40px;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .profile-avatar-section {
        text-align: center;
        margin-bottom: 30px;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 20px;
        border: 4px solid #e5e7eb;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 700;
        color: #5f7995;
        background: #f3f4f6;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .profile-role {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .role-admin {
        background: #dbeafe;
        color: #1e40af;
    }

    .role-creator {
        background: #dcfce7;
        color: #166534;
    }

    .role-user {
        background: #f3f4f6;
        color: #374151;
    }

    .profile-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .info-card {
        background: #f9fafb;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e5e7eb;
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 1rem;
        color: #1f2937;
        font-weight: 500;
    }

    .edit-profile-btn {
        display: inline-block;
        background: #3b82f6;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.2s;
        margin-top: 20px;
    }

    .edit-profile-btn:hover {
        background: #2563eb;
    }

    .account-status {
        margin-top: 20px;
        padding: 16px;
        background: #ecfdf5;
        border: 1px solid #d1fae5;
        border-radius: 8px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    @media (max-width: 768px) {
        .profile-content {
            grid-template-columns: 1fr;
        }

        .profile-info-grid {
            grid-template-columns: 1fr;
        }

        .profile-header h1 {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-section">
    <!-- Header -->
    <div class="profile-header">
        <h1>Profil Pengguna</h1>
        <p>Kelola informasi akun Anda</p>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-avatar-section">
                @if(Auth::user()->avatar)
                    <div class="profile-avatar">
                        <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar {{ Auth::user()->name }}" />
                    </div>
                @else
                    <div class="profile-avatar">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                @endif
                <h2 class="profile-name">{{ Auth::user()->name }}</h2>
                <span class="profile-role role-{{ strtolower(Auth::user()->role ?? 'user') }}">
                    {{ Auth::user()->role ?? 'User' }}
                </span>
            </div>

            <a href="{{ route('profile.edit') }}" class="edit-profile-btn">
                ✏️ Edit Profil
            </a>

            <div class="account-status">
                <div class="info-label">Status Akun</div>
                <div class="status-badge status-active">
                    <span>●</span>
                    Aktif
                </div>
            </div>
        </div>

        <!-- Information Grid -->
        <div class="profile-info-grid">
            <div class="info-card">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">{{ Auth::user()->name }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">Email</div>
                <div class="info-value">{{ Auth::user()->email }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">Bio</div>
                <div class="info-value">{{ Auth::user()->bio ?? 'Belum ada bio' }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">Tanggal Lahir</div>
                <div class="info-value">
                    @if(Auth::user()->birth_date)
                        {{ \Carbon\Carbon::parse(Auth::user()->birth_date)->format('d M Y') }}
                    @else
                        Belum diisi
                    @endif
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">Bergabung Sejak</div>
                <div class="info-value">{{ Auth::user()->created_at->format('d M Y') }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">Role</div>
                <div class="info-value">{{ Auth::user()->role ?? 'User' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.')) {
            // Add delete functionality here
            alert('Fitur penghapusan akun akan diimplementasikan');
        }
    }
</script>
@endpush
