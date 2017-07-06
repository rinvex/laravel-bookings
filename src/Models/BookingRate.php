<?php

declare(strict_types=1);

namespace Rinvex\Bookable\Models;

use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BookingRate extends Model
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

        $this->setTable(config('rinvex.bookable.tables.booking_rates'));
        $this->setRules([
            'bookable_id' => 'required|integer',
            'bookable_type' => 'required|string',
            'percentage' => 'required|integer|min:-100|max:100',
            'operator' => 'required|in:^|>|<|=',
            'amount' => 'required|integer|max:10000000',
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
}
