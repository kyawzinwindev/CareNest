<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public int $unreadCount = 0;
    public array $notifications = [];

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->unreadCount = $user->notifications()->where('is_read', false)->count();
            $this->notifications = $user->notifications()
                ->latest()
                ->take(10)
                ->get()
                ->map(fn($notification) => [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->toIso8601String(),
                    'created_at_human' => $notification->created_at->diffForHumans(),
                ])
                ->toArray();
        }
    }

    public function markAsRead(int $notificationId): void
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->find($notificationId);
            if ($notification) {
                $notification->update(['is_read' => true]);
                $this->loadNotifications();
            }
        }
    }

    public function markAllAsRead(): void
    {
        if (Auth::check()) {
            Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
