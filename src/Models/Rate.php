<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Rinvex\Bookings\Models\Rate.
 *
 * @property int                                                $id
 * @property int                                                $bookable_id
 * @property string                                             $bookable_type
 * @property int                                                $percentage
 * @property string                                             $operator
 * @property int                                                $amount
 * @property \Carbon\Carbon|null                                $created_at
 * @property \Carbon\Carbon|null                                $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $bookable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Rate whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Rate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Rate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Rate whereOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Rate wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Rate whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Rate whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Rate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Rate extends Model
{
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'percentage',
        'operator',
        'amount',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'bookable_id' => 'integer',
        'bookable_type' => 'string',
        'percentage' => 'float',
        'operator' => 'string',
        'amount' => 'integer',
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
    protected $rules = [
        'bookable_id' => 'required|integer',
        'bookable_type' => 'required|string',
        'percentage' => 'required|numeric|min:-100|max:100',
        'operator' => 'required|string|in:^,<,>,=',
        'amount' => 'required|integer|max:10000000',
    ];

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

        $this->setTable(config('rinvex.bookings.tables.rates'));
    }

    /**
     * Get the owning resource model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }
}
