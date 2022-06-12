<?php declare(strict_types=1);

namespace Hansel23\Types\Tests\Unit\Samples;

use Hansel23\Types\AbstractFloatType;

class NoZeroFloatType extends AbstractFloatType
{
	public static function isValid( float $value ): bool
	{
		return $value !== 0.0;
	}
}
