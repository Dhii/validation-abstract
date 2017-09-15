<?php

namespace Dhii\Validation\FuncTest;

use Xpmock\TestCase;
use Dhii\Validation\ValidationErrorsAwareTrait as TestSubject;
use ArrayIterator;
use InvalidArgumentException;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ValidationErrorsAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Validation\ValidationErrorsAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return object
     */
    public function createInstance()
    {
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME);
        $mock->method('__')->will($this->returnArgument(0));
        $mock->method('_createInvalidArgumentException')->will($this->returnCallback(function ($message) {
            return new InvalidArgumentException($message);
        }));

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType('object', $subject, 'A valid instance of the test subject could not be created');
    }

    /*
     * Tests whether setting and getting a validation error list works as expected.
     *
     * @since [*next-version*]
     */
    public function testSetGetValidationErrors()
    {
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);
        $data = new ArrayIterator(array('one', 'two'));

        $this->assertEquals(array(), $_subject->_getValidationErrors(), 'Initial subject state is wrong');

        $_subject->_setValidationErrors($data);
        $this->assertSame($data, $_subject->_getValidationErrors(), 'Altered subject state is wrong');
    }

    /**
     * Tests whether setting an invalid error list is disallowed.
     *
     * @since [*next-version*]
     */
    public function testSetValidatorFailure()
    {
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);
        $data = new \stdClass();

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_setValidationErrors($data);
    }
}
