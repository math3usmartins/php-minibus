<?php

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

    /**
     * @param string $subject
     */
    public function __construct(
        $subject,
        array $headers,
        array $body
    ) {
        $this->subject = $subject;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function subject()
    {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function body()
    {
        return $this->body;
    }
}
