# ComponoKit / Types

**Stark typisierte Value Objects für PHP 8+**

Dieses Paket bietet eine leichtgewichtige Grundlage für die Erstellung von *domain-driven*, *immutable* und *type-safe* Value Objects in PHP, die `compono-kit/types-interfaces` implementieren.
Jeder Typ implementiert seine eigenen Schnittstellen und kann direkt über Traits oder abstrakte Klassen verwendet werden.
Es ist auf Typsicherheit, Validierung und konsistente Methoden für Vergleich, Konvertierung und Manipulation ausgelegt.

100 % Codeabdeckung ohne sinnlose, von LLM generierte Tests!

---

## 🚀 Installation

```bash
composer require compono-kit/types
```

---

## Übersicht der Typen

| Typ         | Interface | Beschreibung                                                                                |
|-------------|-----------|---------------------------------------------------------------------------------------------|
| BooleanType | `RepresentsBoolean` | Repräsentiert einen booleschen Wert mit Konvertierung und Vergleichsmethoden                |
| IntegerType | `RepresentsInteger` | Repräsentiert einen Integer mit mathematischen und Vergleichsoperationen                    |
| FloatType   | `RepresentsFloat` | Repräsentiert einen Float mit mathematischen und Vergleichsoperationen                      |
| StringType  | `RepresentsString` | Repräsentiert einen String mit umfangreichen Methoden für Manipulation, Vergleich und Regex |
| DateType    | `RepresentsDate` | Repräsentiert ein Datum/Zeit mit Vergleich, Addition/Subtraktion und Formatierung           |
| Uuid4       | `RepresentsString` | Repräsentiert ein Uuid v4-Wert                                                              |
| Uuid7       | `RepresentsString` | Repräsentiert ein Uuid v7-Wert                                                              |

Alle Typen implementieren das Basistyp-Interface `RepresentsType`, welches unter anderem die Methoden `equals()` und `toNativeType()` bereitstellt.

---

## Architektur

Jeder Typ kann auf drei Arten genutzt werden:

1. **Trait** – Implementiert alle Interface-Methoden.
   Vorteil: Du kannst Traits in eigenen Klassen verwenden, ohne von der abstrakten Klasse abzuleiten.

   ```php
   class MyString {
       use RepresentingString;
   }

   $str = new MyString("Hello World");
   echo $str->toUpperCase(); // "HELLO WORLD"
   ```

2. **Abstrakte Klasse** – Nutzt den Trait und ergänzt eine abstrakte `isValid()`-Methode für Validierung
   Vorteil: Einfach erweiterbar für spezielle Typregeln.

   ```php
   abstract class AbstractEmail extends AbstractString {
       public static function isValid(string $value): bool {
           return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
       }
   }
   ```

3. **Default-Implementierung** – Standardimplementierung ohne spezielle Validierung
   Vorteil: Schnell verwendbar für generische Typen.

   ```php
   $string = new StringType("Hello World");
   echo $string->toUpperCase(); //HELLO WORLD
   ```

---

## Beispielnutzung

### String
```php
use YourNamespace\StringType;

$string = new StringType("Hello World");

echo $string->toLowerCase();   // "hello world"
echo $string->capitalizeFirst(); // "Hello World"
echo $string->contains("World");  // true
```

### Integer
```php
use YourNamespace\IntegerType;

$int = new IntegerType(42);

$int2 = $int->add(8); // 50
var_dump($int2->isPositive()); // true
```

### Float
```php
use YourNamespace\FloatType;

$float = new FloatType(3.14);

$float2 = $float->multiply(2); // 6.28
var_dump($float2->isGreaterThan(5.0)); // true
```

### Boolean
```php
use YourNamespace\BooleanType;

$bool = new BooleanType(true);

var_dump($bool->isTrue()); // true
var_dump($bool->toInteger()); // 1
```

### Date
```php
use YourNamespace\DateType;

$date = new DateType(new \DateTimeImmutable("2025-11-22"));

$tomorrow = $date->add(new \DateInterval('P1D'));
var_dump($tomorrow->isGreaterThan($date)); // true

$diff = $date->diff($tomorrow, false);
echo $diff->format('%d days'); // "1 days"
```

### UUID4
```php
use YourNamespace\Uuid4;

$uuid = Uuid4::generate();
echo $uuid->toString(); // z.B. "550e8400-e29b-41d4-a716-446655440000"
```

---

## Exceptions

Alle Typen nutzen eigene Exceptions, die von `RepresentsTypeException` abgeleitet sind.
---

## Zusammenfassung

- **Traits**: Vollständige Implementierung der Interfaces, nutzbar ohne abstrakte Klassen.
- **Abstrakte Klasse**: Traits + `isValid()`-Methode für eigene Validierungslogik.
- **Default-Typen**: Schneller Einstieg ohne eigene Validierung.
- **UUID4**: Spezialtyp von String mit generierbarer UUID.
- **Vorteile**: Typensicherheit, konsistente Methoden, einfache Erweiterbarkeit und Validierung.
