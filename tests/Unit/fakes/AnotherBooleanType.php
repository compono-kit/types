<?php declare(strict_types=1);

namespace ComponoKit\Types\Tests\Unit\fakes;

use ComponoKit\Types\Interfaces\RepresentsBoolean;
use ComponoKit\Types\Traits\RepresentingBoolean;

class AnotherBooleanType implements RepresentsBoolean
{
	use RepresentingBoolean;
}
