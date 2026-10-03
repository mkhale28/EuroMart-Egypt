<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Notifications\OrderStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderStatusNotification implements ShouldQueue
{
    public function handle(OrderStatusChanged $event): void
    {
        if ($event->order->user) {
            $event->order->user->notify(
                new OrderStatusNotification($event->order, $event->oldStatus, $event->newStatus)
            );
        }
    }
}
