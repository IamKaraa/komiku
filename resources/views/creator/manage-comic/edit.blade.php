@extends('creator.layout.creator-app')

@section('title', 'Edit Komik')

@push('styles')
    {{-- Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .form-container {
            background-color: #f8f9fa;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .form-card {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.07);
            border: 1px solid #e9ecef;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .form-input,
        .form-textarea,
        .form-select {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            width: 100%;
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }

        .upload-box {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s, background-color 0.3s;
        }

        .upload-box:hover {
            border-color: #3b82f6;
            background-color: #f9fafb;
        }

        .upload-box i {
            font-size: 2.5rem;
            color: #9ca3af;
        }

        .radio-option {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 0.75rem 1.25rem;
            cursor: pointer;
            transition: border-color 0.2s, background-color 0.2s;
        }

        input[type="radio"]:checked+.radio-option {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .btn-publish {
            background-color: #2563eb;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-publish:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .custom-dropdown {
            position: relative;
            width: 100%;
        }

        .dropdown-button {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            background-color: white;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .dropdown-button:hover,
        .dropdown-button:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: white;
            border: 1px solid #ced4da;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            padding: 0.5rem 0;
        }

        .dropdown-content.show {
            display: block;
        }

        .checkbox-label {
            display: block;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .checkbox-label:hover {
            background-color: #f8f9fa;
        }

        .checkbox-label input[type="checkbox"] {
            margin-right: 0.5rem;
        }

        .current-thumbnail {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="form-container w-full min-h-screen p-4 md:p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8 text-center md:text-left">
                <h1 class="text-4xl font-bold text-gray-800">Edit Komik</h1>
                <p class="text-lg text-gray-500 mt-2">Perbarui informasi komikmu.</p>
            </div>

            <!-- Form Card -->
            <div class="form-card p-6 md:p-10">
                <form action="{{ route('creator.comics.update', $comic->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-8">
                        <!-- Informasi Utama -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-gray-700 border-b pb-3">Informasi Utama</h3>
                            <div>
                                <label for="title" class="block text-sm form-label mb-2">Judul Komik</label>
                                <input type="text" id="title" name="title" class="form-input"
                                    placeholder="Contoh: Petualangan di Negeri Awan" value="{{ $comic->title }}">
                            </div>
                            <div>
                                <label for="description" class="block text-sm form-label mb-2">Deskripsi Komik</label>
                                <textarea id="description" name="description" rows="4" class="form-textarea"
                                    placeholder="Ceritakan tentang komikmu secara singkat...">{{ $comic->description }}</textarea>
                            </div>
                            <div>
                                <label for="genres" class="block text-sm form-label mb-2">Pilih Genre (bisa
                                    multiple)</label>
                                <div class="custom-dropdown">
                                    <div class="dropdown-button" onclick="toggleDropdown()">
                                        <span
                                            id="selected-text">{{ $comic->genres->count() > 0 ? ($comic->genres->count() == 1 ? $comic->genres->first()->name : $comic->genres->count() . ' genre dipilih') : 'Pilih Genre' }}</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                    <div class="dropdown-content" id="dropdown-content">
                                        @foreach($genres as $genre)
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
                                                    onchange="updateSelectedText()" {{ in_array($genre->id, $comic->genres->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                {{ $genre->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm form-label mb-2">Tipe Akses</label>
                                <div class="flex space-x-4">
                                    <label class="flex-1">
                                        <input type="radio" name="access_type" value="0" class="sr-only" {{ $comic->is_paid ? '' : 'checked' }}
                                            onclick="togglePriceField()">
                                        <div class="radio-option text-center">Gratis</div>
                                    </label>

                                    <label class="flex-1">
                                        <input type="radio" name="access_type" value="1" class="sr-only" {{ $comic->is_paid ? 'checked' : '' }}
                                            onclick="togglePriceField()">
                                        <div class="radio-option text-center">Premium</div>
                                    </label>
                                </div>
                            </div>

                            <!-- Harga Premium -->
                            <div id="price-field" class="mt-4 {{ $comic->is_paid ? '' : 'hidden' }}">
                                <label for="price" class="block text-sm form-label mb-2">Harga Komik (Rp.)</label>
                                <input type="number" id="price" name="price" class="form-input" placeholder="Contoh: 10000" value="{{ $comic->price }}">
                            </div>
                            <div>
                                <label class="block text-sm form-label mb-2">Thumbnail Komik</label>
                                <div class="upload-box" onclick="document.getElementById('thumbnail').click()">
                                    <i class="fas fa-cloud-upload-alt mb-3"></i>
                                    <span class="text-gray-600">Seret & lepas file atau <span
                                            class="text-blue-600 font-semibold">klik untuk upload</span></span>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP (maks. 800x1200px)</p>
                                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="hidden">
                                </div>
                                <div id="thumbnail-filename" class="text-sm text-gray-600 mt-2"></div>
                                @if($comic->thumbnail_path)
                                    <div class="mt-4">
                                        <p class="text-sm text-gray-600">Thumbnail Saat Ini:</p>
                                        <img src="{{ asset('storage/' . $comic->thumbnail_path) }}" alt="Current Thumbnail"
                                            class="current-thumbnail">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Detail Tambahan -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-gray-700 border-b pb-3">Detail Tambahan</h3>
                            <div>
                                <label for="synopsis" class="block text-sm form-label mb-2">Sinopsis Komik</label>
                                <textarea id="synopsis" name="synopsis" rows="4" class="form-textarea"
                                    placeholder="Sinopsis lengkap komik...">{{ $comic->synopsis }}</textarea>
                            </div>
                            <div>
                                <label for="status" class="block text-sm form-label mb-2">Status Komik</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="ongoing" {{ $comic->status == 'ongoing' ? 'selected' : '' }}>Ongoing
                                    </option>
                                    <option value="completed" {{ $comic->status == 'completed' ? 'selected' : '' }}>Completed
                                    </option>
                                    <option value="hiatus" {{ $comic->status == 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="pt-6 border-t">
                            <button type="submit" class="btn-publish">Perbarui Komik</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleDropdown() {
                const dropdown = document.getElementById('dropdown-content');
                dropdown.classList.toggle('show');
            }

            function updateSelectedText() {
                const checkboxes = document.querySelectorAll('input[name="genres[]"]:checked');
                const selectedText = document.getElementById('selected-text');
                if (checkboxes.length === 0) {
                    selectedText.textContent = 'Pilih Genre';
                } else if (checkboxes.length === 1) {
                    selectedText.textContent = checkboxes[0].parentElement.textContent.trim();
                } else {
                    selectedText.textContent = checkboxes.length + ' genre dipilih';
                }
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function (event) {
                const dropdown = document.querySelector('.custom-dropdown');
                const dropdownContent = document.getElementById('dropdown-content');
                if (!dropdown.contains(event.target)) {
                    dropdownContent.classList.remove('show');
                }
            });

            // Form validation for genres
            document.querySelector('form').addEventListener('submit', function (event) {
                const checkboxes = document.querySelectorAll('input[name="genres[]"]:checked');
                if (checkboxes.length === 0) {
                    alert('Silakan pilih setidaknya satu genre.');
                    event.preventDefault();
                    return false;
                }
            });

            document.getElementById('thumbnail').addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    document.getElementById('thumbnail-filename').textContent = 'File terpilih: ' + file.name;
                } else {
                    document.getElementById('thumbnail-filename').textContent = '';
                }
            });

            function togglePriceField() {
                const premium = document.querySelector('input[name="access_type"][value="1"]').checked;
                const field = document.getElementById('price-field');

                if (premium) {
                    field.classList.remove('hidden');
                } else {
                    field.classList.add('hidden');
                }
            }

            // Initialize selected text on load
            updateSelectedText();
        </script>
    @endpush

@endsection