<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use BBQueue\Queue\Backend\Gearman;
use BBQueue\Queue\JobInterface;
use BBQueue\Queue\Queue;
use BBQueue\StateStorage\Redis;

class EchoJob implements JobInterface
{
    protected $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}

$queue = new Queue(new Gearman(), new Redis());
$queue->queue(new EchoJob([
    'echo' => 'foo.bar',
]))->done();
