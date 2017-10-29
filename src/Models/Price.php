<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Support\Traits\ValidatingTrait;
use Rinvex\Bookings\Contracts\PriceContract;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Rinvex\Bookings\Models\Price.
 *
 * @property int                                                $id
 * @property int                                                $resource_id
 * @property string                                             $resource_type
 * @property \Carbon\Carbon                                     $starts_at
 * @property \Carbon\Carbon                                     $ends_at
 * @property float                                              $percentage
 * @property \Carbon\Carbon|null                                $created_at
 * @property \Carbon\Carbon|null                                $updated_at
 * @property string                                             $weekday
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $resource
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereResourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereWeekday($value)
 * @mixin \Eloquent
 */
class Price extends Model implements PriceContract
{
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'resource_id',
        'resource_type',
        'percentage',
        'weekday',
        'starts_at',
        'ends_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'resource_id' => 'integer',
        'resource_type' => 'string',
        'percentage' => 'float',
        'weekday' => 'string',
        'starts_at' => 'string',
        'ends_at' => 'string',
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

        $this->setTable(config('rinvex.bookings.tables.prices'));
        $this->setRules([
            'resource_id' => 'required|integer',
            'resource_type' => 'required|string',
            'percentage' => 'required|numeric|min:-100|max:100',
            'weekday' => 'required|string|in:sun,mon,tue,wed,thu,fri,sat',
            'starts_at' => 'required|date_format:"H:i:s"',
            'ends_at' => 'required|date_format:"H:i:s"',
        ]);
    }

    /**
     * Get the owning resource model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function resource(): MorphTo
    {
        return $this->morphTo();
    }
}
