<?php

namespace BBQueue\Queue;

use BBQueue\Queue\Response\ResponseJob;
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
     * @var QueueInterface
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
    public function __construct(QueueInterface $queue, PersistenceInterface $persistence, QueueInterface $response)
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
                $this->persistence->markPickedup($envelop->getTrack());
                $promise = $this->matchJob($envelop);
                $promise->done(function () use ($envelop) {
                    $this->persistence->markPickedup($envelop->getTrack());
                });
                return $promise->then(function ($result) use ($envelop) {
                    $this->handleResult($result, $envelop);
                });
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

    protected function handleResult($result, EnvelopInterface $envelop)
    {
        $this->response->queue(new ResponseJob([
            'result' => $result,
            'response_to' => (string) $envelop->getTrack()
        ]));
    }
}
