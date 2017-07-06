<?php

declare(strict_types=1);

namespace Rinvex\Bookable\Traits;

use Rinvex\Bookable\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait IsBookingAgent
{
    use IsBookingPerson;

    /**
     * The agent may have many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'agent_id', 'id');
    }

    /**
     * Book the given model for the given customer at the given dates with the given price.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     * @param \Illuminate\Database\Eloquent\Model $customer
     * @param string                              $starts
     * @param string                              $ends
     * @param float                               $price
     *
     * @return \Rinvex\Bookable\Models\Booking
     */
    public function newBooking(Model $bookable, Model $customer, string $starts, string $ends, float $price): Booking
    {
        return $this->bookings()->create([
            'bookable_id' => $bookable->getKey(),
            'bookable_type' => get_class($bookable),
            'customer_id' => $customer->getKey(),
            'agent_id' => $this->getKey(),
            'starts_at' => $starts,
            'ends_at' => $ends,
            'price' => $price,
        ]);
    }
}
