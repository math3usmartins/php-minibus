<?php

namespace MiniBus\Test\Middleware;

use Functional\Either\Left;
use Functional\Either\Right;
use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Middleware\MiddlewareStack;
use MiniBus\Test\Envelope\Stamp\StubStamp;
use MiniBus\Test\StubMessage;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MiniBus\Middleware\MiddlewareStack
 * @covers \MiniBus\Middleware\StackedMiddleware
 */
final class MiddlewareStackTest extends TestCase
{
    public function testHandleWithEmptyStack()
    {
        $envelope = new BasicEnvelope(
            $this->stubMessage(),
            new StampCollection()
        );

        $stack = new MiddlewareStack();

        static::assertEquals(
            Right::fromValue($envelope),
            $stack->handle($envelope)
        );
    }

    public function testHandleSuccess()
    {
        $envelope = new BasicEnvelope(
            $this->stubMessage(),
            new StampCollection()
        );

        $stack = new MiddlewareStack(
            $this->successfulMiddleware(
                $envelope->withStamp(new StubStamp('success', 'true'))
            ),
            $this->successfulMiddleware(
                $envelope
                    ->withStamp(new StubStamp('success', 'true'))
                    ->withStamp(new StubStamp('foo', 'bar'))
            )
        );

        static::assertEquals(
            Right::fromValue(
                $envelope
                    ->withStamp(new StubStamp('success', 'true'))
                    ->withStamp(new StubStamp('foo', 'bar'))
            ),
            $stack->handle($envelope)
        );
    }

    public function testHandleFailure()
    {
        $envelope = new BasicEnvelope(
            $this->stubMessage(),
            new StampCollection()
        );

        $failure = Left::fromValue('something went wrong');

        $stack = new MiddlewareStack(
            $this->failingMiddleware($failure),
            $this->successfulMiddleware(
                $envelope
                    ->withStamp(new StubStamp('success', 'true'))
                    ->withStamp(new StubStamp('foo', 'bar'))
            )
        );

        static::assertEquals(
            $failure,
            $stack->handle($envelope)
        );
    }

    private function successfulMiddleware(Envelope $outputEnvelope)
    {
        return new CallbackMiddleware(function () use ($outputEnvelope) {
            return Right::fromValue($outputEnvelope);
        });
    }

    private function failingMiddleware(Left $failure)
    {
        return new CallbackMiddleware(function () use ($failure) {
            return $failure;
        });
    }

    private function stubMessage()
    {
        return new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);
    }
}
