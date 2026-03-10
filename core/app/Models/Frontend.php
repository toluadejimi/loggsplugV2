<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frontend extends Model
{
    protected $fillable = ['data_keys', 'data_values'];

    protected $casts = [
        'data_values' => 'object'
    ];

    public static function scopeGetContent($data_keys)
    {
        return Frontend::where('data_keys', $data_keys);
    }

    /** Ensure default Products Page Slider items exist so admin and frontend show the initial slides. */
    public static function ensureProductsSliderDefaults(): void
    {
        if (static::where('data_keys', 'products_slider.element')->exists()) {
            return;
        }
        $defaults = [
            ['image' => 'slider8.png', 'title' => 'SMS Lord', 'url' => 'https://smslord.com/'],
            ['image' => 'slider7.jpg', 'title' => 'Refer', 'url' => '/user/refer'],
            ['image' => 'slide1.png', 'title' => 'WhatsApp', 'url' => 'https://chat.whatsapp.com/FMHbUFjeZi9Jy3raLXB3ZG'],
            ['image' => 'slide2.png', 'title' => 'Telegram Care', 'url' => 'https://t.me/loggsplugcare0'],
            ['image' => 'slide3.png', 'title' => 'Telegram Shop', 'url' => 'https://t.me/loggsplugshop'],
            ['image' => 'slide4.png', 'title' => 'Slide', 'url' => '#'],
            ['image' => 'slider5.png', 'title' => 'Send Gift', 'url' => 'https://jollyboxfr.com/'],
        ];
        foreach ($defaults as $slide) {
            static::create([
                'data_keys'   => 'products_slider.element',
                'data_values' => (object) $slide,
            ]);
        }
    }
}
