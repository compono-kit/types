<?php declare(strict_types=1);

namespace ComponoKit\Types;

class FloatType extends AbstractFloat
{
	public static function isValid( float $value ): bool
	{
		return true;
	}
}
