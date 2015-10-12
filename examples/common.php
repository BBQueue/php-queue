<?php

use BBQueue\Queue\ConsumerInterface;
use BBQueue\Queue\JobInterface;
use React\Promise\FulfilledPromise;

class EchoJob implements JobInterface
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
        return 'echo';
    }

    public function getPayload()
    {
        return $this->payload;
    }
}

class EchoConsumer implements ConsumerInterface
{
    protected $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle()
    {
        echo $this->payload['echo'];
        return new FulfilledPromise($this->payload['echo']);
    }
}
