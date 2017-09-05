<?php

namespace Dhii\Validation\TestStub;

use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Exception;
use Dhii\Validation\Exception\AbstractValidationFailedException as BaseFailedException;

/**
 * Enables the mock of `ValidationFailedExceptionInterface` to be a valid
 * exception.
 *
 * @since 0.1
 */
abstract class AbstractValidationFailedException extends BaseFailedException implements ValidationFailedExceptionInterface
{
}
