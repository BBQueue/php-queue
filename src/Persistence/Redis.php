<?php

namespace BBQueue\Queue\Persistence;

use BBQueue\Queue\AbstractQueue;
use BBQueue\Queue\EnvelopInterface;
use BBQueue\Queue\LoopAwareTrait;
use BBQueue\Queue\PersistenceInterface;
use BBQueue\Queue\TrackInterface;
use Clue\React\Redis\Client;
use Clue\React\Redis\Factory;
use React\EventLoop\LoopInterface;
use React\Promise\FulfilledPromise;

class Redis extends AbstractQueue implements PersistenceInterface
{
    const KEY_PREFIX = 'bbqueue:envelopes:';

    use LoopAwareTrait;

    protected $client;

    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @param EnvelopInterface $track
     * @param array $payload
     * @return void
     */
    public function store(EnvelopInterface $track, array $payload)
    {
        $this->push('set', static::KEY_PREFIX . (string) $track->getTrack() . ':payload', json_encode($payload));
        $this->push('set', static::KEY_PREFIX . (string) $track->getTrack() . ':status', PersistenceInterface::STATUS_NEW);
    }

    /**
     * @param EnvelopInterface $track
     * @param EnvelopInterface $successor
     * @return void
     */
    public function chain(EnvelopInterface $track, EnvelopInterface $successor)
    {
        // TODO: Implement chain() method.
    }

    /**
     * @param EnvelopInterface $track
     * @return void
     */
    public function done(EnvelopInterface $track)
    {
        // TODO: Implement done() method.
    }

    protected function connect()
    {
        if ($this->client !== null) {
            return new FulfilledPromise();
        }

        return (new Factory($this->loop))->createClient()->then(function (Client $client) {
            $this->client = $client;
            return new FulfilledPromise();
        });
    }

    protected function pushMessage($function, ...$arguments)
    {
        return $this->client->$function(...$arguments);
    }

    protected function disconnect()
    {
        $this->client->end();
        $this->client = null;
    }

    public function getPayload(TrackInterface $track)
    {
        return $this->push('get', static::KEY_PREFIX . (string) $track . ':payload')->then(function ($payload) {
            return \React\Promise\resolve(json_decode($payload, true));
        });
    }

    public function markPickedup(TrackInterface $track)
    {
        // TODO: Implement markPickedup() method.
    }

    public function markDone(TrackInterface $track)
    {
        // TODO: Implement markDone() method.
    }
}
