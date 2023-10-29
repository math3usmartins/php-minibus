<?php

declare(strict_types=1);

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
     * @dataProvider provideMapCases
     */
    public function testMap(
        EnvelopeCollection $givenCollection,
        Closure $closure,
        EnvelopeCollection $expectedCollection,
    ): void {
        self::assertEquals($expectedCollection, $givenCollection->map($closure));
    }

    public function provideMapCases(): iterable
    {
        $message = new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);
        $envelope = new BasicEnvelope($message, new StampCollection([]));
        $stamp = new StubStamp('key', 'value');

        yield 'add stamp' => [
            'collection' => new EnvelopeCollection([$envelope]),
            'callback' => static fn (Envelope $envelope) => $envelope->withStamp($stamp),
            'expected collection' => new EnvelopeCollection([
                $envelope->withStamp($stamp),
            ]),
        ];
    }

    /**
     * @dataProvider provideFilterCases
     */
    public function testFilter(
        EnvelopeCollection $givenCollection,
        Closure $closure,
        EnvelopeCollection $expectedCollection,
    ): void {
        self::assertEquals($expectedCollection, $givenCollection->filter($closure));
    }

    public function provideFilterCases(): iterable
    {
        $message = new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);

        $envelope = new BasicEnvelope($message, new StampCollection([
            new StubStamp('stamp-name', 'stamp-value'),
        ]));

        $anotherStamp = new StubStamp('another-stamp-name', 'another-stamp-value');
        $anotherEnvelope = new BasicEnvelope($message, new StampCollection([
            $anotherStamp,
        ]));

        yield 'no matching items' => [
            'collection' => new EnvelopeCollection([$envelope, $anotherEnvelope]),
            'callback' => static fn () => false,
            'expected collection' => new EnvelopeCollection([]),
        ];

        yield 'all items matching' => [
            'collection' => new EnvelopeCollection([$envelope, $anotherEnvelope]),
            'callback' => static fn () => true,
            'expected collection' => new EnvelopeCollection([$envelope, $anotherEnvelope]),
        ];

        yield 'single matching item' => [
            'collection' => new EnvelopeCollection([$envelope, $anotherEnvelope]),
            'callback' => static fn (Envelope $envelope) => $envelope->stamps()->contains($anotherStamp),
            'expected collection' => new EnvelopeCollection([
                $anotherEnvelope,
            ]),
        ];
    }
}
