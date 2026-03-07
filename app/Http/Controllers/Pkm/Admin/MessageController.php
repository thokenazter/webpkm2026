<?php

namespace App\Http\Controllers\Pkm\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->paginate(15);
        $unreadCount = Message::unread()->count();

        return view('pkm.admin.messages.index', compact('messages', 'unreadCount'));
    }

    public function show(Message $message)
    {
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('pkm.admin.messages.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return redirect()->route('pkm-admin.messages.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}
