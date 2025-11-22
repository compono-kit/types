<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit;

use ComponoKit\Types\DateType;
use ComponoKit\Types\Exceptions\InvalidDateException;
use ComponoKit\Types\Tests\Unit\fakes\After2000DateType;
use ComponoKit\Types\Tests\Unit\fakes\AnotherDateType;
use ComponoKit\Types\Traits\RepresentingDate;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
	public function testInstantiatingWithValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new DateType( '1999-12-12 23:59:59' );
	}

	public function testInstantiatingWithInvalidDateTimeThrowsException(): void
	{
		$this->expectExceptionObject( new InvalidDateException( 'Invalid ' . DateType::class ) );

		new DateType( 'invalid' );
	}

	public function testInstantiatingWithInvalidValueThrowsException(): void
	{
		$this->expectExceptionObject( new InvalidDateException( 'Invalid ' . After2000DateType::class ) );

		new After2000DateType('1999-11-12 10:30:15');
	}

	public function testFromDateType(): void
	{
		self::assertEquals( new DateType( '2025-11-12 10:30:15' ), DateType::fromDate( new AnotherDateType( '2025-11-12 10:30:15' ) ) );
		self::assertEquals( new DateType( '2025-11-12 10:30:15' ), DateType::fromDate( new \DateTimeImmutable( '2025-11-12 10:30:15' ) ) );
		self::assertEquals( new DateType( '2025-11-12 10:30:15' ), DateType::fromDate( new \DateTime( '2025-11-12 10:30:15' ) ) );
	}

	public function testFromTimestamp(): void
	{
		self::assertEquals( new DateType( '1970-01-01 00:00:00', new \DateTimeZone( '+0000' ) ), DateType::fromTimestamp( 0, new \DateTimeZone( '+0000' ) ) );
	}

	public function testEquals(): void
	{
		self::assertTrue( (new DateType( '2025-11-12 10:30:15' ))->equals( new DateType( '2025-11-12 10:30:15' ) ) );
		self::assertFalse( (new DateType( '2025-11-12 10:30:15' ))->equals( new DateType( '2025-11-12 21:15:38' ) ) );
		self::assertFalse( (new DateType( '2025-11-12 10:30:15' ))->equals( new AnotherDateType( '2025-11-12 10:30:15' ) ) );
	}

	public function testEqualsValue(): void
	{
		self::assertTrue( (new DateType( '2025-11-12 20:57:48' ))->equalsValue( new AnotherDateType( '2025-11-12 20:57:48' ) ) );
		self::assertTrue( (new DateType( '2025-11-12 20:57:48', new \DateTimeZone( '+0100' ) ))->equalsValue( new AnotherDateType( '2025-11-12 20:57:48', new \DateTimeZone( '+0100' ) ) ) );
		self::assertFalse( (new DateType( '2025-11-12 20:57:48', new \DateTimeZone( '+0100' ) ))->equalsValue( new AnotherDateType( '2025-11-12 20:57:48', new \DateTimeZone( '+0200' ) ) ) );
		self::assertFalse( (new DateType( '2025-11-12 20:57:48', new \DateTimeZone( '+0100' ) ))->equalsValue( new AnotherDateType( '2025-11-12 20:57:49', new \DateTimeZone( '+0100' ) ) ) );
	}

	public function testToNativeType(): void
	{
		$this->assertEquals( '2025-11-12 20:57:48', (new DateType( '2025-11-12 20:57:48' ))->toNativeType() );
		$this->assertEquals( '12.11.2025', (new DateType( '2025-11-12 20:57:48', null, 'd.m.Y' ))->toNativeType() );
	}

	public function testToString(): void
	{
		self::assertEquals( '2025-11-12 10:30:15', (new DateType( '2025-11-12 10:30:15', new \DateTimeZone( '+0200' ) ))->toString() );
		self::assertEquals( '2025-11-12T10:30:15+02:00', (new DateType( '2025-11-12 10:30:15', new \DateTimeZone( '+0200' ), 'c' ))->toString() );
	}

	public function testMagicToString(): void
	{
		self::assertEquals( '2025-11-12 10:30:15', (string)new DateType( '2025-11-12 10:30:15', new \DateTimeZone( '+0200' ) ) );
		self::assertEquals( '2025-11-12T10:30:15+02:00', (string)new DateType( '2025-11-12 10:30:15', new \DateTimeZone( '+0200' ), 'c' ) );
	}

	public function testToDateTime(): void
	{
		self::assertInstanceOf( \DateTimeInterface::class, (new DateType( '2025-11-12 10:30:15' ))->toDateTime() );
	}

	public function testAdd(): void
	{
		self::assertEquals( new DateType( '2025-11-12 15:30:00' ), (new DateType( '2025-11-12 12:30:00' ))->add( new \DateInterval( 'PT3H' ) ) );
		self::assertEquals( new DateType( '2025-11-15 09:30:00' ), (new DateType( '2025-11-12 09:30:00' ))->add( new \DateInterval( 'P3D' ) ) );
	}

	public function testSub(): void
	{
		self::assertEquals( new DateType( '2025-11-12 07:30:00' ), (new DateType( '2025-11-12 10:30:00' ))->sub( new \DateInterval( 'PT3H' ) ) );
		self::assertEquals( new DateType( '2025-11-09 09:30:00' ), (new DateType( '2025-11-12 09:30:00' ))->sub( new \DateInterval( 'P3D' ) ) );
	}

	public function testDiff(): void
	{
		$this->assertEquals( new \DateInterval( 'PT1H' ), (new DateType( '1999-12-12 22:59:59' ))->diff( new DateType( '1999-12-12 23:59:59' ) ) );
		$this->assertEquals( new \DateInterval( 'PT1H' ), (new DateType( '1999-12-12 23:59:59' ))->diff( new DateType( '1999-12-12 22:59:59' ), true ) );
		$this->assertEquals( (new \DateInterval( 'P1D' ))->d, (new DateType( '1999-12-11 23:59:59' ))->diff( new DateType( '1999-12-12 23:59:59' ) )->d );
	}

	public function testIsGreaterThan(): void
	{
		$dateTime = new DateType( '2025-11-12 09:30:00' );

		self::assertTrue( $dateTime->isGreaterThan( new DateType( '2025-11-12 09:29:59' ) ) );
		self::assertFalse( $dateTime->isGreaterThan( new DateType( '2025-11-12 09:30:00' ) ) );
		self::assertFalse( $dateTime->isGreaterThan( new DateType( '2025-11-12 09:30:01' ) ) );
		self::assertTrue( $dateTime->isGreaterThan( new \DateTimeImmutable( '2025-11-12 09:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThan( new \DateTime( '2025-11-12 09:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThan( new AnotherDateType( '2025-11-12 09:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThan( '2025-11-12 09:29:59' ) );
	}

	public function testIsGreaterThanOrEqual(): void
	{
		$dateTime = new DateType( '2025-11-12 09:30:00' );

		self::assertTrue( $dateTime->isGreaterThanOrEqual( new DateType( '2025-11-12 09:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThanOrEqual( new DateType( '2025-11-12 09:30:00' ) ) );
		self::assertFalse( $dateTime->isGreaterThanOrEqual( new DateType( '2025-11-12 09:30:01' ) ) );
		self::assertTrue( $dateTime->isGreaterThanOrEqual( new \DateTimeImmutable( '2025-11-12 09:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThanOrEqual( new \DateTime( '2025-11-12 09:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThanOrEqual( new AnotherDateType( '2025-11-12 09:29:59' ) ) );
		self::assertTrue( $dateTime->isGreaterThanOrEqual( '2025-11-12 09:29:59' ) );
	}

	public function testIsLessThan(): void
	{
		$dateTime = new DateType( '2025-11-12 09:30:00' );

		self::assertTrue( $dateTime->isLessThan( new DateType( '2025-11-12 09:30:01' ) ) );
		self::assertFalse( $dateTime->isLessThan( new DateType( '2025-11-12 09:30:00' ) ) );
		self::assertFalse( $dateTime->isLessThan( new DateType( '2025-11-12 09:29:59' ) ) );
		self::assertTrue( $dateTime->isLessThan( new \DateTimeImmutable( '2025-11-12 09:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThan( new \DateTime( '2025-11-12 09:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThan( new AnotherDateType( '2025-11-12 09:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThan( '2025-11-12 09:30:01' ) );
	}

	public function testIsLessThanOrEqual(): void
	{
		$dateTime = new DateType( '2025-11-12 09:30:00' );

		self::assertTrue( $dateTime->isLessThanOrEqual( new DateType( '2025-11-12 09:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThanOrEqual( new DateType( '2025-11-12 09:30:00' ) ) );
		self::assertFalse( $dateTime->isLessThanOrEqual( new DateType( '2025-11-12 09:29:59' ) ) );
		self::assertTrue( $dateTime->isLessThanOrEqual( new \DateTimeImmutable( '2025-11-12 09:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThanOrEqual( new \DateTime( '2025-11-12 09:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThanOrEqual( new AnotherDateType( '2025-11-12 09:30:01' ) ) );
		self::assertTrue( $dateTime->isLessThanOrEqual( '2025-11-12 09:30:01' ) );
	}

	public function testHasExpired(): void
	{
		$dateTime = new DateType( '2025-11-12 09:30:00' );

		self::assertTrue( $dateTime->hasExpired( null, new \DateTimeImmutable( '2025-11-12 09:30:01' ) ) );
		self::assertFalse( $dateTime->hasExpired( null, new \DateTimeImmutable( '2025-11-12 09:30:00' ) ) );
		self::assertFalse( $dateTime->hasExpired( null, new \DateTimeImmutable( '2025-11-12 09:29:59' ) ) );
		self::assertTrue( $dateTime->hasExpired( new \DateInterval( 'PT15M' ), new \DateTimeImmutable( '2025-11-12 09:45:01' ) ) );
		self::assertFalse( $dateTime->hasExpired( new \DateInterval( 'PT15M' ), new \DateTimeImmutable( '2025-11-12 09:45:00' ) ) );
		self::assertFalse( $dateTime->hasExpired( new \DateInterval( 'PT15M' ), new \DateTimeImmutable( '2025-11-12 09:44:59' ) ) );
	}

	public function testFormat(): void
	{
		self::assertEquals( '2025-11-12 10:30:15', (new DateType( '2025-11-12 10:30:15', null, 'Y.m.d' ))->format( 'Y-m-d H:i:s' ) ); //overriding injected default format
		self::assertEquals( '2025-11-12T10:30:15+02:00', (new DateType( '2025-11-12 10:30:15', new \DateTimeZone( '+0200' ) ))->format( 'c' ) );
		self::assertEquals( '12.11.2025', (new DateType( '2025-11-12 10:30:15' ))->format( 'd.m.Y' ) );
	}

	public function testGetOffset(): void
	{
		self::assertEquals( 7260, (new DateType( '2025-11-12 10:30:15', new \DateTimeZone( '+0201' ) ))->getOffset() );
	}

	public function testGetTimestamp(): void
	{
		self::assertEquals( 1762943415, (new DateType( '2025-11-12 10:30:15' ))->getTimestamp() );
	}

	public function testGetTimezone(): void
	{
		$expectedTimeZone = new \DateTimeZone( '+0200' );

		self::assertEquals( $expectedTimeZone, (new DateType( '2025-11-12 10:30:15', $expectedTimeZone ))->getTimezone() );
	}

	public function testJsonSerialize(): void
	{
		self::assertEquals( '"2025-11-12 10:30:15"', json_encode( new DateType( '2025-11-12 10:30:15', new \DateTimeZone( '+0200' ) ) ) );
		self::assertEquals( '"2025-11-12T10:30:15+02:00"', json_encode( (new DateType( '2025-11-12 10:30:15', new \DateTimeZone( '+0200' ), 'c' )) ) );
	}

	public function testInitializeClassUsingTrait(): void
	{
		$dateType = new class('2025-11-12 20:57:48') {
			use RepresentingDate;
		};

		self::assertEquals( '20251112', $dateType->format( 'Ymd' ) );
	}
}
