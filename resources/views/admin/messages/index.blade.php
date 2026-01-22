@extends('admin.layouts.app')

@section('title', 'Pesan Masuk')
@section('page-title', 'Pesan Masuk')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <p class="text-neutral-600">
                Kelola pesan dari pengunjung website
                @if($unreadCount > 0)
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                        {{ $unreadCount }} belum dibaca
                    </span>
                @endif
            </p>
        </div>
    </div>

    {{-- Messages Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Pengirim</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider hidden md:table-cell">Subjek</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider hidden sm:table-cell">Tanggal</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-neutral-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($messages as $message)
                        <tr class="hover:bg-neutral-50 transition-colors {{ !$message->is_read ? 'bg-primary-50/30' : '' }}">
                            <td class="px-6 py-4">
                                @if($message->is_read)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                        <i data-lucide="mail-open" class="w-3 h-3 mr-1"></i>
                                        Dibaca
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">
                                        <i data-lucide="mail" class="w-3 h-3 mr-1"></i>
                                        Baru
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 {{ !$message->is_read ? 'font-semibold' : '' }}">{{ $message->name }}</p>
                                    <p class="text-xs text-neutral-500 truncate">{{ $message->phone ?: '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <p class="text-sm text-neutral-700 truncate max-w-xs {{ !$message->is_read ? 'font-medium' : '' }}">{{ $message->subject }}</p>
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell">
                                <span class="text-sm text-neutral-600">{{ $message->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.messages.show', $message) }}" 
                                       class="p-2 text-neutral-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
                                       title="Lihat">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-neutral-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-neutral-500">
                                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-neutral-300"></i>
                                    <p>Belum ada pesan masuk</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($messages->hasPages())
            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
@endsection
