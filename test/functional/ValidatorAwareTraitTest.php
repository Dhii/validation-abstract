<?php

namespace Dhii\Validation\FuncTest;

use Xpmock\TestCase;
use Dhii\Validation\ValidatorAwareTrait as TestSubject;
use Dhii\Validation\ValidatorInterface;
use InvalidArgumentException;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ValidatorAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Validation\ValidatorAwareTrait';

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

    /**
     * Creates a new instance of a validator.
     *
     * @since [*next-version*]
     *
     * @return ValidatorInterface The new validator.
     */
    protected function _createValidator()
    {
        $mock = $this->mock('Dhii\Validation\ValidatorInterface')
                ->validate()
                ->new();

        return $mock;
    }

    /*
     * Tests whether setting and getting a validator works as expected.
     *
     * @since [*next-version*]
     */
    public function testSetGetValidator()
    {
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);
        $data = $this->_createValidator();

        $this->assertNull($_subject->_getValidator(), 'Initial subject state is wrong');

        $_subject->_setValidator($data);
        $this->assertSame($data, $_subject->_getValidator(), 'Altered subject state is wrong');
    }

    /**
     * Tests whether setting an invalid validator is disallowed.
     *
     * @since [*next-version*]
     */
    public function testSetValidatorFailure()
    {
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);
        $data = new \stdClass();

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_setValidator($data);
    }
}
