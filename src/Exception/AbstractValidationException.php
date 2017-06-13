<?php

namespace Dhii\Validation\Exception;

/**
 * Common functionality for validation exceptions.
 *
 * @since 0.1
 */
class AbstractValidationException extends \Exception
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
}
