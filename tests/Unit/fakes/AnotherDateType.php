<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit\fakes;

use ComponoKit\Types\AbstractDate;

class AnotherDateType extends AbstractDate
{
	public static function isValid( \DateTimeInterface $value ): bool
	{
		return true;
	}
}
