<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit;

use ComponoKit\Types\BooleanType;
use ComponoKit\Types\Tests\Unit\fakes\AnotherBooleanType;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{
	public function testEquals(): void
	{
		self::assertTrue( (new BooleanType( true ))->equals( new BooleanType( true ) ) );
		self::assertTrue( (new BooleanType( false ))->equals( new BooleanType( false ) ) );
		self::assertFalse( (new BooleanType( true ))->equals( new AnotherBooleanType( true ) ) );
	}

	public function testEqualsValue(): void
	{
		self::assertTrue( (new BooleanType( true ))->equalsValue( new AnotherBooleanType( true ) ) );
		self::assertTrue( (new BooleanType( false ))->equalsValue( new AnotherBooleanType( false ) ) );
		self::assertTrue( (new BooleanType( true ))->equalsValue( true ) );
		self::assertTrue( (new BooleanType( false ))->equalsValue( false ) );
	}

	public function testToNativeType(): void
	{
		self::assertEquals( true, (new BooleanType( true ))->toNativeType() );
		self::assertEquals( false, (new BooleanType( false ))->toNativeType() );
	}

	public function testToBoolean(): void
	{
		self::assertEquals( true, (new BooleanType( true ))->toBoolean() );
		self::assertEquals( false, (new BooleanType( false ))->toBoolean() );
	}

	public function testToInteger(): void
	{
		self::assertEquals( 1, (new BooleanType( true ))->toInteger() );
		self::assertEquals( 0, (new BooleanType( false ))->toInteger() );
	}

	public function testIsTrue(): void
	{
		self::assertTrue( (new BooleanType( true ))->isTrue() );
		self::assertFalse( (new BooleanType( false ))->isTrue() );
	}

	public function testIsFalse(): void
	{
		self::assertTrue( (new BooleanType( false ))->isFalse() );
		self::assertFalse( (new BooleanType( true ))->isFalse() );
	}

	public function testJsonSerialize(): void
	{
		self::assertEquals( 'true', json_encode( new BooleanType( true ) ) );
		self::assertEquals( 'false', json_encode( new BooleanType( false ) ) );
	}
}
