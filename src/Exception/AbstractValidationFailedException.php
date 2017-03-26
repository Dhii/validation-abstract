<?php

namespace Dhii\Validation\Exception;

use Traversable;

/**
 * Common functionality for validation exceptions.
 *
 * @since 0.1
 */
abstract class AbstractValidationFailedException extends AbstractValidationException
{
    /**
     * The list of validation errors associated with this instance.
     *
     * @since 0.1
     *
     * @var array|Traversable
     */
    protected $validationErrors;

    /**
     * The subject of validation associated with this instance.
     *
     * @since 0.1
     *
     * @var mixed
     */
    protected $validationSubject;

    /**
     * Retrieve the list of validation errors that this instance represents.
     *
     * @since 0.1
     *
     * @return array|Traversable The error list.
     */
    protected function _getValidationErrors()
    {
        if (!$this->_isValidList($this->validationErrors)) {
            $this->validationErrors = array();
        }

        return $this->validationErrors;
    }

    /**
     * Sets the list of validation errors that this instance should represent.
     *
     * @since 0.1
     *
     * @param array|Traversable $errorList The list of errors.
     *
     * @return $this This instance.
     */
    protected function _setValidationErrors($errorList)
    {
        $this->_assertList($errorList);
        $this->validationErrors = $errorList;

        return $this;
    }

    /**
     * Associate an invalid subject with this instance.
     *
     * @param mixed $subject The invalid subject.
     *
     * @return $this This instance.
     */
    protected function _setValidationSubject($subject)
    {
        $this->validationSubject = $subject;

        return $this;
    }

    /**
     * Retrieves the invalid subject associated with this instance.
     *
     * @since 0.1
     *
     * @return mixed The subject.
     */
    protected function _getValidationSubject()
    {
        return $this->validationSubject;
    }

    /**
     * Throws an exception if the given list is invalid.
     *
     * @since 0.1
     *
     * @param Traversable $list
     *
     * @throws AbstractValidationException If list is invalid.
     *
     * @return AbstractSourceValidationFailureException This instance.
     */
    protected function _assertList($list)
    {
        if (!$this->_isValidList($list)) {
            throw $this->_createValidationException('The list is invalid');
        }

        return $this;
    }

    /**
     * Creates a new instance of a validation exception.
     *
     * @since 0.1
     * @see \Exception::__construct()
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous An inner exception, if any.
     *
     * @return ValidationExceptionInterface
     */
    abstract protected function _createValidationException($message, $code = 0, \Exception $previous = null);

    /**
     * Determines if the given list is valid.
     *
     * @since 0.1
     *
     * @param array|Traversable $list The list to validate.
     *
     * @return bool True if the given list is valid; false otherwise.
     */
    protected function _isValidList($list)
    {
        if (!is_array($list) && !($list instanceof Traversable)) {
            return false;
        }

        return true;
    }
}
