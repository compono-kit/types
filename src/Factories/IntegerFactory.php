<?php declare(strict_types=1);

namespace ComponoKit\Types\Factories;

use ComponoKit\Types\IntegerType;
use ComponoKit\Types\Interfaces\RepresentsInteger;
use ComponoKit\Types\Interfaces\Factories\BuildsIntegerTypes;

class IntegerFactory implements BuildsIntegerTypes
{
	public function build( int $value ): RepresentsInteger
	{
		return new IntegerType( $value );
	}
}
