<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit\Helpers;

use ComponoKit\Types\Helpers\StringTransformer;
use PHPUnit\Framework\TestCase;

class StringTransformerTest extends TestCase
{
	public static function KebabCaseDataProvider(): array
	{
		return [
			[ 'nice-kebab-case', 'nice-kebab-case' ],
			[ 'Nice Kebab Case', 'Nice-Kebab-Case' ],
			[ 'nice kebab Case', 'nice-kebab-Case' ],
			[ 'nice  kebab  case', 'nice-kebab-case' ],
			[ 'nice  kebab  Case', 'nice-kebab-Case' ],
			[ 'nice-kebab-Case', 'nice-kebab-Case' ],
			[ 'nice_kebab_Case', 'nice-kebab-Case' ],
			[ 'nice_-_-kebab_-_-Case', 'nice-kebab-Case' ],
			[ 'Nice_but-WeirdKebabCase1-2$%#=?a-?b--c', 'Nice-but-Weird-Kebab-Case1-2-a-b-c' ],
			[ 'NICE_KEBAB_CASE', 'NICE-KEBAB-CASE' ],
			[ 'NICE.KEBAB.CASE', 'NICE-KEBAB-CASE' ],
		];
	}

	/**
	 * @dataProvider KebabCaseDataProvider
	 **/
	public function testToKebabCase( string $anyString, string $expectedString ): void
	{
		self::assertEquals( $expectedString, StringTransformer::transformToKebabCase( $anyString ) );
	}

	public static function SnakeCaseDataProvider(): array
	{
		return [
			[ 'nice_snake_case', 'nice_snake_case' ],
			[ 'Nice Snake Case', 'Nice_Snake_Case' ],
			[ 'nice snake Case', 'nice_snake_Case' ],
			[ 'nice  snake  Case', 'nice_snake_Case' ],
			[ 'nice  snake  Case', 'nice_snake_Case' ],
			[ 'nice-snake-Case', 'nice_snake_Case' ],
			[ 'nice_snake_Case', 'nice_snake_Case' ],
			[ 'nice_-_-snake_-_-Case', 'nice_snake_Case' ],
			[ 'Nice_but-WeirdSnakeCase1--2$%#=?a_?b__c', 'Nice_but_Weird_Snake_Case1_2_a_b_c' ],
			[ 'NICE-SNAKE-CASE', 'NICE_SNAKE_CASE' ],
			[ 'NICE.SNAKE.CASE', 'NICE_SNAKE_CASE' ],
		];
	}

	/**
	 * @dataProvider SnakeCaseDataProvider
	 **/
	public function testToSnakeCase( string $anyString, string $expectedString ): void
	{
		self::assertEquals( $expectedString, StringTransformer::transformToSnakeCase( $anyString ) );
	}

	public static function DotCaseDataProvider(): array
	{
		return [
			[ 'nice.dot.case', 'nice.dot.case' ],
			[ 'Nice Dot Case', 'Nice.Dot.Case' ],
			[ 'nice dot Case', 'nice.dot.Case' ],
			[ 'nice  dot  Case', 'nice.dot.Case' ],
			[ 'nice  dot  Case', 'nice.dot.Case' ],
			[ 'nice-dot-Case', 'nice.dot.Case' ],
			[ 'nice_dot_Case', 'nice.dot.Case' ],
			[ 'nice_-_-dot-_-Case', 'nice.dot.Case' ],
			[ 'nice.-.-dot.-.-Case', 'nice.dot.Case' ],
			[ 'Nice.but-WeirdDotCase1--2$%#=?a.?b..c', 'Nice.but.Weird.Dot.Case1.2.a.b.c' ],
			[ 'NICE-DOT-CASE', 'NICE.DOT.CASE' ],
			[ 'NICE_DOT_CASE', 'NICE.DOT.CASE' ],
		];
	}

	/**
	 * @dataProvider DotCaseDataProvider
	 **/
	public function testToDotCase( string $anyString, string $expectedString ): void
	{
		self::assertEquals( $expectedString, StringTransformer::transformToDotCase( $anyString ) );
	}

	public static function LowerCamelCaseDataProvider(): array
	{
		return [
			[ 'Lower', 'lower' ],
			[ 'LOWERCamelCase', 'lowerCamelCase' ],
			[ 'LOWERCamelCASE', 'lowerCamelCase' ],
			[ 'LowerCAMELCase', 'lowerCamelCase' ],
			[ 'lowerCamelCase', 'lowerCamelCase' ],
			[ 'Lower Camel Case', 'lowerCamelCase' ],
			[ 'lower camel case', 'lowerCamelCase' ],
			[ 'lower  camel  case', 'lowerCamelCase' ],
			[ 'lower  camel  case', 'lowerCamelCase' ],
			[ 'lower-camel-case', 'lowerCamelCase' ],
			[ 'lower_camel_case', 'lowerCamelCase' ],
			[ 'lower_-_-camel_-_-case', 'lowerCamelCase' ],
		];
	}

	/**
	 * @dataProvider LowerCamelCaseDataProvider
	 **/
	public function testToLowerCamelCase( string $anyString, string $expectedString ): void
	{
		self::assertEquals( $expectedString, StringTransformer::transformToLowerCamelCase( $anyString ) );
	}

	public static function UpperCamelCaseDataProvider(): array
	{
		return [
			[ 'upper', 'Upper' ],
			[ 'upperCAMELCase', 'UpperCamelCase' ],
			[ 'UpperCamelCase', 'UpperCamelCase' ],
			[ 'Upper Camel Case', 'UpperCamelCase' ],
			[ 'upper camel case', 'UpperCamelCase' ],
			[ 'upper  camel  case', 'UpperCamelCase' ],
			[ 'upper  camel  case', 'UpperCamelCase' ],
			[ 'upper-camel-case', 'UpperCamelCase' ],
			[ 'upper_camel_case', 'UpperCamelCase' ],
			[ 'upper_-_-camel_-_-case', 'UpperCamelCase' ],
		];
	}

	/**
	 * @dataProvider UpperCamelCaseDataProvider
	 **/
	public function testToUpperCamelCase( string $anyString, string $expectedString ): void
	{
		self::assertEquals( $expectedString, StringTransformer::transformToUpperCamelCase( $anyString ) );
	}
}
