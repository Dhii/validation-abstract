<?php

namespace Dhii\Validation;

use Traversable;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Dhii\Util\String\StringableInterface as Stringable;

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
     * Retrieves the child validators.
     *
     * @since [*next-version*]
     *
     * @return ValidatorInterface[]|Traversable A list of validators.
     */
    abstract protected function _getChildValidators();

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
     * Normalizes a list of lists of {@see Stringable} validation errors into a flat list of such errors.
     *
     * @param array[]|Traversable $errorList The list of errors to normalize.
     *
     * @since [*next-version*]
     *
     * @return string[]|Stringable[]|Traversable The flat list of validation errors.
     */
    abstract protected function _normalizeErrorList($errorList);
}
