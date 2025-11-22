<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit\fakes;

use ComponoKit\Types\AbstractString;

class AnotherStringType extends AbstractString
{
	public static function isValid( string $value ): bool
	{
		return true;
	}
}
