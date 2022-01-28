<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class BookableBooking extends Model
{
    use HasFactory;
    use ValidatingTrait;

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
        'quantity',
        'total_paid',
        'currency',
        'formula',
        'canceled_at',
        'options',
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
        'quantity' => 'integer',
        'total_paid' => 'float',
        'currency' => 'string',
        'formula' => 'json',
        'canceled_at' => 'datetime',
        'options' => 'array',
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
        $this->setTable(config('rinvex.bookings.tables.bookable_bookings'));
        $this->mergeRules([
            'bookable_id' => 'required|integer',
            'bookable_type' => 'required|string|strip_tags|max:150',
            'customer_id' => 'required|integer',
            'customer_type' => 'required|string|strip_tags|max:150',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'total_paid' => 'required|numeric',
            'currency' => 'required|alpha|size:3',
            'formula' => 'nullable|array',
            'canceled_at' => 'nullable|date',
            'options' => 'nullable|array',
            'notes' => 'nullable|string|strip_tags|max:32768',
        ]);

        parent::__construct($attributes);
    }

    /**
     * @TODO: refactor
     *
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        //static::validating(function (self $bookableAvailability) {
        //    $calculatedPrice = is_null($bookableAvailability->price)
        //        ? $bookableAvailability->calculatePrice($bookableAvailability->bookable, $bookableAvailability->starts_at, $bookableAvailability->ends_at) : [$bookableAvailability->price, $bookableAvailability->formula, $bookableAvailability->currency];
        //
        //    $bookableAvailability->currency = $calculatedPrice['currency'];
        //    $bookableAvailability->formula = $calculatedPrice['formula'];
        //    $bookableAvailability->price = $calculatedPrice['price'];
        //});
    }

    /**
     * Get options attributes.
     *
     * @return \Spatie\SchemalessAttributes\SchemalessAttributes
     */
    public function getOptionsAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'options');
    }

    /**
     * Scope with options attributes.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithOptions(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('options');
    }

    /**
     * @TODO: implement rates, availabilites, minimum & maximum units
     *
     * Calculate the booking price.
     *
     * @param \Illuminate\Database\Eloquent\Model $bookable
     * @param \Carbon\Carbon                      $startsAt
     * @param \Carbon\Carbon                      $endsAt
     * @param int                                 $quantity
     *
     * @return array
     */
    public function calculatePrice(Model $bookable, Carbon $startsAt, Carbon $endsAt = null, int $quantity = 1): array
    {
        $totalUnits = 0;

        switch ($bookable->unit) {
            case 'use':
                $totalUnits = 1;
                $totalPrice = $bookable->base_cost + ($bookable->unit_cost * $totalUnits * $quantity);
                break;
            default:
                $method = 'add'.ucfirst($bookable->unit);

                for ($date = clone $startsAt; $date->lt($endsAt ?? $date->addDay()); $date->{$method}()) {
                    $totalUnits++;
                }

                $totalPrice = $bookable->base_cost + ($bookable->unit_cost * $totalUnits * $quantity);
                break;
        }

        return [
            'base_cost' => $bookable->base_cost,
            'unit_cost' => $bookable->unit_cost,
            'unit' => $bookable->unit,
            'currency' => $bookable->currency,
            'total_units' => $totalUnits,
            'total_price' => $totalPrice,
        ];
    }

    /**
     * Get the owning resource model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function bookable(): MorphTo
    {
        return $this->morphTo('bookable', 'bookable_type', 'bookable_id', 'id');
    }

    /**
     * Get the booking customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function customer(): MorphTo
    {
        return $this->morphTo('customer', 'customer_type', 'customer_id', 'id');
    }

    /**
     * Get bookings of the given resource.
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
        return $builder->whereNull('canceled_at')
                       ->whereNotNull('ends_at')
                       ->where('ends_at', '<', now());
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
        return $builder->whereNull('canceled_at')
                       ->whereNotNull('starts_at')
                       ->where('starts_at', '>', now());
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
        return $builder->whereNull('canceled_at')
                       ->whereNotNull('starts_at')
                       ->whereNotNull('ends_at')
                       ->where('starts_at', '<', now())
                       ->where('ends_at', '>', now());
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
        return $builder->whereNotNull('canceled_at');
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
        return $builder->whereNull('canceled_at')
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
        return $builder->whereNull('canceled_at')
                       ->whereNotNull('starts_at')
                       ->where('starts_at', '>', new Carbon($date));
    }

    /**
     * Get bookings starts between the given dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $startsAt
     * @param string                                $endsAt
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartsBetween(Builder $builder, string $startsAt, string $endsAt): Builder
    {
        return $builder->whereNull('canceled_at')
                       ->whereNotNull('starts_at')
                       ->where('starts_at', '>=', new Carbon($startsAt))
                       ->where('starts_at', '<=', new Carbon($endsAt));
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
        return $builder->whereNull('canceled_at')
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
        return $builder->whereNull('canceled_at')
                       ->whereNotNull('ends_at')
                       ->where('ends_at', '>', new Carbon($date));
    }

    /**
     * Get bookings ends between the given dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $startsAt
     * @param string                                $endsAt
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEndsBetween(Builder $builder, string $startsAt, string $endsAt): Builder
    {
        return $builder->whereNull('canceled_at')
                       ->whereNotNull('ends_at')
                       ->where('ends_at', '>=', new Carbon($startsAt))
                       ->where('ends_at', '<=', new Carbon($endsAt));
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
        return $builder->whereNotNull('canceled_at')
                       ->where('canceled_at', '<', new Carbon($date));
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
        return $builder->whereNotNull('canceled_at')
                       ->where('canceled_at', '>', new Carbon($date));
    }

    /**
     * Get bookings cancelled between the given dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $startsAt
     * @param string                                $endsAt
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelledBetween(Builder $builder, string $startsAt, string $endsAt): Builder
    {
        return $builder->whereNotNull('canceled_at')
                       ->where('canceled_at', '>=', new Carbon($startsAt))
                       ->where('canceled_at', '<=', new Carbon($endsAt));
    }

    /**
     * Get bookings between the given dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $startsAt
     * @param string                                $endsAt
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRange(Builder $builder, string $startsAt, string $endsAt): Builder
    {
        return $builder->whereNull('canceled_at')
                       ->whereNotNull('starts_at')
                       ->where('starts_at', '>=', new Carbon($startsAt))
                       ->where(function (Builder $builder) use ($endsAt) {
                           $builder->whereNull('ends_at')
                                 ->orWhere(function (Builder $builder) use ($endsAt) {
                                     $builder->whereNotNull('ends_at')
                                           ->where('ends_at', '<=', new Carbon($endsAt));
                                 });
                       });
    }

    /**
     * Check if the booking is cancelled.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return (bool) $this->canceled_at;
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
