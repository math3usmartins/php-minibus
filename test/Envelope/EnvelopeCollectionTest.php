<?php

namespace MiniBus\Test\Envelope;

use Closure;
use MiniBus\Envelope;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Test\Envelope\Stamp\StubStamp;
use MiniBus\Test\StubMessage;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MiniBus\Envelope\EnvelopeCollection
 *
 * @internal
 */
final class EnvelopeCollectionTest extends TestCase
{
    /**
     * @dataProvider mapScenarios
     */
    public function testMap(
        EnvelopeCollection $givenCollection,
        Closure $closure,
        EnvelopeCollection $expectedCollection
    ) {
        static::assertEquals($expectedCollection->items(), $givenCollection->map($closure));
    }

    public function mapScenarios()
    {
        $message = new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);
        $envelope = new BasicEnvelope($message, new StampCollection());
        $stamp = new StubStamp('key', 'value');

        yield 'add stamp' => [
            'collection' => new EnvelopeCollection([$envelope]),
            'callback' => function (Envelope $envelope) use ($stamp) {
                return $envelope->withStamp($stamp);
            },
            'expected collection' => new EnvelopeCollection([
                $envelope->withStamp($stamp),
            ]),
        ];
    }

    /**
     * @dataProvider filterScenarios
     */
    public function testFilter(
        EnvelopeCollection $givenCollection,
        Closure $closure,
        EnvelopeCollection $expectedCollection
    ) {
        static::assertEquals($expectedCollection, $givenCollection->filter($closure));
    }

    public function filterScenarios()
    {
        $message = new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);

        $envelope = new BasicEnvelope($message, new StampCollection(
            new StubStamp('stamp-name', 'stamp-value')
        ));

        $anotherStamp = new StubStamp('another-stamp-name', 'another-stamp-value');
        $anotherEnvelope = new BasicEnvelope($message, new StampCollection(
            $anotherStamp
        ));

        yield 'no matching items' => [
            'collection' => new EnvelopeCollection([$envelope, $anotherEnvelope]),
            'callback' => function () {
                return false;
            },
            'expected collection' => new EnvelopeCollection([]),
        ];

        yield 'all items matching' => [
            'collection' => new EnvelopeCollection([$envelope, $anotherEnvelope]),
            'callback' => function () {
                return true;
            },
            'expected collection' => new EnvelopeCollection([$envelope, $anotherEnvelope]),
        ];

        yield 'single matching item' => [
            'collection' => new EnvelopeCollection([$envelope, $anotherEnvelope]),
            'callback' => function (Envelope $envelope) use ($anotherStamp) {
                return $envelope->stamps()->contains($anotherStamp);
            },
            'expected collection' => new EnvelopeCollection([
                $anotherEnvelope,
            ]),
        ];
    }
}
