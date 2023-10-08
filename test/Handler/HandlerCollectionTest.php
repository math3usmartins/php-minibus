<?php

namespace MiniBus\Test\Handler;

use Functional\Either\Right;
use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Handler;
use MiniBus\Handler\HandlerCollection;
use MiniBus\Test\StubMessage;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MiniBus\Envelope\BasicEnvelope
 * @covers \MiniBus\Handler\HandlerCollection
 */
final class HandlerCollectionTest extends TestCase
{
    /**
     * @dataProvider handleScenarios
     */
    public function testHandle(
        HandlerCollection $collection,
        Envelope $envelope,
        Envelope $expected
    ) {
        $actual = $collection->handle($envelope);

        static::assertInstanceOf(Right::class, $actual);
        static::assertEquals($expected, $actual->value());
    }

    public function handleScenarios()
    {
        $subject = 'some-subject';

        $envelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'h'], ['body' => 'v']),
            new StampCollection()
        );

        yield 'empty collection' => [
            'collection' => new HandlerCollection(),
            'envelope' => $envelope,
            'expected' => $envelope,
        ];

        $anotherEnvelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'x'], ['body' => 'z']),
            new StampCollection()
        );

        $yetAnotherEnvelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'xz'], ['body' => 'zy']),
            new StampCollection()
        );

        yield 'single handler' => [
            'collection' => new HandlerCollection(
                new StubHandler(true, $anotherEnvelope)
            ),
            'envelope' => $envelope,
            'expected' => $anotherEnvelope,
        ];

        yield 'multiple handlers' => [
            'collection' => new HandlerCollection(
                new StubHandler(true, $anotherEnvelope),
                new StubHandler(true, $yetAnotherEnvelope)
            ),
            'envelope' => $envelope,
            // p.s. it MUST return the result of the last handler
            'expected' => $yetAnotherEnvelope,
        ];
    }

    /**
     * @dataProvider findForEnvelopeScenarios
     */
    public function testFindForEnvelope(
        HandlerCollection $collection,
        Envelope $envelope,
        HandlerCollection $expected
    ) {
        $actual = $collection->findForEnvelope($envelope);

        static::assertEquals($expected, $actual);
    }

    public function findForEnvelopeScenarios()
    {
        $subject = 'some-subject';

        $envelope = new BasicEnvelope(
            new StubMessage($subject, ['header' => 'h'], ['body' => 'v']),
            new StampCollection()
        );

        yield 'empty collection' => [
            'collection' => new HandlerCollection(),
            'envelope' => $envelope,
            'expected' => new HandlerCollection(),
        ];

        yield 'no handlers' => [
            'collection' => new HandlerCollection(
                new StubHandler(false),
                new StubHandler(false)
            ),
            'envelope' => $envelope,
            'expected' => new HandlerCollection(),
        ];

        yield 'single handler' => [
            'collection' => new HandlerCollection(
                new StubHandler(true),
                new StubHandler(false)
            ),
            'envelope' => $envelope,
            'expected' => new HandlerCollection(
                new StubHandler(true)
            ),
        ];

        yield 'couple handlers' => [
            'collection' => new HandlerCollection(
                new StubHandler(true),
                new StubHandler(true)
            ),
            'envelope' => $envelope,
            'expected' => new HandlerCollection(
                new StubHandler(true),
                new StubHandler(true)
            ),
        ];
    }

    /**
     * @dataProvider withScenarios
     */
    public function testWith(
        HandlerCollection $collection,
        Handler $handler,
        HandlerCollection $expected
    ) {
        $actual = $collection->with($handler);

        static::assertEquals($expected, $actual);
    }

    public function withScenarios()
    {
        yield 'single handler added to empty collection' => [
            'collection' => new HandlerCollection(),
            'handler' => new StubHandler(true),
            'expected' => new HandlerCollection(
                new StubHandler(true)
            ),
        ];

        yield 'single handler added to non-empty collection' => [
            'collection' => new HandlerCollection(
                new StubHandler(true)
            ),
            'handler' => new StubHandler(false),
            'expected' => new HandlerCollection(
                new StubHandler(true),
                new StubHandler(false)
            ),
        ];
    }
}
