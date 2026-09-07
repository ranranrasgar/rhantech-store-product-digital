@extends('layouts.admin')
@section('title', 'Edit Portfolio: ' . $project->title)
@section('content')
<div class="flex-1 overflow-y-auto p-lg bg-background">
    <div class="max-w-4xl mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Edit Portfolio</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Perbarui informasi portofolio {{ $project->title }}.</p>
            </div>
            <a href="{{ route('admin.projects.index') }}" class="text-on-surface-variant hover:bg-surface-container-high p-2 rounded-full transition-colors flex items-center justify-center" title="Kembali ke Portfolio" wire:navigate>
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 24px;">arrow_back</span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-surface rounded-md border border-outline-variant shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] p-lg">
            <form id="project-form" action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Project Title <span class="text-error">*</span></label>
                    <input type="text" name="title" required value="{{ old('title', $project->title) }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    @error('title')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- Multi-Client Combobox with Search & Tags -->
                <div class="space-y-2" x-data="{
                    open: false,
                    search: '',
                    clients: {{ json_encode($clients->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'company' => $c->company ?? ''])) }},
                    selectedClients: {{ json_encode(old('client_ids', $project->clients ? $project->clients->pluck('id')->toArray() : ($project->client_id ? [$project->client_id] : []))) }},
                    get filteredClients() {
                        if (!this.search.trim()) return this.clients;
                        const term = this.search.toLowerCase();
                        return this.clients.filter(c => 
                            c.name.toLowerCase().includes(term) || 
                            (c.company && c.company.toLowerCase().includes(term))
                        );
                    },
                    isSelected(id) {
                        return this.selectedClients.includes(id);
                    },
                    toggleClient(id) {
                        if (this.isSelected(id)) {
                            this.selectedClients = this.selectedClients.filter(c => c !== id);
                        } else {
                            this.selectedClients.push(id);
                        }
                    },
                    removeClient(id) {
                        this.selectedClients = this.selectedClients.filter(c => c !== id);
                    },
                    getClientName(id) {
                        const found = this.clients.find(c => c.id === id);
                        return found ? found.name : id;
                    }
                }" @click.away="open = false">
                    <div class="flex items-center justify-between">
                        <label class="block font-label-md text-on-surface">
                            Client Pengguna / Dipercaya Oleh (Multi-Client)
                        </label>
                        <span class="text-xs text-on-surface-variant font-semibold" x-text="selectedClients.length + ' klien dipilih'"></span>
                    </div>
                    <p class="text-[11px] text-on-surface-variant">Cari dan pilih satu atau beberapa klien yang telah menggunakan portfolio ini:</p>

                    <!-- Hidden Inputs for Form Submission -->
                    <template x-for="id in selectedClients" :key="id">
                        <input type="hidden" name="client_ids[]" :value="id">
                    </template>

                    <!-- Combobox Box Container -->
                    <div class="relative">
                        <!-- Display Input / Tag Container -->
                        <div class="min-h-[46px] w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg p-2 flex flex-wrap items-center gap-1.5 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10 transition-all cursor-text"
                             @click="$refs.searchInput.focus(); open = true">
                            
                            <!-- Selected Client Tags -->
                            <template x-for="id in selectedClients" :key="id">
                                <span class="inline-flex items-center gap-1.5 bg-primary/10 border border-primary/20 text-primary text-xs font-semibold px-2.5 py-1 rounded-md shadow-xs animate-fadeIn">
                                    <span class="material-symbols-outlined text-[14px]">apartment</span>
                                    <span x-text="getClientName(id)"></span>
                                    <button type="button" 
                                            @click.stop="removeClient(id)" 
                                            class="text-primary hover:text-error hover:bg-white/60 rounded-full p-0.5 transition-colors flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[13px]">close</span>
                                    </button>
                                </span>
                            </template>

                            <!-- Live Search Input -->
                            <input x-ref="searchInput"
                                   type="text" 
                                   x-model="search" 
                                   @focus="open = true"
                                   @keydown.escape="open = false"
                                   placeholder="Ketik untuk mencari klien..." 
                                   class="flex-1 min-w-[140px] bg-transparent border-0 p-1 text-xs text-on-surface focus:outline-none focus:ring-0 placeholder:text-outline/70">

                            <!-- Dropdown Trigger / Clear Buttons -->
                            <div class="flex items-center gap-1 ml-auto">
                                <button type="button" 
                                        x-show="selectedClients.length > 0" 
                                        @click.stop="selectedClients = []" 
                                        title="Hapus semua pilihan"
                                        class="text-[11px] text-on-surface-variant hover:text-error px-1.5 py-0.5 rounded transition-colors font-medium">
                                    Reset
                                </button>
                                <button type="button" 
                                        @click.stop="open = !open" 
                                        class="text-outline hover:text-on-surface p-1 rounded transition-colors">
                                    <span class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                                </button>
                            </div>
                        </div>

                        <!-- Dropdown Options Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute left-0 right-0 top-full mt-1 bg-surface border border-outline-variant rounded-lg shadow-lg z-50 max-h-60 overflow-y-auto p-1.5 divide-y divide-outline-variant/30"
                             style="display: none;">
                            
                            <!-- Search status header -->
                            <div class="px-2 py-1.5 text-[11px] text-on-surface-variant flex justify-between items-center bg-surface-container-low/50 rounded">
                                <span x-text="filteredClients.length + ' klien ditemukan'"></span>
                                <span class="text-[10px] text-outline">Klik untuk memilih/membatalkan</span>
                            </div>

                            <div class="pt-1">
                                <template x-for="client in filteredClients" :key="client.id">
                                    <div @click="toggleClient(client.id)"
                                         class="flex items-center justify-between px-3 py-2 rounded-md text-xs cursor-pointer transition-colors"
                                         :class="isSelected(client.id) ? 'bg-primary/10 text-primary font-bold' : 'hover:bg-surface-container-high text-on-surface'">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[16px]" :class="isSelected(client.id) ? 'text-primary' : 'text-outline'">
                                                domain
                                            </span>
                                            <div>
                                                <div x-text="client.name"></div>
                                                <div x-show="client.company" class="text-[10px] text-on-surface-variant font-normal" x-text="client.company"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <span x-show="isSelected(client.id)" class="material-symbols-outlined text-[16px] text-primary">
                                                check
                                            </span>
                                        </div>
                                    </div>
                                </template>

                                <!-- No results state -->
                                <div x-show="filteredClients.length === 0" class="p-4 text-center text-xs text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[24px] text-outline block mb-1">search_off</span>
                                    Tidak ada klien dengan nama "<span class="font-semibold text-on-surface" x-text="search"></span>"
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('client_ids')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Category (Bidang/Industri)</label>
                        <select name="project_category_id" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('project_category_id', $project->project_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('project_category_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Type / Platform</label>
                        <select name="project_type_id" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                            <option value="">Pilih Tipe / Platform</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ old('project_type_id', $project->project_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('project_type_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $project->slug) }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-mono text-sm">
                    @error('slug')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Short Description</label>
                    <textarea name="short_description" rows="2" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">{{ old('short_description', $project->short_description) }}</textarea>
                    @error('short_description')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Full Description</label>
                    <div id="editor-container" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-b-lg font-body-md text-body-md text-on-surface" style="min-height: 250px;"></div>
                    <input type="hidden" name="description" id="description" value="{{ old('description', $project->description) }}">
                    @error('description')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Project URL (Live Demo / Website)</label>
                        <input type="url" name="project_url" value="{{ old('project_url', $project->project_url) }}" placeholder="https://..." class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        @error('project_url')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Order URL (Tautan Pembelian / Halaman Produk)</label>
                        <input type="url" name="order_url" value="{{ old('order_url', $project->order_url) }}" placeholder="https://rhantech.com/products/..." class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        <p class="text-[11px] text-on-surface-variant mt-1">Opsional: Isi link produk jika project ini dijual atau dapat diorder langsung.</p>
                        @error('order_url')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Completed Date</label>
                        <input type="date" name="completed_at" value="{{ old('completed_at', $project->completed_at ? $project->completed_at->format('Y-m-d') : '') }}" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        @error('completed_at')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-md items-end">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Thumbnail Image</label>
                        @if($project->thumbnail)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="Thumbnail" class="w-32 h-auto rounded border border-outline-variant ">
                            </div>
                        @endif
                        <input type="file" name="thumbnail" accept="image/*" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        <p class="text-xs text-on-surface-variant mt-1">Leave blank to keep current thumbnail.</p>
                        @error('thumbnail')<span class="text-error text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block font-label-md text-on-surface mb-xs">Status</label>
                            <select name="status" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-lg py-2 px-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                                <option value="draft" {{ old('status', $project->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $project->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')<span class="text-error text-xs">{{ $message }}</span>@enderror
                        </div>
                        <div class="flex-1">
                            <label class="block font-label-md text-on-surface mb-xs">Featured</label>
                            <label class="flex items-center gap-2 mt-2 cursor-pointer">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $project->is_featured) ? 'checked' : '' }} class="w-5 h-5 rounded border-[#CBD5E1] text-primary focus:ring-primary">
                                <span class="font-body-md text-on-surface">Yes</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-sm mt-lg pt-md border-t border-outline-variant">
                    <a href="{{ route('admin.projects.index') }}" class="px-6 py-2 border border-outline-variant rounded-lg font-label-md font-bold text-on-surface hover:bg-surface-variant transition" wire:navigate>Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:bg-primary/90 transition shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)]">Update Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quill Rich Text Editor -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    function initQuillProjectEdit() {
        var editorElem = document.getElementById('editor-container');
        if (!editorElem || editorElem.__quill_initialized) return;

        // Clear existing toolbar/editor if re-initialized
        editorElem.innerHTML = '';
        var prevToolbar = editorElem.previousElementSibling;
        if (prevToolbar && prevToolbar.classList.contains('ql-toolbar')) {
            prevToolbar.remove();
        }

        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Write the full description here...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });
        editorElem.__quill_initialized = true;

        var descriptionInput = document.getElementById('description');
        if (descriptionInput && descriptionInput.value) {
            quill.root.innerHTML = descriptionInput.value;
        }

        // Realtime sync to hidden input on every change
        quill.on('text-change', function() {
            var html = quill.root.innerHTML;
            descriptionInput.value = (html === '<p><br></p>' || quill.getText().trim().length === 0) ? '' : html;
        });

        // Add custom styles to match theme
        var toolbar = editorElem.previousElementSibling;
        if (toolbar && toolbar.classList.contains('ql-toolbar')) {
            toolbar.classList.add('bg-surface-container', 'border-[#CBD5E1]', 'rounded-t-lg');
        }
        editorElem.classList.add('border-t-0', 'border-[#CBD5E1]', 'rounded-b-lg', 'bg-surface-container-low');

        var projectForm = document.getElementById('project-form') || editorElem.closest('form');
        if (projectForm) {
            projectForm.addEventListener('submit', function(e) {
                var html = quill.root.innerHTML;
                descriptionInput.value = (html === '<p><br></p>' || quill.getText().trim().length === 0) ? '' : html;
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initQuillProjectEdit);
    document.addEventListener('livewire:navigated', initQuillProjectEdit);
</script>
@endsection
