<?php

namespace Dhii\Validation;

use Traversable;
use InvalidArgumentException;
use Exception as RootException;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Functionality for retrieving the subject.
 *
 * @since [*next-version*]
 */
trait ValidationErrorsAwareTrait
{
    /**
     * The list of validation errors associated with this instance.
     *
     * @since [*next-version*]
     *
     * @var array|Traversable
     */
    protected $validationErrors;

    /**
     * Retrieve the list of validation errors that this instance represents.
     *
     * @since [*next-version*]
     *
     * @return array|Traversable The error list.
     */
    protected function _getValidationErrors()
    {
        if (is_null($this->validationErrors)) {
            return array();
        }

        return $this->validationErrors;
    }

    /**
     * Sets the list of validation errors that this instance should represent.
     *
     * @since [*next-version*]
     *
     * @param array|Traversable|null $errorList The list of errors.
     */
    protected function _setValidationErrors($errorList)
    {
        if (!is_null($errorList) && !is_array($errorList) && !($errorList instanceof Traversable)) {
            throw $this->_createInvalidArgumentException($this->__('Invalid validation error list'), null, null, $errorList);
        }
        $this->validationErrors = $errorList;
    }

    /**
     * Creates a new Dhii invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     * @param mixed|null             $argument The invalid argument, if any.
     *
     * @return InvalidArgumentException The new exception.
     */
    abstract protected function _createInvalidArgumentException(
            $message = null,
            $code = null,
            RootException $previous = null,
            $argument = null
    );

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
