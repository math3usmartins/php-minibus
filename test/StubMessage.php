<?php

declare(strict_types=1);

namespace MiniBus\Test;

use MiniBus\Message;

final class StubMessage implements Message
{
    /**
     * @var string
     */
    private $subject;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $body;

    public function __construct(
        string $subject,
        array $headers,
        array $body
    ) {
        $this->subject = $subject;
        $this->headers = $headers;
        $this->body = $body;
    }

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
