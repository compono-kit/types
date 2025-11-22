<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit;

use ComponoKit\Types\AbstractFloat;
use ComponoKit\Types\Exceptions\InvalidFloatException;
use ComponoKit\Types\FloatType;
use ComponoKit\Types\IntegerType;
use ComponoKit\Types\Interfaces\RepresentsFloat;
use ComponoKit\Types\Interfaces\RepresentsInteger;
use ComponoKit\Types\Tests\Unit\fakes\AnotherFloatType;
use ComponoKit\Types\Tests\Unit\fakes\NoZeroFloatType;
use ComponoKit\Types\Traits\RepresentingFloat;
use PHPUnit\Framework\TestCase;

class FloatTest extends TestCase
{
	public function testInvalidValueThrowsException(): void
	{
		$this->expectExceptionObject( new InvalidFloatException( 'Invalid ' . NoZeroFloatType::class ) );

		new NoZeroFloatType( 0 );
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class(12) extends AbstractFloat {
			public static function isValid( float $value ): bool
			{
				return true;
			}
		};
	}

	public function testFromFloatType(): void
	{
		self::assertEquals( new FloatType( 15.5 ), FloatType::fromFloatType( new AnotherFloatType( 15.5 ) ) );
	}

	public function testEquals(): void
	{
		self::assertTrue( (new FloatType( 51.5 ))->equals( new FloatType( 51.5 ) ) );
		self::assertFalse( (new FloatType( 51.5 ))->equals( new FloatType( 51.0 ) ) );
		self::assertFalse( (new FloatType( 51.5 ))->equals( new AnotherFloatType( 51.5 ) ) );
	}

	public function testToNativeType(): void
	{
		self::assertSame( 7.4, (new FloatType( 7.4 ))->toNativeType() );
	}

	public function testToFloat(): void
	{
		self::assertSame( 1.0, (new FloatType( 1 ))->toFloat() );
		self::assertSame( -1.0, (new FloatType( -1 ))->toFloat() );
		self::assertSame( 0.0, (new FloatType( 0 ))->toFloat() );
		self::assertSame( 0.5, (new FloatType( 0.5 ))->toFloat() );
		self::assertSame( -1.75, (new FloatType( -1.75 ))->toFloat() );
	}

	public function testToString(): void
	{
		self::assertSame( '1', (new FloatType( 1 ))->toString() );
		self::assertSame( '-1.0', (new FloatType( -1 ))->toString( 1 ) );
		self::assertSame( '0', (new FloatType( 0 ))->toString() );
		self::assertSame( '0.50', (new FloatType( 0.5 ))->toString( 2 ) );
		self::assertSame( '-1.75', (new FloatType( -1.75 ))->toString( 2 ) );
		self::assertSame( '-2', (new FloatType( -1.75 ))->toString() );
		self::assertSame( '-1.255', (new FloatType( -1.255 ))->toString( 3 ) );
		self::assertSame( '-1.26', (new FloatType( -1.255 ))->toString( 2 ) );
		self::assertSame( '-1.2550', (new FloatType( -1.255 ))->toString( 4 ) );
	}

	public function testMagicToString(): void
	{
		self::assertSame( '1', (string)(new FloatType( 1 )) );
		self::assertSame( '-1', (string)(new FloatType( -1 )) );
		self::assertSame( '0', (string)(new FloatType( 0 )) );
		self::assertSame( '0.5', (string)(new FloatType( 0.5 )) );
		self::assertSame( '-1.75', (string)(new FloatType( -1.75 )) );
		self::assertSame( '-1.255', (string)(new FloatType( -1.255 )) );
	}

	public static function ComparisonDataProvider(): array
	{
		return [
			[ 0, 0, false, true, false ],
			[ 0, 1, true, false, false ],
			[ -1, 0, true, false, false ],
			[ 1, 0, false, false, true ],
			[ 0, -1, false, false, true ],
			[ 0, 0.1, true, false, false ],
			[ 0, -0.1, false, false, true ],
			[ 0.25, 0.26, true, false, false ],
			[ 0.25, 0.25, false, true, false ],
			[ 0.25, 0.24, false, false, true ],
			[ 0, PHP_INT_MIN, false, false, true ],
			[ PHP_INT_MIN, PHP_INT_MIN, false, true, false ],
			[ PHP_INT_MAX, PHP_INT_MAX, false, true, false ],
			[ PHP_INT_MIN, 0, true, false, false ],
			[ PHP_INT_MIN, PHP_INT_MAX, true, false, false ],
			[ PHP_INT_MAX, PHP_INT_MIN, false, false, true ],
		];
	}

	/**
	 * @dataProvider ComparisonDataProvider
	 **/
	public function testComparingFloatTypes( float $originalFloatValue, float $anotherFloatValue, bool $isLess, bool $isEqual, bool $isGreater ): void
	{
		$originalFloatType = new FloatType( $originalFloatValue );
		$anotherFloatType  = new AnotherFloatType( $anotherFloatValue );

		self::assertEquals( $isLess, $originalFloatType->isLessThan( $anotherFloatType ) );
		self::assertEquals( $isLess || $isEqual, $originalFloatType->isLessThanOrEqual( $anotherFloatType ) );
		self::assertEquals( $isEqual, $originalFloatType->isEqual( $anotherFloatType ) );
		self::assertEquals( $isGreater, $originalFloatType->isGreaterThan( $anotherFloatType ) );
		self::assertEquals( $isGreater || $isEqual, $originalFloatType->isGreaterThanOrEqual( $anotherFloatType ) );

		self::assertEquals( $isLess, $originalFloatType->isLessThan( $anotherFloatType->toFloat() ) );
		self::assertEquals( $isLess || $isEqual, $originalFloatType->isLessThanOrEqual( $anotherFloatType->toFloat() ) );
		self::assertEquals( $isEqual, $originalFloatType->isEqual( $anotherFloatType->toFloat() ) );
		self::assertEquals( $isGreater, $originalFloatType->isGreaterThan( $anotherFloatType->toFloat() ) );
		self::assertEquals( $isGreater || $isEqual, $originalFloatType->isGreaterThanOrEqual( $anotherFloatType->toFloat() ) );
	}

	public function testIsZero(): void
	{
		self::assertTrue( (new FloatType( 0 ))->isZero() );
		self::assertFalse( (new FloatType( -0.1 ))->isZero() );
		self::assertFalse( (new FloatType( 0.1 ))->isZero() );
	}

	public function testIsPositive(): void
	{
		self::assertFalse( (new FloatType( 0 ))->isPositive() );
		self::assertFalse( (new FloatType( -0.1 ))->isPositive() );
		self::assertTrue( (new FloatType( 0.1 ))->isPositive() );
	}

	public function testIsNegative(): void
	{
		self::assertFalse( (new FloatType( 0 ))->isNegative() );
		self::assertTrue( (new FloatType( -0.1 ))->isNegative() );
		self::assertFalse( (new FloatType( 0.1 ))->isNegative() );
	}

	public function testIsPositiveOrZero(): void
	{
		self::assertTrue( (new FloatType( 0 ))->isPositiveOrZero() );
		self::assertFalse( (new FloatType( -0.1 ))->isPositiveOrZero() );
		self::assertTrue( (new FloatType( 0.1 ))->isPositiveOrZero() );
	}

	public function testIsNegativeOrZero(): void
	{
		self::assertTrue( (new FloatType( 0 ))->isNegativeOrZero() );
		self::assertTrue( (new FloatType( -0.1 ))->isNegativeOrZero() );
		self::assertFalse( (new FloatType( 0.1 ))->isNegativeOrZero() );
	}

	public static function AddTestDataProvider(): array
	{
		return [
			[ new FloatType( 0 ), new AnotherFloatType( 0 ), new FloatType( 0 ) ],
			[ new FloatType( 0 ), new FloatType( 5 ), new FloatType( 5 ) ],
			[ new AnotherFloatType( 5 ), new FloatType( 0 ), new AnotherFloatType( 5 ) ],
			[ new FloatType( 5 ), new FloatType( 5 ), new FloatType( 10 ) ],
			[ new FloatType( -5 ), new FloatType( 10 ), new FloatType( 5 ) ],
		];
	}

	/**
	 * @dataProvider AddTestDataProvider
	 **/
	public function testAdd( RepresentsFloat $originalFloatType, RepresentsFloat|RepresentsInteger $anotherType, RepresentsFloat $expectedFloatType ): void
	{
		self::assertEquals( $expectedFloatType, $originalFloatType->add( $anotherType ) );
		self::assertEquals( $expectedFloatType, $originalFloatType->add( $anotherType->toFloat() ) );

		if ( $anotherType instanceof RepresentsInteger )
		{
			self::assertEquals( $expectedFloatType, $originalFloatType->add( $anotherType->toInteger() ) );
		}
	}

	public static function SubtractTestDataProvider(): array
	{
		return [
			[ new FloatType( 0 ), new AnotherFloatType( 0 ), new FloatType( 0 ) ],
			[ new FloatType( 0 ), new IntegerType( 0 ), new FloatType( 0 ) ],
			[ new FloatType( 0 ), new FloatType( 5 ), new FloatType( -5 ) ],
			[ new FloatType( 0 ), new IntegerType( 5 ), new FloatType( -5 ) ],
			[ new AnotherFloatType( 5 ), new FloatType( 0 ), new AnotherFloatType( 5 ) ],
			[ new AnotherFloatType( 5 ), new IntegerType( 0 ), new AnotherFloatType( 5 ) ],
			[ new FloatType( 5 ), new FloatType( 5 ), new FloatType( 0 ) ],
			[ new FloatType( 5 ), new IntegerType( 5 ), new FloatType( 0 ) ],
			[ new FloatType( -5 ), new FloatType( 10 ), new FloatType( -15 ) ],
			[ new FloatType( -5 ), new IntegerType( 10 ), new FloatType( -15 ) ],
		];
	}

	/**
	 * @dataProvider SubtractTestDataProvider
	 **/
	public function testSubtract( RepresentsFloat $originalFloatType, RepresentsFloat|RepresentsInteger $anotherType, RepresentsFloat $expectedFloatType ): void
	{
		self::assertEquals( $expectedFloatType, $originalFloatType->subtract( $anotherType ) );
		self::assertEquals( $expectedFloatType, $originalFloatType->subtract( $anotherType->toFloat() ) );

		if ( $anotherType instanceof RepresentsInteger )
		{
			self::assertEquals( $expectedFloatType, $originalFloatType->subtract( $anotherType->toInteger() ) );
		}
	}

	public static function MultiplyTestDataProvider(): array
	{
		return [
			[ new FloatType( 0 ), new AnotherFloatType( 0 ), new FloatType( 0 ) ],
			[ new FloatType( 0 ), new IntegerType( 0 ), new FloatType( 0 ) ],
			[ new FloatType( 0 ), new FloatType( 5 ), new FloatType( 0 ) ],
			[ new FloatType( 0 ), new IntegerType( 5 ), new FloatType( 0 ) ],
			[ new AnotherFloatType( 5 ), new FloatType( 0 ), new AnotherFloatType( 0 ) ],
			[ new AnotherFloatType( 5 ), new IntegerType( 0 ), new AnotherFloatType( 0 ) ],
			[ new FloatType( 5 ), new FloatType( 5 ), new FloatType( 25 ) ],
			[ new FloatType( 5 ), new IntegerType( 5 ), new FloatType( 25 ) ],
			[ new FloatType( -5 ), new FloatType( 10 ), new FloatType( -50 ) ],
			[ new FloatType( -5 ), new IntegerType( 10 ), new FloatType( -50 ) ],
		];
	}

	/**
	 * @dataProvider MultiplyTestDataProvider
	 **/
	public function testMultiply( RepresentsFloat $originalFloatType, RepresentsFloat|RepresentsInteger $anotherType, RepresentsFloat $expectedFloatType ): void
	{
		self::assertEquals( $expectedFloatType, $originalFloatType->multiply( $anotherType ) );
		self::assertEquals( $expectedFloatType, $originalFloatType->multiply( $anotherType->toFloat() ) );

		if ( $anotherType instanceof RepresentsInteger )
		{
			self::assertEquals( $expectedFloatType, $originalFloatType->multiply( $anotherType->toInteger() ) );
		}
	}

	public static function DivideTestDataProvider(): array
	{
		return [
			[ new FloatType( 0 ), new FloatType( 5 ), new FloatType( 0 ) ],
			[ new FloatType( 0 ), new IntegerType( 5 ), new FloatType( 0 ) ],
			[ new AnotherFloatType( 5 ), new FloatType( 5 ), new AnotherFloatType( 1 ) ],
			[ new AnotherFloatType( 5 ), new IntegerType( 5 ), new AnotherFloatType( 1 ) ],
			[ new FloatType( 25 ), new FloatType( 5 ), new FloatType( 5 ) ],
			[ new FloatType( 25 ), new IntegerType( 5 ), new FloatType( 5 ) ],
			[ new FloatType( -50 ), new FloatType( 10 ), new FloatType( -5 ) ],
			[ new FloatType( -50 ), new IntegerType( 10 ), new FloatType( -5 ) ],
		];
	}

	/**
	 * @dataProvider DivideTestDataProvider
	 **/
	public function testDivide( RepresentsFloat $originalFloatType, RepresentsFloat|RepresentsInteger $anotherType, RepresentsFloat $expectedFloatType ): void
	{
		self::assertEquals( $expectedFloatType, $originalFloatType->divide( $anotherType ) );
		self::assertEquals( $expectedFloatType, $originalFloatType->divide( $anotherType->toFloat() ) );

		if ( $anotherType instanceof RepresentsInteger )
		{
			self::assertEquals( $expectedFloatType, $originalFloatType->divide( $anotherType->toInteger() ) );
		}
	}

	public function testJsonSerialize(): void
	{
		$floatType = new FloatType( 1.255 );

		self::assertSame( '1.255', json_encode( $floatType, JSON_THROW_ON_ERROR ) );
	}

	public function testInitializeClassUsingTrait(): void
	{
		$floatType = new class(2.5) {
			use RepresentingFloat;
		};

		self::assertEquals( 2.5, $floatType->toFloat() );
	}
}
