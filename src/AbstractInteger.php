<?php declare(strict_types=1);

namespace ComponoKit\Types;

use ComponoKit\Types\Exceptions\InvalidIntegerException;
use ComponoKit\Types\Interfaces\RepresentsInteger;
use ComponoKit\Types\Traits\RepresentingInteger;

abstract class AbstractInteger implements RepresentsInteger
{
	use RepresentingInteger;

	public function __construct( private int $value )
	{
		$this->validate( $value );
	}

	abstract public static function isValid( int $value ): bool;

	public static function fromIntegerType( RepresentsInteger $type ): static
	{
		return new static( $type->toInteger() );
	}

	protected function validate( int $value ): void
	{
		if ( !static::isValid( $value ) )
		{
			throw (new InvalidIntegerException( 'Invalid ' . static::class ))->setContext( [ 'value' => $value, ] );
		}
	}
}
