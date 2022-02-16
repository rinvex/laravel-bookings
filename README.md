# Rinvex Bookings

⚠️ This package is abandoned and no longer maintained. No replacement package was suggested. ⚠️

👉 If you are interested to step on as the main maintainer of this package, please [reach out to me](https://twitter.com/omranic)!

---

**Rinvex Bookings** is a generic resource booking system for Laravel, with the required tools to run your SAAS like services efficiently. It has a simple architecture, with powerful underlying to afford solid platform for your business.

[![Packagist](https://img.shields.io/packagist/v/rinvex/laravel-bookings.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/laravel-bookings)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/laravel-bookings.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/laravel-bookings/)
[![Travis](https://img.shields.io/travis/rinvex/laravel-bookings.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/laravel-bookings)
[![StyleCI](https://styleci.io/repos/96481479/shield)](https://styleci.io/repos/96481479)
[![License](https://img.shields.io/packagist/l/rinvex/laravel-bookings.svg?label=License&style=flat-square)](https://github.com/rinvex/laravel-bookings/blob/develop/LICENSE)


## Considerations

- **Rinvex Bookings** is for bookable resources, and has nothing to do with price plans and subscriptions. If you're looking for subscription management system, you may have to look at **[rinvex/laravel-subscriptions](https://github.com/rinvex/laravel-subscriptions).**
- **Rinvex Bookings** assumes that your resource model has at least three fields, `price` as a decimal field, and lastly `unit` as a string field which accepts one of (minute, hour, day, month) respectively.
- Payments and ordering are out of scope for **Rinvex Bookings**, so you've to take care of this yourself. Booking price is calculated by this package, so you may need to hook into the process or listen to saved bookings to issue invoice, or trigger payment process.
- You may extend **Rinvex Bookings** functionality to add features like: minimum and maximum units, and many more. These features may be supported natively sometime in the future.


## Installation

1. Install the package via composer:
    ```shell
    composer require rinvex/laravel-bookings
    ```

2. Publish resources (migrations and config files):
    ```shell
    php artisan rinvex:publish:bookings
    ```

3. Execute migrations via the following command:
    ```shell
    php artisan rinvex:migrate:bookings
    ```

4. Done!


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

### Add bookable functionality to your customer model

To add bookable functionality to your customer model just use the `\Rinvex\Bookings\Traits\HasBookings` trait like this:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Bookings\Traits\HasBookings;

class Customer extends Model
{
    use HasBookings;
}
```

Again, that's all you need to do! Now your Customer model can book resources.

### Create a new booking

Creating a new booking is straight forward, and could be done in many ways. Let's see how could we do that:

```php
$room = \App\Models\Room::find(1);
$customer = \App\Models\Customer::find(1);

// Extends \Rinvex\Bookings\Models\BookableBooking
$serviceBooking = new \App\Models\ServiceBooking;

// Create a new booking via resource model (customer, starts, ends)
$room->newBooking($customer, '2017-07-05 12:44:12', '2017-07-10 18:30:11');

// Create a new booking via customer model (resource, starts, ends)
$customer->newBooking($room, '2017-07-05 12:44:12', '2017-07-10 18:30:11');

// Create a new booking explicitly
$serviceBooking->make(['starts_at' => \Carbon\Carbon::now(), 'ends_at' => \Carbon\Carbon::tomorrow()])
        ->customer()->associate($customer)
        ->bookable()->associate($room)
        ->save();
```

> **Notes:**
> - As you can see, there's many ways to create a new booking, use whatever suits your context.
> - Booking price is calculated automatically on the fly according to the resource price, custom prices, and bookable Rates.
> - **Rinvex Bookings** is intelegent enough to detect date format and convert if required, the above example show the explicitly correct format, but you still can write something like: 'Tomorrow 1pm' and it will be converted automatically for you.

### Query booking models

You can get more details about a specific booking as follows:

```php
// Extends \Rinvex\Bookings\Models\BookableBooking
$serviceBooking = \App\Models\ServiceBooking::find(1);

$bookable = $serviceBooking->bookable; // Get the owning resource model
$customer = $serviceBooking->customer; // Get the owning customer model

$serviceBooking->isPast(); // Check if the booking is past
$serviceBooking->isFuture(); // Check if the booking is future
$serviceBooking->isCurrent(); // Check if the booking is current
$serviceBooking->isCancelled(); // Check if the booking is cancelled
```

And as expected, you can query bookings by date as well:

```php
// Extends \Rinvex\Bookings\Models\BookableBooking
$serviceBooking = new \App\Models\ServiceBooking;

$pastBookings = $serviceBooking->past(); // Get the past bookings
$futureBookings = $serviceBooking->future(); // Get the future bookings
$currentBookings = $serviceBooking->current(); // Get the current bookings
$cancelledBookings = $serviceBooking->cancelled(); // Get the cancelled bookings

$serviceBookingsAfter = $serviceBooking->startsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$serviceBookingsStartsBefore = $serviceBooking->startsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$serviceBookingsBetween = $serviceBooking->startsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$serviceBookingsEndsAfter = $serviceBooking->endsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$serviceBookingsEndsBefore = $serviceBooking->endsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$serviceBookingsEndsBetween = $serviceBooking->endsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$serviceBookingsCancelledAfter = $serviceBooking->cancelledAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$serviceBookingsCancelledBefore = $serviceBooking->cancelledBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$serviceBookingsCancelledBetween = $serviceBooking->cancelledBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$room = \App\Models\Room::find(1);
$serviceBookingsOfBookable = $serviceBooking->ofBookable($room)->get(); // Get bookings of the given resource

$customer = \App\Models\Customer::find(1);
$serviceBookingsOfCustomer = $serviceBooking->ofCustomer($customer)->get(); // Get bookings of the given customer
```

### Create a new booking rate

Bookable Rates are special criteria used to modify the default booking price. For example, let’s assume that you have a resource charged per hour, and you need to set a higher price for the first "2" hours to cover certain costs, while discounting pricing if booked more than "5" hours. That’s totally achievable through bookable Rates. Simply set the amount of units to apply this criteria on, and state the percentage you’d like to have increased or decreased from the default price using +/- signs, i.e. -10%, and of course select the operator from: (**`^`** means the first starting X units, **`<`** means when booking is less than X units, **`>`** means when booking is greater than X units). Allowed percentages could be between -100% and +100%.

To create a new booking rate, follow these steps:

```php
$room = \App\Models\Room::find(1);
$room->newRate('15', '^', 2); // Increase unit price by 15% for the first 2 units
$room->newRate('-10', '>', 5); // Decrease unit price by 10% if booking is greater than 5 units
```

Alternatively you can create a new booking rate explicitly as follows:

```php
$room = \App\Models\Room::find(1);

// Extends \Rinvex\Bookings\Models\BookableRate
$serviceRate = new \App\Models\ServiceRate;

$serviceRate->make(['percentage' => '15', 'operator' => '^', 'amount' => 2])
     ->bookable()->associate($room)
     ->save();
```

And here's the booking rate relations:

```php
$bookable = $serviceRate->bookable; // Get the owning resource model
```

> **Notes:**
> - All booking rate percentages should NEVER contain the `%` sign, it's known that this field is for percentage already.
> - When adding new booking rate with positive percentage, the `+` sign is NOT required, and will be omitted anyway if entered.

### Create a new custom price

Custom prices are set according to specific time based criteria. For example, let’s say you've a Coworking Space business, and one of your rooms is a Conference Room, and you would like to charge differently for both Monday and Wednesday. Will assume that Monday from 09:00 am till 05:00 pm is a peak hours, so you need to charge more, and Wednesday from 11:30 am to 03:45 pm is dead hours so you'd like to charge less! That's totally achievable through custom prices, where you can set both time frames and their prices too using +/- percentage. It works the same way as [Bookable Rates](#create-a-new-booking-rate) but on a time based criteria. Awesome, huh?

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

$customer = \App\Models\Customer::find(1);
$room->bookingsOf($customer)->get(); // Get bookings of the given customer

$room->rates; // Get all bookable Rates
$room->prices; // Get all custom prices
```

All the above properties and methods are actually relationships, so you can call the raw relation methods and chain like any normal Eloquent relationship. E.g. `$room->bookings()->where('starts_at', '>', new \Carbon\Carbon())->first()`.

### Query customer models

Just like how you query your resources, you can query customers to retrieve related booking info easily. Look at these examples:

```php
$customer = \App\Models\Customer::find(1);

$customer->bookings; // Get all bookings
$customer->pastBookings; // Get past bookings
$customer->futureBookings; // Get future bookings
$customer->currentBookings; // Get current bookings
$customer->cancelledBookings; // Get cancelled bookings

$customer->bookingsStartsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$customer->bookingsStartsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$customer->bookingsStartsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$customer->bookingsEndsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$customer->bookingsEndsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$customer->bookingsEndsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$customer->bookingsCancelledBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$customer->bookingsCancelledAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$customer->bookingsCancelledBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$room = \App\Models\Room::find(1);
$customer->isBooked($room); // Check if the customer booked the given room
$customer->bookingsOf($room)->get(); // Get bookings by the customer for the given room
```

Just like resource models, all the above properties and methods are actually relationships, so you can call the raw relation methods and chain like any normal Eloquent relationship. E.g. `$customer->bookings()->where('starts_at', '>', new \Carbon\Carbon())->first()`.


**⚠️ Documentation not complete, the package is under developement, and some part may encounter refactoring! ⚠️**


## Roadmap

**Looking for contributors!**

The following are a set of limitations to be improved, or feature requests that's looking for contributors to implement, all PRs are welcome 🙂

- [ ] Add the ability to cancel bookings (#43)
- [ ] Complete the bookable availability implementation, and document it (#32, #4)
- [ ] Improve the documentation, and complete missing features, and add a workable example for each.


## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](https://bit.ly/rinvex-slack)
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

(c) 2016-2022 Rinvex LLC, Some rights reserved.
