<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Rinvex\Bookings\Models\Price.
 *
 * @property int                                                $id
 * @property int                                                $bookable_id
 * @property string                                             $bookable_type
 * @property \Carbon\Carbon                                     $starts_at
 * @property \Carbon\Carbon                                     $ends_at
 * @property float                                              $percentage
 * @property \Carbon\Carbon|null                                $created_at
 * @property \Carbon\Carbon|null                                $updated_at
 * @property string                                             $weekday
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $bookable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Price whereWeekday($value)
 * @mixin \Eloquent
 */
class Price extends Model
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
        'weekday',
        'starts_at',
        'ends_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'bookable_id' => 'integer',
        'bookable_type' => 'string',
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
    protected $rules = [
        'bookable_id' => 'required|integer',
        'bookable_type' => 'required|string',
        'percentage' => 'required|numeric|min:-100|max:100',
        'weekday' => 'required|string|in:sun,mon,tue,wed,thu,fri,sat',
        'starts_at' => 'required|date_format:"H:i:s"',
        'ends_at' => 'required|date_format:"H:i:s"',
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

        $this->setTable(config('rinvex.bookings.tables.prices'));
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
