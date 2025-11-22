<?php declare(strict_types=1);

namespace ComponoKit\Types;

use ComponoKit\Types\Exceptions\InvalidDateException;
use ComponoKit\Types\Interfaces\RepresentsDate;
use ComponoKit\Types\Traits\RepresentingDate;

abstract class AbstractDate implements RepresentsDate
{
	use RepresentingDate;

	public function __construct( ?string $dateTime = "now", ?\DateTimeZone $timeZone = null, private string $defaultFormat = 'Y-m-d H:i:s' )
	{
		$this->validate( $dateTime, $timeZone );

		$this->dateTime = new \DateTimeImmutable( $dateTime, $timeZone );
	}

	abstract public static function isValid( \DateTimeInterface $value ): bool;

	public static function fromDate( RepresentsDate|\DateTimeInterface $type ): static
	{
		return new static( $type->format( 'c' ), $type->getTimezone() );
	}

	/**
	 * @throws \DateMalformedStringException|\Exception
	 */
	public static function fromTimestamp( int $unixTimestamp, ?\DateTimeZone $timeZone = null ): static
	{
		return new static( (new \DateTimeImmutable( 'now', $timeZone ))->modify( '@' . $unixTimestamp )->format( 'c' ) );
	}

	protected function validate( string $dateTime, ?\DateTimeZone $timeZone ): void
	{
		$context = [
			'date-time' => $dateTime,
			'time-zone' => $timeZone?->getName(),
		];

		try
		{
			$dateTimeImmutable = new \DateTimeImmutable( $dateTime, $timeZone );
		}
		catch ( \Throwable )
		{
			throw (new InvalidDateException( 'Invalid ' . static::class ))->setContext( $context );
		}

		if ( !static::isValid( $dateTimeImmutable ) )
		{
			throw (new InvalidDateException( 'Invalid ' . static::class ))->setContext( $context );
		}
	}
}
