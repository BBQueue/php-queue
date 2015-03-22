<?php

namespace BBQueue\Queue;

interface JobInterface
{
    /**
     * @param array $payload
     */
    public function __construct(array $payload);

    /**
     * @return array
     */
    public function getPayload();
}
