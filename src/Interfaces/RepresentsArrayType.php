<?php declare(strict_types=1);

namespace Hansel23\Types\Interfaces;

interface RepresentsArrayType extends \ArrayAccess, \Iterator, \Countable, \JsonSerializable
{
	public function toArray(): array;

	public function toJson(): string;

	public function jsonSerialize(): array;

	public static function fromJson( string $json ): RepresentsArrayType;
}
