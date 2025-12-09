@extends('admin.layout.admin-app')

@section('title', 'Validation Comic')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Comic List</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Browse and manage your comics.</p>
    </div>
</div>



@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md dark:bg-green-900 dark:text-green-200" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif
@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md dark:bg-red-900 dark:text-red-200" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif

{{-- Filter Bar --}}
<div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
    <!-- Search Input -->
    <div class="relative">
        <form method="GET" action="{{ route('admin.comics.index') }}" class="flex">
            <input type="text" name="search" placeholder="Search comic..." value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 rounded-lg border border-[#778DA9] bg-white focus:outline-none focus:ring-2 focus:ring-[#415A77]">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
            </div>
        </form>
    </div>

    <!-- Status Dropdown -->
    <div class="relative">
        <form method="GET" action="{{ route('admin.comics.index') }}">
            <select name="status" onchange="this.form.submit()" class="w-full appearance-none pl-4 pr-10 py-2 rounded-lg border border-[#778DA9] bg-white focus:outline-none focus:ring-2 focus:ring-[#415A77]">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
            </div>
        </form>
    </div>
</div>

@if (session('success'))
    <div style="background: #d0ffd8; color: #218a3d; padding: 10px; margin: 20px 30px; border-radius: 8px;">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto rounded-lg shadow-md">
    <table class="min-w-full">
        <thead class="bg-[#415A77]">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Id</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Judul</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Creator</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Genre</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Chapter</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date Upload</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="bg-[#778DA9] text-white">
            @forelse ($comics ?? [] as $comic)
                <tr class="border-b border-[#E0E1DD]/20">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">#{{ $comic->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $comic->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $comic->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $comic->genres->pluck('name')->join(', ') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $comic->chapters->count() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $comic->created_at->format('F d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $comic->status == 'approved' ? 'bg-green-200 text-green-800' : ($comic->status == 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') }}">
                            {{ ucfirst($comic->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div x-data="{ open: false }" @click.away="open = false" class="relative inline-block text-left">
                            <button @click="open = !open" type="button" class="p-2 rounded-full hover:bg-white/20 focus:outline-none">
                                <i data-lucide="more-vertical" class="w-5 h-5"></i>
                            </button>

                            <div x-show="open" x-transition class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                <div class="py-1">
                                    <a href="{{ route('admin.comics.edit', $comic) }}" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i data-lucide="edit-2" class="w-4 h-4 mr-2"></i>
                                        View
                                    </a>
                                    @if($comic->status !== 'approved')
                                        <form method="POST" action="{{ route('admin.comics.approve', $comic) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-green-700 hover:bg-gray-100">
                                                <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                    @if($comic->status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.comics.reject', $comic) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center">No comics found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination (if needed) --}}
<div class="mt-8">
    {{ $comics->links() }}
</div>

@endsection
