<?php declare(strict_types=1);

namespace ComponoKit\Types\Traits;

use ComponoKit\Types\Interfaces\RepresentsFloat;
use ComponoKit\Types\Interfaces\RepresentsInteger;
use ComponoKit\Types\Interfaces\RepresentsType;

trait RepresentingFloat
{
	public function __construct( private float $value )
	{
	}

	public function equals( RepresentsType $type ): bool
	{
		/**
		 * @var RepresentsFloat $type
		 */
		return get_class( $type ) === get_class( $this ) && $this->isEqual( $type );
	}

	public function toNativeType(): float
	{
		return $this->value;
	}

	public function toFloat(): float
	{
		return $this->value;
	}

	public function toString( int $decimals = 0 ): string
	{
		return number_format( $this->value, $decimals, '.', '' );
	}

	public function __toString(): string
	{
		return (string)$this->value;
	}

	public function isGreaterThan( RepresentsFloat|float $value ): bool
	{
		return $this->value > $this->getValue( $value );
	}

	public function isGreaterThanOrEqual( RepresentsFloat|float $value ): bool
	{
		return $this->value >= $this->getValue( $value );
	}

	public function isLessThan( RepresentsFloat|float $value ): bool
	{
		return $this->value < $this->getValue( $value );
	}

	public function isLessThanOrEqual( RepresentsFloat|float $value ): bool
	{
		return $this->value <= $this->getValue( $value );
	}

	public function isEqual( RepresentsFloat|float $value ): bool
	{
		return $this->value === $this->getValue( $value );
	}

	public function isZero(): bool
	{
		return $this->value === 0.0;
	}

	public function isPositive(): bool
	{
		return $this->value > 0;
	}

	public function isNegative(): bool
	{
		return $this->value < 0;
	}

	public function isPositiveOrZero(): bool
	{
		return $this->value >= 0.0;
	}

	public function isNegativeOrZero(): bool
	{
		return $this->value <= 0.0;
	}

	public function add( float|RepresentsFloat|RepresentsInteger|int $value ): static
	{
		return new static( $this->value + $this->getValue( $value ) );
	}

	public function subtract( float|RepresentsFloat|RepresentsInteger|int $value ): static
	{
		return new static( $this->value - $this->getValue( $value ) );
	}

	public function multiply( float|RepresentsFloat|RepresentsInteger|int $value ): static
	{
		return new static( $this->value * $this->getValue( $value ) );
	}

	public function divide( float|RepresentsFloat|RepresentsInteger|int $value ): static
	{
		return new static( $this->value / $this->getValue( $value ) );
	}

	public function jsonSerialize(): float
	{
		return $this->value;
	}

	protected function getValue( RepresentsFloat|RepresentsInteger|int|float $value ): float
	{
		if ( $value instanceof RepresentsFloat || $value instanceof RepresentsInteger )
		{
			return $value->toFloat();
		}

		return (float)$value;
	}
}
