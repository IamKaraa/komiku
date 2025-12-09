@extends('creator.layout.creator-app')

@section('title', 'Edit Chapter - ' . $chapter->title)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Chapter</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $comic->title }} - {{ $chapter->title }}</p>
    </div>
    <a href="{{ route('creator.comics.show', $comic->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center">
        <i data-lucide="arrow-left" class="inline-block w-4 h-4 mr-2"></i>
        Back
    </a>
</div>

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
    <form action="{{ route('creator.comics.updateChapter', [$comic->id, $chapter->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chapter Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $chapter->title) }}"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                   placeholder="Enter chapter title" required>
            @error('title')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Existing Images -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Current Images</h3>
            <div id="existing-images" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($chapter->images->sortBy('order_no') as $index => $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                             alt="Chapter image {{ $index + 1 }}"
                             class="w-full h-32 object-cover rounded-lg shadow-md">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-center justify-center">
                            <button type="button" onclick="removeImage({{ $image->id }}, this)"
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-colors duration-300">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Add New Images (Optional)</label>
            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                <i data-lucide="upload" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Upload additional chapter images (multiple files allowed)</p>
                <input type="file" name="new_images[]" id="new_images" multiple accept="image/*"
                       class="hidden" onchange="previewNewImages(event)">
                <label for="new_images" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg cursor-pointer transition-all duration-300">
                    Choose Images
                </label>
            </div>
            @error('new_images.*')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Image Preview Section -->
        <div id="new-image-preview" class="mb-6 hidden">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">New Images Preview</h3>
            <div id="new-preview-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <!-- New images will be displayed here -->
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('creator.comics.show', $comic->id) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                Update Chapter
            </button>
        </div>
    </form>
</div>

<script>
function removeImage(imageId, buttonElement) {
    if (confirm('Are you sure you want to remove this image?')) {
        // Remove the image container
        buttonElement.closest('.relative').remove();

        // Add to removed images list
        let removedInput = document.createElement('input');
        removedInput.type = 'hidden';
        removedInput.name = 'removed_images[]';
        removedInput.value = imageId;
        document.querySelector('form').appendChild(removedInput);
    }
}

function previewNewImages(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('new-preview-container');
    const imagePreview = document.getElementById('new-image-preview');

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
                        <img src="${e.target.result}" alt="New Preview ${index + 1}"
                             class="w-full h-32 object-cover rounded-lg shadow-md">
                        <div class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                            New Image ${index + 1}: ${file.name}
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
