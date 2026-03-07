{{-- Chatbot Widget with Mascot - Appears on all PKM pages --}}
<div x-data="chatbot()" x-cloak>
    {{-- Floating Mascot Button --}}
    <div class="fixed bottom-6 right-6 z-50" x-show="!isOpen">
        {{-- Speech Bubble --}}
        <div x-show="showBubble"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2 scale-90"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="absolute -top-16 right-0 w-48 bg-white rounded-2xl shadow-lg border border-neutral-200 px-3 py-2.5 text-center cursor-pointer"
            @click="toggle()">
            <p class="text-xs font-medium text-neutral-700 leading-snug">Ada yang bisa saya bantu? 💬</p>
            {{-- Speech bubble tail --}}
            <div class="absolute -bottom-2 right-8 w-4 h-4 bg-white border-b border-r border-neutral-200 transform rotate-45"></div>
        </div>

        {{-- Mascot Image (Animated WebP) --}}
        <button @click="toggle()"
            class="relative w-20 h-20 rounded-full focus:outline-none group"
            aria-label="Buka Chat Assistant">
            <img src="/animasi.webp" alt="Asisten Puskesmas"
                class="w-full h-full object-contain drop-shadow-lg transition-transform duration-300 group-hover:scale-110"
                loading="lazy">
            {{-- Notification dot --}}
            <span x-show="!hasInteracted"
                class="absolute top-0 right-0 w-5 h-5 bg-red-500 rounded-full border-2 border-white animate-pulse flex items-center justify-center">
                <span class="text-white text-[9px] font-bold">1</span>
            </span>
        </button>
    </div>

    {{-- Chat Panel --}}
    <div x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="fixed bottom-6 right-6 z-50 w-[360px] max-w-[calc(100vw-3rem)] bg-white rounded-2xl shadow-2xl border border-neutral-200 overflow-hidden flex flex-col"
        style="max-height: min(520px, calc(100vh - 8rem));">

        {{-- Header with Mascot --}}
        <div class="bg-gradient-to-r from-primary-600 to-primary-500 px-5 py-4 flex items-center gap-3 flex-shrink-0">
            <div class="w-11 h-11 rounded-full bg-white/20 p-0.5 backdrop-blur-sm flex-shrink-0">
                <img src="/images/maskot.png" alt="Maskot" class="w-full h-full object-contain rounded-full">
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-white font-semibold text-sm">Asisten Puskesmas</h3>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <p class="text-primary-100 text-xs">Online • Siap membantu</p>
                </div>
            </div>
            <button @click="toggle()" class="text-white/70 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>

        {{-- Messages Area --}}
        <div x-ref="messagesContainer"
            class="flex-1 overflow-y-auto px-4 py-4 space-y-3 scroll-smooth"
            style="min-height: 200px;">

            {{-- Welcome Message --}}
            <template x-if="messages.length === 0">
                <div class="space-y-3">
                    <div class="flex gap-2">
                        <div class="w-7 h-7 rounded-full overflow-hidden flex-shrink-0 mt-0.5">
                            <img src="/images/maskot.png" alt="Maskot" class="w-full h-full object-contain">
                        </div>
                        <div class="bg-neutral-100 rounded-2xl rounded-tl-md px-4 py-3 max-w-[85%]">
                            <p class="text-sm text-neutral-700 leading-relaxed">
                                Halo! 👋 Saya asisten virtual Puskesmas Kabalsiang Benjuring.
                                Ada yang bisa saya bantu?
                            </p>
                        </div>
                    </div>
                    {{-- Quick Actions --}}
                    <div class="flex flex-wrap gap-2 pl-9">
                        <button @click="sendQuick('Apa saja layanan di Puskesmas?')"
                            class="text-xs px-3 py-1.5 rounded-full border border-primary-200 text-primary-700 bg-primary-50 hover:bg-primary-100 transition-colors">
                            🏥 Layanan
                        </button>
                        <button @click="sendQuick('Berapa jam buka Puskesmas?')"
                            class="text-xs px-3 py-1.5 rounded-full border border-primary-200 text-primary-700 bg-primary-50 hover:bg-primary-100 transition-colors">
                            🕐 Jam Buka
                        </button>
                        <button @click="sendQuick('Siapa Kepala Puskesmas?')"
                            class="text-xs px-3 py-1.5 rounded-full border border-primary-200 text-primary-700 bg-primary-50 hover:bg-primary-100 transition-colors">
                            👤 Kepala
                        </button>
                        <button @click="sendQuick('Dimana alamat Puskesmas?')"
                            class="text-xs px-3 py-1.5 rounded-full border border-primary-200 text-primary-700 bg-primary-50 hover:bg-primary-100 transition-colors">
                            📍 Alamat
                        </button>
                    </div>
                </div>
            </template>

            {{-- Chat Messages --}}
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex gap-2'">
                    {{-- Bot avatar --}}
                    <template x-if="msg.role === 'assistant'">
                        <div class="w-7 h-7 rounded-full overflow-hidden flex-shrink-0 mt-0.5">
                            <img src="/images/maskot.png" alt="Maskot" class="w-full h-full object-contain">
                        </div>
                    </template>
                    {{-- Message bubble --}}
                    <div :class="msg.role === 'user'
                            ? 'bg-primary-600 text-white rounded-2xl rounded-tr-md px-4 py-2.5 max-w-[85%]'
                            : 'bg-neutral-100 text-neutral-700 rounded-2xl rounded-tl-md px-4 py-2.5 max-w-[85%]'">
                        <p class="text-sm leading-relaxed whitespace-pre-line" x-text="msg.content"></p>
                    </div>
                </div>
            </template>

            {{-- Typing Indicator --}}
            <div x-show="isLoading" class="flex gap-2">
                <div class="w-7 h-7 rounded-full overflow-hidden flex-shrink-0">
                    <img src="/images/maskot.png" alt="Maskot" class="w-full h-full object-contain">
                </div>
                <div class="bg-neutral-100 rounded-2xl rounded-tl-md px-4 py-3">
                    <div class="flex gap-1.5">
                        <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                        <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                        <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="border-t border-neutral-200 px-4 py-3 flex-shrink-0 bg-white">
            <form @submit.prevent="send()" class="flex items-center gap-2">
                <input x-ref="chatInput" x-model="input" type="text"
                    placeholder="Ketik pertanyaan Anda..."
                    class="flex-1 text-sm border border-neutral-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-neutral-50 placeholder-neutral-400 transition-all"
                    :disabled="isLoading" maxlength="500">
                <button type="submit"
                    class="w-10 h-10 rounded-xl bg-primary-600 text-white flex items-center justify-center hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0"
                    :disabled="isLoading || !input.trim()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                    </svg>
                </button>
            </form>
            <p class="text-[10px] text-neutral-400 text-center mt-2">Didukung oleh AI • Jawaban bersifat informatif</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function chatbot() {
        return {
            isOpen: false,
            hasInteracted: false,
            isLoading: false,
            showBubble: false,
            input: '',
            messages: [],
            bubbleTimer: null,

            init() {
                // Show speech bubble after 3 seconds, then toggle every 8 seconds
                setTimeout(() => {
                    this.showBubble = true;
                    this.bubbleTimer = setInterval(() => {
                        this.showBubble = !this.showBubble;
                    }, 5000);
                }, 3000);
            },

            toggle() {
                this.isOpen = !this.isOpen;
                this.hasInteracted = true;
                this.showBubble = false;
                if (this.bubbleTimer) {
                    clearInterval(this.bubbleTimer);
                    this.bubbleTimer = null;
                }
                if (this.isOpen) {
                    this.$nextTick(() => {
                        this.$refs.chatInput?.focus();
                    });
                }
            },

            async sendQuick(text) {
                this.input = text;
                await this.send();
            },

            async send() {
                const text = this.input.trim();
                if (!text || this.isLoading) return;

                this.messages.push({ role: 'user', content: text });
                this.input = '';
                this.isLoading = true;
                this.scrollToBottom();

                try {
                    const response = await fetch('{{ route("pkm.chatbot") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            message: text,
                            history: this.messages.slice(0, -1).slice(-10),
                        }),
                    });

                    const data = await response.json();

                    this.messages.push({
                        role: 'assistant',
                        content: data.message || 'Maaf, terjadi kesalahan. Silakan coba lagi.',
                    });
                } catch (error) {
                    this.messages.push({
                        role: 'assistant',
                        content: 'Maaf, terjadi kesalahan koneksi. Silakan coba lagi.',
                    });
                }

                this.isLoading = false;
                this.scrollToBottom();
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const container = this.$refs.messagesContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
            },
        };
    }
</script>
@endpush

@push('styles')
<style>
    /* Mascot waving animation */
    .mascot-wave {
        animation: mascotWave 3s ease-in-out infinite;
    }

    @keyframes mascotWave {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        25% { transform: translateY(-4px) rotate(2deg); }
        50% { transform: translateY(0) rotate(0deg); }
        75% { transform: translateY(-2px) rotate(-1deg); }
    }

    .mascot-wave:hover {
        animation: mascotBounce 0.5s ease;
    }

    @keyframes mascotBounce {
        0% { transform: scale(1); }
        30% { transform: scale(1.15); }
        50% { transform: scale(0.95); }
        70% { transform: scale(1.05); }
        100% { transform: scale(1.1); }
    }
</style>
@endpush
