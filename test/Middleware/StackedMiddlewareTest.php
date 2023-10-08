<?php

namespace MiniBus\Test\Middleware;

use Functional\Either\Left;
use Functional\Either\Right;
use Functional\Either\UnexpectedResult;
use MiniBus\Envelope\BasicEnvelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Middleware\StackedMiddleware;
use MiniBus\Test\StubMessage;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MiniBus\Middleware\StackedMiddleware
 */
final class StackedMiddlewareTest extends TestCase
{
    public function testItMustCallNextOnSuccess()
    {
        $envelope = new BasicEnvelope(
            new StubMessage('some-subject', ['header' => 'h'], ['body' => 'v']),
            new StampCollection()
        );

        $input = Right::fromValue($envelope);

        $next = new StubMiddleware();
        $stacked = new StackedMiddleware($next);
        $actualResult = $stacked->handle($input);

        static::assertTrue($next->handled());
        static::assertEquals(
            $input,
            $actualResult
        );
    }

    public function testItMustNotCallNextOnFailure()
    {
        $input = Left::fromValue('something unexpected happened');

        $next = new StubMiddleware();
        $stacked = new StackedMiddleware($next);
        $actualResult = $stacked->handle($input);

        static::assertFalse($next->handled());
        static::assertEquals($input, $actualResult);
    }

    public function testItMustRequireEnvelopeToCallNext()
    {
        $input = Right::fromValue('something unexpected');

        $next = new StubMiddleware();
        $stacked = new StackedMiddleware($next);

        $actualResult = $stacked->handle($input);

        static::assertFalse($next->handled());

        $expectedResult = Left::fromValue(
            new UnexpectedResult(
                'Expected an envelope, got something else.',
                'something unexpected'
            )
        );

        static::assertEquals($expectedResult, $actualResult);
    }
}
