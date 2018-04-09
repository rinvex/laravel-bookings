<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

abstract class BookableRate extends Model
{
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'range',
        'range_from',
        'range_to',
        'base_cost',
        'base_cost_modifier',
        'unit_cost',
        'unit_cost_modifier',
        'priority',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'bookable_id' => 'integer',
        'bookable_type' => 'string',
        'range' => 'string',
        'range_from' => 'string',
        'range_to' => 'string',
        'base_cost' => 'float',
        'base_cost_modifier' => 'string',
        'unit_cost' => 'float',
        'unit_cost_modifier' => 'string',
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
        'range' => 'required|string|in:unit,date,month,week,day,datetime,time,time-sun,time-mon,time-tue,time-wed,time-thu,time-fri,time-sat',
        'range_from' => 'required|string|max:150',
        'range_to' => 'required|string|max:150',
        'base_cost' => 'required|numeric',
        'base_cost_modifier' => 'required|string|in:+,-,×,÷',
        'unit_cost' => 'required|numeric',
        'unit_cost_modifier' => 'required|string|in:+,-,×,÷',
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

        $this->setTable(config('rinvex.bookings.tables.bookable_rates'));
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
