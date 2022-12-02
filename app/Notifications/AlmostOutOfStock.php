<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AlmostOutOfStock extends Notification
{
    use Queueable;

    const MINIMUM_COUNT = 15;
    public Product $product;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        if ($this->product->quantity === 0) {
            return "Product: " . $this->product->name . " is out of stock.";
        }

        return "Product: " . $this->product->name . " is running out of stock.";
    }
}
