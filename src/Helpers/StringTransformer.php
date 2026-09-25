<?php declare(strict_types=1);

namespace ComponoKit\Types\Helpers;

final class StringTransformer
{
	public static function transformToKebabCase( \Stringable|string $value ): string
	{
		return (string)preg_replace(
			[ '/[^-\da-zA-Z]+/', '/-+/', ],
			[ '-', '-', ],
			self::isCamelCase( (string)$value ) ? self::transformFromCamelCase( (string)$value ) : $value
		);
	}

	public static function transformToSnakeCase( \Stringable|string $value ): string
	{
		return (string)preg_replace(
			[ '/\W+/', '/_+/', ],
			[ '_', '_', ],
			self::isCamelCase( (string)$value ) ? self::transformFromCamelCase( (string)$value ) : $value
		);
	}

	public static function transformToUpperCamelCase( \Stringable|string $value ): string
	{
		return self::toCamelCase( (string)$value, false );
	}

	public static function transformToLowerCamelCase( \Stringable|string $value ): string
	{
		return self::toCamelCase( (string)$value, true );
	}

	public static function transformToDotCase( \Stringable|string $value ): string
	{
		return (string)preg_replace(
			[ '/[^.\da-zA-Z]+/', '/\.+/', ],
			[ '.', '.', ],
			self::isCamelCase( (string)$value ) ? self::transformFromCamelCase( (string)$value ) : $value
		);
	}

	public static function transformToUpperKebabCase( \Stringable|string $value ): string
	{
		return strtoupper( self::transformToKebabCase( $value ) );
	}

	public static function transformToLowerKebabCase( \Stringable|string $value ): string
	{
		return strtolower( self::transformToKebabCase( $value ) );
	}

	public static function transformToUpperSnakeCase( \Stringable|string $value ): string
	{
		return strtoupper( self::transformToSnakeCase( $value ) );
	}

	public static function transformToLowerSnakeCase( \Stringable|string $value ): string
	{
		return strtolower( self::transformToSnakeCase( $value ) );
	}

	public static function transformToUpperDotCase( \Stringable|string $value ): string
	{
		return strtoupper( self::transformToDotCase( $value ) );
	}

	public static function transformToLowerDotCase( \Stringable|string $value ): string
	{
		return strtolower( self::transformToDotCase( $value ) );
	}

	private static function toCamelCase( string $value, bool $toLowerCamelCase ): string
	{
		$result = preg_match_all( '#[^-_\s]*#', $value, $matches, PREG_PATTERN_ORDER );

		if ( $result > 0 && count( $matches[0] ) > 2 )
		{
			$camelCaseString = '';
			foreach ( $matches[0] as $matchedValue )
			{
				if ( '' !== $matchedValue )
				{
					$camelCaseString .= ucfirst( strtolower( $matchedValue ) );
				}
			}

			return $toLowerCamelCase ? lcfirst( $camelCaseString ) : $camelCaseString;
		}

		$camelCaseValue = '';
		$lastCharUpper  = false;
		foreach ( str_split( $value ) as $index => $char )
		{
			if ( ctype_upper( $char ) )
			{
				if ( $lastCharUpper )
				{
					$camelCaseValue .= isset( $value[ $index + 1 ] ) && ctype_lower( $value[ $index + 1 ] ) ? $char : strtolower( $char );
				}
				else
				{
					$camelCaseValue .= $char;
				}

				$lastCharUpper = true;
			}
			else
			{
				$camelCaseValue .= $char;
				$lastCharUpper  = false;
			}
		}

		return $toLowerCamelCase ? lcfirst( $camelCaseValue ) : ucfirst( $camelCaseValue );
	}

	private static function isCamelCase( string $value ): bool
	{
		return preg_match( '/[a-z]*[A-Z][a-z]+/', $value ) > 0;
	}

	private static function transformFromCamelCase( string $value ): string
	{
		return preg_replace( '/(?<!^)([A-Z]+[a-z]*)/', ' $0', $value );
	}
}
