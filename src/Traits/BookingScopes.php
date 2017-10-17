<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait BookingScopes
{
    /**
     * Get past bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function pastBookings(): MorphMany
    {
        return $this->bookings()
                    ->whereNull('cancelled_at')
                    ->whereNotNull('ends_at')
                    ->where('ends_at', '<', Carbon::now());
    }

    /**
     * Get future bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function futureBookings(): MorphMany
    {
        return $this->bookings()
                    ->whereNull('cancelled_at')
                    ->whereNotNull('starts_at')
                    ->where('starts_at', '>', Carbon::now());
    }

    /**
     * Get current bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function currentBookings(): MorphMany
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function cancelledBookings(): MorphMany
    {
        return $this->bookings()
                    ->whereNotNull('cancelled_at');
    }

    /**
     * Get bookings starts before the given date.
     *
     * @param string $date
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsStartsBefore(string $date): MorphMany
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsStartsAfter(string $date): MorphMany
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsStartsBetween(string $starts, string $ends): MorphMany
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsEndsBefore(string $date): MorphMany
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsEndsAfter(string $date): MorphMany
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsEndsBetween(string $starts, string $ends): MorphMany
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsCancelledBefore(string $date): MorphMany
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsCancelledAfter(string $date): MorphMany
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsCancelledBetween(string $starts, string $ends): MorphMany
    {
        return $this->bookings()
                    ->whereNotNull('cancelled_at')
                    ->where('cancelled_at', '>', new Carbon($starts))
                    ->where('cancelled_at', '<', new Carbon($ends));
    }
}
