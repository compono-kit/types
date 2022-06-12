<?php declare(strict_types=1);

namespace Hansel23\Types\Tests\Unit\Samples;

use Hansel23\Types\AbstractArrayType;

class JustAnArrayType extends AbstractArrayType
{
	public static function isValid( array $genericArray ): bool
	{
		return true;
	}
}
