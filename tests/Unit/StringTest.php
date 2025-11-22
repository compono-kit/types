<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit;

use ComponoKit\Types\AbstractString;
use ComponoKit\Types\Exceptions\InvalidStringException;
use ComponoKit\Types\Exceptions\RegExException;
use ComponoKit\Types\Interfaces\RepresentsString;
use ComponoKit\Types\StringType;
use ComponoKit\Types\Tests\Unit\fakes\AnotherStringType;
use ComponoKit\Types\Tests\Unit\fakes\NoQuestionMarkStringType;
use ComponoKit\Types\Tests\Unit\fakes\RandomStringableType;
use ComponoKit\Types\Traits\RepresentingString;
use PHPUnit\Framework\TestCase;

class StringTest extends TestCase
{
	public function testInvalidValueThrowsException(): void
	{
		$this->expectExceptionObject( new InvalidStringException( 'Invalid ' . NoQuestionMarkStringType::class ) );

		new NoQuestionMarkStringType( '??' );
	}

	public function testIfValidValueDoesNotThrowException(): void
	{
		$this->expectNotToPerformAssertions();

		new class('Test') extends AbstractString {
			public static function isValid( string $value ): bool
			{
				return true;
			}
		};
	}

	public function testFromStringType(): void
	{
		self::assertEquals( new StringType( 'Test' ), StringType::fromStringType( new AnotherStringType( 'Test' ) ) );
	}

	public function testEquals(): void
	{
		self::assertTrue( (new StringType( 'Test' ))->equals( new StringType( 'Test' ) ) );
		self::assertFalse( (new StringType( 'Test' ))->equals( new StringType( 'test' ) ) );
		self::assertFalse( (new StringType( 'Test' ))->equals( new AnotherStringType( 'Test' ) ) );
	}

	public static function EqualsValueTestDataProvider(): \Iterator
	{
		yield [ new StringType( 'Test 1' ), new AnotherStringType( 'Test 1' ), true, ];
		yield [ new StringType( 'Test 1' ), new StringType( 'Test 1' ), true, ];
		yield [ new StringType( 'Test 1' ), new AnotherStringType( 'Test 2' ), false, ];
		yield [ new StringType( 'Test 1' ), new StringType( 'Test 2' ), false, ];
		yield [ new StringType( 'Test 1' ), new RandomStringableType( 'Test 1' ), true, ];
		yield [ new StringType( 'Test 1' ), new RandomStringableType( 'Test 2' ), false, ];
	}

	/**
	 * @dataProvider EqualsValueTestDataProvider
	 **/
	public function testEqualsValue( RepresentsString $stringType, RepresentsString|\Stringable $anotherStringType, bool $expectedResult ): void
	{
		self::assertSame( $expectedResult, $stringType->equalsValue( $anotherStringType ) );
		self::assertSame( $expectedResult, $stringType->equalsValue( (string)$anotherStringType ) );
	}

	public static function ToStringTestDataProvider(): array
	{
		return [
			[ new StringType( 'test' ), 'test' ],
			[ new StringType( '??!!' ), '??!!' ],
			[ new StringType( 'Just a small phrase' ), 'Just a small phrase' ],
			[ new StringType( '' ), '' ],
			[ new StringType( ' ' ), ' ' ],
		];
	}

	/**
	 * @dataProvider ToStringTestDataProvider
	 **/
	public function testToNativeType( RepresentsString $stringType, string $expectedString ): void
	{
		self::assertSame( $expectedString, $stringType->toNativeType() );
	}

	/**
	 * @dataProvider ToStringTestDataProvider
	 **/
	public function testToString( RepresentsString $stringType, string $expectedString ): void
	{
		self::assertSame( $expectedString, $stringType->toString() );
	}

	/**
	 * @dataProvider ToStringTestDataProvider
	 **/
	public function testMagicToString( RepresentsString $stringType, string $expectedString ): void
	{
		self::assertSame( $expectedString, (string)$stringType );
	}

	public function testIsEmpty(): void
	{
		self::assertTrue( (new StringType( '' ))->isEmpty() );
		self::assertFalse( (new StringType( '0' ))->isEmpty() );
		self::assertFalse( (new StringType( ' ' ))->isEmpty() );
	}

	public function testInitializeClassUsingTrait(): void
	{
		$stringType = new class('a') {
			use RepresentingString;
		};

		self::assertEquals( 'a', $stringType->toString() );
	}

	public function testGetByteLength(): void
	{
		self::assertEquals( 0, (new StringType( '' ))->getByteLength() );
		self::assertEquals( 18, (new StringType( 'Count this please!' ))->getByteLength() );
		self::assertEquals( 14, (new StringType( 'äöüÄÖÜß' ))->getByteLength() );
		self::assertEquals( 13, (new StringType( '你好世界"' ))->getByteLength() );
		self::assertEquals( 4, (new StringType( '😀' ))->getByteLength() );
		self::assertEquals( 8, (new StringType( '👍🏽' ))->getByteLength() );
		self::assertEquals( 5, (new StringType( 'é́' ))->getByteLength() );
		self::assertEquals( 12, (new StringType( 'A😀你好B' ))->getByteLength() );
	}

	public function testCountChars(): void
	{
		self::assertEquals( 0, (new StringType( '' ))->countChars() );
		self::assertEquals( 18, (new StringType( 'Count this please!' ))->countChars() );
		self::assertEquals( 7, (new StringType( 'äöüÄÖÜß' ))->countChars() );
		self::assertEquals( 4, (new StringType( '你好世界' ))->countChars() );
		self::assertEquals( 1, (new StringType( '😀' ))->countChars() );
		self::assertEquals( 1, (new StringType( '👍🏽' ))->countChars() );
		self::assertEquals( 1, (new StringType( 'é́' ))->countChars() );
		self::assertEquals( 5, (new StringType( 'A😀你好B' ))->countChars() );
	}

	public function testTrim(): void
	{
		self::assertEquals( 'Trim this!', (new StringType( ' Trim this! ' ))->trim() );
		self::assertEquals( 'Trim this!', (new StringType( '  Trim this!  ' ))->trim() );
		self::assertEquals( 'Trim this!', (new StringType( "   Trim this!  \n" ))->trim() );
	}

	public function testReplace(): void
	{
		self::assertEquals( new StringType( 'I don\'t like red' ), (new StringType( 'I don\'t like yellow' ))->replace( 'yellow', 'red' ) );
		self::assertEquals(
			new StringType( 'I don\'t like red' ),
			(new StringType( 'I don\'t like yellow' ))->replace( new StringType( 'yellow' ), new StringType( 'red' ) )
		);
		self::assertEquals(
			new StringType( 'I don\'t like red' ),
			(new StringType( 'I don\'t like yellow' ))->replace( new RandomStringableType( 'yellow' ), new RandomStringableType( 'red' ) )
		);
		self::assertEquals( new StringType( 'I like red' ), (new StringType( 'I don\'t like yellow' ))->replace( [ 'yellow', 'don\'t ' ], [ 'red', '' ] ) );

		$anyStringType = new StringType( 'I don\'t like blue' );
		self::assertNotSame( $anyStringType, $anyStringType->replace( 'yellow', 'red' ) );
	}

	public static function SubstringTestDataProvider(): array
	{
		return [
			[ 'abcdef', -1, null, 'f' ],
			[ 'abcdef', -2, null, 'ef' ],
			[ 'abcdef', -3, 1, 'd' ],
			[ 'abcdef', 0, -1, 'abcde' ],
			[ 'abcdef', 2, -1, 'cde' ],
			[ 'abcdef', 4, -4, '' ],
			[ 'abcdef', -3, -1, 'de' ],
		];
	}

	/**
	 * @dataProvider SubstringTestDataProvider
	 **/
	public function testSubstring( string $anyString, int $offset, ?int $length, string $expectedString ): void
	{
		self::assertEquals( new StringType( $expectedString ), (new StringType( $anyString ))->substring( $offset, $length ) );
	}

	public function testToLowerCase(): void
	{
		self::assertEquals( new StringType( 'i like only lower case' ), (new StringType( 'I like only LOWER CASE' ))->toLowerCase() );
	}

	public function testToUpperCase(): void
	{
		self::assertEquals( new StringType( 'I LIKE ONLY UPPER CASE' ), (new StringType( 'I like only upper case' ))->toUpperCase() );
	}

	public function testCapitalizeFirst(): void
	{
		self::assertEquals( new StringType( 'My id' ), (new StringType( 'my id' ))->capitalizeFirst() );
	}

	public function testDeCapitalizeFirst(): void
	{
		self::assertEquals( new StringType( 'myId Ok!' ), (new StringType( 'MyId Ok!' ))->deCapitalizeFirst() );
	}

	public function testSplit(): void
	{
		self::assertEquals(
			[
				new StringType( 'cat' ),
				new StringType( 'dog' ),
				new StringType( 'cow' ),
			],
			iterator_to_array(
				(new StringType( 'cat,dog,cow' ))->split( ',' ),
				false
			)
		);
	}

	public function testSplitNative(): void
	{
		self::assertEquals( [ 'cat', 'dog', 'cow' ], (new StringType( 'cat,dog,cow' ))->splitNative( ',' ) );
	}

	public static function RegularExpressionDataProvider(): array
	{
		return [
			[ 'This is a small text', '/small/', true ],
			[ 'This is a small text', '/not-existing/', false ],
			[ '/abc/url', '/^\/(?<id>.*\/url$)/', true ],
		];
	}

	/**
	 * @dataProvider RegularExpressionDataProvider
	 **/
	public function testMatchRegularExpression( string $anyString, string $pattern, bool $expectedResult ): void
	{
		@preg_match( $pattern, $anyString, $expectedMatches );

		self::assertEquals( $expectedResult, (new StringType( $anyString ))->matchRegularExpression( $pattern, $matches ) );
		self::assertEquals( $expectedResult, (new StringType( $anyString ))->matchRegularExpression( new StringType( $pattern ), $matches ) );
		self::assertEquals( $expectedMatches, $matches );
	}

	public function testIfMatchRegularExpressionThrowsExceptionOnInvalidPattern(): void
	{
		$this->expectException( RegExException::class );

		(new StringType( 'Abc' ))->matchRegularExpression( '}{' );
	}

	public function testContains(): void
	{
		self::assertTrue( (new StringType( 'This is a text containing dogs and not only cats.' ))->contains( 'dogs' ) );
		self::assertTrue( (new StringType( 'This is a text containing dogs and not only cats.' ))->contains( new StringType( 'dogs' ) ) );
		self::assertFalse( (new StringType( 'This is a text containing dogs and not only cats.' ))->contains( 'cow' ) );
	}

	public function testContainsOneOf(): void
	{
		$type = new StringType( 'OuterAndInnerText' );

		self::assertFalse( $type->containsOneOf( 'abc', 'def', 'ghi' ) );
		self::assertFalse( $type->containsOneOf( 'inner', 'def', 'ghi' ) );
		self::assertTrue( $type->containsOneOf( 'abc', 'And', 'ghi' ) );
		self::assertTrue( $type->containsOneOf( 'Inner', 'def', 'ghi' ) );
		self::assertTrue( $type->containsOneOf( 'abc', 'def', 'Text' ) );
		self::assertTrue( $type->containsOneOf( 'OuterAndInnerText', 'def', 'ghi' ) );
	}

	public function testIsOneOf(): void
	{
		$type = new StringType( 'OuterAndInnerText' );

		self::assertFalse( $type->isOneOf( 'abc', 'And', 'ghi' ) );
		self::assertFalse( $type->isOneOf( 'outerAndInnerText', 'def', 'ghi' ) );
		self::assertTrue( $type->isOneOf( 'abc', 'OuterAndInnerText', 'ghi' ) );
	}

	public function testJsonSerialize(): void
	{
		$stringType = new StringType( 'This is json encoded' );

		self::assertSame( '"This is json encoded"', json_encode( $stringType, JSON_THROW_ON_ERROR ) );
	}
}
