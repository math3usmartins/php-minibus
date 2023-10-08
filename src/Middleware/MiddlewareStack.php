<?php

namespace MiniBus\Middleware;

use Functional\Either;
use Functional\Either\Right;
use MiniBus\Envelope;
use MiniBus\Handler\HandlingFailure;
use MiniBus\Middleware;

final class MiddlewareStack implements Middleware
{
    /**
     * @var StackedMiddleware[]
     */
    private $stackedMiddlewares;

    public function __construct(Middleware ...$middlewares)
    {
        $this->stackedMiddlewares = array_map(
            function (Middleware $middleware) {
                return new StackedMiddleware($middleware);
            },
            array_values($middlewares)
        );
    }

    /**
     * @return Either<HandlingFailure|Envelope>
     */
    public function handle(Envelope $envelope, Middleware $next = null)
    {
        $middlewares = $next
            ? array_merge(
                $this->stackedMiddlewares,
                [
                    new StackedMiddleware($next),
                ]
            )
            : $this->stackedMiddlewares;

        if (empty($middlewares)) {
            return Right::fromValue($envelope);
        }

        return array_reduce(
            $middlewares,
            function (Either $result, StackedMiddleware $middleware) {
                return $middleware->handle($result);
            },
            Right::fromValue($envelope)
        );
    }
}
