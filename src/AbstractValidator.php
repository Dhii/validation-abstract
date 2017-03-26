<?php

namespace Dhii\Validation;

use Traversable;
use Countable;
use Dhii\Validation\Exception\ValidationExceptionInterface;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;

/**
 * Common functionality for validators.
 *
 * @since 0.1
 */
abstract class AbstractValidator
{
    /**
     * Creates a new validation exception.
     *
     * @since 0.1
     * @see \Exception::__construct()
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     *
     * @return ValidationExceptionInterface The new exception.
     */
    abstract protected function _createValidationException($message, $code = 0, \Exception $previous = null);

    /**
     * Creates a new validation failed exception.
     *
     * @since 0.1
     * @see \Exception::__construct()
     *
     * @param string                                     $message
     * @param int                                        $code
     * @param \Exception                                 $previous
     * @param string[]|StringableInterface[]|Traversable $validationErrors The errors that are to be associated with the new exception.
     *
     * @return ValidationFailedExceptionInterface The new exception.
     */
    abstract protected function _createValidationFailedException($message, $code = 0, \Exception $previous = null, $validationErrors = array());

    /**
     * Validates a subject.
     *
     * @since 0.1
     *
     * @param mixed $subject The value to validate.
     *
     * @throws ValidationFailedExceptionInterface If subject is invalid.
     */
    protected function _validate($subject)
    {
        $errors = $this->_getValidationErrors($subject);
        if (!count($errors)) {
            return;
        }

        throw $this->_createValidationFailedException('Validation failed', 0, null, $errors);
    }

    /**
     * Retrieve a list of reasons that make the subject invalid.
     *
     * An empty list means that the subject is valid.
     * This is what actually performs the validation.
     *
     * @since [*next-version*]
     *
     * @return Countable|Traversable The list of validation errors.
     */
    abstract protected function _getValidationErrors($subject);

    /**
     * Determines whether the subject is valid.
     *
     * @since 0.1
     *
     * @param mixed $subject The value to validate.
     *
     * @return bool True if the subject is valid; false otherwise.
     */
    protected function _isValid($subject)
    {
        try {
            $this->_validate($subject);
        } catch (ValidationFailedExceptionInterface $e) {
            return false;
        }

        return true;
    }
}
