<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user          = auth()->user();
        $conversations = Message::conversationsFor($user);

        $conversations = $conversations->map(function ($contact) use ($user) {
            $lastMessage = Message::where(function ($q) use ($user, $contact) {
                $q->where('sender_id', $user->id)->where('receiver_id', $contact->id);
            })->orWhere(function ($q) use ($user, $contact) {
                $q->where('sender_id', $contact->id)->where('receiver_id', $user->id);
            })->latest()->first();

            $contact->lastMessage = $lastMessage;
            $contact->unreadCount = Message::where('sender_id', $contact->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)->count();
            return $contact;
        })->sortByDesc(fn($c) => optional($c->lastMessage)->created_at);

        return view('messages.index', compact('conversations'));
    }

    public function thread(User $contact)
    {
        $user = auth()->user();

        $messages = Message::where(function ($q) use ($user, $contact) {
            $q->where('sender_id', $user->id)->where('receiver_id', $contact->id);
        })->orWhere(function ($q) use ($user, $contact) {
            $q->where('sender_id', $contact->id)->where('receiver_id', $user->id);
        })->orderBy('created_at')->get();

        Message::where('sender_id', $contact->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.thread', compact('messages', 'contact'));
    }

    public function send(Request $request, User $contact)
    {
        $validated = $request->validate(['body' => 'required|string|max:2000']);

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $contact->id,
            'body'        => $validated['body'],
            'is_read'     => false,
        ]);

        return redirect()->route('messages.thread', $contact)->with('success', 'Message envoyé.');
    }
}
