<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 text-white">
                    <h1 class="text-2xl font-bold">Buat Rekonsiliasi</h1>
                    <p class="text-indigo-100">Unggah mutasi bank (CSV)</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <form method="POST" action="{{ route('bok.recon.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                            <input type="number" name="year" min="2000" max="2100" value="{{ old('year', date('Y')) }}" class="mt-1 block w-full rounded-xl border-gray-300" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                            <select name="month" class="mt-1 block w-full rounded-xl border-gray-300" required>
                                @php $mn=[1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']; @endphp
                                @foreach($mn as $i=>$n)
                                    <option value="{{ $i }}" {{ (int)date('n')===$i ? 'selected' : '' }}>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">File CSV</label>
                            <input type="file" name="file" class="mt-1 block w-full" required>
                        </div>
                    </div>
                    <div class="pt-2">
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">Proses</button>
                        <a href="{{ route('bok.recon.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Batal</a>
                    </div>
                    <p class="text-xs text-gray-500">Format CSV: kolom header: date, description, amount. amount positif = penerimaan, negatif = pengeluaran.</p>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

