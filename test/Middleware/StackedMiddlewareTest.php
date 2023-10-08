<?php

namespace MiniBus\Test\Middleware;

use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Middleware;
use MiniBus\Middleware\StackedMiddleware;
use MiniBus\Test\StubMessage;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MiniBus\Middleware\StackedMiddleware
 */
final class StackedMiddlewareTest extends TestCase
{
    /**
     * @dataProvider scenarios
     */
    public function testItDoesCallHandlersAsExpected(
        callable $assertionCallback,
        StackedMiddleware $stacked,
        Middleware $next = null
    ) {
        $envelope = new BasicEnvelope(
            new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']),
            new StampCollection([])
        );

        $assertionCallback(
            $stacked->handle($envelope, $next)
        );
    }

    public function scenarios()
    {
        $current = new StubMiddleware();
        $nextViaConstructor = new StubMiddleware();
        $nextViaHandler = new StubMiddleware();

        yield 'it must call next via constructor' => [
            'callback' => function () use ($current, $nextViaConstructor, $nextViaHandler) {
                self::assertTrue($current->handled());
                self::assertTrue($nextViaConstructor->handled());
                self::assertFalse($nextViaHandler->handled());
            },
            'stacked' => new StackedMiddleware($current, $nextViaConstructor),
            'next' => $nextViaHandler,
        ];

        $current = new StubMiddleware();
        $nextViaHandler = new StubMiddleware();

        yield 'it must call next via handle' => [
            'callback' => function () use ($current, $nextViaHandler) {
                self::assertTrue($current->handled());
                self::assertTrue($nextViaHandler->handled());
            },
            'stacked' => new StackedMiddleware($current),
            'next' => $nextViaHandler,
        ];
    }
}
