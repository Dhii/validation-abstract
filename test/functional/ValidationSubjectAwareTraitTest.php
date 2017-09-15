<?php

namespace Dhii\Validation\FuncTest;

use Xpmock\TestCase;
use Dhii\Validation\ValidationSubjectAwareTrait as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ValidationSubjectAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Validation\ValidationSubjectAwareTrait';

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
     * Tests whether setting and getting a subject works as expected.
     *
     * @since [*next-version*]
     */
    public function testSetGetSubject()
    {
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);
        $data = uniqid('subject-');

        $this->assertNull($_subject->_getValidationSubject(), 'Initial subject state is wrong');

        $_subject->_setValidationSubject($data);
        $this->assertSame($data, $_subject->_getValidationSubject(), 'Altered subject state is wrong');
    }
}
