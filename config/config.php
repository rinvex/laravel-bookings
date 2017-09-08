<?php

declare(strict_types=1);

return [

    // Bookings Database Tables
    'tables' => [

        'bookings' => 'bookings',
        'booking_rates' => 'booking_rates',
        'booking_hierarchy' => 'booking_hierarchy',
        'booking_availability' => 'booking_availability',

    ],

    // Bookings Models
    'models' => [

        'booking' => \Rinvex\Bookings\Models\Booking::class,
        'booking_rate' => \Rinvex\Bookings\Models\BookingRate::class,
        'booking_availability' => \Rinvex\Bookings\Models\BookingAvailability::class,

    ],

];
