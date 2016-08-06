<?php

namespace BBQueue\Queue\Response;

use BBQueue\Queue\JobInterface;

class ResponseJob implements JobInterface
{
    protected $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bbqueue.response';
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
