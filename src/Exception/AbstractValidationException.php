<?php

namespace Dhii\Validation\Exception;

use Exception as RootException;

/**
 * Common functionality for validation exceptions.
 *
 * @since 0.1
 */
abstract class AbstractValidationException extends RootException
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
