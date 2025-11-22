<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit\fakes;

use ComponoKit\Types\AbstractInteger;

class AnotherIntegerType extends AbstractInteger
{
	public static function isValid( int $value ): bool
	{
		return true;
	}
}
