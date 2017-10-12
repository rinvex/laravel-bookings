<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Contracts;

/**
 * Rinvex\Bookings\Contracts\BookingContract.
 *
 * @property int                                                $id
 * @property int                                                $bookable_id
 * @property string                                             $bookable_type
 * @property int                                                $user_id
 * @property \Carbon\Carbon                                     $starts_at
 * @property \Carbon\Carbon                                     $ends_at
 * @property float                                              $price
 * @property array                                              $price_equation
 * @property \Carbon\Carbon                                     $cancelled_at
 * @property string                                             $notes
 * @property \Carbon\Carbon                                     $created_at
 * @property \Carbon\Carbon                                     $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $bookable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking bookingsOf($bookable)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking byUser($user)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelledAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelledBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelledBetween($starts, $ends)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking current()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking endsAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking endsBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking endsBetween($starts, $ends)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking future()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking past()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking startsAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking startsBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking startsBetween($starts, $ends)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking wherePriceEquation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereUpdatedAt($value)
 * @mixin \Eloquent
 */
interface BookingContract
{
    //
}
