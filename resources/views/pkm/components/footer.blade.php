<footer id="kontak" class="bg-neutral-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">

            {{-- Column 1: Brand --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Puskesmas" class="w-12 h-12 object-contain">
                    <div>
                        <span class="text-lg font-bold text-white">Puskesmas</span>
                        <span class="text-sm text-neutral-400 block -mt-1">Rawat Inap Kabalsiang Benjuring</span>
                    </div>
                </div>
                <p class="text-sm text-neutral-400 leading-relaxed">
                    Memberikan pelayanan kesehatan primer yang berkualitas bagi masyarakat
                    Kecamatan Aru Utara Timur Batuley, Kabupaten Kepulauan Aru.
                </p>
                {{-- Social Media Links --}}
                <div class="flex items-center gap-3 mt-5">
                    <a href="https://facebook.com/puskesmaskaben" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 rounded-full bg-neutral-800 flex items-center justify-center text-neutral-400 hover:bg-[#1877F2] hover:text-white transition-all duration-300 hover:scale-110"
                        aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://instagram.com/puskesmaskaben" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 rounded-full bg-neutral-800 flex items-center justify-center text-neutral-400 hover:bg-gradient-to-br hover:from-[#f9ce34] hover:via-[#ee2a7b] hover:to-[#6228d7] hover:text-white transition-all duration-300 hover:scale-110"
                        aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="https://tiktok.com/@puskesmaskaben" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 rounded-full bg-neutral-800 flex items-center justify-center text-neutral-400 hover:bg-[#010101] hover:text-white transition-all duration-300 hover:scale-110 hover:ring-2 hover:ring-[#69C9D0]"
                        aria-label="TikTok">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                    </a>
                    <a href="https://youtube.com/@puskesmaskaben" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 rounded-full bg-neutral-800 flex items-center justify-center text-neutral-400 hover:bg-[#FF0000] hover:text-white transition-all duration-300 hover:scale-110"
                        aria-label="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Column 2: Tautan Terkait --}}
            <div>
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Tautan Terkait</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="https://dinkes.kepulauanarukab.go.id/" target="_blank" rel="noopener noreferrer"
                            class="flex items-center gap-2 text-sm text-neutral-400 hover:text-primary-400 transition-colors group">
                            <i data-lucide="external-link" class="w-4 h-4 flex-shrink-0 group-hover:scale-110 transition-transform"></i>
                            Dinkes Kab. Kep. Aru
                        </a>
                    </li>
                    <li>
                        <a href="https://kabensatudata.web.id/dashboard" target="_blank" rel="noopener noreferrer"
                            class="flex items-center gap-2 text-sm text-neutral-400 hover:text-primary-400 transition-colors group">
                            <i data-lucide="external-link" class="w-4 h-4 flex-shrink-0 group-hover:scale-110 transition-transform"></i>
                            KabenSatuData
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 3: Kontak --}}
            <div>
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Kontak</h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-primary-400 mt-0.5 flex-shrink-0"></i>
                        <span class="text-sm text-neutral-400">Desa Benjuring, Kec. Aru Utara Timur Batuley, Kab. Kepulauan Aru</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-4 h-4 text-primary-400 flex-shrink-0"></i>
                        <a href="mailto:kaben032023@gmail.com"
                            class="text-sm text-neutral-400 hover:text-primary-400 transition-colors">
                            kaben032023@gmail.com
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 4: Google Maps --}}
            <div>
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Lokasi Kami</h4>
                <div class="rounded-xl overflow-hidden border border-neutral-700 shadow-lg">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3000!2d134.811533552002!3d-5.740968369549086!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwNDQnMjcuNSJTIDEzNMKwNDgnNDEuNSJF!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
                        width="100%"
                        height="180"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full">
                    </iframe>
                </div>
                <a href="https://www.google.com/maps?q=-5.740968369549086,134.811533552002" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-1.5 mt-3 text-xs text-primary-400 hover:text-primary-300 transition-colors">
                    <i data-lucide="navigation" class="w-3 h-3"></i>
                    Buka di Google Maps
                </a>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-sm text-neutral-500 text-center">
                &copy; {{ date('Y') }} Puskesmas Rawat Inap Kabalsiang Benjuring. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</footer>