@extends('layouts.admin')
@section('title', 'Inbox Messages')
@section('content')
<div class="p-lg md:p-xl flex-1 flex flex-col gap-lg max-w-container-max mx-auto w-full">
    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden flex-1">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container-low sticky top-0 border-b border-outline-variant">
                    <tr>
                        <th class="py-3 px-4 font-label-md font-bold text-on-surface uppercase tracking-wider">Date</th>
                        <th class="py-3 px-4 font-label-md font-bold text-on-surface uppercase tracking-wider">Name</th>
                        <th class="py-3 px-4 font-label-md font-bold text-on-surface uppercase tracking-wider">Subject</th>
                        <th class="py-3 px-4 font-label-md font-bold text-on-surface uppercase tracking-wider text-center">Status</th>
                        <th class="py-3 px-4 font-label-md font-bold text-on-surface uppercase tracking-wider text-right pr-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/50 bg-surface">
                    @forelse($messages as $msg)
                    <tr class="hover:bg-surface-container-lowest transition-colors group {{ $msg->read_at ? 'opacity-80' : 'bg-surface-dim/30' }}">
                        <td class="py-3 px-4 font-body-md text-on-surface whitespace-nowrap">{{ $msg->created_at->format('d M Y H:i') }}</td>
                        <td class="py-3 px-4 font-body-md font-semibold text-on-surface">
                            {{ $msg->name }}
                            <div class="font-code-sm text-on-surface-variant font-normal">{{ $msg->email }}</div>
                        </td>
                        <td class="py-3 px-4 font-body-md text-on-surface">{{ $msg->subject }}</td>
                        <td class="py-3 px-4 text-center">
                            @if($msg->read_at)
                            <span class="inline-flex px-2 py-1 rounded-full bg-[#F3F4F6] text-[#4B5563] font-label-md text-xs font-bold border border-[#E5E7EB]">Read</span>
                            @else
                            <span class="inline-flex px-2 py-1 rounded-full bg-[#E0F2FE] text-[#0369A1] font-label-md text-xs font-bold border border-[#BAE6FD]">Unread</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right pr-6">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.messages.show', $msg) }}" class="p-1.5 text-on-surface-variant hover:text-secondary bg-surface-container hover:bg-secondary-container/30 rounded transition" title="Read">
                                    <span class="material-symbols-outlined" style="font-size: 20px;">visibility</span>
                                </a>
                                <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" onsubmit="return confirm('Delete this message?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-on-surface-variant hover:text-error bg-surface-container hover:bg-error-container/30 rounded transition" title="Delete">
                                        <span class="material-symbols-outlined" style="font-size: 20px;">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 bg-surface-container-high rounded-full flex items-center justify-center mb-4 text-on-surface-variant">
                                    <span class="material-symbols-outlined" style="font-size: 32px;">mark_email_unread</span>
                                </div>
                                <h3 class="font-headline-sm text-on-surface mb-2">No messages found</h3>
                                <p class="font-body-md text-on-surface-variant max-w-sm mx-auto">When visitors submit messages through your contact form, they will appear here.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($messages, 'links'))
        <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
