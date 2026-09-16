<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        /** @var \Illuminate\Database\Eloquent\Builder<ContactMessage> $query */
        $query = ContactMessage::query();

        // 1. Filter Pencarian (Search)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // 2. Filter Status (unread / read)
        if ($request->input('status') === 'unread') {
            $query->where('read_at', null);
        } elseif ($request->input('status') === 'read') {
            $query->whereNotNull('read_at');
        }

        // 3. Filter Tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Metrik Ringkasan
        $totalCount = ContactMessage::count('*');
        $unreadCount = ContactMessage::whereNull('read_at')->count('*');
        $readCount = ContactMessage::whereNotNull('read_at')->count('*');

        $messages = $query->latest()->paginate(15)->withQueryString();

        return view('admin.messages.index', compact('messages', 'totalCount', 'unreadCount', 'readCount'));
    }

    public function show(ContactMessage $message)
    {
        if (is_null($message->read_at)) {
            $message->update(['read_at' => now()]);
        }
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Pesan berhasil dihapus.');
    }

    /**
     * Hapus global / massal (Bulk Destroy)
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

        $ids = array_filter(array_map('intval', (array)$ids));

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu pesan untuk dihapus.');
        }

        $count = ContactMessage::whereIn('id', $ids)->delete();
        return redirect()->route('admin.messages.index')->with('success', "{$count} pesan berhasil dihapus secara massal.");
    }

    /**
     * Aksi massal fleksibel (Bulk Action: delete, mark as read, mark as unread)
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('selected_ids', []);
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        $ids = array_filter(array_map('intval', (array)$ids));

        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu pesan terlebih dahulu.');
        }

        if ($action === 'delete') {
            $count = ContactMessage::whereIn('id', $ids)->delete();
            return redirect()->route('admin.messages.index')->with('success', "{$count} pesan berhasil dihapus.");
        } elseif ($action === 'mark_read') {
            $count = ContactMessage::whereIn('id', $ids)->whereNull('read_at')->update(['read_at' => now()]);
            return redirect()->route('admin.messages.index')->with('success', "{$count} pesan ditandai sudah dibaca.");
        } elseif ($action === 'mark_unread') {
            $count = ContactMessage::whereIn('id', $ids)->whereNotNull('read_at')->update(['read_at' => null]);
            return redirect()->route('admin.messages.index')->with('success', "{$count} pesan ditandai belum dibaca.");
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    /**
     * Toggle status terbaca / belum dibaca
     */
    public function toggleRead(ContactMessage $message)
    {
        $isRead = !is_null($message->read_at);
        $message->update(['read_at' => $isRead ? null : now()]);
        $statusText = $isRead ? 'ditandai belum dibaca' : 'ditandai sudah dibaca';

        return back()->with('success', "Pesan {$statusText}.");
    }

    /**
     * Kirim balasan email ke pengirim pesan
     */
    public function reply(Request $request, ContactMessage $message)
    {
        $request->validate([
            'reply_subject' => 'required|string|max:255',
            'reply_message' => 'required|string',
        ]);

        try {
            $company = CompanyProfile::first();
            $companyName = $company->company_name ?? config('app.name', 'Rhantech');

            $replySubject = $request->input('reply_subject');
            $replyBody = $request->input('reply_message');

            Mail::raw($replyBody, function ($mail) use ($message, $replySubject, $companyName) {
                $mail->to($message->email, $message->name)
                     ->subject($replySubject);
            });

            // Tandai sudah dibaca saat dibalas
            if (is_null($message->read_at)) {
                $message->update(['read_at' => now()]);
            }

            return back()->with('success', "Balasan email berhasil dikirim ke {$message->email}.");
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email balasan ke {$message->email}: " . $e->getMessage());
            return back()->with('error', "Gagal mengirim email: " . $e->getMessage());
        }
    }
}
