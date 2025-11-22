<?php declare(strict_types=1);

namespace ComponoKit\Types\Traits;

use ComponoKit\Types\Interfaces\RepresentsBoolean;
use ComponoKit\Types\Interfaces\RepresentsType;

trait RepresentingBoolean
{
	public function __construct( private bool $value )
	{
	}

	public function equals( RepresentsType $type ): bool
	{
		/**
		 * @var RepresentsBoolean $type
		 */
		return get_class( $this ) === get_class( $type ) && $this->equalsValue( $type );
	}

	public function equalsValue( bool|RepresentsBoolean $value ): bool
	{
		return $this->toBoolean() === (is_bool( $value ) ? $value : $value->toBoolean());
	}

	public function toNativeType(): bool
	{
		return $this->value;
	}

	public function toBoolean(): bool
	{
		return $this->value;
	}

	public function toInteger(): int
	{
		return (int)$this->value;
	}

	public function isTrue(): bool
	{
		return $this->value;
	}

	public function isFalse(): bool
	{
		return !$this->value;
	}

	public function jsonSerialize(): bool
	{
		return $this->value;
	}
}
