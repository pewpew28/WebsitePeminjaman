<x-admin-layout title="Nasabah">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-800">Data Nasabah</h1>
            <a href="{{ route('admin.nasabahs.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Tambah Nasabah
            </a>
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- Search and Filter Section --}}
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.nasabahs.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari berdasarkan nama, email, atau nomor telepon..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="{{ route('admin.nasabahs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                        <i class="fas fa-refresh"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Table Container with Responsive Design --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            @if($nasabahs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Nasabah</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Pekerjaan & Pendapatan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Info Pinjaman</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($nasabahs as $nasabah)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    {{-- Data Nasabah (Nama, KTP, Tgl Lahir, Gender) --}}
                                    <td class="px-4 py-4 text-sm">
                                        <div class="space-y-1">
                                            <div class="font-medium text-gray-900">
                                                {{ $nasabah->name ?? '-' }}
                                                <span class="ml-2 text-xs text-gray-500">(ID: {{ $nasabah->id }})</span>
                                            </div>
                                            <div class="text-xs text-gray-500 flex flex-wrap gap-1">
                                                @if($nasabah->id_card_number)
                                                    <span class="bg-gray-100 px-2 py-1 rounded">
                                                        <i class="fas fa-id-card mr-1"></i>{{ $nasabah->id_card_number }}
                                                    </span>
                                                @endif
                                                @if($nasabah->date_of_birth)
                                                    <span class="bg-blue-100 px-2 py-1 rounded">
                                                        <i class="fas fa-birthday-cake mr-1"></i>{{ \Carbon\Carbon::parse($nasabah->date_of_birth)->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                                @if($nasabah->gender)
                                                    <span class="bg-purple-100 px-2 py-1 rounded capitalize">
                                                        <i class="fas {{ $nasabah->gender == 'male' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>{{ $nasabah->gender }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kontak (Email, Phone, Address) --}}
                                    <td class="px-4 py-4 text-sm">
                                        <div class="space-y-1">
                                            @if($nasabah->email)
                                                <div class="flex items-center text-gray-600">
                                                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                                    <span class="truncate">{{ $nasabah->email }}</span>
                                                </div>
                                            @endif
                                            @if($nasabah->phone_number)
                                                <div class="flex items-center text-gray-600">
                                                    <i class="fas fa-phone mr-2 text-gray-400"></i>
                                                    <span>{{ $nasabah->phone_number }}</span>
                                                </div>
                                            @endif
                                            @if($nasabah->address)
                                                <div class="flex items-start text-gray-600">
                                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400 mt-0.5"></i>
                                                    <span class="text-xs line-clamp-2" title="{{ $nasabah->address }}">{{ Str::limit($nasabah->address, 60) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Pekerjaan & Pendapatan --}}
                                    <td class="px-4 py-4 text-sm hidden lg:table-cell">
                                        <div class="space-y-1">
                                            @if($nasabah->occupation)
                                                <div class="flex items-center text-gray-600">
                                                    <i class="fas fa-briefcase mr-2 text-gray-400"></i>
                                                    <span class="font-medium">{{ $nasabah->occupation }}</span>
                                                </div>
                                            @endif
                                            @if($nasabah->monthly_income)
                                                <div class="flex items-center text-gray-600">
                                                    <i class="fas fa-money-bill-wave mr-2 text-gray-400"></i>
                                                    <span class="text-green-600 font-semibold">Rp {{ number_format($nasabah->monthly_income, 0, ',', '.') }}</span>
                                                </div>
                                            @endif
                                            @if(!$nasabah->occupation && !$nasabah->monthly_income)
                                                <span class="text-gray-400 text-xs">Data tidak tersedia</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Info Pinjaman --}}
                                    <td class="px-4 py-4 text-sm hidden md:table-cell">
                                        @php
                                            $totalLoans = $nasabah->loans->count();
                                            $activeLoans = $nasabah->loans->where('status', 'active')->count();
                                            $completedLoans = $nasabah->loans->where('status', 'completed')->count();
                                            $pendingLoans = $nasabah->loans->where('status', 'pending')->count();
                                            $totalAmount = $nasabah->loans->sum('loan_amount');
                                            $hasCollectorTask = $nasabah->collectorTasks->count() > 0;
                                        @endphp
                                        
                                        <div class="space-y-2">
                                            {{-- Total Pinjaman --}}
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-500">Total:</span>
                                                <span class="font-semibold text-gray-800">{{ $totalLoans }} pinjaman</span>
                                            </div>
                                            
                                            {{-- Status Breakdown --}}
                                            @if($totalLoans > 0)
                                                <div class="space-y-1">
                                                    @if($activeLoans > 0)
                                                        <div class="flex items-center justify-between">
                                                            <span class="inline-flex items-center text-xs">
                                                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-1"></div>
                                                                Aktif
                                                            </span>
                                                            <span class="text-xs font-medium text-blue-600">{{ $activeLoans }}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($completedLoans > 0)
                                                        <div class="flex items-center justify-between">
                                                            <span class="inline-flex items-center text-xs">
                                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                                                Selesai
                                                            </span>
                                                            <span class="text-xs font-medium text-green-600">{{ $completedLoans }}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($pendingLoans > 0)
                                                        <div class="flex items-center justify-between">
                                                            <span class="inline-flex items-center text-xs">
                                                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-1"></div>
                                                                Pending
                                                            </span>
                                                            <span class="text-xs font-medium text-yellow-600">{{ $pendingLoans }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                {{-- Total Amount --}}
                                                <div class="pt-1 border-t border-gray-100">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs text-gray-500">Nominal:</span>
                                                        <span class="text-xs font-semibold text-purple-600">
                                                            Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                {{-- Collector Task Indicator --}}
                                                @if($hasCollectorTask)
                                                    <div class="flex items-center">
                                                        <i class="fas fa-exclamation-triangle text-orange-500 mr-1"></i>
                                                        <span class="text-xs text-orange-600 font-medium">Ada Tugas Collector</span>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-center py-2">
                                                    <i class="fas fa-file-invoice text-gray-300 text-2xl mb-1"></i>
                                                    <p class="text-xs text-gray-400">Belum ada pinjaman</p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                                        @php
                                            $statusConfig = match($nasabah->status) {
                                                'active' => ['bg-green-100 text-green-800', 'fas fa-check-circle', 'Aktif'],
                                                'inactive' => ['bg-red-100 text-red-800', 'fas fa-times-circle', 'Tidak Aktif'],
                                                'pending' => ['bg-yellow-100 text-yellow-800', 'fas fa-clock', 'Pending'],
                                                default => ['bg-gray-100 text-gray-800', 'fas fa-question-circle', 'Unknown']
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig[0] }}">
                                            <i class="{{ $statusConfig[1] }} mr-1"></i>
                                            {{ $statusConfig[2] }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-1">
                                            <a href="{{ route('admin.nasabahs.show', $nasabah->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 transition-colors p-2 rounded-md hover:bg-blue-50"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.nasabahs.edit', $nasabah->id) }}" 
                                               class="text-green-600 hover:text-green-800 transition-colors p-2 rounded-md hover:bg-green-50"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.nasabahs.destroy', $nasabah->id) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus nasabah {{ $nasabah->name }}? Data yang sudah dihapus tidak dapat dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 transition-colors p-2 rounded-md hover:bg-red-50"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data nasabah</h3>
                    <p class="text-gray-500 mb-4">Belum ada nasabah yang terdaftar dalam sistem.</p>
                    <a href="{{ route('admin.nasabahs.create') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Tambah Nasabah Pertama
                    </a>
                </div>
            @endif
        </div>

        {{-- Info Summary --}}
        @if($nasabahs->count() > 0)
            <div class="mt-6 bg-white p-4 rounded-lg shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $nasabahs->count() }}</div>
                        <div class="text-sm text-blue-700">Total Nasabah</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $nasabahs->where('status', 'active')->count() }}
                        </div>
                        <div class="text-sm text-green-700">Nasabah Aktif</div>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">
                            {{ $nasabahs->where('status', 'pending')->count() }}
                        </div>
                        <div class="text-sm text-yellow-700">Nasabah Pending</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>