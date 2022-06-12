<?php declare(strict_types=1);

namespace Hansel23\Types;

use Hansel23\Types\Exceptions\ValidationException;
use Hansel23\Types\Interfaces\RepresentsStringType;
use Hansel23\Types\Traits\RepresentingStringType;

abstract class AbstractStringType implements RepresentsStringType
{
	use RepresentingStringType;

	public function __construct( string $value )
	{
		$this->validate( $value );

		$this->value = $value;
	}

	abstract public static function isValid( string $value ): bool;

	/**
	 * @param RepresentsStringType $type
	 *
	 * @return RepresentsStringType|static
	 */
	public static function fromStringType( RepresentsStringType $type ): RepresentsStringType
	{
		return new static( $type->toString() );
	}

	public function equals( RepresentsStringType $type ): bool
	{
		return get_class( $this ) === get_class( $type ) && $this->equalsValue( $type );
	}

	public function equalsValue( RepresentsStringType $type ): bool
	{
		return $this->toString() === $type->toString();
	}

	protected function validate( string $value ): void
	{
		if ( !static::isValid( $value ) )
		{
			throw new ValidationException(
				sprintf(
					'Invalid %s: %s',
					(new \ReflectionClass( get_called_class() ))->getShortName(),
					$value
				)
			);
		}
	}
}
