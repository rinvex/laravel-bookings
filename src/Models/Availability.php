<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Rinvex\Bookings\Models\Availability.
 *
 * @property int                                                $id
 * @property int                                                $bookable_id
 * @property string                                             $bookable_type
 * @property int                                                $is_available
 * @property string                                             $range
 * @property string                                             $range_from
 * @property string                                             $range_to
 * @property int                                                $priority
 * @property \Carbon\Carbon|null                                $created_at
 * @property \Carbon\Carbon|null                                $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $bookable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Availability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Availability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Availability whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Availability whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Availability whereIsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Availability whereRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Availability whereRangeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Availability whereRangeTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Availability wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Availability whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Availability extends Model
{
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'is_available',
        'range',
        'range_from',
        'range_to',
        'priority',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'bookable_id' => 'integer',
        'bookable_type' => 'string',
        'is_available' => 'boolean',
        'range' => 'string',
        'range_from' => 'string',
        'range_to' => 'string',
        'priority' => 'integer',
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
        'is_available' => 'required|boolean',
        'range' => 'required|string|in:unit,date,month,week,day,datetime,time,time-sun,time-mon,time-tue,time-wed,time-thu,time-fri,time-sat',
        'range_from' => 'required|string|max:150',
        'range_to' => 'required|string|max:150',
        'priority' => 'nullable|integer',
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

        $this->setTable(config('rinvex.bookings.tables.availabilities'));
    }

    /**
     * Get the owning resource model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function bookable(): MorphTo
    {
        return $this->morphTo('bookable', 'bookable_type', 'bookable_id');
    }
}
