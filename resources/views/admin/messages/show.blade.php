@extends('admin.layouts.app')

@section('title', 'Detail Pesan')
@section('page-title', 'Detail Pesan')

@section('content')
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.messages.index') }}"
            class="inline-flex items-center gap-2 text-neutral-600 hover:text-primary-600 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Pesan</span>
        </a>
    </div>

    {{-- Message Card --}}
    <div class="card p-6 lg:p-8">
        {{-- Header --}}
        <div class="border-b border-neutral-200 pb-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-neutral-900 mb-2">{{ $message->subject }}</h2>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-neutral-600">
                        <div class="flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            <span>{{ $message->name }}</span>
                        </div>
                        @if($message->phone)
                            <div class="flex items-center gap-2">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                                <a href="tel:{{ $message->phone }}"
                                    class="text-primary-600 hover:text-primary-700">{{ $message->phone }}</a>
                            </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span>{{ $message->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    @if($message->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}" target="_blank" class="btn btn-primary">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            WhatsApp
                        </a>
                    @endif
                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="inline"
                        onsubmit="return confirm('Yakin ingin menghapus pesan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn border border-red-300 text-red-600 hover:bg-red-50">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Message Content --}}
        <div class="prose prose-neutral max-w-none">
            <div class="bg-neutral-50 rounded-xl p-6 text-neutral-700 leading-relaxed whitespace-pre-wrap">
                {{ $message->message }}</div>
        </div>
    </div>
@endsection