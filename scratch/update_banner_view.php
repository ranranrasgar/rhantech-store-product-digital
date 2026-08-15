<?php

$f = 'resources/views/admin/banners/index.blade.php';

$content = <<<'HTML'
@extends('layouts.admin')
@section('title', 'Kelola Banner Produk')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    .cropper-container { max-height: 60vh; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-6" x-data="bannerCropper()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Banner Publik</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola 3 banner utama yang tampil di halaman Katalog Produk.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.banners.update') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            
            {{-- Main Banner --}}
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 p-5 col-span-1 lg:col-span-2">
                <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-gray-100">Banner Utama (Kiri)</h3>
                <div class="mb-4">
                    @php $main = $banners->get('main'); @endphp
                    <div class="relative group cursor-pointer" @click="$refs.mainInput.click()">
                        <img :src="previews.main || '{{ $main && $main->image_path ? asset('storage/' . $main->image_path) : '' }}'" 
                             x-show="previews.main || '{{ $main && $main->image_path ? 1 : '' }}'"
                             alt="Main Banner" 
                             class="w-full h-auto object-cover border rounded bg-gray-50 dark:border-slate-600 mb-3 hover:opacity-90 transition-opacity" 
                             style="max-height: 250px;">
                        
                        <div x-show="!previews.main && !'{{ $main && $main->image_path ? 1 : '' }}'"
                             class="w-full h-[200px] bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 transition-colors rounded flex flex-col items-center justify-center text-gray-400 mb-3 border dark:border-slate-600">
                             <span class="material-symbols-outlined text-4xl mb-2">add_photo_alternate</span>
                             <span>Klik untuk pilih gambar</span>
                        </div>
                    </div>
                    
                    <input type="file" x-ref="mainInput" accept="image/*" class="hidden" @change="openCropper($event, 'main', 2/1)">
                    <input type="hidden" name="banners[main][image_base64]" :value="base64Data.main">
                    
                    <button type="button" @click="$refs.mainInput.click()" class="w-full py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300 rounded-md font-semibold text-sm transition-colors">
                        Pilih & Potong Gambar Baru
                    </button>
                    <p class="text-xs text-gray-500 mt-2 text-center">Rekomendasi: Lanskap (Rasio 2:1, contoh: 800x400px)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tautan URL (Opsional)</label>
                    <input type="url" name="banners[main][link]" value="{{ $main->link ?? '' }}" placeholder="https://..." class="w-full px-3 py-2 border rounded-md dark:bg-slate-900 dark:border-slate-700 dark:text-white">
                </div>
            </div>

            <div class="col-span-1 flex flex-col gap-6">
                {{-- Side Banner 1 --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 p-5">
                    <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-gray-100">Banner Samping (Atas)</h3>
                    <div class="mb-4">
                        @php $side1 = $banners->get('side_1'); @endphp
                        
                        <div class="relative group cursor-pointer" @click="$refs.side1Input.click()">
                            <img :src="previews.side_1 || '{{ $side1 && $side1->image_path ? asset('storage/' . $side1->image_path) : '' }}'" 
                                 x-show="previews.side_1 || '{{ $side1 && $side1->image_path ? 1 : '' }}'"
                                 alt="Side Banner 1" 
                                 class="w-full h-[120px] object-cover border rounded bg-gray-50 dark:border-slate-600 mb-3 hover:opacity-90 transition-opacity">
                            
                            <div x-show="!previews.side_1 && !'{{ $side1 && $side1->image_path ? 1 : '' }}'"
                                 class="w-full h-[120px] bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 transition-colors rounded flex flex-col items-center justify-center text-gray-400 mb-3 border dark:border-slate-600">
                                 <span class="material-symbols-outlined text-3xl mb-1">add_photo_alternate</span>
                                 <span class="text-xs">Pilih gambar</span>
                            </div>
                        </div>

                        <input type="file" x-ref="side1Input" accept="image/*" class="hidden" @change="openCropper($event, 'side_1', 16/9)">
                        <input type="hidden" name="banners[side_1][image_base64]" :value="base64Data.side_1">
                        
                        <button type="button" @click="$refs.side1Input.click()" class="w-full py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded text-sm font-semibold transition-colors">
                            Pilih & Potong
                        </button>
                    </div>
                    <div>
                        <input type="url" name="banners[side_1][link]" value="{{ $side1->link ?? '' }}" placeholder="Tautan URL..." class="w-full px-3 py-1.5 text-sm border rounded-md dark:bg-slate-900 dark:border-slate-700 dark:text-white">
                    </div>
                </div>

                {{-- Side Banner 2 --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-slate-700 p-5">
                    <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-gray-100">Banner Samping (Bawah)</h3>
                    <div class="mb-4">
                        @php $side2 = $banners->get('side_2'); @endphp
                        
                        <div class="relative group cursor-pointer" @click="$refs.side2Input.click()">
                            <img :src="previews.side_2 || '{{ $side2 && $side2->image_path ? asset('storage/' . $side2->image_path) : '' }}'" 
                                 x-show="previews.side_2 || '{{ $side2 && $side2->image_path ? 1 : '' }}'"
                                 alt="Side Banner 2" 
                                 class="w-full h-[120px] object-cover border rounded bg-gray-50 dark:border-slate-600 mb-3 hover:opacity-90 transition-opacity">
                            
                            <div x-show="!previews.side_2 && !'{{ $side2 && $side2->image_path ? 1 : '' }}'"
                                 class="w-full h-[120px] bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 transition-colors rounded flex flex-col items-center justify-center text-gray-400 mb-3 border dark:border-slate-600">
                                 <span class="material-symbols-outlined text-3xl mb-1">add_photo_alternate</span>
                                 <span class="text-xs">Pilih gambar</span>
                            </div>
                        </div>

                        <input type="file" x-ref="side2Input" accept="image/*" class="hidden" @change="openCropper($event, 'side_2', 16/9)">
                        <input type="hidden" name="banners[side_2][image_base64]" :value="base64Data.side_2">
                        
                        <button type="button" @click="$refs.side2Input.click()" class="w-full py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded text-sm font-semibold transition-colors">
                            Pilih & Potong
                        </button>
                    </div>
                    <div>
                        <input type="url" name="banners[side_2][link]" value="{{ $side2->link ?? '' }}" placeholder="Tautan URL..." class="w-full px-3 py-1.5 text-sm border rounded-md dark:bg-slate-900 dark:border-slate-700 dark:text-white">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-lg shadow transition-colors">
                Simpan Perubahan Banner
            </button>
        </div>
    </form>

    {{-- Cropper Modal --}}
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-black/60 p-4 sm:p-0">
        <div class="relative w-full max-w-4xl max-h-full rounded-lg bg-white shadow-xl dark:bg-slate-800" @click.away="closeCropper()">
            <div class="flex items-center justify-between rounded-t border-b p-4 sm:p-5 dark:border-slate-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Sesuaikan Gambar (Drag & Zoom)
                </h3>
                <button type="button" @click="closeCropper()" class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="p-4 space-y-4">
                <div class="w-full bg-gray-100 dark:bg-slate-900 flex justify-center border dark:border-slate-700">
                    <img id="cropperImage" src="" alt="Image to crop" style="max-width: 100%; max-height: 60vh;">
                </div>
            </div>
            
            <div class="flex items-center justify-end rounded-b border-t p-4 sm:p-5 dark:border-slate-600 gap-3">
                <button type="button" @click="closeCropper()" class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Batal</button>
                <button type="button" @click="applyCrop()" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800">Terapkan Potongan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bannerCropper', () => ({
            showModal: false,
            currentPosition: '',
            cropperInstance: null,
            
            previews: {
                main: '',
                side_1: '',
                side_2: ''
            },
            
            base64Data: {
                main: '',
                side_1: '',
                side_2: ''
            },
            
            openCropper(event, position, ratio) {
                const file = event.target.files[0];
                if (!file) return;
                
                this.currentPosition = position;
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    const imageElement = document.getElementById('cropperImage');
                    imageElement.src = e.target.result;
                    
                    this.showModal = true;
                    
                    if (this.cropperInstance) {
                        this.cropperInstance.destroy();
                    }
                    
                    setTimeout(() => {
                        this.cropperInstance = new Cropper(imageElement, {
                            aspectRatio: ratio,
                            viewMode: 2,
                            dragMode: 'move',
                            autoCropArea: 1,
                            restore: false,
                            guides: true,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                        });
                    }, 100);
                };
                reader.readAsDataURL(file);
                
                // Reset file input so same file can be selected again
                event.target.value = '';
            },
            
            closeCropper() {
                this.showModal = false;
                if (this.cropperInstance) {
                    this.cropperInstance.destroy();
                    this.cropperInstance = null;
                }
                this.currentPosition = '';
            },
            
            applyCrop() {
                if (!this.cropperInstance || !this.currentPosition) return;
                
                // Get cropped canvas
                const canvas = this.cropperInstance.getCroppedCanvas({
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });
                
                // Convert to base64
                const base64 = canvas.toDataURL('image/jpeg', 0.9);
                
                // Save to state
                this.previews[this.currentPosition] = base64;
                this.base64Data[this.currentPosition] = base64;
                
                this.closeCropper();
            }
        }));
    });
</script>
@endpush
HTML;

file_put_contents($f, $content);
echo "Successfully updated Banners Admin view.\n";
