@extends('user.layout.user-app')

@section('title', 'Edit Profil - KOMIKU')

@push('styles')
@vite(['resources/css/dashboard.css'])
@vite(['resources/css/header.css'])
@vite(['resources/css/profile.css'])
<style>
    .edit-profile-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .edit-profile-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .edit-profile-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .edit-profile-header p {
        font-size: 1.1rem;
        color: #6b7280;
    }

    .edit-form-card {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .form-section {
        margin-bottom: 40px;
    }

    .form-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e5e7eb;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        color: #1f2937;
        background: #ffffff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-input.error {
        border-color: #ef4444;
    }

    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        color: #1f2937;
        background: #ffffff;
        min-height: 100px;
        resize: vertical;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-textarea.error {
        border-color: #ef4444;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 4px;
    }

    .avatar-section {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 32px;
    }

    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #e5e7eb;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 700;
        color: #5f7995;
        background: #f3f4f6;
        flex-shrink: 0;
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-upload {
        flex: 1;
    }

    .avatar-upload-label {
        display: inline-block;
        background: #f3f4f6;
        color: #374151;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.2s;
        border: 2px dashed #d1d5db;
    }

    .avatar-upload-label:hover {
        background: #e5e7eb;
    }

    .avatar-upload-input {
        display: none;
    }

    .current-avatar-text {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 8px;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        padding-top: 32px;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }



    @media (max-width: 768px) {
        .edit-profile-container {
            padding: 16px;
        }

        .edit-profile-header h1 {
            font-size: 2rem;
        }

        .edit-form-card {
            padding: 24px;
        }



        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="edit-profile-container">
    <!-- Header -->
    <div class="edit-profile-header">
        <h1>Edit Profil</h1>
        <p>Perbarui informasi akun Anda</p>
    </div>

    <!-- Edit Form -->
    <div class="edit-form-card">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')



            <!-- Personal Information -->
            <div class="form-section">
                <div class="form-section-title">Informasi Pribadi</div>

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') error @enderror"
                           value="{{ old('name', Auth::user()->name) }}" required>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input @error('email') error @enderror"
                           value="{{ old('email', Auth::user()->email) }}" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea id="bio" name="bio" class="form-textarea @error('bio') error @enderror"
                              placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', Auth::user()->bio) }}</textarea>
                    @error('bio')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                    <input type="date" id="birth_date" name="birth_date" class="form-input @error('birth_date') error @enderror"
                           value="{{ old('birth_date', Auth::user()->birth_date ? \Carbon\Carbon::parse(Auth::user()->birth_date)->format('Y-m-d') : '') }}">
                    @error('birth_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>



            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('profile') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Avatar preview functionality
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        if (password && password !== passwordConfirmation) {
            e.preventDefault();
            alert('Konfirmasi password tidak cocok!');
            return false;
        }

        if (password && !document.getElementById('current_password').value) {
            e.preventDefault();
            alert('Masukkan password lama untuk mengubah password!');
            return false;
        }
    });
</script>
@endpush
