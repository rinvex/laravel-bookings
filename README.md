# Rinvex Bookable

**Rinvex Bookable** is a generic resource booking system for Laravel, with the required tools to run your SAAS like services efficiently. It's simple architecture, accompanied by powerful underlying to afford solid platform for your business.

[![Packagist](https://img.shields.io/packagist/v/rinvex/bookable.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/bookable)
[![VersionEye Dependencies](https://img.shields.io/versioneye/d/php/rinvex:bookable.svg?label=Dependencies&style=flat-square)](https://www.versioneye.com/php/rinvex:bookable/)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/bookable.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/bookable/)
[![Code Climate](https://img.shields.io/codeclimate/github/rinvex/bookable.svg?label=CodeClimate&style=flat-square)](https://codeclimate.com/github/rinvex/bookable)
[![Travis](https://img.shields.io/travis/rinvex/bookable.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/bookable)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/1592b83c-f836-4b30-aef3-d997accf9de4.svg?label=SensioLabs&style=flat-square)](https://insight.sensiolabs.com/projects/1592b83c-f836-4b30-aef3-d997accf9de4)
[![StyleCI](https://styleci.io/repos/96481479/shield)](https://styleci.io/repos/96481479)
[![License](https://img.shields.io/packagist/l/rinvex/bookable.svg?label=License&style=flat-square)](https://github.com/rinvex/bookable/blob/develop/LICENSE)


## Considerations

- Payments are out of scope for this package.
- This package is for bookable resources, and has nothing to do with price plans and subscriptions. If you're looking for subscription management system, you may have to look at **[rinvex/subscribable](https://github.com/rinvex/subscribable).**
- This package assumes that you've a bookable model that has at least `price` as a decimal field, and `unit` as a char field which accepts one of (h,d,w,m,y,u) representing (hour, day, week, month, year, use) respectively. But anyway even if you don't have these fields, **Rinvex Bookable** behaves agnostically since you've to define such behaviour and functionality yourself. You can extend package's functionality to add features like: minimum and maximum booking length, early and late booking limit, and many more, but this is out of scope for the package and up to your implementation actually.


## Installation

1. Install the package via composer:
    ```shell
    composer require rinvex/bookable
    ```

2. Execute migrations via the following command:
    ```shell
    php artisan migrate --path="vendor/rinvex/bookable/database/migrations"
    ```

3. Add the following service provider to the `'providers'` array inside `app/config/app.php`:
    ```php
    Rinvex\Bookable\Providers\BookableServiceProvider::class
    ```

   And then you can publish the migrations by running the following command:
    ```shell
    php artisan vendor:publish --tag="migrations" --provider="Rinvex\Bookable\Providers\BookableServiceProvider"
    ```

   And also you can publish the config by running the following command:
    ```shell
    php artisan vendor:publish --tag="config" --provider="Rinvex\Bookable\Providers\BookableServiceProvider"
    ```

4. Done!


## Usage

### Add Bookable to your RESOURCE model

**Rinvex Bookable** has been specially made for Eloquent and simplicity has been taken very serious as in any other Laravel related aspect. To add Bookable functionality to your model just use the `\Rinvex\Bookable\Traits\Bookable` trait like this:

```php
namespace App\Models;

use Rinvex\Bookable\Traits\Bookable;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use Bookable;
}
```

That's it, we only have to use that trait in our Event model! Now your events will be bookable.

### Add Bookable to your USER model

Bookings could be made by customers themeselves, or by agents on behalf of customers; **Rinvex Bookable** could handle both situations intelligently. To add Bookable support for your `Customer` and `Agent` models, follow these simple steps:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Bookable\Traits\IsBookingAgent;
use Rinvex\Bookable\Traits\IsBookingCustomer;

class User extends Model
{
    //
}


class Agent extends User
{
    use IsBookingAgent;
}


class Customer extends Model
{
    use IsBookingCustomer;
}
```

That's it, we only have to use either `IsBookingAgent` or `IsBookingCustomer` traits user model(s)!

### Create a new booking

Creating a new booking is straightforward, and could be done in many right ways. Let's see how could we do that:

```php
// Create a new booking via Bookable model
$event = \App\Models\Event::find(1);
$customer = \App\Models\Customer::find(1);
$event->newBooking($customer, '2017-07-05 12:44:12', '2017-07-10 18:30:11', 8.4);

// Create a new booking via agent model
$event = \App\Models\Event::find(1);
$agent = \App\Models\Agent::find(1);
$customer = \App\Models\Customer::find(1);
$agent->newBooking($event, $customer, '2017-07-05 12:44:12', '2017-07-10 18:30:11', 8.4);

// Create a new booking via customer model
$event = \App\Models\Event::find(1);
$customer = \App\Models\Customer::find(1);
$customer->newBooking($event, '2017-07-05 12:44:12', '2017-07-10 18:30:11', 8.4);
```

As you can see, there's many right ways to create a new booking, it's up to you to use the appropriate method in the appropriate situation, according to the context.

### Create a booking rate

Booking rates are special criteria used to modify the default booking price. For example, let’s assume that you have a model charged per hour, and you need to set a higher price for the first “2” hours to cover certain costs, while discounting pricing if booked more than “5” hours. That’s totally achievable through booking rates. Simply set the amount of units to apply this criteria on, and state the percentage you’d like to have increased or decreased from the default price using +/- signs, i.e. -10%, and of course select the operator from: (`**^**` means the first X units, `**<**` means when booking is less than X units, `**>**` means when booking is greater than X units)

To create a booking rate, follow these steps:

```php
$event = \App\Models\Event::find(1);
$event->newRate('+15%', '^', 2); // Increate unit price by 15% for the first 2 units (probably hours)
$event->newRate('-10%', '>', 5); // Discount unit price by 10% if booking is greater than 5 units (probably hours)
```

### Create a booking availability

Booking availabilities are the times that your model is allowed to be booked at. For example, let’s you've a Coworking Space business, and one of your rooms is a Conference Room, which is only available Sunday through Thursday, from 09:00 am till 05:00 pm. Not just that, let's say you need to charge more for peak hours at Thursday! That's totally achievable through booking avialabilities, where you can set both time frames and their prices too. Awesome, huh?

To create a booking availability, follow these steps:

```php
$event = \App\Models\Event::find(1);
$event->newAvailability('sun', '09:00 am', '05:00 pm');
$event->newAvailability('mon', '09:00 am', '05:00 pm');
$event->newAvailability('tue', '09:00 am', '05:00 pm');
$event->newAvailability('wed', '09:00 am', '05:00 pm');
$event->newAvailability('thu', '09:00 am', '05:00 pm', 10.5);
```

Piece of cake, right? You just set the day, from-to times, and optionally the custom unit price (which should override the default bookable unit price).

> **Note:** If you don't create any availabilities, then the model can be booked at any time.

### Get bookable details

You can query the bookable model for further details, using the intuitive API as follows:

```php
$event = \App\Models\Event::find(1);
$agent = \App\Models\Agent::find(1);
$customer = \App\Models\Customer::find(1);

$event->bookings; // Get all bookings
$event->pastBookings; // Get past bookings
$event->futureBookings; // Get future bookings
$event->currentBookings; // Get current bookings
$event->cancelledBookings; // Get cancelled bookings

$event->bookingsStartsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$event->bookingsStartsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$event->bookingsStartsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$event->bookingsEndsBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$event->bookingsEndsAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$event->bookingsEndsBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$event->bookingsCancelledBefore('2017-06-21 19:28:51')->get(); // Get bookings starts before the given date
$event->bookingsCancelledAfter('2017-06-21 19:28:51')->get(); // Get bookings starts after the given date
$event->bookingsCancelledBetween('2017-06-21 19:28:51', '2017-07-01 12:00:00')->get(); // Get bookings starts between the given dates

$event->bookingsByAgent($agent->id)->get(); // Get bookings by the given agent
$event->bookingsByCustomer($customer->id)->get(); // Get bookings by the given customer

// Get all rates
$event->rates;

// Get all availabilities
$event->availabilities;
```

All the above properties and methods are actually relationships, so you can call the raw relation methods and chain like any normal Eloquent relationship. E.g. `$event->bookings()->where('starts_at', '>', new \Carbon\Carbo())->first()`.

### Get bookings via agent/customer instance

Just like how you query your bookable models, you can query agent and/or customer models to retrieve booking related info so easily. Look at these examples:

```php
$event = \App\Models\Event::find(1);
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

$customer->bookingsOf(get_class($event))->get(); // Get bookings of the given model
$customer->isBooked($event); // Check if the person booked the given model
```

Same functionality applies for both agents and customers, and just like bookable models, all the above properties and methods are actually relationships, so you can call the raw relation methods and chain like any normal Eloquent relationship. E.g. `$customer->bookings()->where('starts_at', '>', new \Carbon\Carbo())->first()`.


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
