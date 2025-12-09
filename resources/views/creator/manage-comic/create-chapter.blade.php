@extends('creator.layout.creator-app')

@section('title', 'Create Chapter - ' . $comic->title)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Create Chapter</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $comic->title }}</p>
    </div>
    <a href="{{ route('creator.comics.show', $comic->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center">
        <i data-lucide="arrow-left" class="inline-block w-4 h-4 mr-2"></i>
        Back
    </a>
</div>

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
    <form action="{{ route('creator.comics.storeChapter', $comic->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chapter Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                   placeholder="Enter chapter title" required>
            @error('title')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chapter Images</label>
            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                <i data-lucide="upload" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Upload chapter images (multiple files allowed)</p>
                <input type="file" name="images[]" id="images" multiple accept="image/*"
                       class="hidden" onchange="previewImages(event)">
                <label for="images" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg cursor-pointer transition-all duration-300">
                    Choose Images
                </label>
            </div>
            @error('images.*')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Image Preview Section -->
        <div id="image-preview" class="mb-6 hidden">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Image Preview</h3>
            <div id="preview-container" class="space-y-4">
                <!-- Images will be displayed here -->
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('creator.comics.show', $comic->id) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                Create Chapter
            </button>
        </div>
    </form>
</div>

<script>
function previewImages(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');

    // Clear previous previews
    previewContainer.innerHTML = '';

    if (files.length > 0) {
        imagePreview.classList.remove('hidden');

        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'relative';
                    imageDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}"
                             class="w-full max-w-md mx-auto rounded-lg shadow-md">
                        <div class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                            Image ${index + 1}: ${file.name}
                        </div>
                    `;
                    previewContainer.appendChild(imageDiv);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        imagePreview.classList.add('hidden');
    }
}
</script>
@endsection
