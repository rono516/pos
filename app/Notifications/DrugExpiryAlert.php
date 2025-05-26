<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DrugExpiryAlert extends Notification
{
    use Queueable;

    protected $product;
    protected $daysToExpiry;

    /**
     * Create a new notification instance.
     */
    public function __construct($product, $daysToExpiry)
    {
        $this->product      = $product;
        $this->daysToExpiry = $daysToExpiry;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // send by mail and store in database
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // return (new MailMessage)
        //     ->line('The introduction to the notification.')
        //     ->action('Notification Action', url('/'))
        //     ->line('Thank you for using our application!');
        return (new MailMessage)
            ->subject("Drug Expiry Alert: {$this->product->name}")
            ->line("The drug **{$this->product->name}** is nearing its expiry date.")
            ->line("Expiry Date: {$this->product->expiry->format('Y-m-d')}")
            ->line("Days to Expiry: {$this->daysToExpiry}")
            ->line("Stock Quantity: {$this->product->quantity}")
            // ->action('View Product', url('/products/' . $this->product->id))
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
        // return [
        //     //
        // ];
        return [
            'product_id'     => $this->product->id,
            'product_name'   => $this->product->name,
            'expiry_date'    => $this->product->expiry->format('Y-m-d'),
            'days_to_expiry' => $this->daysToExpiry,
            'quantity'       => $this->product->quantity,
        ];
    }
}
