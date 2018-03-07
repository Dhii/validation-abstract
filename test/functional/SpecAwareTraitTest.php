<?php

namespace Dhii\Validation\FuncTest;

use Xpmock\TestCase;
use Dhii\Validation\SpecAwareTrait as TestSubject;
use Traversable;
use ArrayIterator;
use InvalidArgumentException;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class SpecAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Validation\SpecAwareTrait';

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

    /**
     * Creates a new instance of a spec.
     *
     * @since [*next-version*]
     *
     * @return Traversable The new spec.
     */
    protected function _createSpec(array $criteria = [])
    {
        return new ArrayIterator($criteria);
    }

    /*
     * Tests whether setting and getting a spec works as expected.
     *
     * @since [*next-version*]
     */
    public function testSetGetSpec()
    {
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);
        $data = $this->_createSpec();

        $this->assertNull($_subject->_getSpec(), 'Initial subject state is wrong');

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($data)
            ->will($this->returnValue($data));

        $_subject->_setSpec($data);
        $this->assertSame($data, $_subject->_getSpec(), 'Altered subject state is wrong');
    }

    /*
     * Tests whether setting and getting a null spec works as expected.
     *
     * @since [*next-version*]
     */
    public function testSetGetSpecNull()
    {
        $data = null;
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $_subject->spec = [uniqid('criterion')];

        $_subject->_setSpec($data);
        $this->assertSame($data, $_subject->_getSpec(), 'Altered subject state is wrong');
    }

    /**
     * Tests whether setting an invalid spec is disallowed.
     *
     * @since [*next-version*]
     */
    public function testSetSpecFailure()
    {
        $data = new \stdClass();
        $exception = new InvalidArgumentException('Invalid iterable');
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($data)
            ->will($this->throwException($exception));

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_setSpec($data);
    }
}
