<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

abstract class BookableAddon extends Model
{
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'slug',
        'name',
        'description',
        'base_cost',
        'base_cost_modifier',
        'unit_cost',
        'unit_cost_modifier',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'bookable_id' => 'integer',
        'bookable_type' => 'string',
        'slug' => 'string',
        'base_cost' => 'float',
        'base_cost_modifier' => 'string',
        'unit_cost' => 'float',
        'unit_cost_modifier' => 'string',
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

        $this->setTable(config('rinvex.bookings.tables.bookable_addons'));
        $this->setRules([
            'bookable_id' => 'required|integer',
            'bookable_type' => 'required|string',
            'slug' => 'required|alpha_dash|max:150|unique:'.config('rinvex.bookings.tables.bookable_addons').',slug,NULL,id,bookable_id,'.($this->bookable_id ?? 'null').',bookable_type,'.($this->bookable_type ?? 'null'),
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:10000',
            'base_cost' => 'required|numeric',
            'base_cost_modifier' => 'required|in:+,-,×,÷',
            'unit_cost' => 'required|numeric',
            'unit_cost_modifier' => 'required|in:+,-,×,÷',
        ]);
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
