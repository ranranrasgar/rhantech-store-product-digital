@extends('layouts.admin')

@section('title', 'Portfolio')

@section('content')
<div class="flex-1 overflow-y-auto p-lg bg-background">
    <div class="max-w-container-max mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Portfolio</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola dan pantau seluruh portofolio & proyek karya perusahaan.</p>
            </div>
            <a href="{{ route('admin.projects.create') }}" class="bg-primary hover:bg-primary/90 text-white font-label-md text-label-md py-2 px-4 rounded-lg flex items-center gap-2 transition-colors shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)]" wire:navigate>
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 20px;">add</span>
                Tambah Portfolio Baru
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
            <div class="bg-surface rounded-md border border-outline-variant p-lg shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] flex items-center justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total Portfolio</p>
                    <p class="font-headline-lg text-headline-lg text-on-surface mt-1">{{ number_format($totalProjects ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">folder</span>
                </div>
            </div>
            <div class="bg-surface rounded-md border border-outline-variant p-lg shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] flex items-center justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Published</p>
                    <p class="font-headline-lg text-headline-lg text-on-surface mt-1">{{ number_format($publishedProjects ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-[#E0F2FE] flex items-center justify-center text-[#0284C7]">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">public</span>
                </div>
            </div>
            <div class="bg-surface rounded-md border border-outline-variant p-lg shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] flex items-center justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Featured</p>
                    <p class="font-headline-lg text-headline-lg text-on-surface mt-1">{{ number_format($featuredProjects ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-[#FEF08A] flex items-center justify-center text-[#A16207]">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="bg-surface rounded-md border border-outline-variant shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Portfolio</th>
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Client</th>
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">ID</th>
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/50 bg-surface">
                        @forelse($projects ?? [] as $project)
                        <tr class="hover:bg-[#F8FAFC] transition-colors group">
                            <td class="p-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-16 h-12 rounded-lg bg-surface-container-high border border-outline-variant flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($project->thumbnail)
                                            <img class="w-full h-full object-cover" src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}"/>
                                        @else
                                            <span class="material-symbols-outlined text-outline-variant">image</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-label-md text-label-md font-bold text-on-surface group-hover:text-primary transition-colors">{{ $project->title }}</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="font-code-sm text-code-sm text-on-surface-variant">{{ $project->projectCategory->name ?? 'Uncategorized' }}</span>
                                            @if($project->projectType)
                                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-surface-container-high text-on-surface-variant font-medium border border-outline-variant/60">
                                                    {{ $project->projectType->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-md">
                                @if($project->clients && $project->clients->count() > 0)
                                    <div class="flex flex-wrap items-center gap-1">
                                        @foreach($project->clients->take(2) as $cl)
                                            <span class="inline-flex items-center text-xs bg-surface-container-high px-2 py-0.5 rounded text-on-surface border border-outline-variant/60 font-medium">
                                                {{ $cl->name }}
                                            </span>
                                        @endforeach
                                        @if($project->clients->count() > 2)
                                            <span class="text-[11px] font-bold text-primary bg-primary/10 px-1.5 py-0.5 rounded">
                                                +{{ $project->clients->count() - 2 }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                @elseif($project->client)
                                    <p class="font-body-md text-body-md text-on-surface">{{ $project->client->name }}</p>
                                @else
                                    <span class="text-xs text-on-surface-variant italic">Internal</span>
                                @endif
                            </td>
                            <td class="p-md">
                                @if($project->status == 'published')
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-[#ECFEFF] text-[#0891B2]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#0891B2]"></span>
                                    Published
                                </span>
                                @elseif($project->status == 'archived')
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-surface-variant text-on-surface-variant">
                                    <span class="w-1.5 h-1.5 rounded-full bg-outline"></span>
                                    Archived
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-[#FEF3C7] text-[#D97706]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#D97706]"></span>
                                    Draft
                                </span>
                                @endif
                                
                                @if($project->is_featured)
                                <span class="material-symbols-outlined text-[#EAB308] text-[16px] ml-1 align-middle" title="Featured">star</span>
                                @endif
                            </td>
                            <td class="p-md">
                                <span class="font-code-sm text-code-sm text-on-surface-variant bg-surface-container-low px-2 py-1 rounded">PRJ-{{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="p-md text-right relative">
                                <div class="flex justify-end items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.projects.create', ['duplicate_from' => $project->id]) }}" class="text-on-surface-variant hover:text-primary p-1 transition-colors" title="Duplikat Portfolio" wire:navigate>
                                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 20px;">content_copy</span>
                                    </a>
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="text-on-surface-variant hover:text-primary p-1 transition-colors" title="Edit" wire:navigate>
                                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 20px;">edit</span>
                                    </a>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus portfolio ini?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-on-surface-variant hover:text-error p-1 transition-colors" title="Delete">
                                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 20px;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-md text-center text-on-surface-variant">Belum ada portfolio ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(!empty($projects) && method_exists($projects, 'links') && $projects->hasPages())
            <div class="border-t border-outline-variant bg-surface p-md">
                {{ $projects->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
