<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Spatie\Sluggable\SlugOptions;
use Rinvex\Support\Traits\HasSlug;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\EloquentSortable\SortableTrait;
use Rinvex\Bookings\Traits\Bookable as BookableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class Bookable extends Model implements Sortable
{
    use HasSlug;
    use HasFactory;
    use BookableTrait;
    use SortableTrait;
    use HasTranslations;
    use ValidatingTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_active',
        'base_cost',
        'unit_cost',
        'currency',
        'unit',
        'maximum_units',
        'minimum_units',
        'is_cancelable',
        'is_recurring',
        'sort_order',
        'capacity',
        'style',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'slug' => 'string',
        'name' => 'string',
        'description' => 'string',
        'is_active' => 'boolean',
        'base_cost' => 'float',
        'unit_cost' => 'float',
        'currency' => 'string',
        'unit' => 'string',
        'maximum_units' => 'integer',
        'minimum_units' => 'integer',
        'is_cancelable' => 'boolean',
        'is_recurring' => 'boolean',
        'sort_order' => 'integer',
        'capacity' => 'integer',
        'style' => 'string',
        'deleted_at' => 'datetime',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * {@inheritdoc}
     */
    public $translatable = [
        'name',
        'description',
    ];

    /**
     * {@inheritdoc}
     */
    public $sortable = [
        'order_column_name' => 'sort_order',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->mergeRules([
            'slug' => 'required|alpha_dash|max:150',
            'name' => 'required|string|strip_tags|max:150',
            'description' => 'nullable|string|max:32768',
            'is_active' => 'sometimes|boolean',
            'base_cost' => 'required|numeric',
            'unit_cost' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'unit' => 'required|in:minute,hour,day,month',
            'maximum_units' => 'nullable|integer|max:100000',
            'minimum_units' => 'nullable|integer|max:100000',
            'is_cancelable' => 'nullable|boolean',
            'is_recurring' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|max:100000',
            'capacity' => 'nullable|integer|max:100000',
            'style' => 'nullable|string|strip_tags|max:150',
        ]);

        parent::__construct($attributes);
    }

    /**
     * Get the active resources.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('is_active', true);
    }

    /**
     * Get the inactive resources.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive(Builder $builder): Builder
    {
        return $builder->where('is_active', false);
    }

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->doNotGenerateSlugsOnUpdate()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug');
    }

    /**
     * Activate the resource.
     *
     * @return $this
     */
    public function activate()
    {
        $this->update(['is_active' => true]);

        return $this;
    }

    /**
     * Deactivate the resource.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);

        return $this;
    }
}
