<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Product;
use App\Models\Report;
use App\Notifications\MarketplaceNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $conversations = Conversation::where(function ($q) use ($userId) {
            $q->where('user_one_id', $userId)->orWhere('user_two_id', $userId);
        })
            ->with(['userOne', 'userTwo', 'product.images'])
            ->orderByDesc('last_message_at')
            ->paginate(15);

        $conversations->getCollection()->transform(function (Conversation $conversation) use ($userId) {
            $conversation->other = $conversation->otherUser($userId);
            $conversation->unread = $conversation->unreadCountFor($userId);

            return $conversation;
        });

        return view('messages.index', compact('conversations'));
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $this->authorize('view', $conversation);

        $userId = $request->user()->id;

        // Mark messages from the other user as read.
        $conversation->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->load(['messages.sender', 'product.images', 'userOne', 'userTwo']);
        $other = $conversation->otherUser($userId);

        return view('messages.show', compact('conversation', 'other'));
    }

    public function start(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        if ($product->user_id === $user->id) {
            return back()->with('warning', 'You cannot start a conversation about your own listing.');
        }

        $conversation = Conversation::firstOrCreate(
            [
                'user_one_id' => min($user->id, $product->user_id),
                'user_two_id' => max($user->id, $product->user_id),
                'product_id' => $product->id,
            ],
            ['last_message_at' => now()],
        );

        return redirect()->route('messages.show', $conversation);
    }

    public function storeMessage(Request $request, Conversation $conversation): RedirectResponse
    {
        $this->authorize('view', $conversation);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        $recipient = $conversation->otherUser($request->user()->id);
        if ($recipient) {
            $recipient->notify(new MarketplaceNotification(
                title: 'New message from ' . $request->user()->name,
                message: \Illuminate\Support\Str::limit($data['body'], 80),
                url: route('messages.show', $conversation),
                level: 'info',
            ));
        }

        return redirect()->route('messages.show', $conversation);
    }

    public function report(Request $request, Conversation $conversation): RedirectResponse
    {
        $this->authorize('view', $conversation);

        $data = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:1000'],
        ]);

        Report::create([
            'reporter_id' => $request->user()->id,
            'reportable_type' => Conversation::class,
            'reportable_id' => $conversation->id,
            'reason' => $data['reason'],
            'details' => $data['details'] ?? null,
            'status' => 'open',
        ]);

        return back()->with('success', 'Thank you. Your report has been submitted for moderation.');
    }
}
