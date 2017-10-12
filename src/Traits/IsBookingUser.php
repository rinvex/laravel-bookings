<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Traits;

use Carbon\Carbon;
use Rinvex\Bookings\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait IsBookingUser
{
    /**
     * The user may have many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(config('rinvex.bookings.models.booking'), 'user_id', 'id');
    }

    /**
     * Get past bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pastBookings(): HasMany
    {
        return $this->bookings()
                    ->whereNull('cancelled_at')
                    ->whereNotNull('ends_at')
                    ->where('ends_at', '<', Carbon::now());
    }

    /**
     * Get future bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function futureBookings(): HasMany
    {
        return $this->bookings()
                    ->whereNull('cancelled_at')
                    ->whereNotNull('starts_at')
                    ->where('starts_at', '>', Carbon::now());
    }

    /**
     * Get current bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function currentBookings(): HasMany
    {
        return $this->bookings()
                    ->whereNull('cancelled_at')
                    ->whereNotNull('starts_at')
                    ->whereNotNull('ends_at')
                    ->where('starts_at', '<', Carbon::now())
                    ->where('ends_at', '>', Carbon::now());
    }

    /**
     * Get cancelled bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cancelledBookings(): HasMany
    {
        return $this->bookings()
                    ->whereNotNull('cancelled_at');
    }

    /**
     * Get bookings starts before the given date.
     *
     * @param string $date
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsStartsBefore(string $date): HasMany
    {
        return $this->bookings()
                    ->whereNull('cancelled_at')
                    ->whereNotNull('starts_at')
                    ->where('starts_at', '<', new Carbon($date));
    }

    /**
     * Get bookings starts after the given date.
     *
     * @param string $date
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsStartsAfter(string $date): HasMany
    {
        return $this->bookings()
                    ->whereNull('cancelled_at')
                    ->whereNotNull('starts_at')
                    ->where('starts_at', '>', new Carbon($date));
    }

    /**
     * Get bookings starts between the given dates.
     *
     * @param string $starts
     * @param string $ends
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsStartsBetween(string $starts, string $ends): HasMany
    {
        return $this->bookings()
                    ->whereNull('cancelled_at')
                    ->whereNotNull('starts_at')
                    ->where('starts_at', '>', new Carbon($starts))
                    ->where('starts_at', '<', new Carbon($ends));
    }

    /**
     * Get bookings ends before the given date.
     *
     * @param string $date
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsEndsBefore(string $date): HasMany
    {
        return $this->bookings()
                    ->whereNull('cancelled_at')
                    ->whereNotNull('ends_at')
                    ->where('ends_at', '<', new Carbon($date));
    }

    /**
     * Get bookings ends after the given date.
     *
     * @param string $date
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsEndsAfter(string $date): HasMany
    {
        return $this->bookings()
                    ->whereNull('cancelled_at')
                    ->whereNotNull('ends_at')
                    ->where('ends_at', '>', new Carbon($date));
    }

    /**
     * Get bookings ends between the given dates.
     *
     * @param string $starts
     * @param string $ends
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsEndsBetween(string $starts, string $ends): HasMany
    {
        return $this->bookings()
                    ->whereNull('cancelled_at')
                    ->whereNotNull('ends_at')
                    ->where('ends_at', '>', new Carbon($starts))
                    ->where('ends_at', '<', new Carbon($ends));
    }

    /**
     * Get bookings cancelled before the given date.
     *
     * @param string $date
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsCancelledBefore(string $date): HasMany
    {
        return $this->bookings()
                    ->whereNotNull('cancelled_at')
                    ->where('cancelled_at', '<', new Carbon($date));
    }

    /**
     * Get bookings cancelled after the given date.
     *
     * @param string $date
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsCancelledAfter(string $date): HasMany
    {
        return $this->bookings()
                    ->whereNotNull('cancelled_at')
                    ->where('cancelled_at', '>', new Carbon($date));
    }

    /**
     * Get bookings cancelled between the given dates.
     *
     * @param string $starts
     * @param string $ends
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsCancelledBetween(string $starts, string $ends): HasMany
    {
        return $this->bookings()
                    ->whereNotNull('cancelled_at')
                    ->where('cancelled_at', '>', new Carbon($starts))
                    ->where('cancelled_at', '<', new Carbon($ends));
    }

    /**
     * Get bookings of the given model.
     *
     * @param string $bookable
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingsOf(string $bookable): HasMany
    {
        return $this->bookings()
                    ->where('bookable_type', $bookable);
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
            'bookable_type' => get_class($bookable),
            'user_id' => $this->getKey(),
            'starts_at' => $starts,
            'ends_at' => $ends,
            'price' => $price,
        ]);
    }
}
