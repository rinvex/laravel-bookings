<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Traits;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Bookings\Models\BookableBooking;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasBookings
{
    use BookingScopes;

    /**
     * Define a polymorphic one-to-many relationship.
     *
     * @param string $related
     * @param string $name
     * @param string $type
     * @param string $id
     * @param string $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    abstract public function morphMany($related, $name, $type = null, $id = null, $localKey = null);

    /**
     * Get the booking model name.
     *
     * @return string
     */
    abstract public static function getBookingModel(): string;

    /**
     * Boot the HasBookings trait for the model.
     *
     * @return void
     */
    public static function bootHasBookings()
    {
        static::deleted(function (self $model) {
            $model->bookings()->delete();
        });
    }

    /**
     * The customer may have many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(static::getBookingModel(), 'customer', 'customer_type', 'customer_id');
    }

    /**
     * Get bookings of the given resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsOf(Model $bookable): MorphMany
    {
        return $this->bookings()->where('bookable_type', $bookable->getMorphClass())->where('bookable_id', $bookable->getKey());
    }

    /**
     * Check if the person booked the given model.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     *
     * @return bool
     */
    public function isBooked(Model $bookable): bool
    {
        return $this->bookings()->where('bookable_id', $bookable->getKey())->exists();
    }

    /**
     * Book the given model at the given dates with the given price.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     * @param string                              $startsAt
     * @param string                              $endsAt
     *
     * @return \Rinvex\Bookings\Models\BookableBooking
     */
    public function newBooking(Model $bookable, string $startsAt, string $endsAt): BookableBooking
    {
        return $this->bookings()->create([
            'bookable_id' => $bookable->getKey(),
            'bookable_type' => $bookable->getMorphClass(),
            'customer_id' => $this->getKey(),
            'customer_type' => $this->getMorphClass(),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);
    }
}
