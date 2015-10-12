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

    protected $client;

    /**
     * @param EnvelopInterface $job
     */
    public function queue(EnvelopInterface $job)
    {
        $this->push(json_encode($job));
    }

    protected function pushMessage($sting)
    {
        $this->client->send('/queue/foo.bar', $sting);
    }

    protected function connect()
    {
        if ($this->client !== null) {
            return new FulfilledPromise();
        }

        return (new Factory($this->loop))->createClient([
            'vhost' => '/',
            'login' => 'guest',
            'passcode' => 'guest',
        ])->connect()->then(function (Client $client) {
            $this->client = $client;
            return new FulfilledPromise();
        });
    }

    protected function disconnect()
    {
        $this->client->disconnect();
        $this->client = null;
    }

    public function pull()
    {
        $this->connect()->then(function () {
            $this->client->subscribe('/queue/foo.bar', function (Frame $frame) {
                $this->emit('message', [
                    json_decode($frame->body, true),
                ]);
            });
        });

        return $this;
    }
}
