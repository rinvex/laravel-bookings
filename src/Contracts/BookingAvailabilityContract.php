<?php

declare(strict_types=1);

namespace Rinvex\Bookable\Contracts;

/**
 * Rinvex\Bookable\Contracts\BookingAvailabilityContract.
 *
 * @property int                                                $id
 * @property int                                                $bookable_id
 * @property string                                             $bookable_type
 * @property string                                             $day
 * @property \Carbon\Carbon                                     $starts_at
 * @property \Carbon\Carbon                                     $ends_at
 * @property float                                              $price
 * @property \Carbon\Carbon                                     $created_at
 * @property \Carbon\Carbon                                     $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $bookable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\BookingAvailability whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\BookingAvailability whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\BookingAvailability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\BookingAvailability whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\BookingAvailability whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\BookingAvailability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\BookingAvailability wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\BookingAvailability whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\BookingAvailability whereUpdatedAt($value)
 * @mixin \Eloquent
 */
interface BookingAvailabilityContract
{
    //
}
