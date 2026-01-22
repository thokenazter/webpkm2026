<footer id="kontak" class="bg-neutral-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            {{-- Brand & Contact --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Puskesmas" class="w-12 h-12 object-contain">
                    <div>
                        <span class="text-lg font-bold text-white">Puskesmas</span>
                        <span class="text-sm text-neutral-400 block -mt-1">Rawat Inap Kabalsiang Benjuring</span>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="hidden sm:block w-px h-10 bg-neutral-700"></div>

                {{-- Contact Info --}}
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 text-sm">
                    <div class="flex items-start gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-primary-400 mt-0.5 flex-shrink-0"></i>
                        <span class="text-neutral-400">Desa Benjuring, Kecamatan Aru Utara Timur Batuley</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-4 h-4 text-primary-400 flex-shrink-0"></i>
                        <a href="mailto:kaben032023@gmail.com"
                            class="text-neutral-400 hover:text-primary-400 transition-colors">
                            kaben032023@gmail.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-sm text-neutral-500 text-center">
                &copy; 2026 Beta | Development
            </p>
        </div>
    </div>
</footer>