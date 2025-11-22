<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit;

use ComponoKit\Types\Exceptions\InvalidStringException;
use ComponoKit\Types\StringType;
use ComponoKit\Types\Tests\Unit\fakes\AnotherUuid4Type;
use ComponoKit\Types\Uuid4;
use PHPUnit\Framework\TestCase;

class Uuid4Test extends TestCase
{
	public static function ValidUuid4Provider(): array
	{
		return [
			[ 'c2667c9b-01b7-48e0-bb16-1df24837ec3f', ],
			[ '00000000-0000-0000-0000-000000000000', ],
		];
	}

	/**
	 * @dataProvider ValidUuid4Provider
	 **/
	public function testIfValidValueDoesNotThrowException( string $validValue ): void
	{
		$this->expectNotToPerformAssertions();

		new Uuid4( $validValue );
	}

	public static function InvalidUuid4Provider(): array
	{
		return [
			[ 'c2667c9b-01b7-48e0-bb16-1df24837ec3f2', ],
			[ 'c2667c9b-01b7-48e0-b-1df24837ec3f', ],
			[ 'c2667c9b-01b7-48e0--1df24837ec3f', ],
			[ 'c2667c9b-01b7-48e0-1df24837ec3f', ],
			[ 'invalid', ],
		];
	}

	/**
	 * @dataProvider InvalidUuid4Provider
	 **/
	public function testIfInvalidValueThrowsException( string $invalidValue ): void
	{
		$this->expectExceptionObject( new InvalidStringException( 'Invalid ' . Uuid4::class ) );

		new Uuid4( $invalidValue );
	}

	public function testEquals(): void
	{
		self::assertTrue( (new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ))->equals( new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ) ) );
		self::assertFalse( (new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ))->equals( new AnotherUuid4Type( '4dce17db-3031-4de2-b428-962952e2166b' ) ) );
		self::assertFalse( (new AnotherUuid4Type( '605879b6-14ae-4323-9994-7bcd6a53bb90' ))->equals( new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ) ) );
		self::assertFalse( (new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ))->equals( new Uuid4( 'f1025ba6-bcaf-4257-8e48-58ebc96788b4' ) ) );
	}

	public function testEqualsValue(): void
	{
		self::assertTrue( (new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ))->equalsValue( new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ) ) );
		self::assertTrue( (new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ))->equalsValue( new AnotherUuid4Type( '4dce17db-3031-4de2-b428-962952e2166b' ) ) );
		self::assertTrue( (new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ))->equalsValue( new StringType( '4dce17db-3031-4de2-b428-962952e2166b' ) ) );
		self::assertTrue( (new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ))->equalsValue( '4dce17db-3031-4de2-b428-962952e2166b' ) );
		self::assertFalse( (new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ))->equalsValue( new Uuid4( 'f1025ba6-bcaf-4257-8e48-58ebc96788b4' ) ) );
		self::assertFalse( (new Uuid4( '4dce17db-3031-4de2-b428-962952e2166b' ))->equalsValue( new AnotherUuid4Type( '605879b6-14ae-4323-9994-7bcd6a53bb90' ) ) );
	}

	public function testToString(): void
	{
		self::assertSame( 'e403623c-934f-4b12-84ee-f358ac3291ba', (new Uuid4( 'e403623c-934f-4b12-84ee-f358ac3291ba' ))->toString() );
		self::assertSame( 'e403623c-934f-4b12-84ee-f358ac3291ba', (string)new Uuid4( 'e403623c-934f-4b12-84ee-f358ac3291ba' ) );
	}

	public function testGeneratingUuid4(): void
	{
		$uuid4 = Uuid4::generate();

		self::assertMatchesRegularExpression( '!^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$!i', $uuid4->toString() );
		self::assertSame( Uuid4::class, get_class( $uuid4 ) );
	}
}
