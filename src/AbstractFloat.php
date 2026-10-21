<?php declare(strict_types=1);

namespace ComponoKit\Types;

use ComponoKit\Types\Exceptions\InvalidFloatException;
use ComponoKit\Types\Interfaces\RepresentsFloat;
use ComponoKit\Types\Traits\RepresentingFloat;

abstract class AbstractFloat implements RepresentsFloat
{
	use RepresentingFloat;

	public function __construct( private float $value )
	{
		$this->validate( $value );
	}

	abstract public static function isValid( float $value ): bool;

	public static function fromFloatType( RepresentsFloat $type ): static
	{
		return new static( $type->toFloat() );
	}

	protected function validate( float $value ): void
	{
		if ( !static::isValid( $value ) )
		{
			throw (new InvalidFloatException( 'Invalid ' . static::class ))->setContext( [ 'value' => $value, ] );
		}
	}
}
