<?php declare(strict_types=1);

namespace ComponoKit\Types;

use ComponoKit\Types\Interfaces\RepresentsBoolean;
use ComponoKit\Types\Traits\RepresentingBoolean;

class BooleanType implements RepresentsBoolean
{
	use RepresentingBoolean;
}
