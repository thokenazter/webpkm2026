@foreach ($allocations as $a)
    <tr>
        <td class="px-6 py-3 text-sm text-gray-900">{{ $a->budget?->year }}</td>
        <td class="px-6 py-3 text-sm text-gray-900">{{ $a->rab?->kegiatan }}</td>
        <td class="px-6 py-3 text-sm text-gray-900">Rp {{ number_format($a->allocated_amount, 0, ',', '.') }}</td>
        <td class="px-6 py-3 text-sm">
            <a href="{{ route('allocations.show', $a) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-eye mr-1"></i>Lihat</a>
            @if(auth()->user()?->isSuperAdmin())
                <a href="{{ route('allocations.edit', $a) }}" class="inline-flex items-center text-yellow-600 hover:text-yellow-900 mr-3"><i class="fas fa-edit mr-1"></i>Edit</a>
                <form action="{{ route('allocations.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center text-red-600 hover:text-red-900"><i class="fas fa-trash mr-1"></i>Hapus</button>
                </form>
            @endif
        </td>
    </tr>
@endforeach
