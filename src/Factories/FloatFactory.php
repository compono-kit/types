<?php declare(strict_types=1);

namespace ComponoKit\Types\Factories;

use ComponoKit\Types\FloatType;
use ComponoKit\Types\Interfaces\RepresentsFloat;
use ComponoKit\Types\Interfaces\Factories\BuildsFloatTypes;

class FloatFactory implements BuildsFloatTypes
{
	public function build( float $value ): RepresentsFloat
	{
		return new FloatType( $value );
	}
}
