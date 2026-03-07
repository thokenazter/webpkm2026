<x-app-layout>
    {{-- <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Buat LPJ Baru') }}
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
                            <h1 class="text-2xl font-bold mb-2">Buat LPJ BOK Baru</h1>
                            <p class="text-green-100">Buat Laporan Pertanggungjawaban dengan mudah dan akurat</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex-shrink-0">
                            <a href="{{ route('lpjs.index') }}" class="inline-flex items-center bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md whitespace-nowrap">
                                <i class="fas fa-arrow-left mr-2 flex-shrink-0"></i>
                                <span class="hidden sm:inline">Kembali ke Daftar</span>
                                <span class="sm:hidden">Kembali</span>
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
                            <div class="flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <span class="ml-3 font-medium text-green-600">Informasi LPJ</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="ml-3 font-medium text-gray-500">Peserta</span>
                        </div>
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="ml-3 font-medium text-gray-500">Selesai</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: 33%"></div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Form Content -->
            <form action="{{ route('lpjs.store') }}" method="POST" id="lpjForm">
                @csrf
                @if(isset($sourceLpjId))
                    <input type="hidden" name="source_lpj_id" value="{{ $sourceLpjId }}">
                @endif
                
                <!-- LPJ Information Section -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 mb-8">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-xl">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Informasi LPJ</h3>
                                <p class="text-sm text-gray-600">Lengkapi data dasar LPJ yang akan dibuat</p>
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
                                    <option value="SPPT" {{ old('type') == 'SPPT' ? 'selected' : '' }}>SPPT - Surat Perintah Perjalanan Tugas</option>
                                    <option value="SPPD" {{ old('type') == 'SPPD' ? 'selected' : '' }}>SPPD - Surat Perintah Perjalanan Dinas</option>
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
                                <select name="kegiatan" id="kegiatan" class="activity-select mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('kegiatan') border-red-500 @enderror">
                                    @if(old('kegiatan'))
                                        <option value="{{ old('kegiatan') }}" selected>{{ old('kegiatan') }}</option>
                                    @endif
                                </select>
                                <div class="activity-search-info">
                                    <i class="fas fa-search mr-1"></i>Ketik untuk mencari kegiatan atau masukkan nama baru
                                </div>
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
                                <input type="text" name="no_surat" id="no_surat" value="{{ old('no_surat') }}" 
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
                                <input type="text" name="tanggal_surat" id="tanggal_surat" value="{{ old('tanggal_surat') }}" 
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
                                <input type="text" name="tanggal_kegiatan" id="tanggal_kegiatan" value="{{ old('tanggal_kegiatan') }}" 
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
                                            <input type="number" name="jumlah_desa_darat" id="jumlah_desa_darat" value="{{ old('jumlah_desa_darat', 0) }}" min="1"
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
                                                      class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('desa_tujuan_darat') border-red-500 @enderror">{{ old('desa_tujuan_darat', 'Desa Kabalsiang dan Desa Benjuring') }}</textarea>
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
                                            <input type="number" name="jumlah_desa_seberang" id="jumlah_desa_seberang" value="{{ old('jumlah_desa_seberang', 0) }}" min="1"
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
                                                <option value="Perahu" {{ old('transport_mode') == 'Perahu' ? 'selected' : '' }}>Perahu</option>
                                                <option value="Speedboat" {{ old('transport_mode') == 'Speedboat' ? 'selected' : '' }}>Speedboat</option>
                                                <option value="Kapal Motor" {{ old('transport_mode') == 'Kapal Motor' ? 'selected' : '' }}>Kapal Motor</option>
                                                <option value="Pompong" {{ old('transport_mode') == 'Pompong' ? 'selected' : '' }}>Pompong</option>
                                                <option value="Lainnya" {{ old('transport_mode') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                                      class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 @error('desa_tujuan_seberang') border-red-500 @enderror">{{ old('desa_tujuan_seberang', 'Desa Kumul, Desa Batuley dan Desa Kompane') }}</textarea>
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
                                <p class="text-xs text-gray-600">Tambahkan pegawai yang akan mengikuti kegiatan</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <div id="participantsContainer">
                            <!-- Participants will be added here dynamically -->
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
                    <a href="{{ route('lpjs.index') }}" class="inline-flex items-center justify-center w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <i class="fas fa-times mr-2 flex-shrink-0"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" id="submitBtn" class="inline-flex items-center justify-center w-full sm:w-auto bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium py-3 px-8 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 btn-modern">
                        <i class="fas fa-save mr-2 flex-shrink-0"></i>
                        <span>Simpan LPJ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Select2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Select2 CSS for searchable select -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Custom Select2 styling -->
    <style>
        .select2-container--default .select2-selection--single {
            height: 42px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.75rem !important;
            padding: 0 12px !important;
            display: flex !important;
            align-items: center !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important;
            padding-left: 0 !important;
            color: #374151 !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 8px !important;
        }
        
        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
        
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 8px 12px !important;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6 !important;
        }
        
        .select2-container--default .select2-results__option {
            padding: 8px 12px !important;
        }
        
        .select2-container {
            width: 100% !important;
        }
        
        .employee-search-info, .activity-search-info {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 4px;
        }
    </style>

    <script>
        // Inject default rates from RateSetting
        window.__DEFAULT_RATES__ = {
            transport: {{ (int) (\App\Models\RateSetting::getTransportRate()) }},
            per_diem: {{ (int) (\App\Models\RateSetting::getPerDiemRate()) }}
        };
        
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
                            <div class="employee-search-info text-xs text-gray-500 mt-1">
                                <i class="fas fa-search mr-1"></i>Cari nama pegawai
                            </div>
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
                            <p class="text-[11px] text-gray-500 mt-1">Jika pinjam nama ASN, pilih pelaksana sebenarnya di sini agar saldo masuk ke orang yang bekerja.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                <i class="fas fa-user-tag text-green-500 mr-1"></i>Sebagai
                            </label>
                            <select name="participants[${newIndex}][role]" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 text-sm py-2">
                                <option value="KETUA" ${newIndex === 0 ? 'selected' : ''}>PJ (Penanggung Jawab)</option>
                                <option value="ANGGOTA" ${newIndex > 0 ? 'selected' : ''}>PENDAMPING</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                <i class="fas fa-calendar-day text-orange-500 mr-1"></i>Lama Tugas (Hari)
                            </label>
                            <input type="number" name="participants[${newIndex}][lama_tugas_hari]" class="lama-tugas block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 text-sm py-2" value="1" min="1" required>
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
            
            // Initialize Select2 for the new employee select
            const newEmployeeSelect = container.querySelector(`.participant-row[data-index="${newIndex}"] .employee-select`);
            if (newEmployeeSelect) {
                initializeEmployeeSelect(newEmployeeSelect);
            }
            
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
            
            // Reset values
            document.getElementById('jumlah_desa_darat').value = 0;
            document.getElementById('jumlah_desa_seberang').value = 0;
            document.getElementById('transport_mode').value = '';
            
            if (type === 'SPPT') {
                spptSections.classList.remove('hidden');
                document.getElementById('jumlah_desa_darat').value = 2;
                // Set default desa tujuan darat jika kosong
                const desaTujuanDarat = document.getElementById('desa_tujuan_darat');
                if (!desaTujuanDarat.value.trim()) {
                    desaTujuanDarat.value = 'Desa Kabalsiang dan Desa Benjuring';
                }
            } else if (type === 'SPPD') {
                sppdSections.classList.remove('hidden');
                document.getElementById('jumlah_desa_seberang').value = 3;
                document.getElementById('transport_mode').value = 'Speedboat';
                // Set default desa tujuan seberang jika kosong
                const desaTujuanSeberang = document.getElementById('desa_tujuan_seberang');
                if (!desaTujuanSeberang.value.trim()) {
                    desaTujuanSeberang.value = 'Desa Kumul, Desa Batuley dan Desa Kompane';
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
            const rabInfo = window.__RAB_INFO__ || {};
            const transportRate = window.__DEFAULT_RATES__?.transport ?? 70000; // Transport fixed @ 70k (via RateSetting)
            const perDiemRate = (typeof rabInfo.per_diem_rate === 'number' && rabInfo.per_diem_rate > 0) ? rabInfo.per_diem_rate : (window.__DEFAULT_RATES__?.per_diem ?? 150000);
            
            if (type === 'SPPT' && jumlahDesaDarat > 0) {
                transportPerPeserta = transportRate * jumlahDesaDarat;
                perDiemTotal = 0;
                lamaTugas = jumlahDesaDarat;
            } else if (type === 'SPPD' && jumlahDesaSeberang > 0) {
                transportPerPeserta = transportRate * jumlahDesaSeberang;
                perDiemTotal = perDiemRate * jumlahDesaSeberang * participants.length; // Total untuk semua peserta
                lamaTugas = jumlahDesaSeberang;
            }
            
            // Update all participants
            let totalTransport = 0;
            let totalPerDiem = 0;
            
            // Calculate per diem per peserta for SPPD
            let perDiemPerPeserta = 0;
            if (type === 'SPPD' && jumlahDesaSeberang > 0) {
                perDiemPerPeserta = perDiemRate * jumlahDesaSeberang; // Per pegawai dikali jumlah desa
            }
            
            participants.forEach((participant, index) => {
                // Update hidden fields for each participant
                const transportHidden = participant.querySelector('.transport-amount-hidden');
                const perDiemRateHidden = participant.querySelector('.per-diem-rate-hidden');
                const perDiemDaysHidden = participant.querySelector('.per-diem-days-hidden');
                const perDiemAmountHidden = participant.querySelector('.per-diem-amount-hidden');
                const lamaTugasInput = participant.querySelector('.lama-tugas');
                
                if (transportHidden) transportHidden.value = transportPerPeserta;
                if (lamaTugasInput) lamaTugasInput.value = lamaTugas;
                
                // For SPPD, all participants get per diem (dibagi rata)
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
        
        // Initialize searchable employee selects - FIXED VERSION
        function initializeEmployeeSelect(selectElement) {
            $(selectElement).select2({
                placeholder: 'Klik untuk memilih atau cari pegawai...',
                allowClear: true,
                ajax: {
                    url: '{{ route("lpjs.search.employees") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        
                        return {
                            results: data.results,
                            pagination: {
                                more: false // No pagination for now
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
                templateResult: function(employee) {
                    if (employee.loading) {
                        return employee.text;
                    }
                    
                    if (!employee.nama) {
                        return employee.text;
                    }
                    
                    var $result = $(
                        '<div class="employee-result">' +
                            '<div class="font-semibold text-gray-900">' + employee.nama + '</div>' +
                            '<div class="text-sm text-gray-600">' + employee.pangkat_golongan + '</div>' +
                            (employee.nip ? '<div class="text-xs text-gray-500">NIP: ' + employee.nip + '</div>' : '') +
                        '</div>'
                    );
                    
                    return $result;
                },
                templateSelection: function(employee) {
                    if (employee.nama) {
                        return employee.nama + ' (' + employee.pangkat_golongan + ')';
                    }
                    return employee.text;
                }
            });
            
            // REMOVED AUTO-OPEN FUNCTIONALITY - No longer auto-opens dropdown
            // User must click on the input field to open dropdown and search
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            
            toggleSections();
            
            // Flag from Blade if we have prefill
            window.__HAS_PREFILL__ = {{ isset($prefill) ? 'true' : 'false' }};
            
            // Add first participant by default (skip when prefilled)
            if (!window.__HAS_PREFILL__) {
                addParticipant();
            }
            
            // Add event listener for "Tambah Peserta" button with RAB limit
            const addParticipantBtn = document.getElementById('addParticipantBtn');
            if (addParticipantBtn) {
                addParticipantBtn.addEventListener('click', function(){
                    const info = window.__RAB_INFO__ || {};
                    const container = document.getElementById('participantsContainer');
                    const count = container.querySelectorAll('.participant-row').length;
                    if (typeof info.orang === 'number' && info.orang > 0 && count >= info.orang) {
                        showNotification('Maksimal peserta sesuai RAB: ' + info.orang, 'error');
                        return;
                    }
                    addParticipant();
                });
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
            console.log('JavaScript is working properly');
        });
        
        // Initialize searchable activity select
        function initializeActivitySelect() {
            $('#kegiatan').select2({
                placeholder: 'Ketik untuk mencari kegiatan atau masukkan nama baru...',
                allowClear: true,
                tags: true, // Allow custom input
                ajax: {
                    url: '{{ route("lpjs.search.activities") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        
                        // Check if the search term exists in results
                        var searchTerm = params.term ? params.term.toLowerCase().trim() : '';
                        var exactMatch = false;
                        
                        if (searchTerm && data.results) {
                            exactMatch = data.results.some(function(item) {
                                return item.nama && item.nama.toLowerCase() === searchTerm;
                            });
                        }
                        
                        // Store the exact match status for createTag function
                        $('#kegiatan').data('exact-match', exactMatch);
                        $('#kegiatan').data('search-term', searchTerm);
                        
                        return {
                            results: data.results,
                            pagination: {
                                more: false // No pagination for now
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
                templateResult: function(activity) {
                    if (activity.loading) {
                        return activity.text;
                    }
                    
                    // If it's a custom tag (new activity)
                    if (activity.id === activity.text && activity.newTag) {
                        // Check if there's an exact match - only show "add new" if no exact match
                        var exactMatch = $('#kegiatan').data('exact-match');
                        if (!exactMatch) {
                            return $('<div class="activity-result-new"><i class="fas fa-plus mr-2 text-green-500"></i>Tambah kegiatan baru: "<strong>' + activity.text + '</strong>"</div>');
                        } else {
                            return null; // Don't show "add new" option if exact match exists
                        }
                    }
                    
                    if (!activity.nama) {
                        return activity.text;
                    }
                    
                    var $result = $(
                        '<div class="activity-result">' +
                            '<div class="font-semibold text-gray-900">' + activity.nama + '</div>' +
                        '</div>'
                    );
                    
                    return $result;
                },
                templateSelection: function(activity) {
                    if (activity.nama) {
                        return activity.nama;
                    }
                    return activity.text;
                },
                createTag: function (params) {
                    var term = $.trim(params.term);
                    
                    if (term === '') {
                        return null;
                    }
                    
                    // Check if there's an exact match in the database
                    var exactMatch = $('#kegiatan').data('exact-match');
                    var searchTerm = $('#kegiatan').data('search-term');
                    
                    // Only allow creating new tag if no exact match found
                    if (exactMatch || term.toLowerCase() !== searchTerm) {
                        return null;
                    }
                    
                    return {
                        id: term,
                        text: term,
                        newTag: true // add additional parameters
                    };
                }
            });
            
            // Handle selection of new activity & fetch RAB info
            $('#kegiatan').on('select2:select', function (e) {
                var data = e.params.data;
                if (data.newTag) {
                    createNewActivity(data.text);
                }
                try {
                    const nama = data.nama || data.text || '';
                    if (nama) {
                        fetch(`{{ route('rabs.info_by_kegiatan') }}?kegiatan=${encodeURIComponent(nama)}`)
                            .then(r => r.json())
                            .then(payload => {
                                const info = payload && payload.data ? payload.data : null;
                                window.__RAB_INFO__ = info || null;
                                // Auto-adjust participants to match orang if present (only if no prefill)
                                if (info && typeof info.orang === 'number' && info.orang > 0 && !window.__HAS_PREFILL__) {
                                    const container = document.getElementById('participantsContainer');
                                    let current = container.querySelectorAll('.participant-row').length;
                                    while (current < info.orang) { addParticipant(); current++; }
                                    while (current > info.orang) {
                                        const rows = container.querySelectorAll('.participant-row');
                                        if (rows.length > 1) { rows[rows.length - 1].remove(); current--; }
                                        else break;
                                    }
                                    renumberParticipants();
                                }
                                calculateAmounts();
                                // Fetch allocation summary for this kegiatan & year
                                const year = new Date().getFullYear();
                                fetch(`{{ route('allocations.summary_by_kegiatan') }}?kegiatan=${encodeURIComponent(nama)}&year=${year}`)
                                    .then(r => r.json())
                                    .then(sum => {
                                        if (sum && sum.data) {
                                            const d = sum.data;
                                            const msg = `Alokasi ${d.year}: Rp ${Number(d.allocated_amount||0).toLocaleString('id-ID')} · Realisasi: Rp ${Number(d.realized_amount||0).toLocaleString('id-ID')} · Sisa: Rp ${Number(d.remaining_amount||0).toLocaleString('id-ID')}`;
                                            showNotification(msg, d.remaining_amount >= 0 ? 'success' : 'error');
                                        }
                                    })
                                    .catch(() => {});
                            })
                            .catch(() => {});
                    }
                } catch (err) { console.warn('Fetch RAB info failed', err); }
            });
        }
        
        // Function to create new activity
        function createNewActivity(activityName) {
            $.ajax({
                url: '{{ route("lpjs.create.activity") }}',
                method: 'POST',
                data: {
                    nama: activityName,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        showNotification(response.message, 'success');
                    }
                },
                error: function(xhr) {
                    var message = 'Gagal membuat kegiatan baru.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showNotification(message, 'error');
                }
            });
        }
        
        // Function to show notification
        function showNotification(message, type) {
            var bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            var notification = $(`
                <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 notification">
                    <div class="flex items-center">
                        <i class="fas ${type === 'success' ? 'fa-check' : 'fa-exclamation-triangle'} mr-2"></i>
                        <span>${message}</span>
                    </div>
                </div>
            `);
            
            $('body').append(notification);
            
            setTimeout(function() {
                notification.fadeOut(function() {
                    notification.remove();
                });
            }, 3000);
        }

        // Initialize Select2 for existing employee selects
        $(document).ready(function() {
            $('.employee-select').each(function() {
                initializeEmployeeSelect(this);
            });
            
            // Initialize activity select
            initializeActivitySelect();
        });
    </script>

    @php
        // Build employees map for prefill (id => display text)
        $employeesMap = isset($employees) ? $employees->mapWithKeys(function($e){
            return [$e->id => $e->nama . ($e->pangkat_golongan ? ' (' . $e->pangkat_golongan . ')' : '')];
        }) : collect();
    @endphp

    @if(isset($prefill))
    <script>
        // Prefill payload from server
        window.prefill = @json($prefill);
        window.employeesMap = @json($employeesMap);

        document.addEventListener('DOMContentLoaded', function() {
            try {
                if (!window.prefill) return;

                // Set type and toggle sections
                const typeSelect = document.getElementById('type');
                if (typeSelect && window.prefill.type) {
                    typeSelect.value = window.prefill.type;
                    toggleSections();
                }

                // Set kegiatan via Select2
                if (window.prefill.kegiatan) {
                    const kegiatan = $('#kegiatan');
                    const opt = new Option(window.prefill.kegiatan, window.prefill.kegiatan, true, true);
                    kegiatan.append(opt).trigger('change');
                }

                // Clear dates and no_surat if provided as empty
                if (typeof window.prefill.no_surat !== 'undefined') {
                    const el = document.getElementById('no_surat');
                    if (el) el.value = window.prefill.no_surat || '';
                }
                if (typeof window.prefill.tanggal_surat !== 'undefined') {
                    const el = document.getElementById('tanggal_surat');
                    if (el) el.value = window.prefill.tanggal_surat || '';
                }
                if (typeof window.prefill.tanggal_kegiatan !== 'undefined') {
                    const el = document.getElementById('tanggal_kegiatan');
                    if (el) el.value = window.prefill.tanggal_kegiatan || '';
                }

                // Add participants
                if (Array.isArray(window.prefill.participants)) {
                    window.prefill.participants.forEach(function(p) {
                        // Add new participant row
                        addParticipant();
                        const participants = document.querySelectorAll('.participant-row');
                        const idx = participants.length - 1;
                        const row = participants[idx];
                        if (!row) return;

                        // Set role select
                        const roleSelect = row.querySelector(`select[name="participants[${idx}][role]"]`);
                        if (roleSelect && p.role) {
                            roleSelect.value = p.role;
                        }

                        // Set employee select2 with proper text
                        const empSelect = row.querySelector('.employee-select');
                        if (empSelect && p.employee_id) {
                            const display = (window.employeesMap && window.employeesMap[p.employee_id]) ? window.employeesMap[p.employee_id] : ('Pegawai #' + p.employee_id);
                            const opt = new Option(display, p.employee_id, true, true);
                            $(empSelect).append(opt).trigger('change');
                        }
                    });
                }

                // Recalculate amounts after prefill
                calculateAmounts();

                // If coming from SPPT to SPPD, highlight fields that must be re-filled
                try {
                    const isFromOther = !!document.querySelector('input[name="source_lpj_id"]');
                    const targetType = (document.getElementById('type')?.value || '').toUpperCase();
                    if (isFromOther && targetType === 'SPPD') {
                        const toMark = ['no_surat', 'tanggal_surat', 'tanggal_kegiatan'];
                        toMark.forEach(function(id) {
                            markNeedsRefill(id);
                        });
                        // Focus first required field
                        const first = document.getElementById('no_surat') || document.getElementById('tanggal_surat') || document.getElementById('tanggal_kegiatan');
                        if (first) first.focus();
                    }
                } catch (e) {
                    console.warn('Mark needs-refill failed:', e);
                }
            } catch (e) {
                console.error('Prefill error:', e);
            }
        });

        function markNeedsRefill(inputId) {
            const el = document.getElementById(inputId);
            if (!el) return;
            // Add red border and focus ring classes
            el.classList.add('border-red-500','focus:border-red-500','focus:ring-red-500');
            // Add subtle pulse on container border (optional)
            // Try to add a helper hint under the input if not already present
            const hintId = inputId + '_needs_refill_hint';
            if (!document.getElementById(hintId)) {
                const hint = document.createElement('p');
                hint.id = hintId;
                hint.className = 'mt-1 text-xs text-red-600 flex items-center';
                hint.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>Harap diisi ulang untuk SPPD';
                // Insert after the input element
                el.parentNode.insertBefore(hint, el.nextSibling);
            }
            // Also color the label if exists
            const label = document.querySelector(`label[for="${inputId}"]`);
            if (label) {
                label.classList.add('text-red-600');
            }
        }
    </script>
    @endif
</x-app-layout>
