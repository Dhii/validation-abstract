<?php

namespace Dhii\Validation\UnitTest;

use Dhii\Validation\Exception\ValidationExceptionInterface;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Dhii\Validation\IsValidCapableTrait as TestSubject;

use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class IsValidCapableTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Validation\IsValidCapableTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return MockObject The new instance.
     */
    public function createInstance($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
            '__',
        ]);

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
            ->setMethods($methods)
            ->getMockForTrait();

        $mock->method('__')
            ->will($this->returnArgument(0));

        return $mock;
    }

    /**
     * Merges the values of two arrays.
     *
     * The resulting product will be a numeric array where the values of both inputs are present, without duplicates.
     *
     * @since [*next-version*]
     *
     * @param array $destination The base array.
     * @param array $source The array with more keys.
     *
     * @return array The array which contains unique values
     */
    public function mergeValues($destination, $source)
    {
        return array_keys(array_merge(array_flip($destination), array_flip($source)));
    }

    /**
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since [*next-version*]
     *
     * @param string $className Name of the class for the mock to extend.
     * @param string[] $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return MockBuilder The builder for a mock of an object that extends and implements
     *                     the specified class and interfaces.
     */
    public function mockClassAndInterfaces($className, $interfaceNames = [])
    {
        $paddingClassName = uniqid($className);
        $definition = vsprintf('abstract class %1$s extends %2$s implements %3$s {}', [
            $paddingClassName,
            $className,
            implode(', ', $interfaceNames),
        ]);
        eval($definition);

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Creates a new exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RootException The new exception.
     */
    public function createException($message = '')
    {
        $mock = $this->getMockBuilder('Exception')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new Validation Failed exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RootException|ValidationFailedExceptionInterface|MockObject The new exception.
     */
    public function createValidationFailedException($message = '')
    {
        $mock = $this->mockClassAndInterfaces('Exception', ['Dhii\Validation\Exception\ValidationFailedExceptionInterface'])
            ->setConstructorArgs([$message])
            ->getMock();

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

        $this->assertInternalType(
            'object',
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests that `_isValid()` works as expected when the subject is valid.
     *
     * @since [*next-version*]
     */
    public function testIsValidValid()
    {
        $val = uniqid('val');
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_validate')
            ->with($val);

        $result = $_subject->_isValid($val);
        $this->assertTrue($result, 'Validation status determined incorrectly');
    }

    /**
     * Tests that `_isValid()` works as expected when the subject is invalid.
     *
     * @since [*next-version*]
     */
    public function testIsValidInvalid()
    {
        $val = uniqid('val');
        $exception = $this->createValidationFailedException('Subject is invalid');
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_validate')
            ->with($val)
            ->will($this->throwException($exception));

        $result = $_subject->_isValid($val);
        $this->assertFalse($result, 'Validation status determined incorrectly');
    }

    /**
     * Tests that `_isValid()` fails as expected when problem validating.
     *
     * @since [*next-version*]
     */
    public function testIsValidFailureProblemValidating()
    {
        $val = uniqid('val');
        $exception = $this->createException('Problem validating');
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_validate')
            ->with($val)
            ->will($this->throwException($exception));

        $this->setExpectedException('Exception');
        $_subject->_isValid($val);
    }
}
