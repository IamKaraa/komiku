<!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-gray-800 text-white flex flex-col">
    <div class="p-6">
        <h1 class="text-2xl font-bold">KOMIKU Creator</h1>
    </div>
    <nav class="flex-1 px-4">
        <a href="{{ route('creator.dashboard') }}" class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ Request::is('creator/dashboard') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-tachometer-alt mr-3"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('creator.comics.index') }}" class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700 {{ Request::is('creator/manage-comic*') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-book mr-3"></i>
            <span>Manage Comics</span>
        </a>
        <a href="{{ route('creator.comics.create') }}" class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-700">
            <i class="fas fa-plus mr-3"></i>
            <span>Create Comic</span>
        </a>
    </nav>
    <div class="p-4">
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="flex items-center py-3 px-4 rounded-lg hover:bg-red-700 w-full text-left bg-transparent border-none cursor-pointer">
                <i class="fas fa-sign-out-alt mr-3"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
