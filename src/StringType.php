<?php declare(strict_types=1);

namespace ComponoKit\Types;

class StringType extends AbstractString
{
	public static function isValid( string $value ): bool
	{
		return true;
	}
}
