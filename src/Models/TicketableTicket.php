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
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketableTicket extends Model implements Sortable
{
    use HasSlug;
    use HasFactory;
    use SortableTrait;
    use HasTranslations;
    use ValidatingTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'ticketable_id',
        'ticketable_type',
        'slug',
        'name',
        'description',
        'is_active',
        'price',
        'currency',
        'quantity',
        'sort_order',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'ticketable_id' => 'integer',
        'ticketable_type' => 'string',
        'slug' => 'string',
        'name' => 'string',
        'description' => 'string',
        'is_active' => 'boolean',
        'price' => 'float',
        'currency' => 'string',
        'quantity' => 'integer',
        'sort_order' => 'integer',
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
        $this->setTable(config('rinvex.bookings.tables.ticketable_tickets'));
        $this->mergeRules([
            'ticketable_id' => 'required|integer',
            'ticketable_type' => 'required|string|strip_tags|max:150',
            'slug' => 'required|alpha_dash|max:150|unique:'.config('rinvex.bookings.tables.ticketable_tickets').',slug,NULL,id,ticketable_id,'.($this->ticketable_id ?? 'null').',ticketable_type,'.($this->ticketable_type ?? 'null'),
            'name' => 'required|string|strip_tags|max:150',
            'description' => 'nullable|string|max:32768',
            'is_active' => 'sometimes|boolean',
            'price' => 'required|numeric',
            'currency' => 'required|alpha|size:3',
            'quantity' => 'nullable|integer|max:100000',
            'sort_order' => 'nullable|integer|max:100000',
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

    /**
     * Get the owning resource model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function ticketable(): MorphTo
    {
        return $this->morphTo('ticketable', 'ticketable_type', 'ticketable_id', 'id');
    }
}
