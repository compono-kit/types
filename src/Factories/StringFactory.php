<?php declare(strict_types=1);

namespace ComponoKit\Types\Factories;

use ComponoKit\Types\Interfaces\RepresentsString;
use ComponoKit\Types\Interfaces\Factories\BuildsStringTypes;
use ComponoKit\Types\StringType;

class StringFactory implements BuildsStringTypes
{
	public function build( string $value ): RepresentsString
	{
		return new StringType( $value );
	}
}
