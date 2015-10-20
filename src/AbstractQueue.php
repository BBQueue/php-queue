<?php

namespace BBQueue\Queue;

use React\Promise\Deferred;

abstract class AbstractQueue
{
    use LoopAwareTrait;

    protected $connected = false;
    protected $connecting = false;

    protected $options;
    protected $list;

    abstract protected function connect();
    abstract protected function disconnect();
    abstract protected function pushMessage($string);

    public function __construct(array $options)
    {
        $this->options = $options;
        $this->list = new \SplQueue();
    }

    protected function push(...$args)
    {
        $deferred = new Deferred();

        $this->list->enqueue([
            'args' => $args,
            'deferred' => $deferred,
        ]);

        $this->pushList();

        return $deferred->promise();
    }

    protected function pushList()
    {
        if ($this->connecting) {
            return;
        }

        $this->connecting = true;

        $this->connect()->then(function () {
            $this->connecting = false;
            $this->handlePushingList();
        });
    }

    protected function handlePushingList()
    {
        while ($this->list->count() > 0) {
            $item = $this->list->dequeue();
            $item['deferred']->resolve($this->pushMessage(...$item['args']));
        }

        $this->disconnect();
    }
}
