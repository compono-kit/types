<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit\Factories;

use ComponoKit\Types\Factories\StringFactory;
use ComponoKit\Types\StringType;
use PHPUnit\Framework\TestCase;

class StringFactoryTest extends TestCase
{
	public function testBuildingType(): void
	{
		$this->assertEquals( new StringType( 'test' ), (new StringFactory())->build( 'test' ) );
	}
}
