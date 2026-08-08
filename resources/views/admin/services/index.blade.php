@extends('layouts.admin')

@section('title', 'Services')

@section('content')
<div class="flex-1 overflow-y-auto p-lg bg-background">
    <div class="max-w-container-max mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Services</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Manage the services offered by the company.</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="bg-[#06B6D4] hover:bg-[#06B6D4]/90 text-white font-label-md text-label-md py-2 px-4 rounded-lg flex items-center gap-2 transition-colors shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)]">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 20px;">add</span>
                Add New Service
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
            <div class="bg-surface rounded-xl border border-outline-variant p-lg shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] flex items-center justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total Services</p>
                    <p class="font-headline-lg text-headline-lg text-on-surface mt-1">{{ number_format($totalServices ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">layers</span>
                </div>
            </div>
            <div class="bg-surface rounded-xl border border-outline-variant p-lg shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] flex items-center justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Active</p>
                    <p class="font-headline-lg text-headline-lg text-on-surface mt-1">{{ number_format($activeServices ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-[#E0F2FE] flex items-center justify-center text-[#0284C7]">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">verified</span>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="bg-surface rounded-xl border border-outline-variant shadow-[0px_4px_6px_-1px_rgba(15,23,42,0.03),0px_2px_4px_-2px_rgba(15,23,42,0.03)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface border-b border-outline-variant">
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap w-20">Icon</th>
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Service</th>
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap">ID</th>
                            <th class="p-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider whitespace-nowrap text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/50 bg-surface">
                        @forelse($services ?? [] as $service)
                        <tr class="hover:bg-[#F8FAFC] transition-colors group">
                            <td class="p-md">
                                <div class="w-12 h-12 rounded-lg bg-surface-container-high border border-outline-variant flex items-center justify-center text-primary overflow-hidden flex-shrink-0">
                                    @if($service->icon)
                                        <span class="material-symbols-outlined" style="font-size: 28px;">{{ $service->icon }}</span>
                                    @else
                                        <span class="material-symbols-outlined text-outline-variant">layers</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-md">
                                <p class="font-label-md text-label-md font-bold text-on-surface group-hover:text-[#06B6D4] transition-colors">{{ $service->name }}</p>
                                <p class="font-code-sm text-code-sm text-on-surface-variant truncate max-w-xs">{{ Str::limit($service->short_description ?: $service->description, 50) }}</p>
                            </td>
                            <td class="p-md">
                                @if($service->is_active)
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-[#ECFEFF] text-[#0891B2]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#0891B2]"></span>
                                    Active
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-surface-variant text-on-surface-variant">
                                    <span class="w-1.5 h-1.5 rounded-full bg-outline"></span>
                                    Inactive
                                </span>
                                @endif
                            </td>
                            <td class="p-md">
                                <span class="font-code-sm text-code-sm text-on-surface-variant bg-surface-container-low px-2 py-1 rounded">SRV-{{ str_pad($service->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="p-md text-right relative">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="text-on-surface-variant hover:text-[#06B6D4] p-1 transition-colors" title="Edit">
                                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0; font-size: 20px;">edit</span>
                                    </a>
                                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');" class="inline-block">
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
                            <td colspan="5" class="p-md text-center text-on-surface-variant">No services found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(!empty($services) && method_exists($services, 'links') && $services->hasPages())
            <div class="border-t border-outline-variant bg-surface p-md">
                {{ $services->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
