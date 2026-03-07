<x-app-layout>
    {{-- <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Tambah Pegawai') }}
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
                <div class="bg-gradient-to-r from-green-600 to-emerald-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h1 class="text-2xl font-bold mb-2 flex items-center">
                                <i class="fas fa-user-plus mr-3"></i>Tambah Pegawai Baru
                            </h1>
                            <p class="text-green-100">Daftarkan pegawai baru untuk kegiatan LPJ BOK</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('employees.index') }}" class="bg-white text-green-600 px-6 py-2 rounded-lg font-semibold hover:bg-green-50 transition duration-200 shadow-md btn-modern">
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
                        <div class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-xl">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-gray-900">Form Data Pegawai</h3>
                            <p class="text-sm text-gray-600">Lengkapi data pegawai dengan benar</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user text-blue-500 mr-2"></i>Nama Lengkap
                                </label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" 
                                       placeholder="Contoh: Dr. Budi Santoso"
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-200 @error('nama') border-red-500 @enderror">
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
                                <input type="text" name="nip" id="nip" value="{{ old('nip') }}" 
                                       placeholder="Contoh: 196701011990031001"
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-200 font-mono @error('nip') border-red-500 @enderror">
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
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" 
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-200 @error('tanggal_lahir') border-red-500 @enderror">
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
                                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-200 @error('pangkat_golongan') border-red-500 @enderror">
                                    <option value="">Pilih Pangkat/Golongan</option>
                                    @foreach(['I/a', 'I/b', 'I/c', 'I/d', 'II/a', 'II/b', 'II/c', 'II/d', 'III/a', 'III/b', 'III/c', 'III/d', 'IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e', 'IV','V','VI','VII','VIII','IX','X'] as $pangkat)
                                        <option value="{{ $pangkat }}" {{ old('pangkat_golongan') == $pangkat ? 'selected' : '' }}>{{ $pangkat }}</option>
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
                                <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}" 
                                       placeholder="Contoh: Dokter Spesialis Anak"
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition duration-200 @error('jabatan') border-red-500 @enderror">
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
                            <a href="{{ route('employees.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-xl transition duration-200 shadow-md">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3 px-8 rounded-xl transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-save mr-2"></i>Simpan Pegawai
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-2xl p-6">
                <div class="flex items-start">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg mr-4 flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-blue-900 mb-2">Informasi Penting</h3>
                        <ul class="text-blue-800 space-y-1 text-sm">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                Pastikan data yang dimasukkan sudah benar dan sesuai dokumen resmi
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                NIP harus unik dan tidak boleh sama dengan pegawai lain
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                Pegawai yang sudah terdaftar dapat digunakan untuk kegiatan LPJ
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>