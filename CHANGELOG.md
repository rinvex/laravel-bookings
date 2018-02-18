# Rinvex Bookings Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


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

[v0.0.2]: https://github.com/rinvex/bookings/compare/v0.0.1...v0.0.2
