<?php

namespace Dhii\Validation\TestStub;

use Dhii\Validation\Exception\ValidationExceptionInterface;

/**
 * Enables the mock of `ValidationExceptionInterface` to be a valid
 * exception.
 *
 * @since 0.1
 */
abstract class AbstractValidationException extends \Exception implements ValidationExceptionInterface
{
}
