<?php

declare(strict_types=1);

return [

    // Manage autoload migrations
    'autoload_migrations' => true,

    // Bookings Database Tables
    'tables' => [
        'bookable_rates' => 'bookable_rates',
        'bookable_bookings' => 'bookable_bookings',
        'bookable_availabilities' => 'bookable_availabilities',
        'ticketable_bookings' => 'ticketable_bookings',
        'ticketable_tickets' => 'ticketable_tickets',
    ],

];
