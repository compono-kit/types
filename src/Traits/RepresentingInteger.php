<?php declare(strict_types=1);

namespace ComponoKit\Types\Traits;

use ComponoKit\Types\Interfaces\RepresentsInteger;
use ComponoKit\Types\Interfaces\RepresentsType;

trait RepresentingInteger
{
	public function __construct( private int $value )
	{
	}

	public function equals( RepresentsType $type ): bool
	{
		/**
		 * @var RepresentsInteger $type
		 */
		return get_class( $this ) === get_class( $type ) && $this->isEqual( $type );
	}

	public function toNativeType(): int
	{
		return $this->value;
	}

	public function toInteger(): int
	{
		return $this->value;
	}

	public function toFloat(): float
	{
		return (float)$this->value;
	}

	public function toString(): string
	{
		return (string)$this->value;
	}

	public function __toString(): string
	{
		return $this->toString();
	}

	public function isGreaterThan( RepresentsInteger|int $value ): bool
	{
		return $this->value > $this->getValue( $value );
	}

	public function isGreaterThanOrEqual( RepresentsInteger|int $value ): bool
	{
		return $this->value >= $this->getValue( $value );
	}

	public function isLessThan( RepresentsInteger|int $value ): bool
	{
		return $this->value < $this->getValue( $value );
	}

	public function isLessThanOrEqual( RepresentsInteger|int $value ): bool
	{
		return $this->value <= $this->getValue( $value );
	}

	public function isEqual( RepresentsInteger|int $value ): bool
	{
		return $this->value === $this->getValue( $value );
	}

	public function isZero(): bool
	{
		return $this->value === 0;
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
		return $this->value >= 0;
	}

	public function isNegativeOrZero(): bool
	{
		return $this->value <= 0;
	}

	public function add( RepresentsInteger|int $value ): static
	{
		return new static( $this->value + $this->getValue( $value ) );
	}

	public function subtract( RepresentsInteger|int $value ): static
	{
		return new static( $this->value - $this->getValue( $value ) );
	}

	public function multiply( RepresentsInteger|int $value ): static
	{
		return new static( $this->value * $this->getValue( $value ) );
	}

	public function increment( RepresentsInteger|int $value = 1 ): static
	{
		return new static( $this->value + $this->getValue( $value ) );
	}

	public function decrement( RepresentsInteger|int $value = 1 ): static
	{
		return new static( $this->value - $this->getValue( $value ) );
	}

	public function jsonSerialize(): int
	{
		return $this->value;
	}

	protected function getValue( RepresentsInteger|int $value ): int
	{
		return is_int( $value ) ? $value : $value->toInteger();
	}
}
