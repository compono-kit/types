<?php declare(strict_types=1);

namespace ComponoKit\Types\Traits;

use ComponoKit\Types\Interfaces\RepresentsDate;
use ComponoKit\Types\Interfaces\RepresentsType;

trait RepresentingDate
{
	private \DateTimeImmutable $dateTime;

	public function __construct( ?string $dateTime = "now", ?\DateTimeZone $timeZone = null )
	{
		$this->dateTime = new \DateTimeImmutable( $dateTime, $timeZone );
	}

	public function equals( RepresentsType $type ): bool
	{
		/**
		 * @var RepresentsDate|\DateTimeInterface|string $type
		 */
		return get_class( $type ) === get_class( $this ) && $this->equalsValue( $type );
	}

	public function equalsValue( RepresentsDate|\DateTimeInterface|string $value ): bool
	{
		return $this->format( 'c' ) === $this->convertToDateTime( $value )->format( 'c' );
	}

	public function toNativeType(): string
	{
		return $this->toString();
	}

	public function toString(): string
	{
		return $this->format( $this->defaultFormat );
	}

	public function __toString(): string
	{
		return $this->toString();
	}

	public function toDateTime(): \DateTimeInterface
	{
		return $this->dateTime;
	}

	public function add( \DateInterval $dateInterval ): static
	{
		$dateTime = $this->dateTime->add( $dateInterval );

		return new static( $dateTime->format( 'c' ), $dateTime->getTimezone() );
	}

	public function sub( \DateInterval $dateInterval ): static
	{
		$dateTime = $this->dateTime->sub( $dateInterval );

		return new static( $dateTime->format( 'c' ), $dateTime->getTimezone() );
	}

	public function diff( RepresentsDate $datetime2, bool $absolute = false ): \DateInterval
	{
		return $this->dateTime->diff( $datetime2->toDateTime(), $absolute );
	}

	public function isGreaterThan( RepresentsDate|\DateTimeInterface|string $value ): bool
	{
		return $this->toDateTime() > $this->convertToDateTime( $value );
	}

	public function isGreaterThanOrEqual( RepresentsDate|\DateTimeInterface|string $value ): bool
	{
		return $this->isGreaterThan( $value ) || $this->equalsValue( $value );
	}

	public function isLessThan( RepresentsDate|\DateTimeInterface|string $value ): bool
	{
		return $this->toDateTime() < $this->convertToDateTime( $value );
	}

	public function isLessThanOrEqual( RepresentsDate|\DateTimeInterface|string $value ): bool
	{
		return $this->isLessThan( $value ) || $this->equalsValue( $value );
	}

	public function hasExpired( ?\DateInterval $expirationInterval = null, null|RepresentsDate|\DateTimeInterface $referenceTime = null ): bool
	{
		$referenceTime = $referenceTime ?? new \DateTimeImmutable();

		if ( null !== $expirationInterval )
		{
			return $this->dateTime->add( $expirationInterval ) < $referenceTime;
		}

		return $this->dateTime < $referenceTime;
	}

	public function format( string $format ): string
	{
		return $this->dateTime->format( $format );
	}

	public function getOffset(): int
	{
		return $this->dateTime->getOffset();
	}

	public function getTimestamp(): int
	{
		return $this->dateTime->getTimestamp();
	}

	public function getTimezone(): \DateTimeZone
	{
		return $this->dateTime->getTimezone();
	}

	public function jsonSerialize(): string
	{
		return $this->dateTime->format( $this->defaultFormat );
	}

	private function convertToDateTime( RepresentsDate|\DateTimeInterface|string $value )
	{
		if ( is_string( $value ) )
		{
			return new \DateTimeImmutable( $value );
		}

		if ( $value instanceof RepresentsDate )
		{
			return $value->toDateTime();
		}

		return $value;
	}
}
