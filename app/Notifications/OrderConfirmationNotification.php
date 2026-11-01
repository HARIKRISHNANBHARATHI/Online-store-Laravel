<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Order Confirmation - #{$this->order->order_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Thank you for your order! We have received your order #{$this->order->order_number}.")
            ->line("Order Total: $" . number_format($this->order->total, 2))
            ->action('View Your Order', url('/orders/' . $this->order->id))
            ->line('Thank you for shopping with us!');
    }
}
