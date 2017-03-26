<?php

namespace Dhii\Validation\FuncTest;

use Xpmock\TestCase;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;

/**
 * Tests {@see Dhii\Validation\AbstractValidator}.
 *
 * @since 0.1
 */
class AbstractValidatorTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since 0.1
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Validation\\AbstractValidator';

    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1
     *
     * @return AbstractValidator
     */
    public function createInstance()
    {
        $me = $this;
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                ->_createValidationException()
                ->_createValidationFailedException(function ($message) use (&$me) {
                    return $me->createValidationFailedException($message);
                })
                ->_getValidationErrors(function($subject) {
                    if ($subject !== true) {
                        return array('Subject must be a boolean `true` value');
                    }

                    return array();
                })
                ->new();

        return $mock;
    }

    /**
     * Creates a new validation failed exception.
     *
     * @since 0.1
     *
     * @return ValidationFailedExceptionInterface
     */
    public function createValidationFailedException($message)
    {
        $mock = $this->mock('Dhii\\Validation\\TestStub\\AbstractValidationFailedException')
                ->getValidationErrors()
                ->getSubject()
                ->new($message);

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since 0.1
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(static::TEST_SUBJECT_CLASSNAME, $subject, 'Could not create a valid instance');
    }

    /**
     * Tests whether validity is correctly determined.
     *
     * @since 0.1
     */
    public function testIsValid()
    {
        $subject = $this->createInstance();
        $me = $this;

        $reflection = $this->reflect($subject);
        $this->assertTrue($reflection->_isValid(true), 'Valid value not validated correctly');
        $this->assertFalse($reflection->_isValid(false), 'Invalid value not validated correctly');
    }
}
