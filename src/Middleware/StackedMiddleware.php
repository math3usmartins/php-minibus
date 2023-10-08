<?php

namespace MiniBus\Middleware;

use Functional\Either;
use Functional\Either\Left;
use Functional\Either\Right;
use Functional\Either\UnexpectedResult;
use MiniBus\Envelope;
use MiniBus\Handler\HandlingFailure;
use MiniBus\Middleware;

final class StackedMiddleware
{
    /**
     * @var Middleware
     */
    private $middleware;

    public function __construct(Middleware $current)
    {
        $this->middleware = $current;
    }

    /**
     * @return Either<HandlingFailure|Envelope>
     */
    public function handle(Either $input)
    {
        return $input->map(function (Either $result) {
            if (!($result instanceof Right)) {
                return Left::fromValue(
                    new UnexpectedResult(
                        'Unexpected value to map on stacked middleware.',
                        $result
                    )
                );
            }

            $expectedEnvelope = $result->value();

            if (!($expectedEnvelope instanceof Envelope)) {
                return Left::fromValue(
                    new UnexpectedResult(
                        'Expected an envelope, got something else.',
                        $expectedEnvelope
                    )
                );
            }

            return $this->middleware->handle($expectedEnvelope);
        });
    }
}
