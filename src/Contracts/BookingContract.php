<?php

declare(strict_types=1);

namespace Rinvex\Bookable\Contracts;

/**
 * Rinvex\Bookable\Contracts\BookingContract.
 *
 * @property int                                                $id
 * @property int                                                $bookable_id
 * @property string                                             $bookable_type
 * @property int                                                $customer_id
 * @property int                                                $agent_id
 * @property \Carbon\Carbon                                     $starts_at
 * @property \Carbon\Carbon                                     $ends_at
 * @property float                                              $price
 * @property array                                              $price_equation
 * @property \Carbon\Carbon                                     $cancelled_at
 * @property string                                             $notes
 * @property \Carbon\Carbon                                     $created_at
 * @property \Carbon\Carbon                                     $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $agent
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $bookable
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $customer
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking bookingsOf($bookable)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking byAgent($agentId)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking byCustomer($customerId)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking cancelledAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking cancelledBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking cancelledBetween($starts, $ends)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking current()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking endsAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking endsBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking endsBetween($starts, $ends)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking future()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking past()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking startsAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking startsBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking startsBetween($starts, $ends)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking whereBookableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking whereBookableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking wherePriceEquation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookable\Models\Booking whereUpdatedAt($value)
 * @mixin \Eloquent
 */
interface BookingContract
{
    //
}
