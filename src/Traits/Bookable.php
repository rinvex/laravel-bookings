<?php

declare(strict_types=1);

namespace Rinvex\Bookable\Traits;

use Carbon\Carbon;
use Rinvex\Bookable\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Bookable\Models\BookingRate;
use Rinvex\Bookable\Models\BookingAvailability;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Bookable
{
    /**
     * Get all bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

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

    /**
     * Get bookings by the given customer.
     *
     * @param int $customerId
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsByCustomer($customerId): MorphMany
    {
        return $this->bookings()
                    ->where('customer_id', $customerId);
    }

    /**
     * Get bookings by the given agent.
     *
     * @param int $agentId
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsByAgent($agentId): MorphMany
    {
        return $this->bookings()
                    ->where('customer_id', $agentId);
    }

    /**
     * Get all rates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function rates(): MorphMany
    {
        return $this->morphMany(BookingRate::class, 'bookable');
    }

    /**
     * Get all availabilities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function availabilities(): MorphMany
    {
        return $this->morphMany(BookingAvailability::class, 'bookable');
    }

    /**
     * Book the model for the given customer at the given dates with the given price.
     *
     * @param \Illuminate\Database\Eloquent\Model $customer
     * @param string                              $starts
     * @param string                              $ends
     * @param float                               $price
     *
     * @return \Rinvex\Bookable\Models\Booking
     */
    public function newBooking(Model $customer, string $starts, string $ends, float $price): Booking
    {
        return $this->bookings()->create([
            'bookable_id' => static::getKey(),
            'bookable_type' => static::class,
            'customer_id' => $customer->getKey(),
            'agent_id' => $this->getKey(),
            'starts_at' => $starts,
            'ends_at' => $ends,
            'price' => $price,
        ]);
    }

    /**
     * Create a new booking rate.
     *
     * @param string $percentage
     * @param string $operator
     * @param int    $amount
     *
     * @return \Rinvex\Bookable\Models\BookingRate
     */
    public function newRate(string $percentage, string $operator, int $amount): BookingRate
    {
        return $this->rates()->create([
            'bookable_id' => static::getKey(),
            'bookable_type' => static::class,
            'percentage' => $percentage,
            'operator' => $operator,
            'amount' => $amount,
        ]);
    }

    /**
     * Create a new booking availability.
     *
     * @param string     $day
     * @param string     $starts
     * @param string     $ends
     * @param float|null $price
     *
     * @return \Rinvex\Bookable\Models\BookingAvailability
     */
    public function newAvailability(string $day, string $starts, string $ends, float $price = null): BookingAvailability
    {
        return $this->rates()->create([
            'bookable_id' => static::getKey(),
            'bookable_type' => static::class,
            'day' => $day,
            'starts_at' => $starts,
            'ends_at' => $ends,
            'price' => $price,
        ]);
    }
}
