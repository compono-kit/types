<?php declare(strict_types=1);

namespace ComponoKit\Types\Traits;

use ComponoKit\Types\Exceptions\RegExException;
use ComponoKit\Types\Interfaces\RepresentsString;
use ComponoKit\Types\Interfaces\RepresentsType;

trait RepresentingString
{
	public function __construct( private string $value )
	{
	}

	public function equals( RepresentsType $type ): bool
	{
		return get_class( $this ) === get_class( $type ) && $this->equalsValue( $type );
	}

	public function equalsValue( string|\Stringable $value ): bool
	{
		return $this->toString() === (is_string( $value ) ? $value : (string)$value);
	}

	public function toNativeType(): string
	{
		return $this->value;
	}

	public function toString(): string
	{
		return $this->value;
	}

	public function __toString(): string
	{
		return $this->value;
	}

	public function isEmpty(): bool
	{
		return $this->value === '';
	}

	public function getByteLength(): int
	{
		return strlen( $this->toString() );
	}

	public function countChars(): int
	{
		return grapheme_strlen( $this->toString() );
	}

	public function trim( string $characters = " \n\r\t\v\x00" ): static
	{
		return new static( trim( $this->toString(), $characters ) );
	}

	public function replace( array|string|\Stringable $search, array|string|\Stringable $replace ): static
	{
		return new static( str_replace( is_array( $search ) ? $search : (string)$search, is_array( $replace ) ? $replace : (string)$replace, $this->toString() ) );
	}

	public function substring( int $offset, ?int $length = null ): static
	{
		return new static( substr( $this->toString(), $offset, $length ) );
	}

	public function toLowerCase(): static
	{
		return new static( strtolower( $this->toString() ) );
	}

	public function toUpperCase(): static
	{
		return new static( strtoupper( $this->toString() ) );
	}

	public function capitalizeFirst(): static
	{
		return new static( ucfirst( $this->toString() ) );
	}

	public function deCapitalizeFirst(): static
	{
		return new static( lcfirst( $this->toString() ) );
	}

	/**
	 * @param string $delimiter
	 *
	 * @return \Iterator<int, static>
	 */
	public function split( string $delimiter ): \Iterator
	{
		foreach ( $this->splitNative( $delimiter ) as $value )
		{
			yield new static( $value );
		}
	}

	/**
	 * @param string $delimiter
	 *
	 * @return string[]
	 */
	public function splitNative( string $delimiter ): array
	{
		return explode( $delimiter, $this->toString() );
	}

	public function matchRegularExpression( string|RepresentsString|\Stringable $pattern, &$matches = null, int $flags = 0, int $offset = 0 ): bool
	{
		$result = @preg_match( (string)$pattern, $this->toString(), $matches, $flags, $offset );

		if ( false === $result )
		{
			throw new RegExException( 'Regular expression error: ' . error_get_last()['message'] );
		}

		return $result > 0;
	}

	public function contains( string|RepresentsString|\Stringable $needle ): bool
	{
		return str_contains( $this->toString(), (string)$needle );
	}

	public function containsOneOf( string|RepresentsString|\Stringable...$values ): bool
	{
		foreach ( $values as $value )
		{
			if ( $this->contains( $value ) )
			{
				return true;
			}
		}

		return false;
	}

	public function isOneOf( string|RepresentsString|\Stringable...$values ): bool
	{
		foreach ( $values as $value )
		{
			if ( $this->value === (string)$value )
			{
				return true;
			}
		}

		return false;
	}

	public function jsonSerialize(): string
	{
		return $this->value;
	}
}
