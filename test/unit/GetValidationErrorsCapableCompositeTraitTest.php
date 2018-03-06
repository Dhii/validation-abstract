<?php

namespace Dhii\Validation\UnitTest;

use Dhii\Validation\Exception\ValidationExceptionInterface;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Dhii\Validation\GetValidationErrorsCapableCompositeTrait as TestSubject;

use Dhii\Validation\SpecValidatorInterface;
use Dhii\Validation\ValidatorInterface;
use InvalidArgumentException;
use OutOfRangeException;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class GetValidationErrorsCapableCompositeTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Validation\GetValidationErrorsCapableCompositeTrait';

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
     * @param array $source      The array with more keys.
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
     * @param string   $className      Name of the class for the mock to extend.
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
     * @return RootException|MockObject The new exception.
     */
    public function createException($message = '')
    {
        $mock = $this->getMockBuilder('Exception')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new Invalid Argument exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return InvalidArgumentException|MockObject The new exception.
     */
    public function createInvalidArgumentException($message = '')
    {
        $mock = $this->getMockBuilder('InvalidArgumentException')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new Out of Range exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return OutOfRangeException|MockObject The new exception.
     */
    public function createOutOfRangeException($message = '')
    {
        $mock = $this->getMockBuilder('OutOfRangeException')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new Validation exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RootException|ValidationExceptionInterface|MockObject The new exception.
     */
    public function createValidationException($message = '')
    {
        $mock = $this->mockClassAndInterfaces('Exception', ['Dhii\Validation\Exception\ValidationExceptionInterface'])
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
     * @param array $methods The methods to mock.
     *
     * @return RootException|ValidationFailedExceptionInterface|MockObject The new exception.
     */
    public function createValidationFailedException($message = '', $methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
            'getSubject',
            'getValidator'
        ]);

        $mock = $this->mockClassAndInterfaces('Exception', ['Dhii\Validation\Exception\ValidationFailedExceptionInterface'])
            ->setConstructorArgs([$message])
            ->setMethods($methods)
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new validator.
     *
     * @since [*next-version*]
     *
     * @param string[]|null $methods The names of methods to mock.
     *
     * @return MockObject|ValidatorInterface The new validator.
     */
    public function createValidator($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
//            'validate',
        ]);
        $mock = $this->getMockBuilder('Dhii\Validation\ValidatorInterface')
            ->setMethods($methods)
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new spec validator.
     *
     * @since [*next-version*]
     *
     * @param string[]|null $methods The names of methods to mock.
     *
     * @return MockObject|SpecValidatorInterface The new spec validator.
     */
    public function createSpecValidator($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
        ]);
        $mock = $this->getMockBuilder('Dhii\Validation\SpecValidatorInterface')
            ->setMethods($methods)
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
     * Tests that `_getValidationErrors()` works as expected when there are errors produced by one of the child validators.
     *
     * @since [*next-version*]
     */
    public function testGetValidationErrorsHasErrors()
    {
        $val = uniqid('val');
        $spec = [uniqid('criterion')];
        $failureMessage = vsprintf('Validation of %1$s failed', [$val]);
        $failureMessages = [$failureMessage];
        $validator1 = $this->createValidator(['validate']);
        $validator2 = $this->createSpecValidator(['validate']);
        $validators = [$validator1, $validator2];
        $exception = $this->createValidationFailedException('Validation failed', ['getValidationErrors']);
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $exception->expects($this->exactly(1))
            ->method('getValidationErrors')
            ->will($this->returnValue($failureMessages));

        $validator1->expects($this->exactly(1))
            ->method('validate')
            ->with($val);
        $validator2->expects($this->exactly(1))
            ->method('validate')
            ->with($val, $spec)
            ->will($this->throwException($exception));

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($spec)
            ->will($this->returnValue($spec));
        $subject->expects($this->exactly(1))
            ->method('_getChildValidators')
            ->will($this->returnValue($validators));
        $subject->expects($this->exactly(1))
            ->method('_normalizeErrorList')
            ->with([$failureMessages])
            ->will($this->returnValue($failureMessages));

        $result = $_subject->_getValidationErrors($val, $spec);
        $this->assertEquals($failureMessages, $result, 'Wrong error message list');
    }

    /**
     * Tests that `_getValidationErrors()` works as expected when there are no errors produced by any of the validators.
     *
     * @since [*next-version*]
     */
    public function testGetValidationErrorsNoErrors()
    {
        $val = uniqid('val');
        $spec = null;
        $validator1 = $this->createValidator(['validate']);
        $validator2 = $this->createSpecValidator(['validate']);
        $validators = [$validator1, $validator2];
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $validator1->expects($this->exactly(1))
            ->method('validate')
            ->with($val);
        $validator2->expects($this->exactly(1))
            ->method('validate')
            ->with($val, $spec);
        $subject->expects($this->exactly(1))
            ->method('_getChildValidators')
            ->will($this->returnValue($validators));
        $subject->expects($this->exactly(1))
            ->method('_normalizeErrorList')
            ->with([])
            ->will($this->returnValue([]));

        $result = $_subject->_getValidationErrors($val, $spec);
        $this->assertEquals([], $result, 'Wrong error message list');
    }

    /**
     * Tests that `_getValidationErrors()` fails correctly when given an invalid list of criteria.
     *
     * @since [*next-version*]
     */
    public function testGetValidationErrorsFailureInvalidSpec()
    {
        $val = uniqid('value');
        $spec = uniqid('spec');
        $subject = $this->createInstance();
        $exception = $this->createInvalidArgumentException('Spec is not a valid list');
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($spec)
            ->will($this->throwException($exception));

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_getValidationErrors($val, $spec);
    }

    /**
     * Tests that `_getValidationErrors()` fails correctly when one of the child validators is not a validator.
     *
     * @since [*next-version*]
     */
    public function testGetValidationErrorsFailureInvalidValidator()
    {
        $val = uniqid('value');
        $spec = uniqid('spec');
        $validator = uniqid('validator');
        $validators = [$validator];
        $subject = $this->createInstance();
        $exception = $this->createOutOfRangeException('Not a validator');
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->will($this->returnValue($spec));
        $subject->expects($this->exactly(1))
            ->method('_getChildValidators')
            ->will($this->returnValue($validators));
        $subject->expects($this->exactly(1))
            ->method('_createOutOfRangeException')
            ->with(
                $this->isType('string'),
                null,
                null,
                $validator
            )
            ->will($this->returnValue($exception));

        $this->setExpectedException('OutOfRangeException');
        $_subject->_getValidationErrors($val, $spec);
    }
}
