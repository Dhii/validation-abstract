<?php

namespace Dhii\Validation\FuncTest;

use Xpmock\TestCase;
use Dhii\Validation\ChildValidatorsAwareTrait as TestSubject;
use Traversable;
use ArrayIterator;
use InvalidArgumentException;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ChildValidatorsAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Validation\ChildValidatorsAwareTrait';

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
     * Creates a new instance of a spec.
     *
     * @since [*next-version*]
     *
     * @return Traversable The new spec.
     */
    protected function _createValidators($amount = 10)
    {
        $validators = [];
        for ($i = 0; $i < $amount; ++$i) {
            $mock = $this->mock('Dhii\Validation\ValidatorInterface')
                    ->validate()
                    ->new();
            $validators[] = $mock;
        }

        return new ArrayIterator($validators);
    }

    /*
     * Tests whether setting and getting a spec works as expected.
     *
     * @since [*next-version*]
     */
    public function testSetGetChildValidators()
    {
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);
        $data = $this->_createValidators();

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($data)
            ->will($this->returnValue($data));

        $this->assertCount(0, $_subject->_getChildValidators(), 'Initial subject state is wrong');

        $_subject->_setChildValidators($data);
        $this->assertSame($data, $_subject->_getChildValidators(), 'Altered subject state is wrong');
    }
}
