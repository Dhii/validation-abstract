<?php

namespace Dhii\Validation;

use Traversable;
use Countable;
use Exception as RootException;
use Dhii\Util\String\StringableInterface as Stringable;
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
     * Parameter-less constructor.
     *
     * Invoke this in the actual constructor.
     *
     * @since [*next-version*]
     */
    protected function _construct()
    {
    }

    /**
     * Creates a new validation exception.
     *
     * @since 0.1
     * @see RootException::__construct()
     *
     * @param string|Stringable|null $message  The message, if any
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception, if any.
     *
     * @return ValidationExceptionInterface The new exception.
     */
    abstract protected function _createValidationException($message = null, $code = null, RootException $previous = null);

    /**
     * Creates a new validation failed exception.
     *
     * @since 0.1
     * @see RootException::__construct()
     *
     * @param string|Stringable|null            $message          The error message, if any.
     * @param int|null                          $code             The error code, if any.
     * @param RootException|null                $previous         The inner exception, if any.
     * @param mixed|null                        $subject          The subject that has failed validation, if any.
     * @param string[]|Stringable[]|Traversable $validationErrors The errors that are to be associated with the new exception, if any.
     *
     * @return ValidationFailedExceptionInterface The new exception.
     */
    abstract protected function _createValidationFailedException($message = null, $code = null, RootException $previous = null, $subject = null, $validationErrors = array());

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

        throw $this->_createValidationFailedException($this->__('Validation failed'), null, null, $this, $subject, $errors);
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

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see sprintf()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = array(), $context = null);
}
