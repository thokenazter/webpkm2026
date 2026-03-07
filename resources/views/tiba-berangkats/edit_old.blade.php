<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Tiba Berangkat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('tiba-berangkats.update', $tibaBerangkat) }}" method="POST" x-data="tibaBerangkatEditForm()">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="no_surat" class="block text-sm font-medium text-gray-700">Nomor Surat</label>
                                <input type="text" name="no_surat" id="no_surat" value="{{ old('no_surat', $tibaBerangkat->no_surat) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('no_surat') border-red-300 @enderror">
                                @error('no_surat')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Desa Kunjungan</label>
                                    <button type="button" @click="addDesa()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Tambah Desa
                                    </button>
                                </div>

                                <div id="desa-container">
                                    <template x-for="(desa, index) in desas" :key="index">
                                        <div class="border border-gray-200 rounded-lg p-4 mb-4">
                                            <div class="flex justify-between items-center mb-3">
                                                <h4 class="text-lg font-medium text-gray-900" x-text="`Desa ${index + 1}`"></h4>
                                                <button type="button" @click="removeDesa(index)" x-show="desas.length > 1" 
                                                        class="text-red-600 hover:text-red-900 text-sm">
                                                    Hapus
                                                </button>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Pilih Desa</label>
                                                    <select :name="`desa[${index}][pejabat_ttd_id]`" 
                                                            x-model="desa.pejabat_ttd_id"
                                                            @change="updatePejabat(index, $event.target.value)"
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="">Pilih Desa</option>
                                                        @foreach ($pejabatTtds as $pejabat)
                                                            <option value="{{ $pejabat->id }}" 
                                                                    data-nama="{{ $pejabat->nama }}" 
                                                                    data-jabatan="{{ $pejabat->jabatan }}"
                                                                    data-desa="{{ $pejabat->desa }}">
                                                                {{ $pejabat->desa }} - {{ $pejabat->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Tanggal Kunjungan</label>
                                                    <input type="date" :name="`desa[${index}][tanggal_kunjungan]`" 
                                                           x-model="desa.tanggal_kunjungan"
                                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4" x-show="desa.pejabat_ttd_id">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Nama Pejabat</label>
                                                    <p class="mt-1 text-sm text-gray-900" x-text="desa.nama_pejabat"></p>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                                                    <p class="mt-1 text-sm text-gray-900" x-text="desa.jabatan"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                @error('desa')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <a href="{{ route('tiba-berangkats.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function tibaBerangkatEditForm() {
            return {
                desas: @json($tibaBerangkat->details->map(function($detail) {
                    return [
                        'pejabat_ttd_id' => $detail->pejabat_ttd_id,
                        'tanggal_kunjungan' => $detail->tanggal_kunjungan->format('Y-m-d'),
                        'nama_pejabat' => $detail->pejabatTtd->nama,
                        'jabatan' => $detail->pejabatTtd->jabatan,
                        'nama_desa' => $detail->pejabatTtd->desa
                    ];
                }))

                addDesa() {
                    this.desas.push({
                        pejabat_ttd_id: '',
                        tanggal_kunjungan: '',
                        nama_pejabat: '',
                        jabatan: '',
                        nama_desa: ''
                    });
                },

                removeDesa(index) {
                    if (this.desas.length > 1) {
                        this.desas.splice(index, 1);
                    }
                },

                updatePejabat(index, pejabatId) {
                    if (pejabatId) {
                        const option = document.querySelector(`option[value="${pejabatId}"]`);
                        if (option) {
                            this.desas[index].nama_pejabat = option.dataset.nama;
                            this.desas[index].jabatan = option.dataset.jabatan;
                            this.desas[index].nama_desa = option.dataset.desa;
                        }
                    } else {
                        this.desas[index].nama_pejabat = '';
                        this.desas[index].jabatan = '';
                        this.desas[index].nama_desa = '';
                    }
                }
            }
        }
    </script>
</x-app-layout>