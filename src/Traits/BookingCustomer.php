<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Traits;

use Rinvex\Bookings\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait BookingCustomer
{
    use BookingScopes;

    /**
     * The customer may have many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(config('rinvex.bookings.models.booking'), 'customer');
    }

    /**
     * Get bookings of the given resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $resource
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsOfResource(Model $resource): MorphMany
    {
        return $this->bookings()->where('resource_type', $resource->getMorphClass())->where('resource_id', $resource->getKey());
    }

    /**
     * Check if the person booked the given model.
     *
     * @param \Illuminate\Database\Eloquent\Model $resource
     *
     * @return bool
     */
    public function isBooked(Model $resource): bool
    {
        return $this->bookings()->where('resource_id', $resource->getKey())->exists();
    }

    /**
     * Book the given model at the given dates with the given price.
     *
     * @param \Illuminate\Database\Eloquent\Model $resource
     * @param string                              $startsAt
     * @param string                              $endsAt
     *
     * @return \Rinvex\Bookings\Models\Booking
     */
    public function newBooking(Model $resource, string $startsAt, string $endsAt): Booking
    {
        return $this->bookings()->create([
            'resource_id' => $resource->getKey(),
            'resource_type' => $resource->getMorphClass(),
            'customer_id' => $this->getKey(),
            'customer_type' => $this->getMorphClass(),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);
    }
}
