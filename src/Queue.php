<?php

namespace BBQueue\Queue;

class Queue implements QueueInterface
{
    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * @var PersistenceInterface
     */
    protected $persistence;

    /**
     * @param QueueInterface $queue
     * @param PersistenceInterface $persistence
     */
    public function __construct(QueueInterface $queue, PersistenceInterface $persistence)
    {
        $this->queue = $queue;
        $this->persistence = $persistence;
    }

    /**
     * @param JobInterface $job
     * @param EnvelopInterface $parent
     * @return Chain
     */
    public function queue(JobInterface $job, EnvelopInterface $parent = null)
    {
        $envelope = new Envelope($this->persistence, $job, $parent);
        $this->queue->queue($envelope);
        return new Chain($this, $envelope);
    }
}
