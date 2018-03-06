<?php

namespace Dhii\Validation;

use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Guzzle\Service\Exception\ValidationException;
use stdClass;
use Traversable;

/**
 * Functionality for determining if something is valid.
 *
 * @since [*next-version*]
 */
trait IsValidCapableTrait
{
    /**
     * Determines whether the subject is valid.
     *
     * @since [*next-version*]
     *
     * @param mixed $subject The value to validate.
     * @param array|Traversable|stdClass|null The validation spec, if any.
     *
     * @return bool True if the subject is valid; false otherwise.
     *
     * @throws ValidationException If problem validating.
     */
    protected function _isValid($subject, $spec = null)
    {
        try {
            $this->_validate($subject, $spec);
        } catch (ValidationFailedExceptionInterface $e) {
            return false;
        }

        return true;
    }

    /**
     * Validates a subject.
     *
     * @since [*next-version*]
     *
     * @param mixed $subject The value to validate.
     * @param array|Traversable|stdClass|null The validation spec, if any.
     *
     * @throws ValidationFailedExceptionInterface If subject is invalid.
     * @throws ValidationException If problem validating.
     */
    abstract protected function _validate($subject, $spec);
}
