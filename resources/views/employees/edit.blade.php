<x-app-layout>
    {{-- <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Edit Pegawai') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ Carbon\Carbon::now()->format('d F Y') }}
            </div>
        </div>
    </x-slot> --}}

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-amber-600 to-orange-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h1 class="text-2xl font-bold mb-2 flex items-center">
                                <i class="fas fa-user-edit mr-3"></i>Edit Data Pegawai
                            </h1>
                            <p class="text-amber-100 mb-2">Perbarui data pegawai yang sudah terdaftar</p>
                            <div class="flex items-center text-sm text-amber-200">
                                <i class="fas fa-user mr-2"></i>
                                <span class="font-medium">{{ $employee->nama }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $employee->nip }}</span>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 flex space-x-3">
                            <a href="{{ route('employees.show', $employee) }}" class="bg-white text-amber-600 px-4 py-2 rounded-lg font-semibold hover:bg-amber-50 transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-eye mr-2"></i>Lihat Detail
                            </a>
                            <a href="{{ route('employees.index') }}" class="bg-amber-800 text-white px-4 py-2 rounded-lg font-semibold hover:bg-amber-900 transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-amber-500 text-white rounded-xl">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">Form Edit Pegawai</h3>
                            <p class="text-sm text-gray-600">Perbarui data pegawai dengan informasi terbaru</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user text-blue-500 mr-2"></i>Nama Lengkap
                                </label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama', $employee->nama) }}" 
                                       placeholder="Contoh: Dr. Budi Santoso"
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 transition duration-200 @error('nama') border-red-500 @enderror">
                                @error('nama')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- NIP -->
                            <div>
                                <label for="nip" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-id-card text-purple-500 mr-2"></i>NIP
                                </label>
                                <input type="text" name="nip" id="nip" value="{{ old('nip', $employee->nip) }}" 
                                       placeholder="Contoh: 196701011990031001"
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 transition duration-200 font-mono @error('nip') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>Nomor Induk Pegawai (18 digit)
                                </p>
                                @error('nip')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar text-orange-500 mr-2"></i>Tanggal Lahir
                                </label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $employee->tanggal_lahir->format('Y-m-d')) }}" 
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 transition duration-200 @error('tanggal_lahir') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>Usia saat ini: {{ $employee->tanggal_lahir->age }} tahun
                                </p>
                                @error('tanggal_lahir')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Pangkat/Golongan -->
                            <div>
                                <label for="pangkat_golongan" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-award text-yellow-500 mr-2"></i>Pangkat/Golongan
                                </label>
                                <select name="pangkat_golongan" id="pangkat_golongan" 
                                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 transition duration-200 @error('pangkat_golongan') border-red-500 @enderror">
                                    <option value="">Pilih Pangkat/Golongan</option>
                                    @foreach(['I/a', 'I/b', 'I/c', 'I/d', 'II/a', 'II/b', 'II/c', 'II/d', 'III/a', 'III/b', 'III/c', 'III/d', 'IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e','IV','V','VI','VII','VIII','IX','X'] as $pangkat)
                                        <option value="{{ $pangkat }}" {{ old('pangkat_golongan', $employee->pangkat_golongan) == $pangkat ? 'selected' : '' }}>{{ $pangkat }}</option>
                                    @endforeach
                                </select>
                                @error('pangkat_golongan')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Jabatan -->
                            <div class="md:col-span-2">
                                <label for="jabatan" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-briefcase text-indigo-500 mr-2"></i>Jabatan
                                </label>
                                <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan', $employee->jabatan) }}" 
                                       placeholder="Contoh: Dokter Spesialis Anak"
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 transition duration-200 @error('jabatan') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>Jabatan atau posisi pegawai (opsional)
                                </p>
                                @error('jabatan')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 flex justify-between items-center">
                            <a href="{{ route('employees.show', $employee) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-xl transition duration-200 shadow-md">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-semibold py-3 px-8 rounded-xl transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Data Info -->
            <div class="mt-8 bg-amber-50 border border-amber-200 rounded-2xl p-6">
                <div class="flex items-start">
                    <div class="flex items-center justify-center w-10 h-10 bg-amber-100 rounded-lg mr-4 flex-shrink-0">
                        <i class="fas fa-user-circle text-amber-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-amber-900 mb-3">Data Pegawai Saat Ini</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="flex justify-between">
                                <span class="font-medium text-amber-800">Nama:</span>
                                <span class="text-amber-900">{{ $employee->nama }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-amber-800">NIP:</span>
                                <span class="text-amber-900 font-mono">{{ $employee->nip }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-amber-800">Pangkat:</span>
                                <span class="text-amber-900">{{ $employee->pangkat_golongan }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-amber-800">Jabatan:</span>
                                <span class="text-amber-900">{{ $employee->jabatan ?? 'Tidak ada' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-amber-800">Tanggal Lahir:</span>
                                <span class="text-amber-900">{{ $employee->tanggal_lahir->format('d F Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-amber-800">Usia:</span>
                                <span class="text-amber-900">{{ $employee->tanggal_lahir->age }} tahun</span>
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