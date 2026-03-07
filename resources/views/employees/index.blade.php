<x-app-layout>
    {{-- <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Data Pegawai') }}
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
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h1 class="text-2xl font-bold mb-2 flex items-center">
                                <i class="fas fa-users mr-3"></i>Data Pegawai BOK
                            </h1>
                            <p class="text-blue-100">Kelola data pegawai yang terlibat dalam kegiatan LPJ</p>
                            <div class="mt-3 flex items-center text-sm text-blue-200">
                                <i class="fas fa-user-friends mr-2"></i>
                                <span>{{ $employees->total() }} pegawai terdaftar</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar mr-2"></i>
                                <span>{{ Carbon\Carbon::now()->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('employees.create') }}" class="bg-white text-blue-600 px-6 py-2 rounded-lg font-semibold hover:bg-blue-50 transition duration-200 shadow-md btn-modern">
                                <i class="fas fa-plus mr-2"></i>Tambah Pegawai
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg mr-3">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="text-green-800 font-semibold">Berhasil!</h3>
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Employees Table -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 bg-indigo-500 text-white rounded-xl">
                                <i class="fas fa-table"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Daftar Pegawai</h3>
                                <p class="text-sm text-gray-600">Kelola data pegawai yang terdaftar</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-indigo-600">{{ $employees->total() }}</div>
                            <div class="text-xs text-gray-500">total pegawai</div>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pegawai</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pangkat/Golongan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jabatan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Lahir</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($employees as $index => $employee)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-lg text-sm font-bold mr-4">
                                                {{ $employees->firstItem() + $index }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $employee->nama }}</div>
                                                <div class="text-sm text-gray-500 flex items-center">
                                                    <i class="fas fa-id-card mr-1"></i>{{ $employee->nip }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ $employee->pangkat_golongan }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $employee->jabatan ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 flex items-center">
                                            <i class="fas fa-calendar text-green-500 mr-2"></i>
                                            {{ $employee->tanggal_lahir->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $employee->tanggal_lahir->age }} tahun
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('employees.show', $employee) }}" 
                                               class="inline-flex items-center px-3 py-1 bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-semibold rounded-lg transition duration-200 shadow-md btn-modern">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </a>
                                            <a href="{{ route('employees.edit', $employee) }}" 
                                               class="inline-flex items-center px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition duration-200 shadow-md btn-modern">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <button type="button" 
                                                class="inline-flex items-center px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition duration-200 shadow-md btn-modern delete-btn" 
                                                data-employee-id="{{ $employee->id }}"
                                                data-employee-nama="{{ $employee->nama }}"
                                                data-employee-nip="{{ $employee->nip }}"
                                                data-employee-pangkat="{{ $employee->pangkat_golongan }}"
                                                data-employee-jabatan="{{ $employee->jabatan }}"
                                                data-employee-tanggal="{{ $employee->tanggal_lahir->format('d/m/Y') }}">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-user-slash text-6xl text-gray-300 mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Pegawai</h3>
                                            <p class="text-gray-500 text-center mb-4">
                                                Belum ada pegawai yang terdaftar dalam sistem. <br>
                                                Silakan tambahkan pegawai untuk memulai.
                                            </p>
                                            <a href="{{ route('employees.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                                                <i class="fas fa-plus mr-2"></i>Tambah Pegawai Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($employees->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-2xl">
                        {{ $employees->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
            <div class="mt-3 text-center">
                <!-- Icon Warning -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Penghapusan Data</h3>
                
                <!-- Detail Pegawai -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4 text-left">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-user mr-2"></i>Data yang akan dihapus:
                    </h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Nama:</span> 
                            <span class="text-gray-900" id="modal-nama"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">NIP:</span> 
                            <span class="text-gray-900 font-mono" id="modal-nip"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Pangkat:</span> 
                            <span class="text-gray-900" id="modal-pangkat"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Jabatan:</span> 
                            <span class="text-gray-900" id="modal-jabatan"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Tanggal Lahir:</span> 
                            <span class="text-gray-900" id="modal-tanggal"></span>
                        </div>
                    </div>
                </div>

                <!-- Warning Message -->
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                    <p class="text-sm text-red-800">
                        <strong><i class="fas fa-exclamation-triangle mr-1"></i>PERINGATAN:</strong><br>
                        Data yang dihapus tidak dapat dikembalikan. Pastikan Anda benar-benar yakin sebelum melanjutkan.
                    </p>
                </div>

                <!-- Konfirmasi Ketik -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Ketik "<strong>HAPUS</strong>" untuk mengkonfirmasi:
                    </label>
                    <input type="text" id="confirmText" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-200" placeholder="Ketik HAPUS">
                    <p id="confirmError" class="text-red-500 text-xs mt-2 hidden flex items-center">
                        <i class="fas fa-times-circle mr-1"></i>Mohon ketik "HAPUS" dengan benar
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-center space-x-3">
                    <button id="cancelBtn" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl shadow-md transition duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button id="confirmBtn" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl shadow-md transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-trash mr-2"></i>Ya, Hapus Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Hidden untuk Submit -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deleteModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const confirmText = document.getElementById('confirmText');
        const confirmError = document.getElementById('confirmError');
        const deleteForm = document.getElementById('deleteForm');
        
        let currentEmployeeId = null;

        // Event listener untuk tombol hapus
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                currentEmployeeId = this.dataset.employeeId;
                
                // Isi data ke modal
                document.getElementById('modal-nama').textContent = this.dataset.employeeNama;
                document.getElementById('modal-nip').textContent = this.dataset.employeeNip;
                document.getElementById('modal-pangkat').textContent = this.dataset.employeePangkat;
                document.getElementById('modal-jabatan').textContent = this.dataset.employeeJabatan || '-';
                document.getElementById('modal-tanggal').textContent = this.dataset.employeeTanggal;
                
                // Reset form
                confirmText.value = '';
                confirmBtn.disabled = true;
                confirmError.classList.add('hidden');
                confirmText.classList.remove('border-red-500', 'border-green-500');
                
                // Tampilkan modal
                modal.classList.remove('hidden');
                confirmText.focus();
            });
        });

        // Event listener untuk input konfirmasi
        confirmText.addEventListener('input', function() {
            const value = this.value.toUpperCase();
            if (value === 'HAPUS') {
                confirmBtn.disabled = false;
                confirmError.classList.add('hidden');
                this.classList.remove('border-red-500');
                this.classList.add('border-green-500');
            } else {
                confirmBtn.disabled = true;
                this.classList.remove('border-green-500');
                if (value.length > 0) {
                    this.classList.add('border-red-500');
                    confirmError.classList.remove('hidden');
                } else {
                    this.classList.remove('border-red-500');
                    confirmError.classList.add('hidden');
                }
            }
        });

        // Event listener untuk tombol konfirmasi
        confirmBtn.addEventListener('click', function() {
            if (confirmText.value.toUpperCase() === 'HAPUS' && currentEmployeeId) {
                // Set action form dan submit
                deleteForm.action = `/employees/${currentEmployeeId}`;
                deleteForm.submit();
            }
        });

        // Event listener untuk tombol batal
        cancelBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            currentEmployeeId = null;
        });

        // Event listener untuk klik di luar modal
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                currentEmployeeId = null;
            }
        });

        // Event listener untuk ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                currentEmployeeId = null;
            }
        });
    });
    </script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>