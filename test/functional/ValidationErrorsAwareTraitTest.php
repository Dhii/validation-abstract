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

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($data)
            ->will($this->returnValue($data));

        $this->assertEquals(array(), $_subject->_getValidationErrors(), 'Initial subject state is wrong');

        $_subject->_setValidationErrors($data);
        $this->assertSame($data, $_subject->_getValidationErrors(), 'Altered subject state is wrong');
    }

    /*
     * Tests whether setting and getting a validation error list works as expected when given null.
     *
     * @since [*next-version*]
     */
    public function testSetGetValidationErrorsNull()
    {
        $data = null;
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $_subject->validationErrors = [];
        $_subject->_setValidationErrors($data);
        $this->assertSame($data, $_subject->validationErrors, 'Altered subject state is wrong');
    }

    /**
     * Tests whether setting an invalid error list is disallowed.
     *
     * @since [*next-version*]
     */
    public function testSetValidatorFailure()
    {
        $data = uniqid('iterable');
        $exception = new InvalidArgumentException('Invalid iterable');
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($data)
            ->will($this->throwException($exception));

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_setValidationErrors($data);
    }
}
