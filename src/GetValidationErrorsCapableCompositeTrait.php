<?php

namespace Dhii\Validation;

use Dhii\Validation\Exception\ValidationExceptionInterface;
use InvalidArgumentException;
use OutOfRangeException;
use stdClass;
use Traversable;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;

/**
 * Common functionality for composite validators.
 * 
 * Composite validators are validators that use one or more "child" validators
 * to validate the subject.
 *
 * @since [*next-version*]
 */
trait GetValidationErrorsCapableCompositeTrait
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @throws OutOfRangeException If one of the child validators is not a validator.
     * @throws ValidationExceptionInterface If problem validating.
     */
    protected function _getValidationErrors($subject, $spec = null)
    {
        $errors = array();
        foreach ($this->_getChildValidators() as $_idx => $_validator) {

            try {
                if ($_validator instanceof SpecValidatorInterface) {
                    $_validator->validate($subject, $spec);
                }
                elseif ($_validator instanceof ValidatorInterface) {
                    $_validator->validate($subject);
                }
                else {
                    throw $this->_createOutOfRangeException($this->__('Validator %1$s is invalid', [$_idx]), null, null, $_validator);
                }
            } catch (ValidationFailedExceptionInterface $e) {
                $errors[] = $e->getValidationErrors();
            }
        }

        return $this->_normalizeErrorList($errors);
    }

    /**
     * Retrieves the child validators.
     *
     * @since [*next-version*]
     *
     * @return array|Traversable|stdClass A list of validators.
     */
    abstract protected function _getChildValidators();

    /**
     * Normalizes a list of lists of {@see Stringable} validation errors into a flat list of such errors.
     *
     * @param array[]|Traversable|stdClass $errorList The list of errors to normalize.
     *
     * @since [*next-version*]
     *
     * @return string[]|Stringable[]|Traversable|stdClass The flat list of validation errors.
     */
    abstract protected function _normalizeErrorList($errorList);

    /**
     * Normalizes an iterable.
     *
     * Makes sure that the return value can be iterated over.
     *
     * @since [*next-version*]
     *
     * @param mixed $iterable The iterable to normalize.
     *
     * @throws InvalidArgumentException If the iterable could not be normalized.
     *
     * @return array|Traversable|stdClass The normalized iterable.
     */
    abstract protected function _normalizeIterable($iterable);

    /**
     * Creates a new Out Of Range exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|int|float|bool|null $message  The message, if any.
     * @param int|float|string|Stringable|null      $code     The numeric error code, if any.
     * @param RootException|null                    $previous The inner exception, if any.
     * @param mixed|null                            $argument The value that is out of range, if any.
     *
     * @return OutOfRangeException The new exception.
     */
    abstract protected function _createOutOfRangeException(
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
