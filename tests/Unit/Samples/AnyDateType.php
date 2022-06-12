<?php declare(strict_types=1);

namespace Hansel23\Types\Tests\Unit\Samples;

use Hansel23\Types\AbstractDateType;

class AnyDateType extends AbstractDateType
{
	public static function isValid( \DateTimeInterface $value ): bool
	{
		return true;
	}
}
