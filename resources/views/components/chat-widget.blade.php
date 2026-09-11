@auth
<!-- Floating Chat Widget -->
<!-- Floating Chat Widget -->
    <div x-data="buyerChatWidget()" x-init="initWidget()" class="fixed bottom-4 right-4 z-50 items-end flex">
        <!-- Chat Button (Closed State) -->
        <button x-show="!chatOpen" @click="toggleChat(true)" x-transition.opacity 
                class="bg-primary/95 hover:bg-primary text-white shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-1.5 font-bold p-3 md:px-3.5 md:py-2 rounded-full md:rounded-xl backdrop-blur-xs relative cursor-pointer group hover:scale-105 active:scale-95" 
                title="Buka Chat Toko">
            <span class="material-symbols-outlined text-[20px] md:text-[18px]">chat</span>
            <span class="hidden md:inline text-xs font-semibold tracking-tight">Chat Toko</span>
            <!-- Notification Badge -->
            <template x-if="unreadTotal > 0">
                <div class="absolute -top-1 -right-1 bg-amber-400 text-slate-900 text-[9px] font-black px-1.5 py-0.2 rounded-full border-2 border-white animate-bounce shadow" x-text="unreadTotal"></div>
            </template>
        </button>

        <!-- Chat Window (Opened State) -->
        <div x-show="chatOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" class="bg-white w-screen h-[100dvh] md:w-[650px] md:h-[480px] fixed md:relative bottom-0 right-0 md:rounded-t-xl shadow-2xl flex border border-outline-variant/40 overflow-hidden z-[60]" style="display: none;">
            
            <!-- Left Side (Chat List) -->
            <div class="border-r border-outline-variant/30 bg-surface-bright flex-shrink-0" :class="selectedStore ? 'hidden md:flex md:w-[240px] flex-col' : 'flex flex-col w-full md:w-[240px]'">
                <div class="p-3 border-b border-outline-variant/30 flex justify-between items-center bg-white">
                    <h3 class="font-bold text-sm text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">chat</span> 
                        Pesan (<span x-text="conversations.length"></span>)
                    </h3>
                    <button @click="chatOpen = false" class="md:hidden text-gray-500 hover:text-primary">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <div class="p-2 border-b border-outline-variant/30 bg-white">
                    <div class="bg-surface-container rounded-md flex items-center px-2 py-1">
                        <span class="material-symbols-outlined text-[16px] text-gray-400 leading-none flex items-center justify-center shrink-0">search</span>
                        <input type="text" x-model="searchStoreQuery" placeholder="Cari toko..." class="bg-transparent border-none focus:ring-0 text-xs w-full px-1.5 py-0 focus:outline-none leading-normal">
                    </div>
                </div>
                <!-- Chat List Items -->
                <div class="flex-1 overflow-y-auto divide-y divide-outline-variant/20">
                    <template x-for="conv in filteredConversations" :key="conv.store_id">
                        <div @click="selectStore(conv)" 
                             class="flex gap-2.5 p-3 hover:bg-surface-container cursor-pointer transition-colors"
                             :class="selectedStore && selectedStore.id === conv.store_id ? 'bg-surface-container-low border-l-4 border-primary' : ''">
                            <div class="relative shrink-0">
                                <img :src="conv.store_logo" class="w-8 h-8 rounded-full object-cover border border-outline-variant/50">
                                <template x-if="conv.unread_count > 0">
                                    <div class="absolute -bottom-1 -right-1 bg-primary text-white text-[9px] rounded-full w-4 h-4 font-bold flex items-center justify-center shadow" x-text="conv.unread_count"></div>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-0.5">
                                    <span class="font-bold text-xs text-on-surface truncate" x-text="conv.store_name"></span>
                                    <span class="text-[9px] text-on-surface-variant whitespace-nowrap" x-text="conv.last_time"></span>
                                </div>
                                <p class="text-[11px] text-on-surface-variant truncate" :class="conv.unread_count > 0 ? 'font-bold text-on-surface' : ''" x-text="conv.last_message || 'Mulai chat...'"></p>
                            </div>
                        </div>
                    </template>

                    <!-- Empty state -->
                    <div x-show="filteredConversations.length === 0" class="p-6 text-center text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-3xl text-gray-300 mb-1">chat_bubble_outline</span>
                        <p>Belum ada riwayat chat dengan toko.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side (Chat Detail) -->
            <div class="flex-col bg-surface-container-lowest min-w-0" :class="!selectedStore ? 'hidden md:flex flex-1' : 'flex flex-1'">
                
                <template x-if="selectedStore">
                    <div class="flex-1 flex flex-col h-full min-h-0">
                        <!-- Header -->
                        <div class="h-12 border-b border-outline-variant/30 flex justify-between items-center px-4 bg-white flex-shrink-0">
                            <div class="flex items-center gap-2 min-w-0">
                                <button @click="selectedStore = null" class="md:hidden mr-1 text-gray-500 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                                </button>
                                <img :src="selectedStore.logo" class="w-7 h-7 rounded-full object-cover border border-outline-variant/50">
                                <span class="font-bold text-xs text-on-surface truncate" x-text="selectedStore.name"></span>
                                <a :href="'/' + selectedStore.slug" target="_blank" class="text-primary hover:underline text-[11px] ml-1 flex items-center gap-0.5" title="Kunjungi Toko">
                                    <span class="material-symbols-outlined text-[13px]">open_in_new</span> Toko
                                </a>
                            </div>
                            <div class="flex items-center gap-1 text-gray-500">
                                <button @click="chatOpen = false" class="hover:bg-surface-container p-1 rounded-md transition-colors" title="Tutup Chat">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                </button>
                            </div>
                        </div>

                        <!-- Active Product Attachment Preview if starting chat from product detail -->
                        <template x-if="attachedProduct">
                            <div class="px-3 py-2 bg-primary/5 border-b border-primary/20 flex items-center justify-between gap-2 flex-shrink-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <img x-show="attachedProduct.image" :src="attachedProduct.image" class="w-8 h-8 rounded object-cover border border-outline-variant/30">
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-bold truncate text-on-surface" x-text="attachedProduct.name"></p>
                                        <p class="text-[10px] font-extrabold text-primary" x-text="'Rp ' + attachedProduct.price"></p>
                                    </div>
                                </div>
                                <button @click="attachedProduct = null" class="text-gray-400 hover:text-rose-500 text-[14px]">
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </button>
                            </div>
                        </template>

                        <!-- Messages Container -->
                        <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-3 bg-surface-bright" id="buyerMessageContainer">
                            <template x-for="msg in messages" :key="msg.id">
                                <div class="flex flex-col" :class="msg.sender_type === 'user' ? 'items-end' : 'items-start'">
                                    
                                    <!-- Attached Product Card in message -->
                                    <template x-if="msg.product">
                                        <a :href="msg.product.url" target="_blank" class="mb-1 p-2 rounded-lg bg-white border border-outline-variant/60 shadow-sm max-w-[240px] flex items-center gap-2 hover:border-primary transition">
                                            <img x-show="msg.product.image" :src="msg.product.image" class="w-9 h-9 rounded object-cover">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-[11px] font-bold text-on-surface truncate" x-text="msg.product.name"></p>
                                                <p class="text-[11px] font-extrabold text-primary" x-text="'Rp ' + msg.product.price"></p>
                                            </div>
                                        </a>
                                    </template>

                                    <!-- Message Bubble -->
                                    <div class="max-w-[80%] rounded-xl px-3.5 py-2 shadow-sm text-xs leading-relaxed"
                                         :class="msg.sender_type === 'user'
                                            ? 'bg-primary text-white rounded-br-none'
                                            : 'bg-white text-on-surface border border-outline-variant/40 rounded-bl-none'">
                                        <p class="whitespace-pre-line" x-text="msg.message"></p>
                                        <div class="text-[9px] mt-1 text-right" :class="msg.sender_type === 'user' ? 'text-white/80' : 'text-on-surface-variant'" x-text="msg.created_at"></div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="messages.length === 0" class="text-center text-xs text-on-surface-variant py-8">
                                Belum ada pesan. Tanyakan sesuatu tentang produk atau toko ini!
                            </div>
                        </div>

                        <!-- Input Area -->
                        <div class="border-t border-outline-variant/30 bg-white flex-shrink-0">
                            <form @submit.prevent="sendMessage()" class="flex items-center px-3 py-2 gap-2">
                                <textarea x-model="newMessage" 
                                          @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                                          placeholder="Tulis pesan ke toko..." 
                                          rows="1" 
                                          class="flex-1 resize-none border-none focus:ring-0 text-xs bg-transparent px-1 py-1 leading-tight text-on-surface focus:outline-none"></textarea>
                                <button type="submit" 
                                        :disabled="!newMessage.trim() || isSending" 
                                        class="text-primary hover:text-primary/80 disabled:opacity-40 disabled:cursor-not-allowed transition-colors p-1">
                                    <span class="material-symbols-outlined text-[20px]">send</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </template>

                <template x-if="!selectedStore">
                    <div class="flex-1 flex flex-col items-center justify-center p-6 text-center text-on-surface-variant">
                        <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-2">
                            <span class="material-symbols-outlined text-2xl">chat</span>
                        </div>
                        <h4 class="font-bold text-xs text-on-surface mb-1">Chat Penjual</h4>
                        <p class="text-[11px]">Pilih salah satu toko di daftar kiri untuk mulai berkirim pesan.</p>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <script>
    function buyerChatWidget() {
        return {
            chatOpen: false,
            conversations: [],
            selectedStore: null,
            messages: [],
            newMessage: '',
            searchStoreQuery: '',
            attachedProduct: null,
            unreadTotal: 0,
            isSending: false,
            pollTimer: null,
            isFetchingConversations: false,
            isFetchingMessages: false,

            get filteredConversations() {
                if (!this.searchStoreQuery) return this.conversations;
                return this.conversations.filter(c => c.store_name.toLowerCase().includes(this.searchStoreQuery.toLowerCase()));
            },

            initWidget() {
                // Fetch unread count & initial conversations once at load
                this.fetchConversations();

                // Listen for tab visibility changes (pause when tab in background)
                document.addEventListener('visibilitychange', () => {
                    if (!document.hidden) {
                        this.fetchConversations();
                        if (this.chatOpen && this.selectedStore) {
                            this.fetchMessages(this.selectedStore.id, false);
                        }
                    }
                });

                // Listen for global FCM messages
                window.addEventListener('fcm-message-received', (e) => {
                    this.fetchConversations(false);
                    if (this.chatOpen && this.selectedStore) {
                        this.fetchMessages(this.selectedStore.id, false);
                    }
                });

                // Listen for global open chat triggers (from Store profile or Product Detail page)
                window.addEventListener('open-chat-with-store', (event) => {
                    this.openWithStore(event.detail);
                });
            },

            startPolling() {
                this.stopPolling();
                this.pollTimer = setInterval(() => {
                    if (document.hidden) return;
                    if (this.chatOpen) {
                        this.fetchConversations(false);
                        if (this.selectedStore) {
                            this.fetchMessages(this.selectedStore.id, false);
                        }
                    } else {
                        this.stopPolling();
                    }
                }, 5000);
            },

            stopPolling() {
                if (this.pollTimer) {
                    clearInterval(this.pollTimer);
                    this.pollTimer = null;
                }
            },

            toggleChat(state) {
                this.chatOpen = state;
                if (state) {
                    this.fetchConversations(true);
                    this.startPolling();
                } else {
                    this.stopPolling();
                }
            },

            openWithStore(detail) {
                this.chatOpen = true;
                this.selectedStore = {
                    id: detail.store_id,
                    name: detail.store_name,
                    logo: detail.store_logo || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(detail.store_name) + '&background=00838f&color=fff',
                    slug: detail.store_slug || ''
                };

                if (detail.product) {
                    this.attachedProduct = detail.product;
                }

                this.fetchMessages(detail.store_id, true);
                this.startPolling();
            },

            fetchConversations(autoSelect = false) {
                if (this.isFetchingConversations) return;
                this.isFetchingConversations = true;

                fetch('{{ route("chat.conversations") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(r => r.json())
                    .then(res => {
                        this.conversations = res.conversations || [];
                        this.unreadTotal = res.unread_total || 0;
                        if (autoSelect && !this.selectedStore && this.conversations.length > 0) {
                            this.selectStore(this.conversations[0]);
                        }
                    })
                    .catch(() => {})
                    .finally(() => {
                        this.isFetchingConversations = false;
                    });
            },

            selectStore(conv) {
                this.selectedStore = {
                    id: conv.store_id,
                    name: conv.store_name,
                    logo: conv.store_logo,
                    slug: conv.store_slug
                };
                this.fetchMessages(conv.store_id, true);
            },

            fetchMessages(storeId, scrollDown = true) {
                if (this.isFetchingMessages) return;
                this.isFetchingMessages = true;

                fetch('{{ url("chat/messages") }}/' + storeId, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(r => r.json())
                    .then(res => {
                        this.messages = res.messages || [];
                        if (res.store) {
                            this.selectedStore = res.store;
                        }
                        if (scrollDown) {
                            this.$nextTick(() => {
                                const el = document.getElementById('buyerMessageContainer');
                                if (el) el.scrollTop = el.scrollHeight;
                            });
                        }
                    })
                    .catch(() => {})
                    .finally(() => {
                        this.isFetchingMessages = false;
                    });
            },

            sendMessage() {
                if (!this.newMessage.trim() || !this.selectedStore || this.isSending) return;
                this.isSending = true;

                const payload = {
                    store_id: this.selectedStore.id,
                    message: this.newMessage,
                    product_id: this.attachedProduct ? this.attachedProduct.id : null
                };

                fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(r => r.json())
                .then(res => {
                    this.isSending = false;
                    if (res.success) {
                        this.messages.push(res.message);
                        this.newMessage = '';
                        this.attachedProduct = null;
                        this.$nextTick(() => {
                            const el = document.getElementById('buyerMessageContainer');
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                        this.fetchConversations();
                    }
                })
                .catch(() => {
                    this.isSending = false;
                });
            }
        };
    }
    </script>
@endauth
