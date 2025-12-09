@extends('admin.layout.admin-app')

@section('title', 'Edit Genre')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Genre</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400">Update the genre information.</p>
</div>

@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md dark:bg-green-900 dark:text-green-200" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif
@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md dark:bg-red-900 dark:text-red-200" role="alert">
        <p>{{ session('error') }}</p>
@endif

<form action="{{ route('admin.genres.update', $genre) }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Genre Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $genre->name) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#415A77] focus:border-[#415A77] dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.genres.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">Cancel</a>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">Update Genre</button>
    </div>
</form>
@endsection
