<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Traits;

use Rinvex\Bookings\Models\Rate;
use Rinvex\Bookings\Models\Price;
use Rinvex\Bookings\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Bookable
{
    use BookingScopes;

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
     * The resource may have many rates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function rates(): MorphMany
    {
        return $this->morphMany(config('rinvex.bookings.models.rate'), 'bookable');
    }

    /**
     * The resource may have many prices.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function prices(): MorphMany
    {
        return $this->morphMany(config('rinvex.bookings.models.price'), 'bookable');
    }

    /**
     * Book the model for the given user at the given dates with the given price.
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

    /**
     * Create a new booking rate.
     *
     * @param float  $percentage
     * @param string $operator
     * @param int    $amount
     *
     * @return \Rinvex\Bookings\Models\Rate
     */
    public function newRate(float $percentage, string $operator, int $amount): Rate
    {
        return $this->rates()->create([
            'bookable_id' => static::getKey(),
            'bookable_type' => static::getMorphClass(),
            'percentage' => $percentage,
            'operator' => $operator,
            'amount' => $amount,
        ]);
    }

    /**
     * Create a new booking price.
     *
     * @param string $weekday
     * @param string $startsAt
     * @param string $endsAt
     * @param float  $percentage
     *
     * @return \Rinvex\Bookings\Models\Price
     */
    public function newPrice(string $weekday, string $startsAt, string $endsAt, float $percentage): Price
    {
        return $this->prices()->create([
            'bookable_id' => static::getKey(),
            'bookable_type' => static::getMorphClass(),
            'percentage' => $percentage,
            'weekday' => $weekday,
            'starts_at' => (new Carbon($startsAt))->toTimeString(),
            'ends_at' => (new Carbon($endsAt))->toTimeString(),
        ]);
    }
}
