<?php

namespace BBQueue\Queue\Queue;

use BBQueue\Queue\AbstractQueue;
use BBQueue\Queue\BackendInterface;
use BBQueue\Queue\ChainInterface;
use BBQueue\Queue\EnvelopInterface;
use BBQueue\Queue\LoopAwareTrait;
use BBQueue\Queue\QueueInterface;
use Evenement\EventEmitterTrait;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use React\Promise\FulfilledPromise;
use React\Stomp\Client;
use React\Stomp\Factory;
use React\Stomp\Protocol\Frame;

class RabbitMQ extends AbstractQueue implements BackendInterface
{
    use EventEmitterTrait;

    /**
     * @var Client
     */
    protected $client;

    protected $connectPromise;

    /**
     * @param EnvelopInterface $job
     */
    public function queue(EnvelopInterface $job)
    {
        $this->push(json_encode($job));
    }

    protected function pushMessage($sting)
    {
        var_export($this->options);
        $this->client->send($this->options['queue'], $sting);
    }

    public function connect()
    {
        if ($this->client !== null) {
            return new FulfilledPromise($this->client);
        }

        if ($this->connectPromise !== null) {
            return $this->connectPromise;
        }
echo 'connecting';
        $deferred = new Deferred();

        (new Factory($this->loop))->createClient([
            'vhost' => '/',
            'login' => 'guest',
            'passcode' => 'guest',
        ])->connect()->then(function (Client $client) use ($deferred) {
            $this->connectPromise = null;
            $this->client = $client;
            echo 'connected';
            $deferred->resolve();
        }, function ($e) {
            var_export($e->getMessage());
        });

        $this->connectPromise = $deferred->promise();
        return $this->connectPromise;
    }

    protected function disconnect()
    {
        $this->client->disconnect();
        $this->client = null;
    }

    public function pull()
    {
        $this->connect()->then(function () {
            $this->client->subscribe($this->options['queue'], function (Frame $frame) {
                $this->emit('message', [
                    json_decode($frame->body, true),
                ]);
            });
        });

        return $this;
    }
}
