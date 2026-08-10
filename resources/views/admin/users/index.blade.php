@extends('layouts.admin')
@section('title', 'Users Management')

@section('content')
<div class="p-lg">
    <div class="flex justify-between items-center mb-lg">
        <h2 class="font-headline-sm font-bold text-on-surface">All Users</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-bold hover:bg-primary/90 transition-colors flex items-center gap-2" wire:navigate>
            <span class="material-symbols-outlined text-[1.25rem]">add</span> Add New User
        </a>
    </div>

    @if(session('success'))
        <div class="bg-surface-container-highest text-on-surface p-4 rounded-lg mb-lg border border-outline-variant">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-error-container text-on-error-container p-4 rounded-lg mb-lg border border-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden ">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="py-3 px-4 font-bold text-on-surface-variant font-label-md">Name</th>
                        <th class="py-3 px-4 font-bold text-on-surface-variant font-label-md">Email</th>
                        <th class="py-3 px-4 font-bold text-on-surface-variant font-label-md">Role</th>
                        <th class="py-3 px-4 font-bold text-on-surface-variant font-label-md">Joined</th>
                        <th class="py-3 px-4 font-bold text-on-surface-variant font-label-md text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/50">
                    @forelse($users as $user)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="py-3 px-4 text-on-surface font-body-md">{{ $user->name }}</td>
                            <td class="py-3 px-4 text-on-surface font-body-md">{{ $user->email }}</td>
                            <td class="py-3 px-4 text-on-surface font-body-md">
                                <span class="px-2 py-1 bg-secondary-container text-on-secondary-container text-xs font-bold rounded-full uppercase">{{ $user->role }}</span>
                            </td>
                            <td class="py-3 px-4 text-on-surface font-body-md">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-1.5 bg-surface-variant text-on-surface-variant rounded hover:bg-secondary-container hover:text-on-secondary-container transition-colors" title="Edit" wire:navigate>
                                        <span class="material-symbols-outlined text-[1.25rem]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-surface-variant text-on-surface-variant rounded hover:bg-error hover:text-on-error transition-colors" title="Delete">
                                            <span class="material-symbols-outlined text-[1.25rem]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-on-surface-variant">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-outline-variant/50">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
