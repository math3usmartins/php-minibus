<?php

declare(strict_types=1);

namespace MiniBus\Test\Handler\Locator;

use Generator;
use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Handler\HandlerCollection;
use MiniBus\Handler\Locator\SubjectLocator;
use MiniBus\Test\Handler\StubHandler;
use MiniBus\Test\StubMessage;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MiniBus\Handler\Locator\SubjectLocator
 */
final class SubjectLocatorTest extends TestCase
{
    /**
     * @dataProvider scenarios
     */
    public function testItDoesLocatePerSubject(
        SubjectLocator $locator,
        Envelope $envelope,
        HandlerCollection $expectedCollection
    ) {
        static::assertEquals($expectedCollection, $locator->locate($envelope));
    }

    public function scenarios(): Generator
    {
        $collection = new HandlerCollection([
            new StubHandler(),
        ]);

        $anotherLocation = new HandlerCollection([
            new StubHandler(),
            new StubHandler(),
        ]);

        $locator = new SubjectLocator([
            'some-subject' => $collection,
            'another-subject' => $anotherLocation,
        ]);

        yield 'no handlers found' => [
            'locator' => $locator,
            'envelope' => new BasicEnvelope(
                new StubMessage('yet-another-subject', [], []),
                new StampCollection([])
            ),
            'expected' => new HandlerCollection([]),
        ];

        yield 'a collection found' => [
            'locator' => $locator,
            'envelope' => new BasicEnvelope(
                new StubMessage('some-subject', [], []),
                new StampCollection([])
            ),
            'expected' => $collection,
        ];

        yield 'another collection found' => [
            'locator' => $locator,
            'envelope' => new BasicEnvelope(
                new StubMessage('another-subject', [], []),
                new StampCollection([])
            ),
            'expected' => $anotherLocation,
        ];
    }
}
