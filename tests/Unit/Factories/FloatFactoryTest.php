<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit\Factories;

use ComponoKit\Types\Factories\FloatFactory;
use ComponoKit\Types\FloatType;
use PHPUnit\Framework\TestCase;

class FloatFactoryTest extends TestCase
{
	public function testBuildingType(): void
	{
		$this->assertEquals( new FloatType( 3.5 ), (new FloatFactory())->build( 3.5 ) );
	}
}
