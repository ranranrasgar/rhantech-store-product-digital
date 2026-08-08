@extends('layouts.admin')
@section('title', 'Edit User')

@section('content')
<div class="p-lg max-w-3xl mx-auto">
    <div class="flex items-center gap-4 mb-lg">
        <a href="{{ route('admin.users.index') }}" class="p-2 bg-surface-container-low text-on-surface-variant hover:bg-surface-variant rounded-full transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="font-headline-sm font-bold text-on-surface">Edit User: {{ $user->name }}</h2>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-surface-container-lowest p-lg rounded-2xl border border-outline-variant shadow-sm flex flex-col gap-lg">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-label-md font-bold text-on-surface mb-2">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full bg-surface-container-low border @error('name') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required>
            @error('name')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block font-label-md font-bold text-on-surface mb-2">Email Address</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full bg-surface-container-low border @error('email') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required>
            @error('email')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="role" class="block font-label-md font-bold text-on-surface mb-2">Role</label>
            <select name="role" id="role" class="w-full bg-surface-container-low border @error('role') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required>
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="p-4 bg-surface-container-low border border-outline-variant rounded-lg">
            <h3 class="font-label-md font-bold text-on-surface mb-2">Change Password (Optional)</h3>
            <p class="text-sm text-on-surface-variant mb-4">Leave fields blank if you do not want to change the password.</p>
            
            <div class="mb-4">
                <label for="password" class="block font-label-md font-bold text-on-surface mb-2">New Password</label>
                <input type="password" name="password" id="password" class="w-full bg-surface border @error('password') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                @error('password')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block font-label-md font-bold text-on-surface mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full bg-surface border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-outline-variant/30">
            <button type="submit" class="bg-primary text-on-primary px-6 py-2 rounded-lg font-bold hover:bg-primary/90 transition-colors">Update User</button>
        </div>
    </form>
</div>
@endsection
