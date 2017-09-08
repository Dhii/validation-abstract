<?php

namespace Dhii\Validation;

use Traversable;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;

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
}
