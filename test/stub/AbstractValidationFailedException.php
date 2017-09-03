<?php

namespace Dhii\Validation\TestStub;

use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Exception;
use Dhii\Validation\Exception\AbstractValidationFailedException as BaseFailedException;

/**
 * Enables the mock of `ValidationFailedExceptionInterface` to be a valid
 * exception.
 *
 * @since 0.1
 */
abstract class AbstractValidationFailedException extends BaseFailedException implements ValidationFailedExceptionInterface
{
    /**
     * @since [*next-version*]
     *
     * @param string    $message
     * @param int       $code
     * @param Exception $previous
     * @param mixed     $subject  The validation subject
     * @param array     $errors   The error list.
     */
    public function __construct($message = '', $code = 0, Exception $previous = null, $subject = null, $errors = array())
    {
        parent::__construct($message, $code, $previous);
        $this->_setValidationErrors($errors);
        $this->_setValidationSubject($subject);
    }
}
