<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

abstract class BookableAvailability extends Model
{
    use ValidatingTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'range',
        'from',
        'to',
        'is_bookable',
        'priority',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'bookable_id' => 'integer',
        'bookable_type' => 'string',
        'range' => 'string',
        'from' => 'string',
        'to' => 'string',
        'is_bookable' => 'boolean',
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
        'bookable_type' => 'required|string|strip_tags|max:150',
        'range' => 'required|in:datetimes,dates,months,weeks,days,times,sunday,monday,tuesday,wednesday,thursday,friday,saturday',
        'from' => 'required|string|strip_tags|max:150',
        'to' => 'required|string|strip_tags|max:150',
        'is_bookable' => 'required|boolean',
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

        $this->setTable(config('rinvex.bookings.tables.bookable_availabilities'));
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
}
