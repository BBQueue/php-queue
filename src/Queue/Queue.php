<?php

namespace BBQueue\Queue;

class Queue
{
    protected $queue;
    protected $persistence;

    public function __construct(QueueInterface $queue, PersistenceInterface $persistence)
    {
        $this->queue = $queue;
        $this->persistence = $persistence;
    }

    public function prepare(JobInterface $job)
    {
        $chain = new EnvelopeChain($this);
        $chain->then($job);
        return $chain;
    }

    public function queue(JobInterface $job)
    {
        return $this->prepare($job)->enqueue();
    }
}
