# Rinvex Bookings Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


## [v2.1.1] - 2019-06-03
- Enforce latest composer package versions

## [v2.1.0] - 2019-06-02
- Update composer deps
- Drop PHP 7.1 travis test
- Refactor migrations and artisan commands, and tweak service provider publishes functionality
- Fix outdated documentation

## [v2.0.0] - 2019-03-03
- Rename environment variable QUEUE_DRIVER to QUEUE_CONNECTION
- Require PHP 7.2 & Laravel 5.8
- Tweak and simplify FormRequest validations

## [v1.0.1] - 2018-12-22
- Add missing use statement
- Update composer dependencies
- Add PHP 7.3 support to travis
- Fix MySQL / PostgreSQL json column compatibility

## [v1.0.0] - 2018-10-01
- Enforce Consistency
- Support Laravel 5.7+
- Rename package to rinvex/laravel-bookings

## [v0.0.3] - 2018-09-22
- Update travis php versions
- Define polymorphic relationship parameters explicitly
- Rename booking polymorphic relation "user" to "customer"
- Fix fully qualified booking unit methods
- Move Bookable abstract model from the module cortex/bookings
- Fix few readme typos
- Tweak few things
- Change bookable price to base cost and unit cost
- Refactor bookable fields
- Refactor rates, availabilities and add bookable addons
- Enforce naming conventions consistency
- Delete bookings on bookable resource or resource owner deletion
- Refactor bookings!
- Tweak validation rules
- Apply few tweaks
- Drop StyleCI multi-language support (paid feature now!)
- Update composer dependencies
- Prepare and tweak testing configuration
- Drop service addons for now
- Rename cancelled_at to canceled_at
- Add actual_paid field to bookings
- Update bookable rates and availability database structure
- Fix wrong range values
- Refactor and simplify booking price calculation
- Refactor booking attributes
- Update StyleCI options
- Add quantity field, rename actual_paid to total_paid and "use" unit
- Fix wrong db table name
- Update PHPUnit options
- Rename model activation and deactivation methods

## [v0.0.2] - 2018-02-18
- Update supplementary files
- Update composer depedencies
- Major refactor for simplicity & flexibility
- Radical refactor for better pricing features & enforced consistency
- Rewrite price calculation, relationships, booking functionality & enforce consistency
- Make "between" scopes inclusive
- Add start/end between scope
- Rename `between` scope to `range` and include full day events in query results
- Require booking start & end dates
- Add Rollback Console Command
- Add PHPUnitPrettyResultPrinter
- Use Carbon global helper
- Typehint method returns
- Drop useless model contracts (models already swappable through IoC)
- Add Laravel v5.6 support
- Simplify IoC binding
- Fix wrong database table names
- Add force option to artisan commands
- Define abstract morphMany method on trait
- Rename BookingsCustomer trait to HasBookings
- Rename polymorphic relation customer to user
- Drop Laravel 5.5 support
- Convert unit column data type into string from character

## v0.0.1 - 2017-09-08
- Tag first release

[v2.1.1]: https://github.com/rinvex/laravel-bookings/compare/v2.1.0...v2.1.1
[v2.1.0]: https://github.com/rinvex/laravel-bookings/compare/v2.0.0...v2.1.0
[v2.0.0]: https://github.com/rinvex/laravel-bookings/compare/v1.0.1...v2.0.0
[v1.0.1]: https://github.com/rinvex/laravel-bookings/compare/v1.0.0...v1.0.1
[v1.0.0]: https://github.com/rinvex/laravel-bookings/compare/v0.0.3...v1.0.0
[v0.0.3]: https://github.com/rinvex/laravel-bookings/compare/v0.0.2...v0.0.3
[v0.0.2]: https://github.com/rinvex/laravel-bookings/compare/v0.0.1...v0.0.2
