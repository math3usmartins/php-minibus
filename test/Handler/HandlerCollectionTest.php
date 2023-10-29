<?php

declare(strict_types=1);

namespace MiniBus\Test\Handler;

use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Handler\HandlerCollection;
use MiniBus\Test\StubMessage;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \MiniBus\Envelope\BasicEnvelope
 * @covers \MiniBus\Handler\HandlerCollection
 */
final class HandlerCollectionTest extends TestCase
{
    /**
     * @dataProvider provideHandleCases
     */
    public function testHandle(
        HandlerCollection $collection,
        Envelope $envelope,
        Envelope $expected,
    ): void {
        self::assertEquals($expected, $collection->handle($envelope));
    }

    public function provideHandleCases(): iterable
    {
        $subject = 'some-subject';

        $envelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'h'], ['body' => 'v']),
            new StampCollection([]),
        );

        yield 'empty collection' => [
            'collection' => new HandlerCollection([]),
            'envelope' => $envelope,
            'expected' => $envelope,
        ];

        $anotherEnvelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'x'], ['body' => 'z']),
            new StampCollection([]),
        );

        $yetAnotherEnvelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'xz'], ['body' => 'zy']),
            new StampCollection([]),
        );

        yield 'single handler' => [
            'collection' => new HandlerCollection([
                new StubHandler($anotherEnvelope),
            ]),
            'envelope' => $envelope,
            'expected' => $anotherEnvelope,
        ];

        yield 'multiple handlers' => [
            'collection' => new HandlerCollection([
                new StubHandler($anotherEnvelope),
                new StubHandler($yetAnotherEnvelope),
            ]),
            'envelope' => $envelope,
            // p.s. it MUST return the result of the last handler
            'expected' => $yetAnotherEnvelope,
        ];
    }
}
