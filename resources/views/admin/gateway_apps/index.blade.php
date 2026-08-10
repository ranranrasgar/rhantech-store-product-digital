@extends('layouts.admin')
@section('title', 'Gateway Apps')

@section('content')
<div class="p-lg">
    <div class="flex justify-between items-center mb-lg">
        <h2 class="font-headline-sm font-bold text-on-surface">Manage Gateway Apps</h2>
        <a href="{{ route('admin.gateway_apps.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-bold hover:bg-primary/90 transition-colors flex items-center gap-2" wire:navigate>
            <span class="material-symbols-outlined">add</span>
            Add Gateway App
        </a>
    </div>

    @if(session('success'))
        <div class="bg-secondary-container text-on-secondary-container p-4 rounded-lg mb-lg border border-secondary/20">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-lg border border-outline-variant  overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/50">
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Name</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Prefix</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Callback URL</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Status</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse($apps as $app)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="p-4 font-body-md text-on-surface">{{ $app->name }}</td>
                            <td class="p-4 font-body-md text-on-surface"><span class="bg-surface-variant px-2 py-1 rounded font-code-sm">{{ $app->prefix }}</span></td>
                            <td class="p-4 font-body-md text-on-surface text-sm truncate max-w-xs">{{ $app->callback_url }}</td>
                            <td class="p-4 font-body-md">
                                @if($app->is_active)
                                    <span class="bg-[#e6f4ea] text-[#137333] px-2 py-1 rounded-full text-xs font-bold">Active</span>
                                @else
                                    <span class="bg-[#fce8e6] text-[#c5221f] px-2 py-1 rounded-full text-xs font-bold">Inactive</span>
                                @endif
                            </td>
                            <td class="p-4 font-body-md">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.gateway_apps.edit', $app->id) }}" class="text-secondary hover:bg-secondary/10 p-1 rounded transition-colors" title="Edit" wire:navigate>
                                        <span class="material-symbols-outlined text-[1.25rem]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.gateway_apps.destroy', $app->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this Gateway App?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-error hover:bg-error/10 p-1 rounded transition-colors" title="Delete">
                                            <span class="material-symbols-outlined text-[1.25rem]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-on-surface-variant">
                                No Gateway Apps found. Create one to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-outline-variant/30">
            {{ $apps->links() }}
        </div>
    </div>
</div>
@endsection
