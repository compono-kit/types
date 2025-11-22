<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit\fakes;

use ComponoKit\Types\AbstractFloat;

class NoZeroFloatType extends AbstractFloat
{
	public static function isValid( float $value ): bool
	{
		return $value !== 0.0;
	}
}
