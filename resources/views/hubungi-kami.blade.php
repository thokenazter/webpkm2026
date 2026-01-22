@extends('layouts.app')

@section('title', 'Hubungi Kami - Puskesmas Rawat Inap Kabalsiang Benjuring')

@section('content')
    {{-- Hero Header --}}
    <section class="bg-gradient-to-br from-primary-600 to-primary-800 pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Hubungi Kami</h1>
                <p class="text-primary-100 max-w-2xl mx-auto">
                    Sampaikan pertanyaan, saran, atau kritik Anda. Kami akan merespons secepat mungkin.
                </p>
            </div>
        </div>
    </section>

    {{-- Contact Form --}}
    <section class="py-12 lg:py-16 bg-gradient-to-b from-white to-neutral-50">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Messages --}}
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <span class="font-medium">Terjadi kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST"
                class="bg-white rounded-2xl shadow-lg border border-neutral-200 p-6 lg:p-8 space-y-6">
                @csrf

                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="Masukkan nama Anda">
                    </div>

                    {{-- Phone (Optional) --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-neutral-700 mb-2">
                            No. HP <span class="text-neutral-400 text-xs">(opsional)</span>
                        </label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-3 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                {{-- Subject --}}
                <div>
                    <label for="subject" class="block text-sm font-medium text-neutral-700 mb-2">
                        Subjek <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                        class="w-full px-4 py-3 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                        placeholder="Subjek pesan Anda">
                </div>

                {{-- Message --}}
                <div>
                    <label for="message" class="block text-sm font-medium text-neutral-700 mb-2">
                        Pesan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" id="message" rows="5" required
                        class="w-full px-4 py-3 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors resize-none"
                        placeholder="Tulis pesan Anda di sini...">{{ old('message') }}</textarea>
                </div>

                {{-- Submit Button --}}
                <div>
                    <button type="submit" class="w-full btn btn-primary py-3 text-base">
                        <i data-lucide="send" class="w-5 h-5"></i>
                        Kirim Pesan
                    </button>
                </div>
            </form>

            {{-- Contact Info --}}
            <div class="mt-8 text-center text-sm text-neutral-600">
                <p>Atau hubungi kami melalui email:</p>
                <a href="mailto:kaben032023@gmail.com" class="text-primary-600 hover:text-primary-700 font-medium">
                    kaben032023@gmail.com
                </a>
            </div>
        </div>
    </section>
@endsection