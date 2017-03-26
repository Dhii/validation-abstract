<?php

namespace Dhii\Validation\TestStub;

use Dhii\Validation\Exception\ValidationFailedExceptionInterface;

/**
 * Enables the mock of `ValidationFailedExceptionInterface` to be a valid
 * exception.
 *
 * @since 0.1
 */
abstract class AbstractValidationFailedException extends \Exception implements ValidationFailedExceptionInterface
{
}
