<?php

namespace Dhii\Validation\UnitTest;

use Xpmock\TestCase;
use Traversable;
use Iterator;
use ArrayIterator;
use IteratorIterator;
use AppendIterator;
use Dhii\Validation\Exception\ValidationFailedExceptionInterface;
use Dhii\Validation\Exception\AbstractCompositeValidator as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class AbstractCompositeValidatorTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Validation\AbstractCompositeValidator';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return TestSubject
     */
    public function createInstance($childValidators = null)
    {
        $me = $this;
        if (is_null($childValidators)) {
            $childValidators = [];
        }
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                ->_createValidationException()
                ->_createValidationFailedException(function ($message = null, $code = null, $exception = null, $subject = null, $errors = null) use (&$me) {
                    return $me->createValidationFailedException($message, $code, $exception, $this, $subject, $errors);
                })
                ->_normalizeErrorList(function ($errorList) use (&$me) {
                    $listIterator = new AppendIterator();
                    foreach ($errorList as $_error) {
                        $listIterator->append($me->normalizeIterator($_error));
                    }

                    return $listIterator;
                })
                ->__(function ($string) {
                    return $string;
                })
                ->_countIterable($this->returnCallback(function ($iterable) {
                    return $iterable instanceof Traversable
                            ? iterator_to_array($iterable)
                            : count($iterable);

                }))
                ->_getChildValidators($childValidators)
                ->new();

        return $mock;
    }

    /**
     * Normalizes a value into an Iterator.
     *
     * If value is not iterable, it will be treated as a set of itself.
     *
     * @since [*next-version*]
     *
     * @param array|Traversable|mixed $value The value to normalize.
     *
     * @return Iterator The iterator.
     */
    public function normalizeIterator($value)
    {
        if (!is_array($value) && !($value instanceof Traversable)) {
            $value = [$value];
        }

        if ($value instanceof Iterator) {
            return $value;
        }

        if (is_array($value)) {
            return new ArrayIterator($value);
        }

        return new IteratorIterator($value);
    }

    /**
     * Creates a new validation failed exception.
     *
     * @since [*next-version*]
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
     * Creates a new list of validators.
     *
     * @param array|Traversable $messages A list of messages for the validators to fail with, one per validator.
     *                                    Falsy value indicates that the validator should pass.
     *
     * @since [*next-version*]
     *
     * @return Traversable The new spec.
     */
    protected function _createValidators($messages = [])
    {
        $me = $this;
        $validators = [];
        foreach ($messages as $_idx => $_message) {
            $mock = $this->mock('Dhii\Validation\ValidatorInterface')
                    ->validate(function ($subject) use ($_message, &$me) {
                        if (!$_message) {
                            return;
                        }

                        throw $me->createValidationFailedException('Validation failed', null, null, $this, $subject, [$_message]);
                    })
                    ->new();
            $validators[] = $mock;
        }

        return new ArrayIterator($validators);
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(static::TEST_SUBJECT_CLASSNAME, $subject, 'Could not create a valid instance');
    }

    /**
     * Tests whether failed validation throws a correctly populated exception when one of the children fails.
     *
     * @since [*next-version*]
     */
    public function testValidateFailure()
    {
        // 4 validatos, one passes, the rest fail
        $data = [
            uniqid('message1-'),
            uniqid('message2-'),
            null,
            uniqid('message3-'),
        ];
        $messages = array_values(array_filter($data));
        $children = $this->_createValidators($data);
        $subject = $this->createInstance($children);
        $_subject = $this->reflect($subject);

        $errors = iterator_to_array($_subject->_getValidationErrors(uniqid()), false);
        $this->assertEquals($messages, $errors, 'Validation error message list is wrong', 0.0, 10, true);
    }
}
