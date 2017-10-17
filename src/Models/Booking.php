<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Illuminate\Database\Eloquent\Builder;
use Rinvex\Support\Traits\ValidatingTrait;
use Rinvex\Bookings\Contracts\BookingContract;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Rinvex\Bookings\Models\Booking.
 *
 * @property int                                                $id
 * @property int                                                $bookable_id
 * @property string                                             $bookable_type
 * @property int                                                $customer_id
 * @property string                                             $customer_type
 * @property \Carbon\Carbon                                     $starts_at
 * @property \Carbon\Carbon                                     $ends_at
 * @property float                                              $price
 * @property array                                              $price_equation
 * @property \Carbon\Carbon                                     $cancelled_at
 * @property string                                             $notes
 * @property \Carbon\Carbon|null                                $created_at
 * @property \Carbon\Carbon|null                                $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $bookable
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $customer
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelledAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelledBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelledBetween($starts, $ends)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking current()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking endsAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking endsBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking endsBetween($starts, $ends)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking future()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking ofBookable(\Illuminate\Database\Eloquent\Model $bookable)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking ofCustomer(\Illuminate\Database\Eloquent\Model $customer)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking past()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking startsAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking startsBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking startsBetween($starts, $ends)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking wherePriceEquation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Booking extends Model implements BookingContract
{
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'customer_id',
        'customer_type',
        'starts_at',
        'ends_at',
        'price',
        'price_equation',
        'cancelled_at',
        'notes',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'bookable_id' => 'integer',
        'bookable_type' => 'string',
        'customer_id' => 'integer',
        'customer_type' => 'string',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'price' => 'float',
        'price_equation' => 'json',
        'cancelled_at' => 'datetime',
        'notes' => 'string',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('rinvex.bookings.tables.bookings'));
        $this->setRules([
            'bookable_id' => 'required|integer',
            'bookable_type' => 'required|string',
            'customer_id' => 'required|integer',
            'customer_type' => 'required|string',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'price' => 'required|numeric',
            'price_equation' => 'json',
            'cancelled_at' => 'nullable|date',
            'notes' => 'nullable|string|max:10000',
        ]);
    }

    /**
     * Get the owning bookable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the owning customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function customer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get bookings of the given bookable.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $bookable
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfBookable(Builder $builder, Model $bookable): Builder
    {
        return $builder->where('bookable_type', $bookable->getMorphClass())->where('bookable_id', $bookable->getKey());
    }

    /**
     * Get bookings of the given customer.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $customer
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCustomer(Builder $builder, Model $customer): Builder
    {
        return $builder->where('customer_type', $customer->getMorphClass())->where('customer_id', $customer->getKey());
    }

    /**
     * Get the past bookings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast(Builder $builder): Builder
    {
        return $builder->whereNull('cancelled_at')
                       ->whereNotNull('ends_at')
                       ->where('ends_at', '<', Carbon::now());
    }

    /**
     * Get the future bookings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFuture(Builder $builder): Builder
    {
        return $builder->whereNull('cancelled_at')
                       ->whereNotNull('starts_at')
                       ->where('starts_at', '>', Carbon::now());
    }

    /**
     * Get the current bookings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrent(Builder $builder): Builder
    {
        return $builder->whereNull('cancelled_at')
                       ->whereNotNull('starts_at')
                       ->whereNotNull('ends_at')
                       ->where('starts_at', '<', Carbon::now())
                       ->where('ends_at', '>', Carbon::now());
    }

    /**
     * Get the cancelled bookings.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelled(Builder $builder): Builder
    {
        return $builder->whereNotNull('cancelled_at');
    }

    /**
     * Get bookings starts before the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartsBefore(Builder $builder, string $date): Builder
    {
        return $builder->whereNull('cancelled_at')
                       ->whereNotNull('starts_at')
                       ->where('starts_at', '<', new Carbon($date));
    }

    /**
     * Get bookings starts after the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartsAfter(Builder $builder, string $date): Builder
    {
        return $builder->whereNull('cancelled_at')
                       ->whereNotNull('starts_at')
                       ->where('starts_at', '>', new Carbon($date));
    }

    /**
     * Get bookings starts between the given dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $starts
     * @param string                                $ends
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartsBetween(Builder $builder, string $starts, string $ends): Builder
    {
        return $builder->whereNull('cancelled_at')
                       ->whereNotNull('starts_at')
                       ->where('starts_at', '>', new Carbon($starts))
                       ->where('starts_at', '<', new Carbon($ends));
    }

    /**
     * Get bookings ends before the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEndsBefore(Builder $builder, string $date): Builder
    {
        return $builder->whereNull('cancelled_at')
                       ->whereNotNull('ends_at')
                       ->where('ends_at', '<', new Carbon($date));
    }

    /**
     * Get bookings ends after the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEndsAfter(Builder $builder, string $date): Builder
    {
        return $builder->whereNull('cancelled_at')
                       ->whereNotNull('ends_at')
                       ->where('ends_at', '>', new Carbon($date));
    }

    /**
     * Get bookings ends between the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $starts
     * @param string                                $ends
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEndsBetween(Builder $builder, string $starts, string $ends): Builder
    {
        return $builder->whereNull('cancelled_at')
                       ->whereNotNull('ends_at')
                       ->where('ends_at', '>', new Carbon($starts))
                       ->where('ends_at', '<', new Carbon($ends));
    }

    /**
     * Get bookings cancelled before the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelledBefore(Builder $builder, string $date): Builder
    {
        return $builder->whereNotNull('cancelled_at')
                       ->where('cancelled_at', '<', new Carbon($date));
    }

    /**
     * Get bookings cancelled after the given date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelledAfter(Builder $builder, string $date): Builder
    {
        return $builder->whereNotNull('cancelled_at')
                       ->where('cancelled_at', '>', new Carbon($date));
    }

    /**
     * Get bookings cancelled between the given dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $starts
     * @param string                                $ends
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelledBetween(Builder $builder, string $starts, string $ends): Builder
    {
        return $builder->whereNotNull('cancelled_at')
                       ->where('cancelled_at', '>', new Carbon($starts))
                       ->where('cancelled_at', '<', new Carbon($ends));
    }

    /**
     * Check if the booking is cancelled.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return (bool) $this->cancelled_at;
    }

    /**
     * Check if the booking is past.
     *
     * @return bool
     */
    public function isPast(): bool
    {
        return ! $this->isCancelled() && $this->ends_at->isPast();
    }

    /**
     * Check if the booking is future.
     *
     * @return bool
     */
    public function isFuture(): bool
    {
        return ! $this->isCancelled() && $this->starts_at->isFuture();
    }

    /**
     * Check if the booking is current.
     *
     * @return bool
     */
    public function isCurrent(): bool
    {
        return ! $this->isCancelled() && Carbon::now()->between($this->starts_at, $this->ends_at);
    }
}
