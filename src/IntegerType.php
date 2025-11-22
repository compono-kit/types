<?php declare(strict_types=1);

namespace ComponoKit\Types;

class IntegerType extends AbstractInteger
{
	public static function isValid( int $value ): bool
	{
		return true;
	}
}
