<?php

namespace BBQueue\Queue;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

class Queue implements QueueInterface, LoopAwareInterface
{
    use LoopAwareTrait;

    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * @var PersistenceInterface
     */
    protected $persistence;

    /**
     * @var bool
     */
    protected $autoRunLoop = false;

    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @param BackendInterface $queue
     * @param PersistenceInterface $persistence
     * @param LoopInterface $loop
     */
    public function __construct(BackendInterface $queue, PersistenceInterface $persistence, LoopInterface $loop = null)
    {
        $this->queue = $queue;
        $this->persistence = $persistence;

        if ($this->loop === null) {
            $this->loop = Factory::create();
            $this->autoRunLoop = true;
        }

        $this->queue->setLoop($this->loop);
        $this->persistence->setLoop($this->loop);
    }

    public function run()
    {
        if ($this->autoRunLoop) {
            $this->loop->run();
        }
    }

    /**
     * @param JobInterface $job
     * @param EnvelopInterface $parent
     * @return Chain
     */
    public function queue(JobInterface $job, EnvelopInterface $parent = null)
    {
        $envelope = Envelope::create($this->persistence, $job, $parent);
        $this->queue->queue($envelope);
        return new Chain($this, $envelope);
    }

    public function pull()
    {
        return $this->queue->pull();
    }
}
