<?php

namespace Database\Seeders;

use App\Models\Frontend;
use Illuminate\Database\Seeder;

class ProductsPageSliderSeeder extends Seeder
{
    /**
     * Default slides for Products page slider (image filenames in assets/assets2/images/slider/).
     */
    protected array $defaultSlides = [
        ['image' => 'slider8.png',  'title' => 'SMS Lord',     'url' => 'https://smslord.com/'],
        ['image' => 'slider7.jpg',  'title' => 'Refer',        'url' => '/user/refer'],
        ['image' => 'slide1.png',  'title' => 'WhatsApp',     'url' => 'https://chat.whatsapp.com/FMHbUFjeZi9Jy3raLXB3ZG'],
        ['image' => 'slide2.png',  'title' => 'Telegram Care', 'url' => 'https://t.me/loggsplugcare0'],
        ['image' => 'slide3.png',  'title' => 'Telegram Shop', 'url' => 'https://t.me/loggsplugshop'],
        ['image' => 'slide4.png',  'title' => 'Slide',         'url' => '#'],
        ['image' => 'slider5.png',  'title' => 'Send Gift',    'url' => 'https://jollyboxfr.com/'],
    ];

    public function run(): void
    {
        $existing = Frontend::where('data_keys', 'products_slider.element')->count();
        if ($existing > 0) {
            return;
        }

        foreach ($this->defaultSlides as $index => $slide) {
            Frontend::create([
                'data_keys'   => 'products_slider.element',
                'data_values' => (object) [
                    'image' => $slide['image'],
                    'title' => $slide['title'],
                    'url'   => $slide['url'],
                ],
            ]);
        }
    }
}
