<?php

namespace Dhii\Validation\FuncTest;

use Xpmock\TestCase;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Dhii\Validation\Exception\AbstractValidator as TestSubject;

/**
 * Tests {@see TestSubject}.
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
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Validation\AbstractValidator';

    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1
     *
     * @return TestSubject
     */
    public function createInstance()
    {
        $me = $this;
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                ->_createValidationException()
                ->_createValidationFailedException(function ($message = null, $code = null, $exception = null, $validator = null, $subject = null, $errors = null) use (&$me) {
                    return $me->createValidationFailedException($message, $code, $exception, $validator, $subject, $errors);
                })
                ->_getValidationErrors(function ($subject) {
                    if ($subject !== true) {
                        return array('Subject must be a boolean `true` value');
                    }

                    return array();
                })
                ->__(function ($string) {
                    return $string;
                })
                ->_countIterable($this->returnCallback(function ($iterable) { return count($iterable); }))
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
    public function createValidationFailedException($message = null, $code = null, $previous = null, $validator = null, $subject = null, $errors = null)
    {
        $mock = $this->mock('Dhii\Validation\TestStub\AbstractValidationFailedException')
                ->getValidator($this->returnValue($validator))
                ->getValidationErrors(function () use ($errors) {return $errors;})
                ->getSubject(function () use ($subject) {return $subject;})
                ->getMessage($this->returnValue($message))
                ->getCode($this->returnValue($code))
                ->getPrevious($this->returnValue($previous))
                ->new();

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

        $reflection = $this->reflect($subject);
        $this->assertTrue($reflection->_isValid(true), 'Valid value not validated correctly');
        $this->assertFalse($reflection->_isValid(false), 'Invalid value not validated correctly');
    }

    /**
     * Tests whether failed validation throws a correctly populated exception.
     *
     * @since [*next-version*]
     */
    public function testValidate()
    {
        $subject = $this->createInstance();
        $reflection = $this->reflect($subject);
        $value = uniqid('subject-');

        try {
            $reflection->_validate($value);
        } catch (ValidationFailedExceptionInterface $e) {
            $this->assertSame($e->getSubject(), $value, 'Validation exception must keep track of invalid subject');
            $errors = $e->getValidationErrors();
            $this->assertNotEmpty($errors, 'Validation exception must provide some error text');

            return;
        }

        $this->assertTrue(false, 'Validation was supposed to fail');
    }
}
