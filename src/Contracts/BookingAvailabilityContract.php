<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Contracts;

/**
 * Rinvex\Bookings\Contracts\BookingAvailabilityContract.
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
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingAvailability whereUpdatedAt($value)
 * @mixin \Eloquent
 */
interface BookingAvailabilityContract
{
    //
}
