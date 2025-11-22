<?php declare(strict_types=1);

namespace ComponoKit\Types;

class Uuid7 extends AbstractString
{
	public static function generate(): static
	{
		$unixMillis = (int)(microtime( true ) * 1000);
		$timeHex    = str_pad( dechex( $unixMillis ), 12, '0', STR_PAD_LEFT );
		$rand       = random_bytes( 10 );
		$data       = hex2bin( $timeHex ) . $rand;
		$data[6]    = chr( (ord( $data[6] ) & 0x0F) | 0x70 );
		$data[8]    = chr( (ord( $data[8] ) & 0x3F) | 0x80 );
		$uuid       = vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split( bin2hex( $data ), 4 ) );

		return new static( $uuid );
	}

	public static function isValid( string $value ): bool
	{
		if ( '00000000-0000-0000-0000-000000000000' === $value )
		{
			return true;
		}

		return preg_match( '!^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$!i', $value ) > 0;
	}
}
