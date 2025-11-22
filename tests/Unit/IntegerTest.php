<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit;

use ComponoKit\Types\AbstractInteger;
use ComponoKit\Types\Exceptions\InvalidIntegerException;
use ComponoKit\Types\IntegerType;
use ComponoKit\Types\Interfaces\RepresentsInteger;
use ComponoKit\Types\Tests\Unit\fakes\AnotherIntegerType;
use ComponoKit\Types\Tests\Unit\fakes\NoZeroIntegerType;
use ComponoKit\Types\Traits\RepresentingInteger;
use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{
	public function testInvalidValueThrowsException(): void
	{
		$this->expectExceptionObject( new InvalidIntegerException( 'Invalid ' . NoZeroIntegerType::class ) );

		new NoZeroIntegerType( 0 );
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class(12) extends AbstractInteger {
			public static function isValid( int $value ): bool
			{
				return true;
			}
		};
	}

	public function testFromIntegerType(): void
	{
		self::assertEquals( new IntegerType( 15 ), IntegerType::fromIntegerType( new AnotherIntegerType( 15 ) ) );
	}

	public function testEquals(): void
	{
		self::assertTrue( (new IntegerType( 12 ))->equals( new IntegerType( 12 ) ) );
		self::assertTrue( (new IntegerType( 0 ))->equals( new IntegerType( 0 ) ) );
		self::assertTrue( (new IntegerType( -100 ))->equals( new IntegerType( -100 ) ) );
		self::assertFalse( (new IntegerType( -12 ))->equals( new IntegerType( 12 ) ) );
		self::assertFalse( (new IntegerType( PHP_INT_MIN ))->equals( new IntegerType( PHP_INT_MAX ) ) );
		self::assertFalse( (new IntegerType( 2 ))->equals( new AnotherIntegerType( 2 ) ) );
	}

	public function testToNativeType(): void
	{
		self::assertSame( 7, (new IntegerType( 7 ))->toNativeType() );
	}

	public function testToInteger(): void
	{
		self::assertSame( 1, (new IntegerType( 1 ))->toInteger() );
		self::assertSame( -1, (new IntegerType( -1 ))->toInteger() );
		self::assertSame( 0, (new IntegerType( 0 ))->toInteger() );
	}

	public function testToFloat(): void
	{
		self::assertEquals( 1.0, (new IntegerType( 1 ))->toFloat() );
		self::assertEquals( -1.0, (new IntegerType( -1 ))->toFloat() );
		self::assertEquals( 0.0, (new IntegerType( 0 ))->toFloat() );
	}

	public function testToString(): void
	{
		self::assertSame( '1', (new IntegerType( 1 ))->toString() );
		self::assertSame( '-1', (new IntegerType( -1 ))->toString() );
		self::assertSame( '0', (new IntegerType( 0 ))->toString() );
	}

	public function testMagicToString(): void
	{
		self::assertSame( '1', (string)(new IntegerType( 1 )) );
		self::assertSame( '-1', (string)(new IntegerType( -1 )) );
		self::assertSame( '0', (string)(new IntegerType( 0 )) );
	}

	public static function ComparisonDataProvider(): array
	{
		return [
			[ 0, 0, false, true, false ],
			[ 0, 1, true, false, false ],
			[ -1, 0, true, false, false ],
			[ 1, 0, false, false, true ],
			[ 0, -1, false, false, true ],
			[ 0, PHP_INT_MIN, false, false, true ],
			[ PHP_INT_MIN, 0, true, false, false ],
			[ PHP_INT_MIN, PHP_INT_MAX, true, false, false ],
			[ PHP_INT_MIN, PHP_INT_MIN, false, true, false ],
			[ PHP_INT_MAX, PHP_INT_MAX, false, true, false ],
			[ PHP_INT_MAX, PHP_INT_MIN, false, false, true ],
		];
	}

	/**
	 * @dataProvider ComparisonDataProvider
	 **/
	public function testComparingIntegerTypes( int $integerValue, int $anotherIntegerValue, bool $isLess, bool $isEqual, bool $isGreater ): void
	{
		$integerType        = new IntegerType( $integerValue );
		$anotherIntegerType = new AnotherIntegerType( $anotherIntegerValue );

		self::assertEquals( $isLess, $integerType->isLessThan( $anotherIntegerType ) );
		self::assertEquals( $isLess || $isEqual, $integerType->isLessThanOrEqual( $anotherIntegerType ) );
		self::assertEquals( $isEqual, $integerType->isEqual( $anotherIntegerType ) );
		self::assertEquals( $isGreater, $integerType->isGreaterThan( $anotherIntegerType ) );
		self::assertEquals( $isGreater || $isEqual, $integerType->isGreaterThanOrEqual( $anotherIntegerType ) );

		self::assertEquals( $isLess, $integerType->isLessThan( $anotherIntegerType->toInteger() ) );
		self::assertEquals( $isLess || $isEqual, $integerType->isLessThanOrEqual( $anotherIntegerType->toInteger() ) );
		self::assertEquals( $isEqual, $integerType->isEqual( $anotherIntegerType->toInteger() ) );
		self::assertEquals( $isGreater, $integerType->isGreaterThan( $anotherIntegerType->toInteger() ) );
		self::assertEquals( $isGreater || $isEqual, $integerType->isGreaterThanOrEqual( $anotherIntegerType->toInteger() ) );
	}

	public function testIsZero(): void
	{
		self::assertTrue( (new IntegerType( 0 ))->isZero() );
		self::assertFalse( (new IntegerType( -1 ))->isZero() );
		self::assertFalse( (new IntegerType( 1 ))->isZero() );
	}

	public function testIsPositive(): void
	{
		self::assertFalse( (new IntegerType( 0 ))->isPositive() );
		self::assertFalse( (new IntegerType( -1 ))->isPositive() );
		self::assertTrue( (new IntegerType( 1 ))->isPositive() );
	}

	public function testIsNegative(): void
	{
		self::assertFalse( (new IntegerType( 0 ))->isNegative() );
		self::assertTrue( (new IntegerType( -1 ))->isNegative() );
		self::assertFalse( (new IntegerType( 1 ))->isNegative() );
	}

	public function testIsPositiveOrZero(): void
	{
		self::assertTrue( (new IntegerType( 0 ))->isPositiveOrZero() );
		self::assertFalse( (new IntegerType( -1 ))->isPositiveOrZero() );
		self::assertTrue( (new IntegerType( 1 ))->isPositiveOrZero() );
	}

	public function testIsNegativeOrZero(): void
	{
		self::assertTrue( (new IntegerType( 0 ))->isNegativeOrZero() );
		self::assertTrue( (new IntegerType( -1 ))->isNegativeOrZero() );
		self::assertFalse( (new IntegerType( 1 ))->isNegativeOrZero() );
	}

	public static function AddTestDataProvider(): array
	{
		return [
			[ new IntegerType( 0 ), new AnotherIntegerType( 0 ), new IntegerType( 0 ) ],
			[ new IntegerType( 0 ), new IntegerType( 5 ), new IntegerType( 5 ) ],
			[ new AnotherIntegerType( 5 ), new IntegerType( 0 ), new AnotherIntegerType( 5 ) ],
			[ new IntegerType( 5 ), new IntegerType( 5 ), new IntegerType( 10 ) ],
			[ new IntegerType( -5 ), new IntegerType( 10 ), new IntegerType( 5 ) ],
			[ new IntegerType( PHP_INT_MIN ), new IntegerType( PHP_INT_MAX ), new IntegerType( PHP_INT_MIN + PHP_INT_MAX ) ],
		];
	}

	/**
	 * @dataProvider AddTestDataProvider
	 **/
	public function testAdd( RepresentsInteger $integerType, RepresentsInteger $anotherIntegerType, RepresentsInteger $expectedIntegerType ): void
	{
		self::assertEquals( $expectedIntegerType, $integerType->add( $anotherIntegerType ) );
		self::assertEquals( $expectedIntegerType, $integerType->add( $anotherIntegerType->toInteger() ) );
	}

	public static function SubtractTestDataProvider(): array
	{
		return [
			[ new IntegerType( 0 ), new AnotherIntegerType( 0 ), new IntegerType( 0 ) ],
			[ new IntegerType( 0 ), new IntegerType( 5 ), new IntegerType( -5 ) ],
			[ new AnotherIntegerType( 5 ), new IntegerType( 0 ), new AnotherIntegerType( 5 ) ],
			[ new IntegerType( 5 ), new IntegerType( 5 ), new IntegerType( 0 ) ],
			[ new IntegerType( -5 ), new IntegerType( 10 ), new IntegerType( -15 ) ],
		];
	}

	/**
	 * @dataProvider SubtractTestDataProvider
	 **/
	public function testSubtract( RepresentsInteger $integerType, RepresentsInteger $anotherIntegerType, RepresentsInteger $expectedIntegerType ): void
	{
		self::assertEquals( $expectedIntegerType, $integerType->subtract( $anotherIntegerType ) );
		self::assertEquals( $expectedIntegerType, $integerType->subtract( $anotherIntegerType->toInteger() ) );
	}

	public static function MultiplyTestDataProvider(): array
	{
		return [
			[ new IntegerType( 0 ), new AnotherIntegerType( 0 ), new IntegerType( 0 ) ],
			[ new IntegerType( 0 ), new IntegerType( 5 ), new IntegerType( 0 ) ],
			[ new AnotherIntegerType( 5 ), new IntegerType( 0 ), new AnotherIntegerType( 0 ) ],
			[ new IntegerType( 5 ), new IntegerType( 5 ), new IntegerType( 25 ) ],
			[ new IntegerType( -5 ), new IntegerType( 10 ), new IntegerType( -50 ) ],
		];
	}

	/**
	 * @dataProvider MultiplyTestDataProvider
	 **/
	public function testMultiply( RepresentsInteger $integerType, RepresentsInteger $anotherIntegerType, RepresentsInteger $expectedIntegerType ): void
	{
		self::assertEquals( $expectedIntegerType, $integerType->multiply( $anotherIntegerType ) );
		self::assertEquals( $expectedIntegerType, $integerType->multiply( $anotherIntegerType->toInteger() ) );
	}

	public static function IncrementTestDataProvider(): array
	{
		return [
			[ new IntegerType( 0 ), 0, new IntegerType( 0 ) ],
			[ new IntegerType( 0 ), 5, new IntegerType( 5 ) ],
			[ new AnotherIntegerType( 5 ), 0, new AnotherIntegerType( 5 ) ],
			[ new IntegerType( 5 ), 5, new IntegerType( 10 ) ],
			[ new IntegerType( -5 ), 10, new IntegerType( 5 ) ],
			[ new IntegerType( -5 ), 1, new IntegerType( -4 ) ],
			[ new IntegerType( 0 ), 1, new IntegerType( 1 ) ],
		];
	}

	/**
	 * @dataProvider IncrementTestDataProvider
	 **/
	public function testIncrement( RepresentsInteger $integerType, int $value, RepresentsInteger $expectedIntegerType ): void
	{
		self::assertEquals( $expectedIntegerType, $integerType->increment( $value ) );
		self::assertEquals( $expectedIntegerType, $integerType->increment( new IntegerType( $value ) ) );
	}

	public static function DecrementTestDataProvider(): array
	{
		return [
			[ new IntegerType( 0 ), 0, new IntegerType( 0 ) ],
			[ new IntegerType( 0 ), 5, new IntegerType( -5 ) ],
			[ new AnotherIntegerType( 5 ), 0, new AnotherIntegerType( 5 ) ],
			[ new IntegerType( 5 ), 5, new IntegerType( 0 ) ],
			[ new IntegerType( -5 ), 10, new IntegerType( -15 ) ],
			[ new IntegerType( -5 ), 1, new IntegerType( -6 ) ],
			[ new IntegerType( 0 ), 1, new IntegerType( -1 ) ],
			[ new IntegerType( 1 ), 1, new IntegerType( 0 ) ],
		];
	}

	/**
	 * @dataProvider DecrementTestDataProvider
	 **/
	public function testDecrement( RepresentsInteger $integerType, int $value, RepresentsInteger $expectedIntegerType ): void
	{
		self::assertEquals( $expectedIntegerType, $integerType->decrement( $value ) );
		self::assertEquals( $expectedIntegerType, $integerType->decrement( new IntegerType( $value ) ) );
	}

	public function testJsonSerialize(): void
	{
		self::assertSame( '255', json_encode( new IntegerType( 255 ), JSON_THROW_ON_ERROR ) );
	}

	public function testInitializeClassUsingTrait(): void
	{
		$integerType = new class(2) {
			use RepresentingInteger;
		};

		self::assertEquals( 2, $integerType->toInteger() );
	}
}


