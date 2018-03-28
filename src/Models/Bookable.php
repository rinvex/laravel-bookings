<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Models;

use Spatie\Sluggable\SlugOptions;
use Rinvex\Support\Traits\HasSlug;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Illuminate\Database\Eloquent\Builder;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\EloquentSortable\SortableTrait;
use Rinvex\Bookings\Traits\Bookable as BookableTrait;

abstract class Bookable extends Model implements Sortable
{
    use HasSlug;
    use BookableTrait;
    use SortableTrait;
    use HasTranslations;
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'title',
        'description',
        'is_active',
        'base_cost',
        'unit_cost',
        'currency',
        'unit',
        'maximum_units',
        'minimum_units',
        'is_recurring',
        'sort_order',
        'capacity',
        'style',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'name' => 'string',
        'title' => 'string',
        'description' => 'string',
        'is_active' => 'boolean',
        'base_cost' => 'float',
        'unit_cost' => 'float',
        'currency' => 'string',
        'unit' => 'string',
        'maximum_units' => 'integer',
        'minimum_units' => 'integer',
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
        'title',
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
                          ->generateSlugsFrom('title')
                          ->saveSlugsTo('name');
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
