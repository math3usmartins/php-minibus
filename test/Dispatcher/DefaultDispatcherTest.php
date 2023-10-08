<?php

namespace MiniBus\Test\Dispatcher;

use Exception;
use Functional\Either\Left;
use Functional\Either\Right;
use MiniBus\Dispatcher\DefaultDispatcher;
use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\BasicEnvelopeFactory;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Handler\HandlingFailure;
use MiniBus\Middleware\MiddlewareStack;
use MiniBus\Test\Envelope\Stamp\StubStamp;
use MiniBus\Test\Middleware\CallbackMiddleware;
use MiniBus\Test\Middleware\FailingMiddleware;
use MiniBus\Test\StubMessage;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MiniBus\Dispatcher\DefaultDispatcher
 */
final class DefaultDispatcherTest extends TestCase
{
    public function testEmptyStack()
    {
        $dispatcher = new DefaultDispatcher(
            new BasicEnvelopeFactory(),
            new MiddlewareStack()
        );

        $givenEnvelope = new BasicEnvelope(
            $this->stubMessage(),
            new StampCollection()
        );

        $result = $dispatcher->dispatch($givenEnvelope->message(), new StampCollection());
        static::assertInstanceOf(Right::class, $result);

        $actualValue = $result->value();
        static::assertInstanceOf(Envelope::class, $actualValue);

        static::assertEquals($givenEnvelope, $actualValue);
    }

    public function testFailingStack()
    {
        $expectedException = new Exception('something went wrong');

        $dispatcher = new DefaultDispatcher(
            new BasicEnvelopeFactory(),
            new MiddlewareStack(
                new FailingMiddleware($expectedException)
            )
        );

        $givenEnvelope = new BasicEnvelope(
            $this->stubMessage(),
            new StampCollection()
        );

        $result = $dispatcher->dispatch($givenEnvelope->message(), new StampCollection());
        static::assertInstanceOf(Left::class, $result);

        $actualValue = $result->value();
        static::assertInstanceOf(HandlingFailure::class, $actualValue);

        $reason = $actualValue->reason();

        static::assertEquals($expectedException, $reason);
        static::assertEquals($givenEnvelope, $actualValue->envelope());
    }

    public function testSuccessfulStack()
    {
        $middleware = new CallbackMiddleware(function (Envelope $envelope) {
            return Right::fromValue(
                $envelope->withStamp(new StubStamp('sample-key', 'sample-value'))
            );
        });

        $dispatcher = new DefaultDispatcher(
            new BasicEnvelopeFactory(),
            new MiddlewareStack(
                $middleware
            )
        );

        $givenEnvelope = new BasicEnvelope(
            $this->stubMessage(),
            new StampCollection()
        );

        $result = $dispatcher->dispatch($givenEnvelope->message(), new StampCollection());
        static::assertInstanceOf(Right::class, $result);

        $actualValue = $result->value();

        /* @var Envelope $actualValue */
        static::assertInstanceOf(Envelope::class, $actualValue);

        /** @var StubStamp $stamp */
        $stamp = $actualValue->stamps()->last('sample-key');
        static::assertInstanceOf(StubStamp::class, $stamp);
        static::assertEquals('sample-value', $stamp->keyValue());
    }

    private function stubMessage()
    {
        return new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);
    }
}
