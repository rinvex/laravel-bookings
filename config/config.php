<?php

declare(strict_types=1);

return [

    // Bookings Database Tables
    'tables' => [
        'bookings' => 'bookings',
        'rates' => 'bookable_rates',
        'prices' => 'bookable_prices',
    ],

    // Bookings Models
    'models' => [
        'rate' => \Rinvex\Bookings\Models\Rate::class,
        'price' => \Rinvex\Bookings\Models\Price::class,
        'booking' => \Rinvex\Bookings\Models\Booking::class,
    ],

];
