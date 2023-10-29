<?php

declare(strict_types=1);

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
 *
 * @coversNothing
 */
final class DefaultDispatcherTest extends TestCase
{
    /**
     * @dataProvider provideDispatchCases
     */
    public function testDispatch(
        Closure $dispatcherFactory,
        Message $message,
        StampCollection $stampCollection,
        Closure $assertionCallback,
    ): void {
        /** @var DefaultDispatcher $dispatcher */
        $dispatcher = $dispatcherFactory();
        self::assertInstanceOf(DefaultDispatcher::class, $dispatcher);

        try {
            $envelope = $dispatcher->dispatch($message, $stampCollection);
            $assertionCallback($envelope);
        } catch (Exception $exception) {
            $assertionCallback($exception);
        }
    }

    public function provideDispatchCases(): iterable
    {
        $envelopeFactory = new BasicEnvelopeFactory();
        $givenEnvelope = new BasicEnvelope(
            $this->stubMessage(),
            new StampCollection([]),
        );

        yield 'empty stack must return initial envelope' => [
            'dispatcher factory' => static fn () => new DefaultDispatcher(
                $envelopeFactory,
                new MiddlewareStack([]),
            ),
            'message' => $givenEnvelope->message(),
            'stamps' => $givenEnvelope->stamps(),
            'assertion callback' => static function (Envelope $actualEnvelope) use ($givenEnvelope): void {
                self::assertEquals($givenEnvelope, $actualEnvelope);
            },
        ];

        $expectedException = new Exception('something went wrong');

        yield 'failing stack must throw exception' => [
            'dispatcher factory' => static fn () => new DefaultDispatcher(
                $envelopeFactory,
                new MiddlewareStack([
                    new FailingMiddleware($expectedException),
                ]),
            ),
            'message' => $givenEnvelope->message(),
            'stamps' => $givenEnvelope->stamps(),
            'assertion callback' => static function (Exception $exception) use ($expectedException): void {
                self::assertEquals($expectedException, $exception);
            },
        ];

        $middleware = new CallbackMiddleware(static fn (Envelope $envelope) => $envelope->withStamp(
            new StubStamp('sample-key', 'sample-value'),
        ));

        yield 'successful stack must return another envelope' => [
            'dispatcher factory' => static fn () => new DefaultDispatcher(
                $envelopeFactory,
                new MiddlewareStack([
                    $middleware,
                ]),
            ),
            'message' => $givenEnvelope->message(),
            'stamps' => $givenEnvelope->stamps(),
            'assertion callback' => static function (Envelope $actualEnvelope): void {
                /** @var StubStamp $stamp */
                $stamp = $actualEnvelope->stamps()->last('sample-key');
                self::assertInstanceOf(StubStamp::class, $stamp);

                self::assertEquals('sample-value', $stamp->keyValue());
            },
        ];
    }

    private function stubMessage(): StubMessage
    {
        return new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);
    }
}
