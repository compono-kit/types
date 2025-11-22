<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit\fakes;

use ComponoKit\Types\AbstractDate;

class After2000DateType extends AbstractDate
{
	public static function isValid( \DateTimeInterface $value ): bool
	{
		return $value->getTimestamp() - (new \DateTimeImmutable( '2000-01-01 00:00:00' ))->getTimestamp() >= 0;
	}
}
