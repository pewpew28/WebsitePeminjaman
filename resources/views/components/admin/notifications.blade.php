<!-- Notifications -->
<button class="relative p-2 text-gray-500 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200">
    <i class="fas fa-bell text-lg"></i>
    {{-- @if(auth()->user()->unreadNotifications->count() > 0)
        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
    @endif --}}
</button>