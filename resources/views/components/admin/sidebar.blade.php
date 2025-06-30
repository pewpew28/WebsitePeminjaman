<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out"
     :class="sidebarOpen ? 'w-64' : 'w-16'">
    <div class="flex flex-col h-full bg-gradient-to-b from-slate-800 to-slate-900 text-white shadow-xl">
        
        <!-- Logo Component -->
        <x-admin.logo />

        <!-- Navigation Component -->
        <x-admin.navigation />

        <!-- User Profile Component -->
        <x-admin.sidebar-profile />
    </div>
</div>