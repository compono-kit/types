<?php declare(strict_types=1);

namespace ComponoKit\Types;

class DateType extends AbstractDate
{
	public static function isValid( \DateTimeInterface $value ): bool
	{
		return true;
	}
}
