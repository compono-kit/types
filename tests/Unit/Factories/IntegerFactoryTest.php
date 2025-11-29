<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit\Factories;

use ComponoKit\Types\Factories\IntegerFactory;
use ComponoKit\Types\IntegerType;
use PHPUnit\Framework\TestCase;

class IntegerFactoryTest extends TestCase
{
	public function testBuildingType(): void
	{
		$this->assertEquals( new IntegerType( 3 ), (new IntegerFactory())->build( 3 ) );
	}
}
