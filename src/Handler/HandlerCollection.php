<?php

namespace MiniBus\Handler;

use Functional\Either;
use Functional\Either\Left;
use Functional\Either\Right;
use MiniBus\Envelope;
use MiniBus\Handler;
use function get_class;
use function gettype;
use function is_object;

final class HandlerCollection
{
    /**
     * @var Handler[]
     */
    private $handlers;

    public function __construct(Handler ...$handlers)
    {
        $this->handlers = array_values($handlers);
    }

    public function findForEnvelope(Envelope $envelope)
    {
        return $this->filter(
            function (Handler $handler) use ($envelope) {
                return $handler->supports($envelope);
            }
        );
    }

    public function filter(callable $callback)
    {
        $matches = array_filter($this->handlers, $callback);

        return new self(...array_values($matches));
    }

    public function with(Handler ...$handlers)
    {
        $merged = array_values(array_merge($this->handlers, $handlers));

        return new self(...$merged);
    }

    /**
     * @return Either<HandlingFailure|Envelope>
     */
    public function handle(Envelope $envelope)
    {
        return array_reduce(
            $this->handlers,
            function (Either $result, Handler $handler) use ($envelope) {
                return $this->reduceCallback($envelope, $result, $handler);
            },
            Right::fromValue($envelope)
        );
    }

    private function reduceCallback(
        Envelope $envelope,
        Either $result,
        Handler $handler
    ) {
        return $result->map(function (Right $success) use ($handler, $envelope) {
            return $this->mapResult($envelope, $success, $handler);
        });
    }

    /**
     * @param Right<Envelope> $success
     */
    private function mapResult(
        Envelope $envelope,
        Right $success,
        Handler $handler
    ) {
        $value = $success->value();

        if ($value instanceof Envelope) {
            return $handler->handle($value);
        }

        $somethingElse = is_object($value)
            ? get_class($value)
            : gettype($value);

        return Left::fromValue(
            new HandlingFailure(
                $envelope,
                sprintf('Expected an envelope, got "%s"', $somethingElse)
            )
        );
    }
}
