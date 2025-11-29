<?php declare(strict_types=1);

namespace ComponoKit\Types\Factories;

use ComponoKit\Types\BooleanType;
use ComponoKit\Types\Interfaces\Factories\BuildsBooleanTypes;
use ComponoKit\Types\Interfaces\RepresentsBoolean;

class BooleanFactory implements BuildsBooleanTypes
{
	public function build( bool $value ): RepresentsBoolean
	{
		return new BooleanType( $value );
	}
}
