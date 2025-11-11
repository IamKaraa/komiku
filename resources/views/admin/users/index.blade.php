@extends('layouts.admin')

@section('title', 'Manage User')

@section('content')
{{-- Filter Bar --}}
<div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
    <!-- Search Input -->
    <div class="relative">
        <input type="text" placeholder="Search user..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-[#778DA9] bg-white focus:outline-none focus:ring-2 focus:ring-[#415A77]">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
        </div>
    </div>

    <!-- Status Dropdown -->
    <div class="relative">
        <select class="w-full appearance-none pl-4 pr-10 py-2 rounded-lg border border-[#778DA9] bg-white focus:outline-none focus:ring-2 focus:ring-[#415A77]">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="pending">Pending</option>
        </select>
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
        </div>
    </div>

    <!-- Role Dropdown -->
    <div class="relative">
        <select class="w-full appearance-none pl-4 pr-10 py-2 rounded-lg border border-[#778DA9] bg-white focus:outline-none focus:ring-2 focus:ring-[#415A77]">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="creator">Creator</option>
            <option value="user">User</option>
        </select>
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
        </div>
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

<div class="overflow-x-auto rounded-lg shadow-md">
    <table class="min-w-full">
        <thead class="bg-[#415A77]">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Id</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nama</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Email</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Role</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="bg-[#778DA9] text-white">
            @forelse ($users as $user)
                <tr class="border-b border-[#E0E1DD]/20">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucfirst($user->role) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status == 'active' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div x-data="{ open: false }" @click.away="open = false" class="relative inline-block text-left">
                            <button @click="open = !open" type="button" class="p-2 rounded-full hover:bg-white/20 focus:outline-none">
                                <i data-lucide="more-vertical" class="w-5 h-5"></i>
                            </button>
                        
                            <div x-show="open" x-transition class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                <div class="py-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i data-lucide="edit-2" class="w-4 h-4 mr-2"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-[#E57373] hover:bg-gray-100">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection