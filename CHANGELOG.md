# CHANGELOG

## [dev-php/8.0]

### Changed
* Maintenance branch of 2.0.0 for PHP 8.0, receives fixes only
* Requires PHP 8.0, `compono-kit/types-interfaces` dev-php/8.0 and for development PHPUnit 9.6
* `DEFAULT_FORMAT` and `LOSSLESS_FORMAT` are declared in `AbstractDate` instead of the trait `RepresentingDate`, because traits cannot declare constants before PHP 8.2
* Properties are not `readonly` and constants are not typed
* `trim()`, `capitalizeFirst()` and `deCapitalizeFirst()` of strings do not use `mb_trim()`, `mb_ucfirst()` and `mb_lcfirst()` of PHP 8.4
* `fromTimestamp()` of dates does not use `DateTimeImmutable::createFromTimestamp()` of PHP 8.4

## [2.0.0] - 2026-10-14

### Changed
* [BC] Requires PHP 8.5 and `compono-kit/types-interfaces` ^2.0
* [BC] Values of integer, float, string, boolean and date types are stored in `readonly` properties
* [BC] String types are multibyte safe: `trim()`, `substring()`, `toLowerCase()`, `toUpperCase()`, `capitalizeFirst()` and `deCapitalizeFirst()` use `mb_trim()`, `mb_substr()`, `mb_strtolower()`, `mb_strtoupper()`, `mb_ucfirst()` and `mb_lcfirst()`; character ranges like `a..z` are no longer supported by `trim()`
* [BC] The date constructor expects a `string` instead of `?string` for `$dateTime`
* [BC] `AbstractDate::fromTimestamp()` returns the date in the given time zone instead of UTC
* Comparing a date with an invalid date string throws `InvalidDateException` instead of `DateMalformedStringException`
* `equals()` compares classes with `::class` instead of `get_class()`
* `StringTransformer` uses the pipe operator

### Added
* Requirement of `ext-mbstring`
* `AbstractDate::DEFAULT_FORMAT`
* `Uuid4::NIL` and `Uuid7::NIL`

### Fixed
* `hasExpired()` compared a `RepresentsDate` reference time always as not expired
* `add()` and `sub()` of date types lost the default format and microseconds
* `AbstractDate::fromDate()` lost microseconds
* `matchRegularExpression()` failed with a type error if no last error was available
* `StringTransformer` could return `null` for camel case values if `preg_replace()` failed

### Removed
* Requirement of `ext-json`, which is always available since PHP 8.0
