<?php declare(strict_types=1);

namespace Hansel23\Types\Tests\Unit\Samples;

use Hansel23\Types\AbstractFloatType;

class AnyFloatType extends AbstractFloatType
{
	public static function isValid( float $value ): bool
	{
		return true;
	}
}
