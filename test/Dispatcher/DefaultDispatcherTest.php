<?php

namespace MiniBus\Test\Dispatcher;

use Closure;
use Exception;
use MiniBus\Dispatcher\DefaultDispatcher;
use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\BasicEnvelopeFactory;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Message;
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
    /**
     * @dataProvider scenarios
     */
    public function testDispatch(
        Closure $dispatcherFactory,
        Message $message,
        StampCollection $stampCollection,
        Closure $assertionCallback
    ) {
        /** @var DefaultDispatcher $dispatcher */
        $dispatcher = $dispatcherFactory();
        static::assertInstanceOf(DefaultDispatcher::class, $dispatcher);

        try {
            $envelope = $dispatcher->dispatch($message, $stampCollection);
            $assertionCallback($envelope);
        } catch (Exception $exception) {
            $assertionCallback($exception);
        }
    }

    public function scenarios()
    {
        $envelopeFactory = new BasicEnvelopeFactory();
        $givenEnvelope = new BasicEnvelope(
            $this->stubMessage(),
            new StampCollection([])
        );

        yield 'empty stack must return initial envelope' => [
            'dispatcher factory' => function () use ($envelopeFactory) {
                return new DefaultDispatcher(
                    $envelopeFactory,
                    new MiddlewareStack([])
                );
            },
            'message' => $givenEnvelope->message(),
            'stamps' => $givenEnvelope->stamps(),
            'assertion callback' => function (Envelope $actualEnvelope) use ($givenEnvelope) {
                self::assertEquals($givenEnvelope, $actualEnvelope);
            },
        ];

        $expectedException = new Exception('something went wrong');

        yield 'failing stack must throw exception' => [
            'dispatcher factory' => function () use ($envelopeFactory, $expectedException) {
                return new DefaultDispatcher(
                    $envelopeFactory,
                    new MiddlewareStack([
                        new FailingMiddleware($expectedException),
                    ])
                );
            },
            'message' => $givenEnvelope->message(),
            'stamps' => $givenEnvelope->stamps(),
            'assertion callback' => function (Exception $exception) use ($expectedException) {
                self::assertEquals($expectedException, $exception);
            },
        ];

        $middleware = new CallbackMiddleware(function (Envelope $envelope) {
            return $envelope->withStamp(
                new StubStamp('sample-key', 'sample-value')
            );
        });

        yield 'successful stack must return another envelope' => [
            'dispatcher factory' => function () use ($envelopeFactory, $middleware) {
                return new DefaultDispatcher(
                    $envelopeFactory,
                    new MiddlewareStack([
                        $middleware,
                    ])
                );
            },
            'message' => $givenEnvelope->message(),
            'stamps' => $givenEnvelope->stamps(),
            'assertion callback' => function (Envelope $actualEnvelope) {
                /** @var StubStamp $stamp */
                $stamp = $actualEnvelope->stamps()->last('sample-key');
                self::assertInstanceOf(StubStamp::class, $stamp);

                self::assertEquals('sample-value', $stamp->keyValue());
            },
        ];
    }

    private function stubMessage()
    {
        return new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);
    }
}
