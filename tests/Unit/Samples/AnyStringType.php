<?php declare(strict_types=1);

namespace Hansel23\Types\Tests\Unit\Samples;

use Hansel23\Types\AbstractStringType;

class AnyStringType extends AbstractStringType
{
	public static function isValid( string $value ): bool
	{
		return true;
	}
}
