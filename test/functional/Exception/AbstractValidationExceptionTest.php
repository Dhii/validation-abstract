<?php

namespace Dhii\Validation\FuncTest;

use Xpmock\TestCase;

/**
 * Tests {@see Dhii\Validation\Exception\AbstractValidationException}.
 *
 * @since 0.1
 */
class AbstractValidationExceptionTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since 0.1
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Validation\\Exception\\AbstractValidationException';

    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1
     *
     * @return AbstractValidationException
     */
    public function createInstance()
    {
        $me = $this;
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
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
        $this->assertInstanceOf('Exception', $subject, 'Subject must be a valid exception');
    }
}
