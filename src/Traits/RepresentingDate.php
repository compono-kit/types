<?php declare(strict_types=1);

namespace ComponoKit\Types\Traits;

use ComponoKit\Types\Exceptions\InvalidDateException;
use ComponoKit\Types\Interfaces\RepresentsDate;
use ComponoKit\Types\Interfaces\RepresentsType;

trait RepresentingDate
{
	public const string  DEFAULT_FORMAT  = 'Y-m-d H:i:s';

	private const string LOSSLESS_FORMAT = 'Y-m-d\TH:i:s.uP';

	private readonly \DateTimeImmutable $dateTime;

	private readonly string             $defaultFormat;

	public function __construct( string $dateTime = 'now', ?\DateTimeZone $timeZone = null, string $defaultFormat = 'Y-m-d H:i:s' )
	{
		$this->dateTime      = new \DateTimeImmutable( $dateTime, $timeZone );
		$this->defaultFormat = $defaultFormat;
	}

	public function equals( RepresentsType $type ): bool
	{
		return $this::class === $type::class && $this->equalsValue( $type );
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

		return new static( $dateTime->format( self::LOSSLESS_FORMAT ), $dateTime->getTimezone(), $this->defaultFormat );
	}

	public function sub( \DateInterval $dateInterval ): static
	{
		$dateTime = $this->dateTime->sub( $dateInterval );

		return new static( $dateTime->format( self::LOSSLESS_FORMAT ), $dateTime->getTimezone(), $this->defaultFormat );
	}

	public function diff( RepresentsDate $anotherDate, bool $absolute = false ): \DateInterval
	{
		return $this->dateTime->diff( $anotherDate->toDateTime(), $absolute );
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
		$referenceDateTime = null === $referenceTime ? new \DateTimeImmutable() : $this->convertToDateTime( $referenceTime );

		if ( null !== $expirationInterval )
		{
			return $this->dateTime->add( $expirationInterval ) < $referenceDateTime;
		}

		return $this->dateTime < $referenceDateTime;
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

	private function convertToDateTime( RepresentsDate|\DateTimeInterface|string $value ): \DateTimeInterface
	{
		if ( $value instanceof RepresentsDate )
		{
			return $value->toDateTime();
		}

		if ( $value instanceof \DateTimeInterface )
		{
			return $value;
		}

		try
		{
			return new \DateTimeImmutable( $value );
		}
		catch ( \DateMalformedStringException $exception )
		{
			throw new InvalidDateException( 'Invalid date: ' . $value, 0, $exception )->setContext( [ 'date-time' => $value ] );
		}
	}
}
