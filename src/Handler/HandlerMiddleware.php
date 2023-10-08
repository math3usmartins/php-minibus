<?php

namespace MiniBus\Handler;

use Exception;
use Functional\Either;
use Functional\Either\Left;
use Functional\Either\Right;
use MiniBus\Envelope;
use MiniBus\Middleware;
use function get_class;
use function gettype;
use function is_object;

final class HandlerMiddleware implements Middleware
{
    /**
     * @var HandlerLocator
     */
    private $locator;

    public function __construct(
        HandlerLocator $locator
    ) {
        $this->locator = $locator;
    }

    /**
     * @return Either<HandlingFailure|Envelope>
     */
    public function handle(Envelope $envelope, Middleware $next = null)
    {
        try {
            return $this->locator->locate($envelope)
                ->handle($envelope)
                ->map(function (Right $result) use ($next, $envelope) {
                    $actualValue = $result->value();

                    $somethingElse = is_object($actualValue)
                        ? get_class($actualValue)
                        : gettype($actualValue);

                    if (!($actualValue instanceof Envelope)) {
                        return Left::fromValue(
                            new HandlingFailure(
                                $envelope,
                                sprintf('Expected an envelope, got "%s"', $somethingElse)
                            )
                        );
                    }

                    $stamped = $actualValue->withStamp(new HandlerStamp());

                    return $next
                        ? $next->handle($stamped)
                        : Right::fromValue($stamped);
                });
        } catch (Exception $exception) {
            return Left::fromValue($exception);
        }
    }
}
