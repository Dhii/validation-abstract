<?php

namespace Dhii\Validation;

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
abstract class AbstractCompositeValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getValidationErrors($subject)
    {
        $errors = array();
        foreach ($this->_getChildValidators() as $_idx => $_validator) {
            try {
                $_validator->validate($subject);
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
}
