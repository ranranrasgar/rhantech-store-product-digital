@extends('layouts.tenant')

@section('title', 'Chat Pelanggan - Seller Center')

@section('content')
<div class="p-4 md:p-6 h-[calc(100vh-80px)] flex flex-col" x-data="tenantChatManager()" x-init="initChat()">
    
    <!-- Top Header -->
    <div class="flex items-center justify-between mb-4 flex-shrink-0">
        <div>
            <h1 class="text-xl font-bold text-zinc-800 dark:text-zinc-100 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">chat</span>
                Chat Pelanggan
            </h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Komunikasi langsung dengan calon pembeli & pelanggan toko Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Online
            </span>
        </div>
    </div>

    <!-- Chat Container Card -->
    <div class="flex-1 bg-white dark:bg-[#000000] border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-none overflow-hidden flex flex-col md:flex-row min-h-0">
        
        <!-- Left Column: Customer Conversations List -->
        <div class="w-full md:w-80 lg:w-96 border-r border-slate-200 dark:border-zinc-800 flex flex-col bg-slate-50/50 dark:bg-[#000000]/40 flex-shrink-0">
            <!-- Search bar -->
            <div class="p-3 border-b border-slate-200 dark:border-zinc-800 bg-white dark:bg-[#000000]">
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-[18px] leading-none">search</span>
                    </div>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama pelanggan..." class="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl bg-slate-100 dark:bg-[#21262d] border border-transparent focus:border-primary dark:focus:border-blue-500 focus:bg-white dark:focus:bg-[#161b22] focus:outline-none text-zinc-800 dark:text-zinc-100 placeholder:text-slate-400 transition-all">
                </div>
            </div>

            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100 dark:divide-[#21262d]">
                <template x-for="conv in filteredConversations" :key="conv.user_id">
                    <div @click="selectCustomer(conv)" 
                         class="p-3.5 flex items-start gap-3 cursor-pointer transition-colors relative"
                         :class="selectedUser && selectedUser.id === conv.user_id ? 'bg-primary/10 dark:bg-blue-500/15 border-l-4 border-primary' : 'hover:bg-slate-100/70 dark:hover:bg-[#21262d]/50'">
                        
                        <div class="relative flex-shrink-0">
                            <img :src="conv.user_avatar" :alt="conv.user_name" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-gray-700">
                            <template x-if="conv.unread_count > 0">
                                <span class="absolute -top-1 -right-1 px-1.5 py-0.5 bg-primary text-white text-[10px] font-bold rounded-full animate-bounce shadow" x-text="conv.unread_count"></span>
                            </template>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1 mb-1">
                                <h4 class="text-xs font-bold text-zinc-800 dark:text-zinc-100 truncate" x-text="conv.user_name"></h4>
                                <span class="text-[10px] text-slate-400 whitespace-nowrap" x-text="conv.last_time"></span>
                            </div>
                            <p class="text-xs truncate" :class="conv.unread_count > 0 ? 'font-bold text-zinc-900 dark:text-zinc-100' : 'text-zinc-500 dark:text-zinc-400'" x-text="conv.last_message || 'Mulai percakapan...'"></p>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="filteredConversations.length === 0" class="p-8 text-center text-slate-400 flex flex-col items-center justify-center h-full">
                    <span class="material-symbols-outlined text-4xl mb-2 text-slate-300 dark:text-slate-600">forum</span>
                    <p class="text-xs">Belum ada percakapan pelanggan.</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Active Chat Window -->
        <div class="flex-1 flex flex-col bg-white dark:bg-[#000000] min-w-0">
            
            <template x-if="selectedUser">
                <div class="flex-1 flex flex-col h-full min-h-0">
                    
                    <!-- Chat Header -->
                    <div class="p-3.5 px-4 border-b border-slate-200 dark:border-zinc-800 flex items-center justify-between bg-slate-50/70 dark:bg-[#000000] flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <img :src="selectedUser.avatar" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-gray-700">
                            <div>
                                <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-100" x-text="selectedUser.name"></h3>
                                <p class="text-[11px] text-slate-400" x-text="selectedUser.email"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button @click="fetchMessages(selectedUser.id)" class="p-2 text-slate-400 hover:text-primary dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-[#21262d] transition" title="Muat Ulang Pesan">
                                <span class="material-symbols-outlined text-[18px]">refresh</span>
                            </button>
                        </div>
                    </div>

                    <!-- Messages Container -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-[#f8fafc] dark:bg-[#000000]/60" id="tenantMessageContainer">
                        
                        <template x-for="msg in messages" :key="msg.id">
                            <div class="flex flex-col" :class="msg.sender_type === 'tenant' ? 'items-end' : 'items-start'">
                                
                                <!-- Attached Product snippet if any -->
                                <template x-if="msg.product">
                                    <div class="mb-1 p-2 rounded-xl bg-white dark:bg-[#21262d] border border-slate-200 dark:border-zinc-800 shadow-none max-w-xs flex items-center gap-2.5">
                                        <img x-show="msg.product.image" :src="msg.product.image" class="w-10 h-10 rounded-lg object-cover">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold text-zinc-800 dark:text-zinc-100 truncate" x-text="msg.product.name"></p>
                                            <p class="text-xs font-extrabold text-primary" x-text="'Rp ' + msg.product.price"></p>
                                        </div>
                                    </div>
                                </template>

                                <!-- Message Bubble -->
                                <div class="max-w-[75%] rounded-2xl px-4 py-2.5 shadow-none text-xs leading-relaxed"
                                     :class="msg.sender_type === 'tenant' 
                                        ? 'bg-primary text-white rounded-br-none' 
                                        : 'bg-white dark:bg-[#21262d] text-slate-800 dark:text-slate-100 border border-slate-200/80 dark:border-zinc-800 rounded-bl-none'">
                                    <p class="whitespace-pre-line" x-text="msg.message"></p>
                                    <div class="text-[9px] mt-1 text-right opacity-75" x-text="msg.created_at"></div>
                                </div>
                            </div>
                        </template>

                        <div x-show="messages.length === 0" class="text-center text-xs text-slate-400 py-12">
                            Mulai obrolan ramah dengan pelanggan ini!
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div class="p-3 border-t border-slate-200 dark:border-zinc-800 bg-white dark:bg-[#000000] flex-shrink-0">
                        <form @submit.prevent="sendMessage()" class="flex items-end gap-2">
                            <div class="flex-1 bg-slate-100 dark:bg-[#21262d] rounded-2xl border border-slate-200 dark:border-transparent focus-within:border-primary dark:focus-within:border-blue-500 focus-within:bg-white dark:focus-within:bg-[#161b22] p-2 transition-all">
                                <textarea x-model="newMessage" 
                                          @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                                          placeholder="Tulis balasan untuk pelanggan... (Tekan Enter untuk kirim)" 
                                          rows="2" 
                                          class="w-full bg-transparent border-none text-xs text-zinc-800 dark:text-zinc-100 placeholder:text-slate-400 focus:outline-none resize-none leading-relaxed"></textarea>
                            </div>
                            <button type="submit" 
                                    :disabled="!newMessage.trim() || isSending"
                                    class="w-10 h-10 rounded-2xl bg-primary text-white flex items-center justify-center hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed shadow transition flex-shrink-0">
                                <span class="material-symbols-outlined text-[18px]">send</span>
                            </button>
                        </form>
                    </div>

                </div>
            </template>

            <!-- No conversation selected -->
            <template x-if="!selectedUser">
                <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-400 bg-slate-50/30 dark:bg-[#000000]/30">
                    <div class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-3xl">chat</span>
                    </div>
                    <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-100 mb-1">Pilih Pelanggan untuk Memulai Chat</h3>
                    <p class="text-xs max-w-sm">Pilih salah satu kontak di sisi kiri untuk melihat riwayat obrolan dan mengirimkan respon.</p>
                </div>
            </template>

        </div>

    </div>
</div>

<script>
function tenantChatManager() {
    return {
        conversations: [],
        selectedUser: null,
        messages: [],
        newMessage: '',
        searchQuery: '',
        isSending: false,
        pollInterval: null,

        get filteredConversations() {
            if (!this.searchQuery) return this.conversations;
            return this.conversations.filter(c => c.user_name.toLowerCase().includes(this.searchQuery.toLowerCase()));
        },

        initChat() {
            this.fetchConversations();
            this.pollInterval = setInterval(() => {
                if (document.hidden) return;
                this.fetchConversations();
                if (this.selectedUser) {
                    this.fetchMessages(this.selectedUser.id, false);
                }
            }, 5000);

            // Listen for global FCM messages
            window.addEventListener('fcm-message-received', (e) => {
                this.fetchConversations();
                if (this.selectedUser) {
                    this.fetchMessages(this.selectedUser.id, false);
                }
            });
        },

        fetchConversations() {
            fetch('{{ route("tenant.chat.conversations") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(r => r.json())
                .then(data => {
                    this.conversations = data.conversations || [];
                    if (!this.selectedUser && this.conversations.length > 0) {
                        this.selectCustomer(this.conversations[0]);
                    }
                });
        },

        selectCustomer(conv) {
            this.selectedUser = {
                id: conv.user_id,
                name: conv.user_name,
                email: conv.user_email,
                avatar: conv.user_avatar,
            };
            this.fetchMessages(conv.user_id, true);
        },

        fetchMessages(userId, scrollDown = true) {
            fetch('{{ url("dashboard/chat/messages") }}/' + userId, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(r => r.json())
                .then(data => {
                    this.messages = data.messages || [];
                    if (scrollDown) {
                        this.$nextTick(() => {
                            const c = document.getElementById('tenantMessageContainer');
                            if (c) c.scrollTop = c.scrollHeight;
                        });
                    }
                });
        },

        sendMessage() {
            if (!this.newMessage.trim() || !this.selectedUser || this.isSending) return;
            this.isSending = true;

            fetch('{{ route("tenant.chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user_id: this.selectedUser.id,
                    message: this.newMessage
                })
            })
            .then(r => r.json())
            .then(res => {
                this.isSending = false;
                if (res.success) {
                    this.messages.push(res.message);
                    this.newMessage = '';
                    this.$nextTick(() => {
                        const c = document.getElementById('tenantMessageContainer');
                        if (c) c.scrollTop = c.scrollHeight;
                    });
                    this.fetchConversations();
                }
            })
            .catch(() => {
                this.isSending = false;
            });
        }
    }
}
</script>
@endsection
