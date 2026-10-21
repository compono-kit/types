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
		return $this::class === $type::class && $this->equalsValue( $type );
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
		return new static( $this->trimMultiByte( $this->toString(), $characters ) );
	}

	public function replace( array|string|\Stringable $search, array|string|\Stringable $replace ): static
	{
		return new static( str_replace( is_array( $search ) ? $search : (string)$search, is_array( $replace ) ? $replace : (string)$replace, $this->toString() ) );
	}

	public function substring( int $offset, ?int $length = null ): static
	{
		return new static( mb_substr( $this->toString(), $offset, $length ) );
	}

	public function toLowerCase(): static
	{
		return new static( mb_strtolower( $this->toString() ) );
	}

	public function toUpperCase(): static
	{
		return new static( mb_strtoupper( $this->toString() ) );
	}

	public function capitalizeFirst(): static
	{
		return new static( mb_convert_case( mb_substr( $this->toString(), 0, 1 ), MB_CASE_TITLE ) . mb_substr( $this->toString(), 1 ) );
	}

	public function deCapitalizeFirst(): static
	{
		return new static( mb_strtolower( mb_substr( $this->toString(), 0, 1 ) ) . mb_substr( $this->toString(), 1 ) );
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

	public function matchRegularExpression( string|RepresentsString|\Stringable $pattern, ?array &$matches = null, int $flags = 0, int $offset = 0 ): bool
	{
		$result = @preg_match( (string)$pattern, $this->toString(), $matches, $flags, $offset );

		if ( false === $result )
		{
			throw new RegExException( 'Regular expression error: ' . (error_get_last()['message'] ?? preg_last_error_msg()) );
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

	/**
	 * Replacement of mb_trim(), which is available since PHP 8.4
	 */
	private function trimMultiByte( string $value, string $characters ): string
	{
		if ( '' === $characters )
		{
			return $value;
		}

		$characterClass = implode(
			'',
			array_map(
				static fn( string $character ): string => sprintf( '\x{%X}', mb_ord( $character ) ),
				mb_str_split( $characters )
			)
		);

		return (string)preg_replace( '/\A[' . $characterClass . ']+|[' . $characterClass . ']+\z/u', '', $value );
	}
}
