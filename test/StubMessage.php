<?php

declare(strict_types=1);

namespace MiniBus\Test;

use MiniBus\Message;

final class StubMessage implements Message
{
    /** @phpstan-ignore-next-line */
    public function __construct(
        private string $subject,
        private array $headers,
        private array $body,
    ) {}

    public function subject(): string
    {
        return $this->subject;
    }

    public function normalize(): array
    {
        return [
            'headers' => ['subject' => $this->subject] + $this->headers,
            'body' => $this->body,
        ];
    }
}
