<?php

declare(strict_types=1);

namespace MiniBus\Test\Handler;

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
     * @dataProvider provideItDoesAddStampCases
     */
    public function testItDoesAddStamp(
        HandlerMiddleware $middleware,
        Envelope $givenEnvelope,
        Envelope $expectedEnvelope,
    ): void {
        self::assertEquals($expectedEnvelope, $middleware->handle($givenEnvelope));
        self::assertTrue($expectedEnvelope->stamps()->contains(new HandlerStamp()));
    }

    public function provideItDoesAddStampCases(): iterable
    {
        $subject = 'some-subject';

        $envelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'h'], ['body' => 'v']),
            new StampCollection([]),
        );

        yield 'no handler found' => [
            'middleware' => new HandlerMiddleware(
                new StubHandlerLocator(
                    new HandlerCollection([]),
                ),
            ),
            'given envelope' => $envelope,
            'expected' => $envelope->withStamp(new HandlerStamp()),
        ];

        $anotherEnvelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'x'], ['body' => 'z']),
            new StampCollection([]),
        );

        yield 'it does add stamp to envelope returned by handler' => [
            'middleware' => new HandlerMiddleware(
                new StubHandlerLocator(
                    new HandlerCollection([
                        new StubHandler($anotherEnvelope),
                    ]),
                ),
            ),
            'given envelope' => $envelope,
            'expected' => $anotherEnvelope->withStamp(new HandlerStamp()),
        ];
    }
}
