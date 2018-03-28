<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Rinvex\Bookings\Models\Addon.
 *
 * @property int                                                $id
 * @property int                                                $bookable_id
 * @property string                                             $bookable_type
 * @property string                                             $name
 * @property string                                             $title
 * @property string                                             $description
 * @property float                                              $base_cost
 * @property string                                             $base_cost_modifier
 * @property float                                              $unit_cost
 * @property string                                             $unit_cost_modifier
 * @property int                                                $priority
 * @property \Carbon\Carbon|null                                $created_at
 * @property \Carbon\Carbon|null                                $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $bookable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereBaseCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereBaseCostModifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereRangeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereRangeTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereUnitCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereUnitCostModifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Addon whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Addon extends Model
{
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'name',
        'title',
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
        'name' => 'string',
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

        $this->setTable(config('rinvex.bookings.tables.addons'));
        $this->setRules([
            'bookable_id' => 'required|integer',
            'bookable_type' => 'required|string',
            'name' => 'required|alpha_dash|max:150|unique:'.config('rinvex.bookings.tables.addons').',name',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:10000',
            'base_cost' => 'required|numeric',
            'base_cost_modifier' => 'required|string|in:+,-,×,÷',
            'unit_cost' => 'required|numeric',
            'unit_cost_modifier' => 'required|string|in:+,-,×,÷',
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
