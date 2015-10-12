<?php

namespace BBQueue\Queue;

use React\Promise\RejectedPromise;

class Worker
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
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @param QueueInterface $queue
     * @param PersistenceInterface $persistence
     */
    public function __construct(QueueInterface $queue, PersistenceInterface $persistence, ResponseInterface $response)
    {
        $this->queue = $queue;
        $this->persistence = $persistence;
        $this->response = $response;
    }

    public function register($job, $task)
    {
        $this->handlers[$job] = $task;
    }

    public function run()
    {
        $this->queue->pull()->on('message', function ($message) {
            Envelope::hydrate($this->persistence, $message)->then(function (EnvelopInterface $envelop) {
                return $this->matchJob($envelop);
            })->then(function ($result) {
                $this->handleResult($result);
            });
        });
        $this->queue->run();
    }

    protected function matchJob(EnvelopInterface $envelope)
    {
        if (!isset($this->handlers[get_class($envelope->getJob())])) {
            return new RejectedPromise();
        }

        $consumer = $this->handlers[get_class($envelope->getJob())];
        return \React\Promise\resolve((new $consumer($envelope->getJob()->getPayload()))->handle());
    }

    protected function handleResult()
    {

    }
}
