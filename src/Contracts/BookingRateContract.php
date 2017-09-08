<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Contracts;

/**
 * Rinvex\Bookings\Contracts\BookingRateContract.
 *
 * @property int                                                $id
 * @property int                                                $bookable_id
 * @property string                                             $bookable_type
 * @property int                                                $percentage
 * @property string                                             $operator
 * @property int                                                $amount
 * @property \Carbon\Carbon                                     $created_at
 * @property \Carbon\Carbon                                     $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $bookable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingRate whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingRate whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingRate whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingRate whereOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingRate wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\BookingRate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
interface BookingRateContract
{
    //
}
