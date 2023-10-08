<?php

namespace MiniBus\Test\Handler;

use Functional\Either\Right;
use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Handler\HandlerCollection;
use MiniBus\Handler\HandlerMiddleware;
use MiniBus\Handler\HandlerStamp;
use MiniBus\Test\StubMessage;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MiniBus\Envelope\BasicEnvelope
 * @covers \MiniBus\Handler\HandlerMiddleware
 * @covers \MiniBus\Handler\HandlerStamp
 *
 * @internal
 */
final class HandlerMiddlewareTest extends TestCase
{
    /**
     * @dataProvider itDoesAddStampScenarios
     */
    public function testItDoesAddStamp(
        HandlerMiddleware $middleware,
        Envelope $givenEnvelope,
        Envelope $expectedEnvelope
    ) {
        $actual = $middleware->handle($givenEnvelope);
        static::assertInstanceOf(Right::class, $actual);
        static::assertEquals($expectedEnvelope, $actual->value());
        static::assertTrue($expectedEnvelope->stamps()->contains(new HandlerStamp()));
    }

    public function itDoesAddStampScenarios()
    {
        $subject = 'some-subject';

        $envelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'h'], ['body' => 'v']),
            new StampCollection()
        );

        yield 'no handler found' => [
            'middleware' => new HandlerMiddleware(
                new StubHandlerLocator(
                    new HandlerCollection()
                )
            ),
            'given envelope' => $envelope,
            'expected' => $envelope->withStamp(new HandlerStamp()),
        ];

        $anotherEnvelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'x'], ['body' => 'z']),
            new StampCollection()
        );

        yield 'it does add stamp to envelope returned by handler' => [
            'middleware' => new HandlerMiddleware(
                new StubHandlerLocator(
                    new HandlerCollection(
                        new StubHandler(true, $anotherEnvelope)
                    )
                )
            ),
            'given envelope' => $envelope,
            'expected' => $anotherEnvelope->withStamp(new HandlerStamp()),
        ];
    }
}
