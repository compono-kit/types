<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit\Factories;

use ComponoKit\Types\BooleanType;
use ComponoKit\Types\Factories\BooleanFactory;
use PHPUnit\Framework\TestCase;

class BooleanFactoryTest extends TestCase
{
	public function testBuildingType(): void
	{
		$this->assertEquals( new BooleanType( true ), (new BooleanFactory())->build( true ) );
	}
}
