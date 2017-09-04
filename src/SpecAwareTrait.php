<?php

namespace Dhii\Validation;

use Traversable;
use Exception as RootException;
use InvalidArgumentException;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Functionality for retrieving the spec.
 *
 * @since [*next-version*]
 */
trait SpecAwareTrait
{
    /**
     * The spec.
     *
     * @since [*next-version*]
     *
     * @var mixed
     */
    protected $spec;

    /**
     * Retrieves the spec associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return array|Traversable The spec.
     */
    protected function _getSpec()
    {
        return $this->spec;
    }

    /**
     * Assigns a spec to this instance.
     *
     * @since [*next-version*]
     *
     * @param array|Traversable|null $spec The spec.
     */
    protected function _setSpec($spec)
    {
        if (!is_null($spec) && !is_array($spec) && !($spec instanceof Traversable)) {
            throw $this->_createInvalidArgumentException($this->__('Invalid specification'), null, null, $spec);
        }

        $this->spec = $spec;
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
