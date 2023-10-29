<?php

declare(strict_types=1);

namespace MiniBus\Middleware;

use MiniBus\Envelope;
use MiniBus\Middleware;

use function array_slice;

final class MiddlewareStack implements Middleware
{
    /**
     * @var Middleware[]
     */
    private array $middlewares;

    /**
     * @param Middleware[] $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->middlewares = array_values($middlewares);
    }

    public function handle(
        Envelope $envelope,
        Middleware $next = null,
    ): Envelope {
        // the stack must be created at handle time, to call the next handler
        // as the last one.
        // also, it needs to be created starting from the last middleware.
        // despite that, the final stack will match the given array.
        // so an array [A, B, C, N]
        // will be turned into stacked(A, stacked(B, stacked(C, N)))
        // where middlewares are executed in the same order as the initial array
        // i.e. first A() then B() then C() then N()
        // p.s. A might not call next B and so on.
        $middlewares = array_reverse(
            $next
                ? array_merge($this->middlewares, [$next])
                : $this->middlewares,
        );

        if (empty($middlewares)) {
            return $envelope;
        }

        $stack = array_reduce(
            array_slice($middlewares, 1),
            static fn (Middleware $carry, Middleware $current) =>
                // p.s. carry (previous) becomes next middleware.
                new StackedMiddleware($current, $carry),
            $middlewares[0],
        );

        return $stack->handle($envelope);
    }
}
