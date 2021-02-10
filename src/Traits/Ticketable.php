<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Traits;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Bookings\Models\TicketableBooking;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Ticketable
{
    /**
     * Register a saved model event with the dispatcher.
     *
     * @param \Closure|string $callback
     *
     * @return void
     */
    abstract public static function saved($callback);

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     *
     * @return void
     */
    abstract public static function deleted($callback);

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
    abstract public function getBookingModel(): string;

    /**
     * Get the ticket model name.
     *
     * @return string
     */
    abstract public function getTicketModel(): string;

    /**
     * Boot the Ticketable trait for the model.
     *
     * @return void
     */
    public static function bootTicketable()
    {
        static::deleted(function (self $model) {
            $model->bookings()->delete();
        });
    }

    /**
     * Attach the given bookings to the model.
     *
     * @param \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|array $ids
     * @param mixed                                                                         $bookings
     *
     * @return void
     */
    public function setBookingsAttribute($bookings): void
    {
        static::saved(function (self $model) use ($bookings) {
            $this->bookings()->sync($bookings);
        });
    }

    /**
     * The resource may have many tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tickets(): MorphMany
    {
        return $this->morphMany(static::getTicketModel(), 'ticketable', 'ticketable_type', 'ticketable_id');
    }

    /**
     * The resource may have many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(static::getBookingModel(), 'ticketable', 'ticketable_type', 'ticketable_id');
    }

    /**
     * Get bookings by the given customer.
     *
     * @param \Illuminate\Database\Eloquent\Model $customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookingsBy(Model $customer): MorphMany
    {
        return $this->bookings()->where('customer_type', $customer->getMorphClass())->where('customer_id', $customer->getKey());
    }

    /**
     * Book the model for the given customer at the given dates with the given price.
     *
     * @param \Illuminate\Database\Eloquent\Model $customer
     * @param float                               $paid
     * @param string                              $currency
     *
     * @return \Rinvex\Bookings\Models\TicketableBooking
     */
    public function newBooking(Model $customer, float $paid, string $currency): TicketableBooking
    {
        return $this->bookings()->create([
            'ticketable_id' => static::getKey(),
            'ticketable_type' => static::getMorphClass(),
            'customer_id' => $customer->getKey(),
            'customer_type' => $customer->getMorphClass(),
            'paid' => $paid,
            'currency' => $currency,
        ]);
    }
}
