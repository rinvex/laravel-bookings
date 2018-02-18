# Rinvex Bookings

**Rinvex Bookings** is a generic resource booking system for Laravel, with the required tools to run your SAAS like services efficiently. It has a simple architecture, with powerful underlying to afford solid platform for your business.

[![Packagist](https://img.shields.io/packagist/v/rinvex/bookings.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/bookings)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/bookings.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/bookings/)
[![Code Climate](https://img.shields.io/codeclimate/github/rinvex/bookings.svg?label=CodeClimate&style=flat-square)](https://codeclimate.com/github/rinvex/bookings)
[![Travis](https://img.shields.io/travis/rinvex/bookings.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/bookings)
[![StyleCI](https://styleci.io/repos/96481479/shield)](https://styleci.io/repos/96481479)
[![License](https://img.shields.io/packagist/l/rinvex/bookings.svg?label=License&style=flat-square)](https://github.com/rinvex/bookings/blob/develop/LICENSE)


## Considerations

- **Rinvex Bookings** is for bookable resources, and has nothing to do with price plans and subscriptions. If you're looking for subscription management system, you may have to look at **[rinvex/subscriptions](https://github.com/rinvex/subscriptions).**
- **Rinvex Bookings** assumes that your resource model has at least three fields, `price` as a decimal field, and lastly `unit` as a string field which accepts one of (minute, hour, day) respectively.
- Payments and ordering are out of scope for **Rinvex Bookings**, so you've to take care of this yourself. Booking price is calculated by this package, so you may need to hook into the process or listen to saved bookings to issue invoice, or trigger payment process.
- You may extend **Rinvex Bookings** functionality to add features like: minimum and maximum booking length, early and late booking limit, and many more. These features may be supported natively sometime in the future.


## Installation

1. Install the package via composer:
    ```shell
    composer require rinvex/bookings
    ```

2. Execute migrations via the following command:
    ```shell
    php artisan rinvex:migrate:bookings
    ```

3. Done!


## Usage

**Rinvex Bookings** has been specially made for Eloquent and simplicity has been taken very serious as in any other Laravel related aspect. 

### Add bookable functionality to your resource model

To add bookable functionality to your resource model just use the `\Rinvex\Bookings\Traits\Bookable` trait like this:

```php
namespace App\Models;

use Rinvex\Bookings\Traits\Bookable;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use Bookable;
}
```

That's it, you only have to use that trait in your Room model! Now your rooms will be bookable.

### Add bookable functionality to your user model

To add bookable functionality to your user model just use the `\Rinvex\Bookings\Traits\HasBookings` trait like this:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Bookings\Traits\HasBookings;

class User extends Model
{
    use HasBookings;
}
```

Again, that's all you need to do! Now your User model can book resources.

### Create a new booking

Creating a new booking is straight forward, and could be done in many ways. Let's see how could we do that:

```php
$room = \App\Models\Room::find(1);
$user = \App\Models\User::find(1);
$booking = app('rinvex.bookings.booking');

// Create a new booking via resource model (user, starts, ends)
$room->newBooking($user, '2017-07-05 12:44:12', '2017-07-10 18:30:11');

// Create a new booking via user model (resource, starts, ends)
$user->newBooking($room, '2017-07-05 12:44:12', '2017-07-10 18:30:11');

// Create a new booking explicitly
$booking->make(['starts_at' => \Carbon\Carbon::now(), 'ends_at' => \Carbon\Carbon::tomorrow()])
        ->user()->associate($user)
        ->bookable()->associate($room)
        ->save();
```

> **Notes:**
> - As you can see, there's many ways to create a new booking, use whatever suits your context.
> - Booking price is calculated automatically on the fly according to the resource price, custom prices, and booking rates.
> - **Rinvex Bookings** is intelegent enough to detect date format and convert if required, the above example show the explicitly correct format, but you still can write something like: 'Tomorrow 1pm' and it will be converted automatically for you.

### Query booking models

You can get more details about a specific booking as follows:

```php
$booking = app('rinvex.bookings.booking')->find(1);

$bookable = $booking->bookable; // Get the owning resource model
$user = $booking->user; // Get the owning user model

$booking->isPast(); // Check if the booking is past
$booking->isFuture(); // Check if the booking is future
$booking->isCurrent(); // Check if the booking is current
$booking->isCancelled(); // Check if the booking is cancelled
```

And as expected, you can query bookings by date as well:

```php
$pastBookings = app('rinvex.bookings.booking')->past(); // Get the past bookings
$futureBookings = app('rinvex.bookings.booking')->future(); // Get the future bookings
$currentBookings = app('rinvex.bookings.booking')->current(); // Get the current bookings
$cancelledBookings = app('rinvex.bookings.booking')->cancelled(); // Get the cancelled bookings

$bookingsSAfter = app('rinvex.bookings.booking')->startsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$bookingsStartsBefore = app('rinvex.bookings.booking')->startsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$bookingsSBetween = app('rinvex.bookings.booking')->startsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$bookingsEndsAfter = app('rinvex.bookings.booking')->endsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$bookingsEndsBefore = app('rinvex.bookings.booking')->endsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$bookingsEndsBetween = app('rinvex.bookings.booking')->endsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$bookingsCancelledAfter = app('rinvex.bookings.booking')->cancelledAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$bookingsCancelledBefore = app('rinvex.bookings.booking')->cancelledBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$bookingsCancelledBetween = app('rinvex.bookings.booking')->cancelledBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$room = \App\Models\Room::find(1);
$bookingsOfBookable = app('rinvex.bookings.booking')->ofBookable($room)->get(); // Get bookings of the given resource

$user = \App\Models\User::find(1);
$bookingsOfUser = app('rinvex.bookings.booking')->ofUser($user)->get(); // Get bookings of the given user
```

### Create a new booking rate

Booking rates are special criteria used to modify the default booking price. For example, let’s assume that you have a resource charged per hour, and you need to set a higher price for the first "2" hours to cover certain costs, while discounting pricing if booked more than "5" hours. That’s totally achievable through booking rates. Simply set the amount of units to apply this criteria on, and state the percentage you’d like to have increased or decreased from the default price using +/- signs, i.e. -10%, and of course select the operator from: (**`^`** means the first starting X units, **`<`** means when booking is less than X units, **`>`** means when booking is greater than X units). Allowed percentages could be between -100% and +100%.

To create a new booking rate, follow these steps:

```php
$room = \App\Models\Room::find(1);
$room->newRate('15', '^', 2); // Increate unit price by 15% for the first 2 units (probably hours)
$room->newRate('-10', '>', 5); // Discount unit price by 10% if booking is greater than 5 units (probably hours)
```

Alternatively you can create a new booking rate explicitly as follows:

```php
$room = \App\Models\Room::find(1);
$rate = app('rinvex.bookings.rate');
$rate->make(['percentage' => '15', 'operator' => '^', 'amount' => 2])
     ->bookable()->associate($room)
     ->save();
```

And here's the booking rate relations:

```php
$bookable = $rate->bookable; // Get the owning resource model
```

> **Notes:**
> - All booking rate percentages should NEVER contain the `%` sign, it's known that this field is for percentage already.
> - When adding new booking rate with positive percentage, the `+` sign is NOT required, and will be omitted anyway if entered.

### Create a new custom price

Custom prices are set according to specific time based criteria. For example, let’s say you've a Coworking Space business, and one of your rooms is a Conference Room, and you would like to charge differently for both Monday and Wednesday. Will assume that Monday from 09:00 am till 05:00 pm is a peak hours, so you need to charge more, and Wednesday from 11:30 am to 03:45 pm is dead hours so you'd like to charge less! That's totally achievable through custom prices, where you can set both time frames and their prices too using +/- percentage. It works the same way as [Booking Rates](#create-a-new-booking-rate) but on a time based criteria. Awesome, huh?

To create a custom price, follow these steps:

```php
$room = \App\Models\Room::find(1);
$room->newPrice('mon', '09:00:00', '17:00:00', '26'); // Increase pricing on Monday from 09:00 am to 05:00 pm by 26%
$room->newPrice('wed', '11:30:00', '15:45:00', '-10.5'); // Decrease pricing on Wednesday from 11:30 am to 03:45 pm by 10.5%
```

Piece of cake, right? You just set the day, from-to times, and the +/- percentage to increase/decrease your unit price.

And here's the custom price relations:

```php
$bookable = $room->bookable; // Get the owning resource model
```

> **Notes:**
> - If you don't create any custom prices, then the resource will be booked at the default resource price.
> - **Rinvex Bookings** is intelegent enough to detect time format and convert if required, the above example show the explicitly correct format, but you still can write something like: '09:00 am' and it will be converted automatically for you.

### Query resource models

You can query your resource models for further details, using the intuitive API as follows:

```php
$room = \App\Models\Room::find(1);

$room->bookings; // Get all bookings
$room->pastBookings; // Get past bookings
$room->futureBookings; // Get future bookings
$room->currentBookings; // Get current bookings
$room->cancelledBookings; // Get cancelled bookings

$room->bookingsStartsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$room->bookingsStartsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$room->bookingsStartsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$room->bookingsEndsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$room->bookingsEndsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$room->bookingsEndsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$room->bookingsCancelledBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$room->bookingsCancelledAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$room->bookingsCancelledBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$user = \App\Models\User::find(1);
$room->bookingsOf($user)->get(); // Get bookings of the given user

$room->rates; // Get all booking rates
$room->prices; // Get all custom prices
```

All the above properties and methods are actually relationships, so you can call the raw relation methods and chain like any normal Eloquent relationship. E.g. `$room->bookings()->where('starts_at', '>', new \Carbon\Carbon())->first()`.

### Query user models

Just like how you query your resources, you can query users to retrieve related booking info easily. Look at these examples:

```php
$user = \App\Models\User::find(1);

$user->bookings; // Get all bookings
$user->pastBookings; // Get past bookings
$user->futureBookings; // Get future bookings
$user->currentBookings; // Get current bookings
$user->cancelledBookings; // Get cancelled bookings

$user->bookingsStartsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$user->bookingsStartsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$user->bookingsStartsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$user->bookingsEndsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$user->bookingsEndsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$user->bookingsEndsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$user->bookingsCancelledBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$user->bookingsCancelledAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$user->bookingsCancelledBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$room = \App\Models\Room::find(1);
$user->isBooked($room); // Check if the user booked the given room
$user->bookingsOf($room)->get(); // Get bookings by the user for the given room
```

Just like resource models, all the above properties and methods are actually relationships, so you can call the raw relation methods and chain like any normal Eloquent relationship. E.g. `$user->bookings()->where('starts_at', '>', new \Carbon\Carbon())->first()`.


## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](http://chat.rinvex.com)
- [Help on Email](mailto:help@rinvex.com)
- [Follow on Twitter](https://twitter.com/rinvex)


## Contributing & Protocols

Thank you for considering contributing to this project! The contribution guide can be found in [CONTRIBUTING.md](CONTRIBUTING.md).

Bug reports, feature requests, and pull requests are very welcome.

- [Versioning](CONTRIBUTING.md#versioning)
- [Pull Requests](CONTRIBUTING.md#pull-requests)
- [Coding Standards](CONTRIBUTING.md#coding-standards)
- [Feature Requests](CONTRIBUTING.md#feature-requests)
- [Git Flow](CONTRIBUTING.md#git-flow)


## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to [help@rinvex.com](help@rinvex.com). All security vulnerabilities will be promptly addressed.


## About Rinvex

Rinvex is a software solutions startup, specialized in integrated enterprise solutions for SMEs established in Alexandria, Egypt since June 2016. We believe that our drive The Value, The Reach, and The Impact is what differentiates us and unleash the endless possibilities of our philosophy through the power of software. We like to call it Innovation At The Speed Of Life. That’s how we do our share of advancing humanity.


## License

This software is released under [The MIT License (MIT)](LICENSE).

(c) 2016-2018 Rinvex LLC, Some rights reserved.
