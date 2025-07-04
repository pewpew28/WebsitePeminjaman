<!-- Navigation -->
<nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto">
    
    <!-- Dashboard -->
    <x-admin.nav-item 
        href="{{ route('admin.dashboard') }}" 
        icon="fas fa-tachometer-alt" 
        :active="request()->routeIs('admin.dashboard')">
        Dashboard
    </x-admin.nav-item>

    <!-- Users -->
    <x-admin.nav-item 
        href="{{ route('admin.users.index') }}" 
        icon="fas fa-user-shield"
        :active="request()->routeIs('admin.users.*')">
        Users
    </x-admin.nav-item>

    <!-- Nasabah -->
    <x-admin.nav-item 
        href="{{ route('admin.nasabahs.index') }}" 
        icon="fas fa-users"
        :active="request()->routeIs('admin.nasabahs.*')">
        Nasabah
    </x-admin.nav-item>

    <!-- Pinjaman -->
    <x-admin.nav-item 
        href="{{ route('admin.loans.index') }}" 
        icon="fas fa-hand-holding-usd"
        :active="request()->routeIs('admin.loans.*')">
        Pinjaman
    </x-admin.nav-item>

    <!-- Pembayaran -->
    <x-admin.nav-item 
        href="{{ route('admin.payment.form') }}" 
        icon="fas fa-credit-card"
        :active="request()->routeIs('admin.payment.*')">
        Pembayaran
    </x-admin.nav-item>

    <!-- Laporan -->
    <x-admin.nav-item 
        href="" 
        icon="fas fa-chart-line"
        :active="request()->routeIs('admin.laporan.*')">
        Laporan
    </x-admin.nav-item>

    <!-- Financial Management Dropdown (Optional) -->
    {{-- <x-admin.nav-dropdown 
        icon="fas fa-coins" 
        :active="request()->routeIs('admin.keuangan.*')"
        title="Keuangan">
        <x-admin.nav-sub-item href="{{ route('admin.keuangan.kas') }}">
            Kas & Bank
        </x-admin.nav-sub-item>
        <x-admin.nav-sub-item href="{{ route('admin.keuangan.bunga') }}">
            Pengaturan Bunga
        </x-admin.nav-sub-item>
        <x-admin.nav-sub-item href="{{ route('admin.keuangan.denda') }}">
            Denda & Penalty
        </x-admin.nav-sub-item>
    </x-admin.nav-dropdown> --}}

    <!-- Settings -->
    <x-admin.nav-item 
        href="{{ route('admin.settings.index') }}" 
        icon="fas fa-cog"
        :active="request()->routeIs('admin.settings.*')">
        Settings
    </x-admin.nav-item>
</nav>