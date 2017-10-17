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
     * Get bookings of the given bookable.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsOfBookable(string $bookable): MorphMany
    {
        return $this->bookings()->where('bookable_type', $bookable->getMorphClass())->where('bookable_id', $bookable->getKey());
    }

    /**
     * Check if the person booked the given model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function isBooked(Model $model): bool
    {
        return $this->bookings()->where('bookable_id', $model->getKey())->where('bookable_type', get_class($model))->exists();
    }

    /**
     * Book the given model at the given dates with the given price.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     * @param string                              $starts
     * @param string                              $ends
     * @param float                               $price
     *
     * @return \Rinvex\Bookings\Models\Booking
     */
    public function newBooking(Model $bookable, string $starts, string $ends, float $price): Booking
    {
        return $this->bookings()->create([
            'bookable_id' => $bookable->getKey(),
            'bookable_type' => $bookable->getMorphClass(),
            'customer_id' => $this->getKey(),
            'customer_type' => $this->getMorphClass(),
            'starts_at' => $starts,
            'ends_at' => $ends,
            'price' => $price,
        ]);
    }
}
