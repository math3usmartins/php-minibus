<?php

namespace MiniBus\Test\Middleware;

use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Middleware\MiddlewareStack;
use MiniBus\Test\StubMessage;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MiniBus\Middleware\MiddlewareStack
 * @covers \MiniBus\Middleware\StackedMiddleware
 */
final class MiddlewareStackTest extends TestCase
{
    /**
     * @dataProvider scenarios
     */
    public function testItDoesCallHandlersAsExpected(MiddlewareStack $stack, callable $assertionCallback)
    {
        $envelope = new BasicEnvelope(
            $this->stubMessage(),
            new StampCollection([])
        );

        $assertionCallback(
            $stack->handle($envelope)
        );
    }

    public function scenarios()
    {
        yield 'it supports empty array' => [
            'stack' => new MiddlewareStack([]),
            'callback' => function (BasicEnvelope $envelope) {
                self::assertEquals($this->stubMessage(), $envelope->message());
            },
        ];

        yield 'it must call all handlers' => [
            'stack' => new MiddlewareStack([
                $a = new StubMiddleware(),
                $b = new StubMiddleware(),
                $c = new StubMiddleware(),
            ]),
            'callback' => function () use ($a, $b, $c) {
                self::assertTrue($a->handled());
                self::assertTrue($b->handled());
                self::assertTrue($c->handled());
            },
        ];

        yield 'it does stop on first handler' => [
            'stack' => new MiddlewareStack([
                $a = new StubMiddleware(false),
                $b = new StubMiddleware(),
                $c = new StubMiddleware(),
            ]),
            'callback' => function () use ($a, $b, $c) {
                self::assertTrue($a->handled());
                self::assertFalse($b->handled());
                self::assertFalse($c->handled());
            },
        ];

        yield 'it does stop on second handler' => [
            'stack' => new MiddlewareStack([
                $a = new StubMiddleware(),
                $b = new StubMiddleware(false),
                $c = new StubMiddleware(),
            ]),
            'callback' => function () use ($a, $b, $c) {
                self::assertTrue($a->handled());
                self::assertTrue($b->handled());
                self::assertFalse($c->handled());
            },
        ];

        yield 'it supports a nested stack in the beginning' => [
            'stack' => new MiddlewareStack([
                new MiddlewareStack([
                    $n1 = new StubMiddleware(),
                    $n2 = new StubMiddleware(),
                    $n3 = new StubMiddleware(),
                ]),
                $a = new StubMiddleware(),
                $b = new StubMiddleware(),
                $c = new StubMiddleware(),
            ]),
            'callback' => function () use ($a, $b, $c, $n1, $n2, $n3) {
                self::assertTrue($a->handled());
                self::assertTrue($b->handled());
                self::assertTrue($c->handled());
                self::assertTrue($n1->handled());
                self::assertTrue($n2->handled());
                self::assertTrue($n3->handled());
            },
        ];

        yield 'it supports a nested stack in the middle' => [
            'stack' => new MiddlewareStack([
                $a = new StubMiddleware(),
                $b = new StubMiddleware(),
                new MiddlewareStack([
                    $n1 = new StubMiddleware(),
                    $n2 = new StubMiddleware(),
                    $n3 = new StubMiddleware(),
                ]),
                $c = new StubMiddleware(),
            ]),
            'callback' => function () use ($a, $b, $c, $n1, $n2, $n3) {
                self::assertTrue($a->handled());
                self::assertTrue($b->handled());
                self::assertTrue($c->handled());
                self::assertTrue($n1->handled());
                self::assertTrue($n2->handled());
                self::assertTrue($n3->handled());
            },
        ];

        yield 'it supports a nested stack in the end' => [
            'stack' => new MiddlewareStack([
                $a = new StubMiddleware(),
                $b = new StubMiddleware(),
                $c = new StubMiddleware(),
                new MiddlewareStack([
                    $n1 = new StubMiddleware(),
                    $n2 = new StubMiddleware(),
                    $n3 = new StubMiddleware(),
                ]),
            ]),
            'callback' => function () use ($a, $b, $c, $n1, $n2, $n3) {
                self::assertTrue($a->handled());
                self::assertTrue($b->handled());
                self::assertTrue($c->handled());
                self::assertTrue($n1->handled());
                self::assertTrue($n2->handled());
                self::assertTrue($n3->handled());
            },
        ];
    }

    private function stubMessage()
    {
        return new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']);
    }
}
