<?php declare(strict_types=1);

namespace ComponoKit\Types\Exceptions;

use ComponoKit\Types\Interfaces\RepresentsTypeException;
use ComponoKit\Types\Interfaces\Traits\ProvidingContext;

class InvalidFloatException extends \LogicException implements RepresentsTypeException
{
	use ProvidingContext;
}
