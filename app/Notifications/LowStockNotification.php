<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    private $ingredient;

    public function __construct($ingredient)
    {
        $this->ingredient = $ingredient;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // คุณสามารถเลือกใช้ 'mail', 'database', 'slack' หรือช่องทางอื่นได้
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('วัตถุดิบเหลือน้อย')
                    ->line('วัตถุดิบ ' . $this->ingredient->name . ' เหลือน้อยในคลัง')
                    ->line('ปริมาณคงเหลือ: ' . $this->ingredient->stock)
                    ->line('กรุณาจัดการสั่งซื้อเพิ่มเติม');
    }

    public function toArray($notifiable)
    {
        return [
            'ingredient_name' => $this->ingredient->name,
            'current_stock' => $this->ingredient->stock,
        ];
    }
}
