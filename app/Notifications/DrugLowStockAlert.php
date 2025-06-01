<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DrugLowStockAlert extends Notification
{
    use Queueable;

    protected $product;
    protected $quantity;

    /**
     * Create a new notification instance.
     */
    public function __construct($product, $quantity)

    {
       $this->product = $product;
       $this->quantity = $quantity;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];


    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Low Stock Alert: {$this->product->name}")
            ->line("The drug **{$this->product->name}** is low in stock.")
            ->line("Expiry Date: {$this->product->expiry->format('Y-m-d')}")
            ->line("Stock Quantity: {$this->product->quantity}")
            ->action('View Product', url('/admin/products'))
            ->line('Please take action to manage this stock.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'product_id'     => $this->product->id,
            'product_name'   => $this->product->name,
            'expiry_date'    => $this->product->expiry->format('Y-m-d'),
            'quantity'       => $this->product->quantity,
        ];
    }
}
