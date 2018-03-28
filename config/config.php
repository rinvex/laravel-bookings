<?php

declare(strict_types=1);

return [

    // Bookings Database Tables
    'tables' => [
        'addons' => 'addons',
        'bookings' => 'bookings',
        'rates' => 'booking_rates',
        'availabilities' => 'booking_availabilities',
    ],

    // Bookings Models
    'models' => [
        'rate' => \Rinvex\Bookings\Models\Rate::class,
        'addon' => \Rinvex\Bookings\Models\Addon::class,
        'booking' => \Rinvex\Bookings\Models\Booking::class,
        'availability' => \Rinvex\Bookings\Models\Availability::class,
    ],

];
