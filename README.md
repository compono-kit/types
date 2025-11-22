# ComponoKit / Types

**Strongly typed value objects for PHP 8+**

This package provides a lightweight foundation for building *domain-driven*, *immutable*, and *type-safe* value objects in PHP implementing `compono-kit/types-interfaces`.
Each type implements its own interfaces and can be used directly via traits or abstract classes. 
It is designed for type safety, validation, and consistent methods for comparison, conversion, and manipulation.

100% code coverage without senseless LLM generated tests!

## Requirements

* PHP 8+
* compono-kit/types-interfaces

---

## 🚀 Installation

```bash
composer require compono-kit/types
```

---

## Type Overview

| Type        | Interface | Description |
|-------------|-----------|--------------|
| BooleanType | `RepresentsBoolean` | Represents a boolean value with conversion and comparison methods |
| IntegerType | `RepresentsInteger` | Represents an integer with mathematical and comparison operations |
| FloatType   | `RepresentsFloat` | Represents a float with mathematical and comparison operations |
| StringType  | `RepresentsString` | Represents a string with extensive methods for manipulation, comparison, and regex |
| DateType    | `RepresentsDate` | Represents a date/time with comparison, addition/subtraction, and formatting |
| Uuid4       | `RepresentsString` | Represents a Uuid v4 value                                                |
| Uuid7       | `RepresentsString` | Represents a Uuid v7 value                                                |

All types implement the base interface `RepresentsType`, which includes methods such as `equals()` and `toNativeType()`.

---

## Architecture

Each type can be used in three ways:

1. **Trait** – Implements all interface methods.  
   Advantage: You can use traits in your own classes without inheriting the abstract class.

   ```php
   class MyString {
       use RepresentingString;
   }

   $str = new MyString("Hello World");
   echo $str->toUpperCase(); // "HELLO WORLD"
   ```

2. **Abstract Class** – Uses the trait and adds an abstract `isValid()` method for validation.  
   Advantage: Easily extendable for specific type rules.

   ```php
   abstract class AbstractEmail extends AbstractString {
       public static function isValid(string $value): bool {
           return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
       }
   }
   ```

3. **Default Implementation** – Standard implementation without special validation.  
   Advantage: Quick usage for generic types.

   ```php
   $string = new StringType("Hello World");
   echo $string->toUpperCase(); // "HELLO WORLD"
   ```

---

## Usage Examples

### String
```php
use ComponoKit\Types\StringType;

$string = new StringType("Hello World");

echo $string->toLowerCase();   // "hello world"
echo $string->capitalizeFirst(); // "Hello World"
echo $string->contains("World");  // true
```

### Integer
```php
use ComponoKit\Types\IntegerType;

$int = new IntegerType(42);

$int2 = $int->add(8); // 50
var_dump($int2->isPositive()); // true
```

### Float
```php
use ComponoKit\Types\FloatType;

$float = new FloatType(3.14);

$float2 = $float->multiply(2); // 6.28
var_dump($float2->isGreaterThan(5.0)); // true
```

### Boolean
```php
use ComponoKit\Types\BooleanType;

$bool = new BooleanType(true);

var_dump($bool->isTrue()); // true
var_dump($bool->toInteger()); // 1
```

### Date
```php
use ComponoKit\Types\DateType;

$date = new DateType(new \DateTimeImmutable("2025-11-22"));

$tomorrow = $date->add(new \DateInterval('P1D'));
var_dump($tomorrow->isGreaterThan($date)); // true

$diff = $date->diff($tomorrow, false);
echo $diff->format('%d days'); // "1 days"
```

### UUID4
```php
use ComponoKit\Types\Uuid4;

$uuid = Uuid4::generate();
echo $uuid->toString(); // e.g., "550e8400-e29b-41d4-a716-446655440000"
```

---

## Exceptions

All types use their own exceptions derived from `RepresentsTypeException`. 

---

## Summary

- **Traits**: Full interface implementation, usable without abstract classes.
- **Abstract Classes**: Traits + `isValid()` method for custom validation logic.
- **Default Types**: Quick start without custom validation.
- **UUID4**: Special string type with generated UUID.
- **Benefits**: Type safety, consistent methods, easy extensibility and validation.
