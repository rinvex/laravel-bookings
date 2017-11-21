<?php

declare(strict_types=1);

namespace Rinvex\Bookings\Contracts;

/**
 * Rinvex\Bookings\Contracts\BookingContract.
 *
 * @property int                                                $id
 * @property int                                                $resource_id
 * @property string                                             $resource_type
 * @property string                                             $currency
 * @property int                                                $customer_id
 * @property string                                             $customer_type
 * @property \Carbon\Carbon                                     $starts_at
 * @property \Carbon\Carbon                                     $ends_at
 * @property float                                              $price
 * @property array                                              $price_equation
 * @property \Carbon\Carbon                                     $cancelled_at
 * @property string                                             $notes
 * @property \Carbon\Carbon|null                                $created_at
 * @property \Carbon\Carbon|null                                $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $resource
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $customer
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelled()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelledAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelledBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking cancelledBetween($startsAt, $endsAt)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking current()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking endsAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking endsBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking endsBetween($startsAt, $endsAt)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking future()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking ofCustomer(\Illuminate\Database\Eloquent\Model $customer)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking ofResource(\Illuminate\Database\Eloquent\Model $resource)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking past()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking startsAfter($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking startsBefore($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking startsBetween($startsAt, $endsAt)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking wherePriceEquation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereResourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Bookings\Models\Booking whereUpdatedAt($value)
 * @mixin \Eloquent
 */
interface BookingContract
{
    //
}
