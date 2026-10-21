<?php declare(strict_types=1);

namespace ComponoKit\Types;

use ComponoKit\Types\Exceptions\InvalidStringException;
use ComponoKit\Types\Interfaces\RepresentsString;
use ComponoKit\Types\Traits\RepresentingString;

abstract class AbstractString implements RepresentsString
{
	use RepresentingString;

	public function __construct( private string $value )
	{
		$this->validate( $value );
	}

	abstract public static function isValid( string $value ): bool;

	public static function fromStringType( RepresentsString $type ): static
	{
		return new static( $type->toString() );
	}

	protected function validate( string $value ): void
	{
		if ( !static::isValid( $value ) )
		{
			throw (new InvalidStringException( 'Invalid ' . static::class ))->setContext( [ 'value' => $value, ] );
		}
	}
}
