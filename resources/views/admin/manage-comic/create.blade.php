@extends('admin.layout.admin-app')

@section('title', 'Create New Comic')

@section('content')
<h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6">Create a New Comic</h3>

<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <form action="{{ route('admin.comics.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comic Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Synopsis -->
            <div class="md:col-span-2">
                <label for="synopsis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Synopsis</label>
                <textarea name="synopsis" id="synopsis" rows="6" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('synopsis') }}</textarea>
                @error('synopsis') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Creator -->
            <div class="md:col-span-2">
                <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Creator</label>
                <select name="user_id" id="user_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Select a creator</option>
                    @foreach($creators as $creator)
                        <option value="{{ $creator->id }}" {{ old('user_id') == $creator->id ? 'selected' : '' }}>{{ $creator->name }} ({{ $creator->email }})</option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Thumbnail -->
            <div class="md:col-span-2">
                <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thumbnail Image</label>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/*" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('thumbnail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Recommended size: 300x400px. Max file size: 2MB</p>
            </div>

            <!-- Genres -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Genres</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($genres as $genre)
                        <label class="flex items-center">
                            <input type="checkbox" name="genres[]" value="{{ $genre->id }}" {{ in_array($genre->id, old('genres', [])) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $genre->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('genres') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.comics.index') }}" class="bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded-lg mr-2">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                Create Comic
            </button>
        </div>
    </form>
</div>
@endsection
