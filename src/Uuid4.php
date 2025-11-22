<?php declare(strict_types=1);

namespace ComponoKit\Types;

class Uuid4 extends AbstractString
{
	public static function generate(): static
	{
		$data = random_bytes( 16 );

		$data[6] = chr( ord( $data[6] ) & 0x0f | 0x40 );
		$data[8] = chr( ord( $data[8] ) & 0x3f | 0x80 );

		return new static( vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split( bin2hex( $data ), 4 ) ) );
	}

	public static function isValid( string $value ): bool
	{
		if ( '00000000-0000-0000-0000-000000000000' === $value )
		{
			return true;
		}

		return preg_match( '!^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$!i', $value ) > 0;
	}
}
