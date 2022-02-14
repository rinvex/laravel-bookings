# Rinvex Bookings Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


## [v6.1.0] - 2022-02-14
- Update composer dependencies to Laravel v9
- Add support for model HasFactory
- Comment incomplete code (needs refactor #31)
- Update roadmap

## [v6.0.0] - 2021-08-22
- Drop PHP v7 support, and upgrade rinvex package dependencies to next major version
- Update composer dependencies
- Upgrade to GitHub-native Dependabot
- Enable StyleCI risky mode
- Merge rules instead of resetting, to allow adequate model override
- Fix constructor initialization order (fill attributes should come next after merging fillables & rules)
- Set validation rules in constructor for consistency & flexibility
- Drop old MySQL versions support that doesn't support json columns
- Define morphMany parameters explicitly

## [v5.0.1] - 2020-12-25
- Add support for PHP v8

## [v5.0.0] - 2020-12-22
- Upgrade to Laravel v8
- Update validation rules

## [v4.1.0] - 2020-06-15
- Update validation rules
- Drop using rinvex/laravel-cacheable from core packages for more flexibility
  - Caching should be handled on the application layer, not enforced from the core packages
- Drop PHP 7.2 & 7.3 support from travis

## [v4.0.6] - 2020-05-30
- Remove default indent size config
- Add strip_tags validation rule to string fields
- Specify events queue
- Explicitly specify relationship attributes
- Add strip_tags validation rule
- Explicitly define relationship name

## [v4.0.5] - 2020-04-12
- Fix ServiceProvider registerCommands method compatibility

## [v4.0.4] - 2020-04-09
- Tweak artisan command registration
- Reverse commit "Convert database int fields into bigInteger"
- Refactor publish command and allow multiple resource values

## [v4.0.3] - 2020-04-04
- Fix namespace issue

## [v4.0.2] - 2020-04-04
- Enforce consistent artisan command tag namespacing
- Enforce consistent package namespace
- Drop laravel/helpers usage as it's no longer used

## [v4.0.1] - 2020-03-20
- Convert into bigInteger database fields
- Add shortcut -f (force) for artisan publish commands
- Fix migrations path

## [v4.0.0] - 2020-03-15
- Upgrade to Laravel v7.1.x & PHP v7.4.x

## [v3.0.3] - 2020-03-13
- Tweak TravisCI config
- Add migrations autoload option to the package
- Tweak service provider `publishesResources`
- Remove indirect composer dependency
- Drop using global helpers
- Update StyleCI config

## [v3.0.2] - 2019-12-18
- Fix `migrate:reset` args as it doesn't accept --step

## [v3.0.1] - 2019-09-23
- Fix outdated package version

## [v3.0.0] - 2019-09-23
- Upgrade to Laravel v6 and update dependencies

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

[v6.1.0]: https://github.com/rinvex/laravel-bookings/compare/v6.0.0...v6.1.0
[v6.0.0]: https://github.com/rinvex/laravel-bookings/compare/v5.0.1...v6.0.0
[v5.0.1]: https://github.com/rinvex/laravel-bookings/compare/v5.0.0...v5.0.1
[v5.0.0]: https://github.com/rinvex/laravel-bookings/compare/v4.1.0...v5.0.0
[v4.1.0]: https://github.com/rinvex/laravel-bookings/compare/v4.0.6...v4.1.0
[v4.0.6]: https://github.com/rinvex/laravel-bookings/compare/v4.0.5...v4.0.6
[v4.0.5]: https://github.com/rinvex/laravel-bookings/compare/v4.0.4...v4.0.5
[v4.0.4]: https://github.com/rinvex/laravel-bookings/compare/v4.0.3...v4.0.4
[v4.0.3]: https://github.com/rinvex/laravel-bookings/compare/v4.0.2...v4.0.3
[v4.0.2]: https://github.com/rinvex/laravel-bookings/compare/v4.0.1...v4.0.2
[v4.0.1]: https://github.com/rinvex/laravel-bookings/compare/v4.0.0...v4.0.1
[v4.0.0]: https://github.com/rinvex/laravel-bookings/compare/v3.0.3...v4.0.0
[v3.0.3]: https://github.com/rinvex/laravel-bookings/compare/v3.0.2...v3.0.3
[v3.0.2]: https://github.com/rinvex/laravel-bookings/compare/v3.0.1...v3.0.2
[v3.0.1]: https://github.com/rinvex/laravel-bookings/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/rinvex/laravel-bookings/compare/v2.1.1...v3.0.0
[v2.1.1]: https://github.com/rinvex/laravel-bookings/compare/v2.1.0...v2.1.1
[v2.1.0]: https://github.com/rinvex/laravel-bookings/compare/v2.0.0...v2.1.0
[v2.0.0]: https://github.com/rinvex/laravel-bookings/compare/v1.0.1...v2.0.0
[v1.0.1]: https://github.com/rinvex/laravel-bookings/compare/v1.0.0...v1.0.1
[v1.0.0]: https://github.com/rinvex/laravel-bookings/compare/v0.0.3...v1.0.0
[v0.0.3]: https://github.com/rinvex/laravel-bookings/compare/v0.0.2...v0.0.3
[v0.0.2]: https://github.com/rinvex/laravel-bookings/compare/v0.0.1...v0.0.2
