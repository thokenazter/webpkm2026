<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input RAB: ') }} {{ $activity->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('activities.update_budget', $activity) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Rincian Anggaran Biaya</h3>
                        <p class="text-sm text-gray-500">Tambahkan item anggaran sesuai kebutuhan.</p>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 mb-4" id="budget-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian / Item</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volume</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frekuensi</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="budget-items">
                            @foreach ($activity->budgetItems as $index => $item)
                                <tr class="budget-row">
                                    <td class="px-4 py-2"><input type="text" name="items[{{ $index }}][name]" value="{{ $item->name }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required></td>
                                    <td class="px-4 py-2"><input type="number" name="items[{{ $index }}][volume]" value="{{ $item->volume }}" class="w-20 border-gray-300 rounded-md shadow-sm text-sm volume" min="1" required></td>
                                    <td class="px-4 py-2"><input type="number" name="items[{{ $index }}][frequency]" value="{{ $item->frequency }}" class="w-20 border-gray-300 rounded-md shadow-sm text-sm frequency" min="1" required></td>
                                    <td class="px-4 py-2"><input type="text" name="items[{{ $index }}][unit]" value="{{ $item->unit }}" class="w-24 border-gray-300 rounded-md shadow-sm text-sm" required></td>
                                    <td class="px-4 py-2"><input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" class="w-32 border-gray-300 rounded-md shadow-sm text-sm unit-price" min="0" step="0.01" required></td>
                                    <td class="px-4 py-2"><span class="total-price font-medium">{{ number_format($item->total_price, 0, ',', '.') }}</span></td>
                                    <td class="px-4 py-2"><button type="button" class="text-red-600 hover:text-red-900 remove-row">Hapus</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-right font-bold">Grand Total:</td>
                                <td class="px-4 py-2 font-bold" id="grand-total">0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="flex justify-between items-center">
                        <button type="button" id="add-row" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                            + Tambah Item
                        </button>
                        <div>
                            <a href="{{ route('activities.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Kembali</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan RAB
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('budget-items');
            const addBtn = document.getElementById('add-row');
            let rowCount = {{ $activity->budgetItems->count() }};

            function updateTotals() {
                let grandTotal = 0;
                document.querySelectorAll('.budget-row').forEach(row => {
                    const vol = parseFloat(row.querySelector('.volume').value) || 0;
                    const freq = parseFloat(row.querySelector('.frequency').value) || 0;
                    const price = parseFloat(row.querySelector('.unit-price').value) || 0;
                    const total = vol * freq * price;
                    row.querySelector('.total-price').textContent = new Intl.NumberFormat('id-ID').format(total);
                    grandTotal += total;
                });
                document.getElementById('grand-total').textContent = new Intl.NumberFormat('id-ID').format(grandTotal);
            }

            container.addEventListener('input', function(e) {
                if (e.target.matches('.volume, .frequency, .unit-price')) {
                    updateTotals();
                }
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                    updateTotals();
                }
            });

            addBtn.addEventListener('click', function() {
                const index = rowCount++;
                const row = `
                    <tr class="budget-row">
                        <td class="px-4 py-2"><input type="text" name="items[${index}][name]" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required></td>
                        <td class="px-4 py-2"><input type="number" name="items[${index}][volume]" value="1" class="w-20 border-gray-300 rounded-md shadow-sm text-sm volume" min="1" required></td>
                        <td class="px-4 py-2"><input type="number" name="items[${index}][frequency]" value="1" class="w-20 border-gray-300 rounded-md shadow-sm text-sm frequency" min="1" required></td>
                        <td class="px-4 py-2"><input type="text" name="items[${index}][unit]" class="w-24 border-gray-300 rounded-md shadow-sm text-sm" required></td>
                        <td class="px-4 py-2"><input type="number" name="items[${index}][unit_price]" value="0" class="w-32 border-gray-300 rounded-md shadow-sm text-sm unit-price" min="0" step="0.01" required></td>
                        <td class="px-4 py-2"><span class="total-price font-medium">0</span></td>
                        <td class="px-4 py-2"><button type="button" class="text-red-600 hover:text-red-900 remove-row">Hapus</button></td>
                    </tr>
                `;
                container.insertAdjacentHTML('beforeend', row);
            });

            updateTotals();
        });
    </script>
</x-app-layout>
