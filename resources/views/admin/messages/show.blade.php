@extends('layouts.admin')
@section('title', 'Read Message')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-3xl mx-auto w-full">
    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm p-lg">
        <div class="flex justify-between items-start border-b border-outline-variant pb-md mb-md">
            <div>
                <h3 class="font-headline-sm font-bold text-on-surface mb-1">{{ $message->subject }}</h3>
                <div class="font-body-md text-on-surface-variant">
                    From: <strong>{{ $message->name }}</strong> &lt;<a href="mailto:{{ $message->email }}" class="text-secondary hover:underline">{{ $message->email }}</a>&gt;
                </div>
                @if($message->phone)
                <div class="font-body-md text-on-surface-variant">
                    Phone: {{ $message->phone }}
                </div>
                @endif
            </div>
            <div class="text-right">
                <span class="font-code-sm text-on-surface-variant">{{ $message->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
        
        <div class="font-body-lg text-on-surface whitespace-pre-wrap min-h-[200px]">{{ $message->message }}</div>

        <div class="flex justify-end gap-sm mt-lg pt-md border-t border-outline-variant">
            <a href="{{ route('admin.messages.index') }}" class="px-md py-2 border border-outline-variant rounded-lg font-label-md text-on-surface hover:bg-surface-variant transition">Back to Inbox</a>
            <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Delete this message?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-md py-2 bg-error text-white rounded-lg font-label-md font-bold hover:bg-[#93000A] transition shadow">Delete Message</button>
            </form>
        </div>
    </div>
</div>
@endsection
