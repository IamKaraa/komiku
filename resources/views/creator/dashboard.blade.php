@extends('creator.layout.creator-app')

@section('page-title', 'Dashboard')

@section('content')
<div class="mb-6">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
        <i class="fas fa-exchange-alt mr-2"></i>
        Switch to User Dashboard
    </a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Comics -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-book text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Comics</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalComics }}</p>
            </div>
        </div>
    </div>

    <!-- Total Views -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-eye text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Views</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalViews }}</p>
            </div>
        </div>
    </div>

    <!-- Total Chapters -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-file-alt text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Chapters</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalChapters }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('creator.comics.create') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
            <i class="fas fa-plus-circle text-blue-600 text-2xl mr-3"></i>
            <div>
                <p class="font-medium text-gray-900">Create New Comic</p>
                <p class="text-sm text-gray-600">Start a new comic series</p>
            </div>
        </a>

        <a href="{{ route('creator.comics.index') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
            <i class="fas fa-list text-green-600 text-2xl mr-3"></i>
            <div>
                <p class="font-medium text-gray-900">Manage Comics</p>
                <p class="text-sm text-gray-600">Edit existing comics</p>
            </div>
        </a>

        <a href="{{ route('dashboard') }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
            <i class="fas fa-exchange-alt text-purple-600 text-2xl mr-3"></i>
            <div>
                <p class="font-medium text-gray-900">Switch to User Dashboard</p>
                <p class="text-sm text-gray-600">View as regular user</p>
            </div>
        </a>
    </div>
</div>

<!-- Recent Comics -->
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Comics</h3>
    @if($recentComics->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($recentComics as $comic)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                    <img src="{{ asset('storage/' . $comic->thumbnail_path) }}" alt="{{ $comic->title }}" class="w-full h-32 object-cover rounded mb-3">
                    <h4 class="font-medium text-gray-900 mb-2">{{ $comic->title }}</h4>
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($comic->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">{{ $comic->created_at->format('M d, Y') }}</span>
                        <a href="{{ route('creator.comics.edit', $comic->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">No comics created yet. <a href="{{ route('creator.comics.create') }}" class="text-blue-600 hover:text-blue-800">Create your first comic</a></p>
    @endif
</div>
@endsection
