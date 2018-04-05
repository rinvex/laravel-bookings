<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Traits;

use Rinvex\Bookings\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Bookable
{
    use BookingScopes;

    /**
     * Boot the Bookable trait for the model.
     *
     * @return void
     */
    public static function bootBookable()
    {
        static::deleted(function (self $model) {
            $model->bookings()->delete();
        });
    }

    /**
     * The resource may have many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(config('rinvex.bookings.models.booking'), 'bookable');
    }

    /**
     * Get bookings of the given customer.
     *
     * @param \Illuminate\Database\Eloquent\Model $customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsOf(Model $customer): MorphMany
    {
        return $this->bookings()->where('customer_type', $customer->getMorphClass())->where('customer_id', $customer->getKey());
    }

    /**
     * The resource may have many addons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function addons(): MorphMany
    {
        return $this->morphMany(config('rinvex.bookings.models.addon'), 'bookable');
    }

    /**
     * The resource may have many availabilities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function availabilities(): MorphMany
    {
        return $this->morphMany(config('rinvex.bookings.models.availability'), 'bookable');
    }

    /**
     * The resource may have many rates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function rates(): MorphMany
    {
        return $this->morphMany(config('rinvex.bookings.models.rate'), 'bookable');
    }

    /**
     * Book the model for the given customer at the given dates with the given price.
     *
     * @param \Illuminate\Database\Eloquent\Model $customer
     * @param string                              $startsAt
     * @param string                              $endsAt
     *
     * @return \Rinvex\Bookings\Models\Booking
     */
    public function newBooking(Model $customer, string $startsAt, string $endsAt): Booking
    {
        return $this->bookings()->create([
            'bookable_id' => static::getKey(),
            'bookable_type' => static::getMorphClass(),
            'customer_id' => $customer->getKey(),
            'customer_type' => $customer->getMorphClass(),
            'starts_at' => (new Carbon($startsAt))->toDateTimeString(),
            'ends_at' => (new Carbon($endsAt))->toDateTimeString(),
        ]);
    }
}
