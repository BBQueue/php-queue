<?php

namespace BBQueue\Queue;

interface JobInterface
{
    /**
     * @param array $payload
     */
    public function __construct(array $payload);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getPayload();
}
