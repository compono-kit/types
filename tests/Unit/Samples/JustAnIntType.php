<?php declare(strict_types=1);

namespace Hansel23\Types\Tests\Unit\Samples;

use Hansel23\Types\AbstractIntType;

class JustAnIntType extends AbstractIntType
{
	public static function isValid( int $value ): bool
	{
		return true;
	}
}
