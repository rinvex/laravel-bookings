<?php

declare(strict_types=1);

return [

    // Bookable Database Tables
    'tables' => [

        'bookings' => 'bookings',
        'booking_rates' => 'booking_rates',
        'booking_hierarchy' => 'booking_hierarchy',
        'booking_availability' => 'booking_availability',

    ],

    // Bookable Models
    'models' => [

        'booking' => \Rinvex\Bookable\Models\Booking::class,
        'booking_rate' => \Rinvex\Bookable\Models\BookingRate::class,
        'booking_availability' => \Rinvex\Bookable\Models\BookingAvailability::class,

    ],

];
