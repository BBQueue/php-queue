<?php

namespace BBQueue\Queue;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;

class Chain implements ChainInterface, PromiseInterface
{
    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * @var JobInterface
     */
    protected $predecessor;

    /**
     * @param QueueInterface $queue
     * @param EnvelopInterface $predecessor
     */
    public function __construct(QueueInterface $queue, EnvelopInterface $predecessor)
    {
        $this->queue = $queue;
        $this->predecessor = $predecessor;
    }

    /**
     * @param callable $onFulfilled
     * @param callable $onRejected
     * @param callable $onProgress
     *
     * @return PromiseInterface
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onProgress = null)
    {
        $deferred = new Deferred();

        $this->predecessor->done();
        $this->queue->run();

        return $deferred->promise()->then($onFulfilled, $onRejected, $onProgress);
    }

    /**
     * @param JobInterface $job
     * @param QueueInterface $queue
     *
     * @return ChainInterface
     */
    public function chain(JobInterface $job, QueueInterface $queue = null)
    {
        if ($queue === null) {
            $queue = $this->queue;
        }

        $chain = $queue->queue($job, $this->predecessor);

        $this->predecessor->done();

        return $chain;
    }

    /**
     *
     */
    public function done()
    {
        $this->predecessor->done();
        $this->queue->run();
    }
}
