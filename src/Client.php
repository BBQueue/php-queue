<?php

namespace BBQueue\Queue;

class Client
{
    /**
     * @var Queue|QueueInterface
     */
    protected $queue;

    /**
     * @var PersistenceInterface
     */
    protected $persistence;

    /**
     * @var BackendInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @param QueueInterface $queue
     * @param PersistenceInterface $persistence
     * @param QueueInterface $response
     */
    public function __construct(QueueInterface $queue, PersistenceInterface $persistence, QueueInterface $response = null)
    {
        $this->queue = $queue;
        $this->persistence = $persistence;
        $this->response = $response;

        if ($this->response !== null) {
            $this->response->pull()->on('message', function ($message) {
                Envelope::hydrate($this->persistence, $message)->then(function (EnvelopInterface $envelop) {

                });
            });
        }
    }

    /**
     * @param JobInterface $job
     * @param EnvelopInterface $parent
     * @return Chain
     */
    public function queue(JobInterface $job, EnvelopInterface $parent = null)
    {
        return $this->queue->queue($job, $parent);
    }
}
