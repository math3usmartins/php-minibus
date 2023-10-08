<?php

namespace MiniBus\Test\Handler\Locator;

use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Handler\HandlerCollection;
use MiniBus\Handler\Locator\DefaultLocator;
use MiniBus\Test\Handler\StubHandler;
use MiniBus\Test\StubMessage;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MiniBus\Handler\Locator\DefaultLocator
 */
final class DefaultLocatorTest extends TestCase
{
    /**
     * @dataProvider scenarios
     */
    public function testItDoesLocate(
        DefaultLocator $locator,
        Envelope $envelope,
        HandlerCollection $expectedCollection
    ) {
        static::assertEquals($expectedCollection, $locator->locate($envelope));
    }

    public function scenarios()
    {
        yield 'no handlers found' => [
            'locator' => new DefaultLocator(
                new HandlerCollection()
            ),
            'envelope' => new BasicEnvelope(
                new StubMessage('some-subject', [], []),
                new StampCollection()
            ),
            'expected' => new HandlerCollection(),
        ];

        yield 'a single handler found' => [
            'locator' => new DefaultLocator(
                new HandlerCollection(
                    new StubHandler(true),
                    new StubHandler(false)
                )
            ),
            'envelope' => new BasicEnvelope(
                new StubMessage('some-subject', [], []),
                new StampCollection()
            ),
            'expected' => new HandlerCollection(
                new StubHandler(true)
            ),
        ];

        yield 'a couple handlers found' => [
            'locator' => new DefaultLocator(
                new HandlerCollection(
                    new StubHandler(true),
                    new StubHandler(true),
                    new StubHandler(false)
                )
            ),
            'envelope' => new BasicEnvelope(
                new StubMessage('some-subject', [], []),
                new StampCollection()
            ),
            'expected' => new HandlerCollection(
                new StubHandler(true),
                new StubHandler(true)
            ),
        ];
    }
}
