<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-teal-600 to-cyan-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold mb-2 flex items-center">
                                <i class="fas fa-user-circle mr-3"></i>Detail Pegawai BOK
                            </h1>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-teal-100">
                                <div>
                                    <p class="text-sm opacity-80">Nama Pegawai</p>
                                    <p class="text-lg font-semibold">{{ $employee->nama }}</p>
                                </div>
                                <div>
                                    <p class="text-sm opacity-80">NIP</p>
                                    <p class="text-lg font-mono">{{ $employee->nip }}</p>
                                </div>
                                <div>
                                    <p class="text-sm opacity-80">Pangkat/Golongan</p>
                                    <p class="text-lg font-semibold">{{ $employee->pangkat_golongan }}</p>
                                </div>
                                <div>
                                    <p class="text-sm opacity-80">Jabatan</p>
                                    <p class="text-lg">{{ $employee->jabatan ?? 'Tidak ada' }}</p>
                                </div>
                            </div>
                            <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-teal-200">
                                <span class="flex items-center">
                                    <i class="fas fa-birthday-cake mr-2"></i>{{ $employee->tanggal_lahir->format('d F Y') }} ({{ $employee->tanggal_lahir->age }} tahun)
                                </span>
                                {{-- <span class="flex items-center">
                                    <i class="fas fa-file-alt mr-2"></i>{{ $employee->lpjParticipants->count() }} kegiatan LPJ
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Total: Rp {{ number_format($employee->lpjParticipants->sum('total_amount'), 0, ',', '.') }}
                                </span> --}}
                            </div>
                        </div>
                        <div class="mt-4 lg:mt-0 flex flex-wrap gap-3">
                            <a href="{{ route('employees.edit', $employee) }}" class="bg-white text-teal-600 px-4 py-2 rounded-lg font-semibold hover:bg-teal-50 transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-edit mr-2"></i>Edit Data
                            </a>
                            <a href="{{ route('employees.index') }}" class="bg-teal-800 text-white px-4 py-2 rounded-lg font-semibold hover:bg-teal-900 transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Employee Meta Info -->
                    <div class="bg-gradient-to-br from-teal-50 to-cyan-100 border border-teal-200 rounded-2xl p-4">
                        <h3 class="font-bold text-teal-900 mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>Informasi Sistem
                        </h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-teal-700 font-medium">Dibuat:</span>
                                <span class="text-teal-900">{{ $employee->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-teal-700 font-medium">Diperbarui:</span>
                                <span class="text-teal-900">{{ $employee->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-teal-700 font-medium">ID Pegawai:</span>
                                <span class="text-teal-900 font-mono">#{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>