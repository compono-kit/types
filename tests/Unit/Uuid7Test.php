<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit;

use ComponoKit\Types\Exceptions\InvalidStringException;
use ComponoKit\Types\StringType;
use ComponoKit\Types\Tests\Unit\fakes\AnotherUuid7Type;
use ComponoKit\Types\Uuid7;
use PHPUnit\Framework\TestCase;

class Uuid7Test extends TestCase
{
	public static function ValidUuid7Provider(): array
	{
		return [
			[ '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ],
			[ '00000000-0000-0000-0000-000000000000' ],
		];
	}

	/**
	 * @dataProvider ValidUuid7Provider
	 **/
	public function testIfValidValueDoesNotThrowException( string $validValue ): void
	{
		$this->expectNotToPerformAssertions();

		new Uuid7( $validValue );
	}

	public static function InvalidUuid7Provider(): array
	{
		return [
			[ '018e6ce5-8f5b-4b47-b8d5-02f92a47cfa9' ], // falsche Version (4)
			[ '018e6ce5-8f5b-7b47-b-02f92a47cfa9' ],   // zu kurz
			[ '018e6ce5-8f5b-7b47--02f92a47cfa9' ],   // doppelte Trennstriche
			[ '018e6ce5-8f5b-7b47-b8d502f92a47cfa9' ], // fehlender Bindestrich
			[ 'invalid' ],                            // kompletter Unsinn
		];
	}

	/**
	 * @dataProvider InvalidUuid7Provider
	 **/
	public function testIfInvalidValueThrowsException( string $invalidValue ): void
	{
		$this->expectExceptionObject( new InvalidStringException( 'Invalid ' . Uuid7::class ) );

		new Uuid7( $invalidValue );
	}

	public function testEquals(): void
	{
		self::assertTrue( (new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ))->equals( new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ) ) );
		self::assertFalse( (new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ))->equals( new AnotherUuid7Type( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ) ) );
		self::assertFalse( (new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ))->equals( new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfb0' ) ) );
		self::assertFalse( (new AnotherUuid7Type( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ))->equals( new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfb0' ) ) );
	}

	public function testEqualsValue(): void
	{
		self::assertTrue( (new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ))->equalsValue( new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ) ) );
		self::assertTrue( (new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ))->equalsValue( new AnotherUuid7Type( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ) ) );
		self::assertTrue( (new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ))->equalsValue( new StringType( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ) ) );
		self::assertTrue( (new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ))->equalsValue( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ) );
		self::assertFalse( (new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ))->equalsValue( new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfb0' ) ) );
		self::assertFalse( (new AnotherUuid7Type( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ))->equalsValue( new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfb0' ) ) );
	}

	public function testToString(): void
	{
		self::assertSame( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9', (new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ))->toString() );
		self::assertSame( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9', (string)new Uuid7( '018e6ce5-8f5b-7b47-b8d5-02f92a47cfa9' ) );
	}

	public function testGeneratingUuid7(): void
	{
		$uuid7 = Uuid7::generate();

		self::assertMatchesRegularExpression(
			'!^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$!i',
			$uuid7->toString()
		);

		self::assertSame( Uuid7::class, get_class( $uuid7 ) );
	}
}
