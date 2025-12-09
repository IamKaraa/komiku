@extends('admin.layout.admin-app')

@section('title', 'Edit Comic')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Comic</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400">Update the comic information.</p>
</div>

<form action="{{ route('admin.comics.update', $comic) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $comic->title) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#415A77] focus:border-[#415A77] dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
        </div>

        <div>
            <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thumbnail (leave empty to keep current)</label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#415A77] focus:border-[#415A77] dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @if($comic->thumbnail_path)
                <img src="{{ asset('storage/' . $comic->thumbnail_path) }}" alt="Current thumbnail" class="mt-2 w-24 h-32 object-cover rounded">
            @endif
        </div>
    </div>

    <div class="mt-6">
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
        <textarea name="description" id="description" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#415A77] focus:border-[#415A77] dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>{{ old('description', $comic->description) }}</textarea>
    </div>

    <div class="mt-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Genres</label>
        <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
            @foreach($genres as $genre)
                <label class="inline-flex items-center">
                    <input type="checkbox" name="genres[]" value="{{ $genre->id }}" {{ $comic->genres->contains($genre->id) ? 'checked' : '' }} class="rounded border-gray-300 text-[#415A77] shadow-sm focus:border-[#415A77] focus:ring focus:ring-[#415A77] focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $genre->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('admin.comics.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">Cancel</a>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">Update Comic</button>
    </div>
</form>
@endsection
