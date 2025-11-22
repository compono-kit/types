<?php declare(strict_types=1);

namespace ComponoKit\Types\Exceptions;

use ComponoKit\Types\Interfaces\RepresentsTypeException;
use ComponoKit\Types\Interfaces\Traits\ProvidingContext;

class InvalidStringException extends \LogicException implements RepresentsTypeException
{
	use ProvidingContext;
}
