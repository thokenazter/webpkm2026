<x-app-layout>
    {{-- <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Edit LPJ') }}
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
                                <i class="fas fa-edit mr-3"></i>Edit LPJ BOK
                            </h1>
                            <p class="text-amber-100 mb-2">Perbarui data Laporan Pertanggungjawaban</p>
                            <div class="flex items-center text-sm text-amber-200">
                                <i class="fas fa-file-alt mr-2"></i>
                                <span class="font-medium">{{ $lpj->no_surat }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $lpj->kegiatan }}</span>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 flex space-x-2 flex-shrink-0">
                            <a href="{{ route('lpjs.show', $lpj) }}" class="inline-flex items-center bg-white text-amber-600 px-3 py-2 rounded-lg font-medium hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md whitespace-nowrap">
                                <i class="fas fa-eye mr-1 sm:mr-2 flex-shrink-0"></i>
                                <span class="hidden sm:inline">Lihat Detail</span>
                                <span class="sm:hidden">Detail</span>
                            </a>
                            <a href="{{ route('lpjs.index') }}" class="inline-flex items-center bg-amber-800 text-white px-3 py-2 rounded-lg font-medium hover:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-700 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md whitespace-nowrap">
                                <i class="fas fa-arrow-left mr-1 sm:mr-2 flex-shrink-0"></i>
                                <span class="hidden sm:inline">Kembali</span>
                                <span class="sm:hidden">Back</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Indicator -->
            {{-- <div class="mb-8">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-8 h-8 bg-amber-500 text-white rounded-full">
                                <i class="fas fa-edit"></i>
                            </div>
                            <span class="ml-3 font-medium text-amber-600">Edit Informasi LPJ</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="ml-3 font-medium text-gray-500">Update Peserta</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full">
                                <i class="fas fa-save"></i>
                            </div>
                            <span class="ml-3 font-medium text-gray-500">Simpan Perubahan</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full transition-all duration-300" style="width: 33%"></div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Form Content -->
            <form action="{{ route('lpjs.update', $lpj) }}" method="POST" id="lpjForm">
                @csrf
                @method('PUT')
                
                <!-- LPJ Information Section -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 mb-8">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-amber-500 text-white rounded-xl">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Informasi LPJ</h3>
                                <p class="text-sm text-gray-600">Perbarui data dasar LPJ</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tipe LPJ -->
                            <div>
                                <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-tag text-indigo-500 mr-2"></i>Tipe LPJ
                                </label>
                                <select name="type" id="type" 
                                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('type') border-red-500 @enderror">
                                    <option value="">Pilih Tipe LPJ</option>
                                    <option value="SPPT" {{ old('type', $lpj->type) == 'SPPT' ? 'selected' : '' }}>SPPT - Surat Perintah Perjalanan Tugas</option>
                                    <option value="SPPD" {{ old('type', $lpj->type) == 'SPPD' ? 'selected' : '' }}>SPPD - Surat Perintah Perjalanan Dinas</option>
                                </select>
                                @error('type')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Kegiatan -->
                            <div>
                                <label for="kegiatan" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>Kegiatan
                                </label>
                                <input type="text" name="kegiatan" id="kegiatan" value="{{ old('kegiatan', $lpj->kegiatan) }}" 
                                       placeholder="Contoh: Pelayanan HomeCare"
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('kegiatan') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>Isi nama kegiatan secara manual
                                </p>
                                @error('kegiatan')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- No. Surat -->
                            <div>
                                <label for="no_surat" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-hashtag text-purple-500 mr-2"></i>No. Surat
                                </label>
                                <input type="text" name="no_surat" id="no_surat" value="{{ old('no_surat', $lpj->no_surat) }}" 
                                       placeholder="Contoh: 666 Lihat RPK"
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('no_surat') border-red-500 @enderror">
                                @error('no_surat')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Tanggal Surat -->
                            <div>
                                <label for="tanggal_surat" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt text-green-500 mr-2"></i>Tanggal Surat
                                </label>
                                <input type="text" name="tanggal_surat" id="tanggal_surat" value="{{ old('tanggal_surat', $lpj->tanggal_surat) }}" 
                                       placeholder="Contoh: 27 Januari 2025"
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('tanggal_surat') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>Format: "DD Bulan YYYY", contoh: "27 Januari 2025"
                                </p>
                                @error('tanggal_surat')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Tanggal Kegiatan -->
                            <div>
                                <label for="tanggal_kegiatan" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-check text-orange-500 mr-2"></i>Tanggal Kegiatan
                                </label>
                                <input type="text" name="tanggal_kegiatan" id="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', $lpj->tanggal_kegiatan) }}" 
                                       placeholder="Contoh: 23 s/d 25 Juni 2025"
                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('tanggal_kegiatan') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>Format bebas, contoh: "23 s/d 25 Juni 2025" atau "15 Januari 2025"
                                </p>
                                @error('tanggal_kegiatan')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dynamic Sections based on Type -->
                        <div class="mt-6 space-y-6">
                            <!-- SPPT Sections -->
                            <div id="sppt-sections" class="hidden">
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                    <h4 class="font-semibold text-green-800 mb-4 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2"></i>Informasi Desa Darat (SPPT)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div id="desa_darat_section">
                                            <label for="jumlah_desa_darat" class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Desa Darat</label>
                                            <input type="number" name="jumlah_desa_darat" id="jumlah_desa_darat" value="{{ old('jumlah_desa_darat', $lpj->jumlah_desa_darat ?? 0) }}" min="1"
                                                   class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('jumlah_desa_darat') border-red-500 @enderror">
                                            <p class="text-xs text-gray-500 mt-1">Minimal 1 desa untuk SPPT</p>
                                            @error('jumlah_desa_darat')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div id="desa_tujuan_darat_section">
                                            <label for="desa_tujuan_darat" class="block text-sm font-semibold text-gray-700 mb-2">Desa Tujuan (Darat)</label>
                                            <textarea name="desa_tujuan_darat" id="desa_tujuan_darat" rows="3" 
                                                      placeholder="Desa Kabalsiang dan Desa Benjuring"
                                                      class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('desa_tujuan_darat') border-red-500 @enderror">{{ old('desa_tujuan_darat', $lpj->desa_tujuan_darat ?? 'Desa Kabalsiang dan Desa Benjuring') }}</textarea>
                                            <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma jika lebih dari satu desa</p>
                                            @error('desa_tujuan_darat')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SPPD Sections -->
                            <div id="sppd-sections" class="hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <h4 class="font-semibold text-blue-800 mb-4 flex items-center">
                                        <i class="fas fa-ship mr-2"></i>Informasi Desa Seberang (SPPD)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div id="desa_seberang_section">
                                            <label for="jumlah_desa_seberang" class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Desa Seberang</label>
                                            <input type="number" name="jumlah_desa_seberang" id="jumlah_desa_seberang" value="{{ old('jumlah_desa_seberang', $lpj->jumlah_desa_seberang ?? 0) }}" min="1"
                                                   class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('jumlah_desa_seberang') border-red-500 @enderror">
                                            <p class="text-xs text-gray-500 mt-1">Minimal 1 desa untuk SPPD</p>
                                            @error('jumlah_desa_seberang')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div id="transport_mode_section">
                                            <label for="transport_mode" class="block text-sm font-semibold text-gray-700 mb-2">Mode Transport</label>
                                            <select name="transport_mode" id="transport_mode" 
                                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('transport_mode') border-red-500 @enderror">
                                                <option value="">Pilih Mode Transport</option>
                                                <option value="Perahu" {{ old('transport_mode', $lpj->transport_mode) == 'Perahu' ? 'selected' : '' }}>Perahu</option>
                                                <option value="Speedboat" {{ old('transport_mode', $lpj->transport_mode) == 'Speedboat' ? 'selected' : '' }}>Speedboat</option>
                                                <option value="Kapal Motor" {{ old('transport_mode', $lpj->transport_mode) == 'Kapal Motor' ? 'selected' : '' }}>Kapal Motor</option>
                                                <option value="Pompong" {{ old('transport_mode', $lpj->transport_mode) == 'Pompong' ? 'selected' : '' }}>Pompong</option>
                                                <option value="Lainnya" {{ old('transport_mode', $lpj->transport_mode) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            <p class="text-xs text-gray-500 mt-1">Mode transportasi untuk ke desa seberang</p>
                                            @error('transport_mode')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div id="desa_tujuan_seberang_section" class="md:col-span-1">
                                            <label for="desa_tujuan_seberang" class="block text-sm font-semibold text-gray-700 mb-2">Desa Tujuan (Seberang)</label>
                                            <textarea name="desa_tujuan_seberang" id="desa_tujuan_seberang" rows="3" 
                                                      placeholder="Desa Kumul, Desa Batuley dan Desa Kompane"
                                                      class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('desa_tujuan_seberang') border-red-500 @enderror">{{ old('desa_tujuan_seberang', $lpj->desa_tujuan_seberang ?? 'Desa Kumul, Desa Batuley dan Desa Kompane') }}</textarea>
                                            <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma jika lebih dari satu desa</p>
                                            @error('desa_tujuan_seberang')
                                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Participants Section -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 mb-8">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200 sticky top-0 z-10">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-lg">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base font-bold text-gray-900">Peserta Kegiatan</h3>
                                <p class="text-xs text-gray-600">Update peserta yang mengikuti kegiatan</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <div id="participantsContainer">
                            @php $existingParticipants = $lpj->participants ?? []; @endphp
                            @foreach($existingParticipants as $index => $participant)
                            <div class="participant-row bg-gray-50 border-2 border-gray-200 rounded-lg p-4 mb-4 hover:border-indigo-300 transition-colors duration-200" data-index="{{ $index }}">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-base font-semibold text-gray-900 flex items-center">
                                        <div class="w-6 h-6 bg-indigo-500 text-white rounded-md flex items-center justify-center mr-2 text-sm">
                                            {{ $index + 1 }}
                                        </div>
                                        Peserta {{ $index + 1 }}
                                    </h4>
                                    <button type="button" class="remove-participant text-red-600 hover:text-red-800 hover:bg-red-50 px-2 py-1 rounded-md transition duration-200 text-sm {{ $index === 0 ? 'hidden' : '' }}">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            <i class="fas fa-user text-blue-500 mr-1"></i>Pegawai
                                        </label>
                                        <select name="participants[{{ $index }}][employee_id]" class="employee-select block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 text-sm py-2" required>
                                            <option value="">Pilih Pegawai</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ $participant->employee_id == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->nama }} ({{ $employee->pangkat_golongan }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            <i class="fas fa-user-tag text-green-500 mr-1"></i>Sebagai
                                        </label>
                                        <select name="participants[{{ $index }}][role]" class="role-select block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 text-sm py-2">
                                            <option value="KETUA" {{ $participant->role == 'KETUA' ? 'selected' : '' }}>PJ (Penanggung Jawab)</option>
                                            <option value="ANGGOTA" {{ $participant->role == 'ANGGOTA' ? 'selected' : '' }}>ANGGOTA</option>
                                            <option value="PENDAMPING" {{ $participant->role == 'PENDAMPING' ? 'selected' : '' }}>PENDAMPING</option>
                                            <option value="LAINNYA" {{ $participant->role == 'LAINNYA' ? 'selected' : '' }}>LAINNYA</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            <i class="fas fa-calendar-day text-orange-500 mr-1"></i>Lama Tugas (Hari)
                                        </label>
                                        <input type="number" name="participants[{{ $index }}][lama_tugas_hari]" class="lama-tugas-input block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 text-sm py-2" value="{{ $participant->lama_tugas_hari ?? 1 }}" min="1" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            <i class="fas fa-hand-holding-usd text-purple-500 mr-1"></i>Kreditkan ke Pegawai (opsional)
                                        </label>
                                        <select name="participants[{{ $index }}][credited_employee_id]" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 text-sm py-2">
                                            <option value="">Sama dengan pegawai</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ (int)($participant->credited_employee_id) === (int)($employee->id) ? 'selected' : '' }}>
                                                    {{ $employee->nama }} ({{ $employee->pangkat_golongan }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-[11px] text-gray-500 mt-1">Atur penerima saldo jika nama dokumen meminjam ASN.</p>
                                    </div>
                                    <input type="hidden" name="participants[{{ $index }}][transport_amount]" class="transport-amount-hidden" value="{{ $participant->transport_amount ?? 0 }}">
                                    <input type="hidden" name="participants[{{ $index }}][per_diem_rate]" class="per-diem-rate-hidden" value="{{ $participant->per_diem_rate ?? 0 }}">
                                    <input type="hidden" name="participants[{{ $index }}][per_diem_days]" class="per-diem-days-hidden" value="{{ $participant->per_diem_days ?? 0 }}">
                                    <input type="hidden" name="participants[{{ $index }}][per_diem_amount]" class="per-diem-amount-hidden" value="{{ $participant->per_diem_amount ?? 0 }}">
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Floating Add Participant Button - will be moved dynamically -->
                        <div id="floatingAddBtn" class="mt-4 text-center">
                            <button type="button" id="addParticipantBtn" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-plus mr-2"></i>Tambah Peserta
                            </button>
                        </div>
                        
                        <!-- Summary Info -->
                        <div class="mt-4 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-4">
                            <h4 class="text-base font-semibold text-indigo-900 mb-3 flex items-center">
                                <i class="fas fa-calculator mr-2"></i>Ringkasan Biaya
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-blue-600" id="transport_display">Rp 0</div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        <i class="fas fa-car mr-1"></i>Transport per peserta
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-green-600" id="perdiem_display">Rp 0</div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        <i class="fas fa-money-bill-wave mr-1"></i>Uang harian total
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl font-bold text-purple-600" id="total_display">Rp 0</div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        <i class="fas fa-coins mr-1"></i>Total keseluruhan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ route('lpjs.show', $lpj) }}" class="inline-flex items-center justify-center w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <i class="fas fa-times mr-2 flex-shrink-0"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" id="submitBtn" class="inline-flex items-center justify-center w-full sm:w-auto bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-medium py-3 px-8 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 btn-modern">
                        <i class="fas fa-save mr-2 flex-shrink-0"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addParticipant() {
            const container = document.getElementById('participantsContainer');
            const currentParticipants = container.querySelectorAll('.participant-row');
            const newIndex = currentParticipants.length; // Use current count as new index
            
            const participantHtml = `
                <div class="participant-row bg-gray-50 border-2 border-gray-200 rounded-lg p-4 mb-4 hover:border-indigo-300 transition-colors duration-200" data-index="${newIndex}">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-base font-semibold text-gray-900 flex items-center">
                            <div class="w-6 h-6 bg-indigo-500 text-white rounded-md flex items-center justify-center mr-2 text-sm">
                                ${newIndex + 1}
                            </div>
                            Peserta ${newIndex + 1}
                        </h4>
                        <button type="button" class="remove-participant text-red-600 hover:text-red-800 hover:bg-red-50 px-2 py-1 rounded-md transition duration-200 text-sm ${newIndex === 0 ? 'hidden' : ''}">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                <i class="fas fa-user text-blue-500 mr-1"></i>Pegawai
                            </label>
                            <select name="participants[${newIndex}][employee_id]" class="employee-select block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 text-sm py-2" required>
                                <option value="">Pilih Pegawai</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->nama }} ({{ $employee->pangkat_golongan }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                <i class="fas fa-user-tag text-green-500 mr-1"></i>Sebagai
                            </label>
                            <select name="participants[${newIndex}][role]" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 text-sm py-2">
                                <option value="KETUA" ${newIndex === 0 ? 'selected' : ''}>PJ (Penanggung Jawab)</option>
                                <option value="ANGGOTA" ${newIndex > 0 ? 'selected' : ''}>ANGGOTA</option>
                                <option value="PENDAMPING">PENDAMPING</option>
                                <option value="LAINNYA">LAINNYA</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                <i class="fas fa-calendar-day text-orange-500 mr-1"></i>Lama Tugas (Hari)
                            </label>
                            <input type="number" name="participants[${newIndex}][lama_tugas_hari]" class="lama-tugas block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 text-sm py-2" value="1" min="1" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                <i class="fas fa-hand-holding-usd text-purple-500 mr-1"></i>Kreditkan ke Pegawai (opsional)
                            </label>
                            <select name="participants[${newIndex}][credited_employee_id]" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 text-sm py-2">
                                <option value="">Sama dengan pegawai</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->nama }} ({{ $employee->pangkat_golongan }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Hidden Auto-calculated Fields -->
                        <input type="hidden" name="participants[${newIndex}][transport_amount]" class="transport-amount-hidden" value="0">
                        <input type="hidden" name="participants[${newIndex}][per_diem_rate]" class="per-diem-rate-hidden" value="0">
                        <input type="hidden" name="participants[${newIndex}][per_diem_days]" class="per-diem-days-hidden" value="0">
                        <input type="hidden" name="participants[${newIndex}][per_diem_amount]" class="per-diem-amount-hidden" value="0">
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', participantHtml);
            
            // Move the floating add button after the last participant
            moveAddButtonToBottom();
            
            // Add event listeners for the new participant
            setupParticipantEvents();
            calculateAmounts();
        }
        
        function removeParticipant(button) {
            const participantRow = button.closest('.participant-row');
            participantRow.remove();
            
            // Renumber participants
            renumberParticipants();
            
            // Move the floating add button after the last participant
            moveAddButtonToBottom();
            
            calculateAmounts();
        }
        
        function moveAddButtonToBottom() {
            const container = document.getElementById('participantsContainer');
            const floatingBtn = document.getElementById('floatingAddBtn');
            const participants = container.querySelectorAll('.participant-row');
            
            if (participants.length > 0) {
                // Get the last participant
                const lastParticipant = participants[participants.length - 1];
                
                // Insert the floating button after the last participant
                lastParticipant.insertAdjacentElement('afterend', floatingBtn);
            } else {
                // If no participants, keep the button in its original position
                const participantsSection = container.parentElement;
                participantsSection.appendChild(floatingBtn);
            }
        }
        
        function renumberParticipants() {
            const participants = document.querySelectorAll('.participant-row');
            participants.forEach((participant, index) => {
                participant.setAttribute('data-index', index);
                const numberBadge = participant.querySelector('.w-6.h-6');
                const title = participant.querySelector('h4');
                
                if (numberBadge) numberBadge.textContent = index + 1;
                if (title) title.innerHTML = `<div class="w-6 h-6 bg-indigo-500 text-white rounded-md flex items-center justify-center mr-2 text-sm">${index + 1}</div>Peserta ${index + 1}`;
                
                // Update name attributes
                const inputs = participant.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name && name.includes('participants[')) {
                        const newName = name.replace(/participants\[\d+\]/, `participants[${index}]`);
                        input.setAttribute('name', newName);
                    }
                });
                
                // Hide/show remove button
                const removeBtn = participant.querySelector('.remove-participant');
                if (index === 0) {
                    removeBtn.classList.add('hidden');
                } else {
                    removeBtn.classList.remove('hidden');
                }
            });
        }
        
        function setupParticipantEvents() {
            // Remove participant events
            document.querySelectorAll('.remove-participant').forEach(btn => {
                btn.onclick = function() {
                    removeParticipant(this);
                };
            });
        }
        
        function toggleSections() {
            const type = document.getElementById('type').value;
            const spptSections = document.getElementById('sppt-sections');
            const sppdSections = document.getElementById('sppd-sections');
            
            // Hide all sections first
            spptSections.classList.add('hidden');
            sppdSections.classList.add('hidden');
            
            if (type === 'SPPT') {
                spptSections.classList.remove('hidden');
                // Use existing values or defaults
                if (!document.getElementById('jumlah_desa_darat').value) {
                    document.getElementById('jumlah_desa_darat').value = {{ $lpj->jumlah_desa_darat ?? 2 }};
                }
            } else if (type === 'SPPD') {
                sppdSections.classList.remove('hidden');
                // Use existing values or defaults
                if (!document.getElementById('jumlah_desa_seberang').value) {
                    document.getElementById('jumlah_desa_seberang').value = {{ $lpj->jumlah_desa_seberang ?? 3 }};
                }
            }
            
            calculateAmounts();
        }

        function calculateAmounts() {
            const type = document.getElementById('type').value;
            const jumlahDesaDarat = parseInt(document.getElementById('jumlah_desa_darat').value) || 0;
            const jumlahDesaSeberang = parseInt(document.getElementById('jumlah_desa_seberang').value) || 0;
            
            // Get participants first
            const participants = document.querySelectorAll('.participant-row');
            
            let transportPerPeserta = 0;
            let perDiemTotal = 0;
            let lamaTugas = 1;
            
            if (type === 'SPPT' && jumlahDesaDarat > 0) {
                transportPerPeserta = 70000 * jumlahDesaDarat;
                perDiemTotal = 0;
                lamaTugas = jumlahDesaDarat;
            } else if (type === 'SPPD' && jumlahDesaSeberang > 0) {
                transportPerPeserta = 70000 * jumlahDesaSeberang;
                perDiemTotal = 150000 * jumlahDesaSeberang * participants.length; // Total untuk semua peserta
                lamaTugas = jumlahDesaSeberang;
            }
            
            // Update all participants
            let totalTransport = 0;
            let totalPerDiem = 0;
            
            // Calculate per diem per peserta for SPPD
            let perDiemPerPeserta = 0;
            if (type === 'SPPD' && jumlahDesaSeberang > 0) {
                perDiemPerPeserta = 150000 * jumlahDesaSeberang; // Per pegawai dikali jumlah desa
            }
            
            participants.forEach((participant, index) => {
                // Update hidden fields for each participant
                const transportHidden = participant.querySelector('.transport-amount-hidden');
                const perDiemRateHidden = participant.querySelector('.per-diem-rate-hidden');
                const perDiemDaysHidden = participant.querySelector('.per-diem-days-hidden');
                const perDiemAmountHidden = participant.querySelector('.per-diem-amount-hidden');
                const lamaTugasInput = participant.querySelector('.lama-tugas, .lama-tugas-input');
                
                if (transportHidden) transportHidden.value = transportPerPeserta;
                if (lamaTugasInput) lamaTugasInput.value = lamaTugas;
                
                // For SPPD, all participants get per diem
                if (type === 'SPPD') {
                    if (perDiemRateHidden) perDiemRateHidden.value = perDiemPerPeserta;
                    if (perDiemDaysHidden) perDiemDaysHidden.value = 1;
                    if (perDiemAmountHidden) perDiemAmountHidden.value = perDiemPerPeserta;
                    totalPerDiem += perDiemPerPeserta;
                } else {
                    // For SPPT, no per diem
                    if (perDiemRateHidden) perDiemRateHidden.value = 0;
                    if (perDiemDaysHidden) perDiemDaysHidden.value = 0;
                    if (perDiemAmountHidden) perDiemAmountHidden.value = 0;
                }
                
                totalTransport += transportPerPeserta;
            });
            
            const grandTotal = totalTransport + totalPerDiem;
            
            // Update display
            document.getElementById('transport_display').textContent = 'Rp ' + transportPerPeserta.toLocaleString('id-ID');
            document.getElementById('perdiem_display').textContent = 'Rp ' + totalPerDiem.toLocaleString('id-ID');
            document.getElementById('total_display').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }
        
        // Add event listeners
        document.getElementById('type').addEventListener('change', toggleSections);
        document.getElementById('jumlah_desa_darat').addEventListener('input', calculateAmounts);
        document.getElementById('jumlah_desa_seberang').addEventListener('input', calculateAmounts);
        
        // Prepare form before submission
        function prepareFormSubmission(e) {
            // Ensure all calculations are up to date
            calculateAmounts();
            
            // Check required fields
            const type = document.getElementById('type').value;
            if (!type) {
                alert('Pilih tipe LPJ terlebih dahulu');
                e.preventDefault();
                return false;
            }
            
            return true;
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Edit Page');
            
            // Initialize sections based on current LPJ type
            toggleSections();
            
            // Setup existing participant events
            setupParticipantEvents();
            
            // Move add button to bottom initially
            moveAddButtonToBottom();
            
            // Calculate amounts for existing data
            calculateAmounts();
            
            // Add event listener for "Tambah Peserta" button
            const addParticipantBtn = document.getElementById('addParticipantBtn');
            if (addParticipantBtn) {
                addParticipantBtn.addEventListener('click', addParticipant);
                console.log('Add participant button listener added');
            }
            
            // Check if elements exist
            const form = document.getElementById('lpjForm');
            const submitBtn = document.getElementById('submitBtn');
            
            console.log('Form element:', form);
            console.log('Submit button:', submitBtn);
            
            if (!form) {
                console.error('Form not found!');
                return;
            }
            
            if (!submitBtn) {
                console.error('Submit button not found!');
                return;
            }
            
            // Submit button click event
            submitBtn.addEventListener('click', function(e) {
                console.log('Submit button clicked - event:', e);
                console.log('Button type:', submitBtn.type);
                
                // Prevent default if it's a button, not submit type
                if (submitBtn.type !== 'submit') {
                    e.preventDefault();
                }
                
                // Prepare and submit form
                if (prepareFormSubmission(e)) {
                    console.log('Attempting to submit form...');
                    form.submit();
                }
            });
            
            // Form submit event
            form.addEventListener('submit', function(e) {
                console.log('Form submit event triggered');
                console.log('Event:', e);
                return prepareFormSubmission(e);
            });
            
            console.log('Event listeners added successfully');
            
            // Test if JavaScript is working
            console.log('JavaScript is working properly - Edit Page');
        });
    </script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
