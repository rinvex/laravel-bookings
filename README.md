# Rinvex Bookings

**Rinvex Bookings** is a generic resource booking system for Laravel, with the required tools to run your SAAS like services efficiently. It's simple architecture, accompanied by powerful underlying to afford solid platform for your business.

[![Packagist](https://img.shields.io/packagist/v/rinvex/bookings.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/bookings)
[![VersionEye Dependencies](https://img.shields.io/versioneye/d/php/rinvex:bookings.svg?label=Dependencies&style=flat-square)](https://www.versioneye.com/php/rinvex:bookings/)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/bookings.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/bookings/)
[![Code Climate](https://img.shields.io/codeclimate/github/rinvex/bookings.svg?label=CodeClimate&style=flat-square)](https://codeclimate.com/github/rinvex/bookings)
[![Travis](https://img.shields.io/travis/rinvex/bookings.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/bookings)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/1592b83c-f836-4b30-aef3-d997accf9de4.svg?label=SensioLabs&style=flat-square)](https://insight.sensiolabs.com/projects/1592b83c-f836-4b30-aef3-d997accf9de4)
[![StyleCI](https://styleci.io/repos/96481479/shield)](https://styleci.io/repos/96481479)
[![License](https://img.shields.io/packagist/l/rinvex/bookings.svg?label=License&style=flat-square)](https://github.com/rinvex/bookings/blob/develop/LICENSE)


## Considerations

- Payments are out of scope for this package.
- This package is for bookable resources, and has nothing to do with price plans and subscriptions. If you're looking for subscription management system, you may have to look at **[rinvex/subscriptions](https://github.com/rinvex/subscriptions).**
- This package assumes that you've a bookable model that has at least `price` as a decimal field, and `unit` as a char field which accepts one of (h,d,w,m,y,u) representing (hour, day, week, month, year, use) respectively. But anyway even if you don't have these fields, **Rinvex Bookings** behaves agnostically since you've to define such behaviour and functionality yourself. You can extend package's functionality to add features like: minimum and maximum booking length, early and late booking limit, and many more, but this is out of scope for the package and up to your implementation actually.


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

### Add Bookings to your RESOURCE model

To add Bookings functionality to your model just use the `\Rinvex\Bookings\Traits\Bookable` trait like this:

```php
namespace App\Models;

use Rinvex\Bookings\Traits\Bookable;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use Bookable;
}
```

That's it, we only have to use that trait in our Room model! Now your rooms will be bookable.

### Add Bookings to your user model

To add support for booking resources to your user model(s), just use the `\Rinvex\Bookings\Traits\BookingCustomer` trait like this:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Bookings\Traits\BookingCustomer;

class User extends Model
{
    use BookingCustomer;
}
```

### Create a new booking

Creating a new booking is straightforward, and could be done in many ways. Let's see how could we do that:

```php
$room = \App\Models\Room::find(1);
$user = \App\Models\User::find(1);

// Create a new booking via Bookable model (model, starts, ends, price)
$room->newBooking($user, '2017-07-05 12:44:12', '2017-07-10 18:30:11', 8.4);

// Create a new booking via user model (model, starts, ends, price)
$user->newBooking($room, '2017-07-05 12:44:12', '2017-07-10 18:30:11', 8.4);
```

As you can see, there's many ways to create a new booking, use whatever suits your context.

### Create a booking rate

Booking rates are special criteria used to modify the default booking price. For example, let’s assume that you have a model charged per hour, and you need to set a higher price for the first "2" hours to cover certain costs, while discounting pricing if booked more than "5" hours. That’s totally achievable through booking rates. Simply set the amount of units to apply this criteria on, and state the percentage you’d like to have increased or decreased from the default price using +/- signs, i.e. -10%, and of course select the operator from: (`**^**` means the first X units, `**<**` means when booking is less than X units, `**>**` means when booking is greater than X units)

To create a booking rate, follow these steps:

```php
$room = \App\Models\Room::find(1);
$room->newRate('+15%', '^', 2); // Increate unit price by 15% for the first 2 units (probably hours)
$room->newRate('-10%', '>', 5); // Discount unit price by 10% if booking is greater than 5 units (probably hours)
```

### Create a booking price

Booking prices are the times that your model is allowed to be booked at. For example, let’s you've a Coworking Space business, and one of your rooms is a Conference Room, which is only available Sunday through Thursday, from 09:00 am till 05:00 pm. Not just that, let's say you need to charge more for peak hours at Thursday! That's totally achievable through booking avialabilities, where you can set both time frames and their prices too. Awesome, huh?

To create a booking price, follow these steps:

```php
$room = \App\Models\Room::find(1);
$room->newPrice('sun', '09:00 am', '05:00 pm');
$room->newPrice('mon', '09:00 am', '05:00 pm');
$room->newPrice('tue', '09:00 am', '05:00 pm');
$room->newPrice('wed', '09:00 am', '05:00 pm');
$room->newPrice('thu', '09:00 am', '05:00 pm', 10.5);
```

Piece of cake, right? You just set the day, from-to times, and optionally the custom unit price (which should override the default bookable unit price).

> **Note:** If you don't create any prices, then the model can be booked at any time.

### Get bookable details

You can query the bookable model for further details, using the intuitive API as follows:

```php
$room = \App\Models\Room::find(1);
$user = \App\Models\User::find(1);

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

$room->bookingsOfCustomer($user)->get(); // Get bookings of the given customer

// Get all rates
$room->rates;

// Get all prices
$room->prices;
```

All the above properties and methods are actually relationships, so you can call the raw relation methods and chain like any normal Eloquent relationship. E.g. `$room->bookings()->where('starts_at', '>', new \Carbon\Carbo())->first()`.

### Get bookings via user instance

Just like how you query your bookable models, you can query user models to retrieve bookings related info so easily. Look at these examples:

```php
$room = \App\Models\Room::find(1);
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

$user->bookingsOf(get_class($room))->get(); // Get bookings of the given model
$user->isBooked($room); // Check if the person booked the given model
```

Just like bookable models, all the above properties and methods are actually relationships, so you can call the raw relation methods and chain like any normal Eloquent relationship. E.g. `$user->bookings()->where('starts_at', '>', new \Carbon\Carbo())->first()`.


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

(c) 2016-2017 Rinvex LLC, Some rights reserved.
