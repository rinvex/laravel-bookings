<?php

declare(strict_types=1);

namespace Rinvex\Bookable\Models;

use Carbon\Carbon;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use ValidatingTrait;
    use CacheableEloquent;

    protected $dates = [
        'starts_at',
        'ends_at',
        'cancelled_at',
    ];
    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'customer_id',
        'agent_id',
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

        // Get users model
        $userModel = config('auth.providers.'.config('auth.guards.'.config('auth.defaults.guard').'.provider').'.model');

        $this->setTable(config('rinvex.bookable.tables.bookings'));
        $this->setRules([
            'bookable_id' => 'required|integer',
            'bookable_type' => 'required|string',
            'customer_id' => 'required|integer|exists:'.(new $userModel())->getTable().',id',
            'agent_id' => 'required|integer|exists:'.(new $userModel())->getTable().',id',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'price' => 'numeric',
            'price_equation' => 'json',
            'cancelled_at' => 'nullable|date',
            'notes' => 'nullable|string|max:10000',
        ]);
    }

    /**
     * Get the owning bookable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function bookable(): MorphTo
    {
        return $this->morphTo();
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
     * Get bookings by the given customer.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int                                   $customerId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCustomer(Builder $builder, int $customerId): Builder
    {
        return $builder->where('customer_id', $customerId);
    }

    /**
     * Get bookings by the given agent.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int                                   $agentId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAgent(Builder $builder, int $agentId): Builder
    {
        return $builder->where('agent_id', $agentId);
    }

    /**
     * Get bookings of the given model.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $bookable
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBookingsOf(Builder $builder, string $bookable): Builder
    {
        return $builder->where('bookable_type', $bookable);
    }

    /**
     * Get the booking customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): belongsTo
    {
        $userModel = config('auth.providers.'.config('auth.guards.'.config('auth.defaults.guard').'.provider').'.model');

        return $this->belongsTo($userModel, 'customer_id', 'id');
    }

    /**
     * Get the booking agent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent(): belongsTo
    {
        $userModel = config('auth.providers.'.config('auth.guards.'.config('auth.defaults.guard').'.provider').'.model');

        return $this->belongsTo($userModel, 'agent_id', 'id');
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
