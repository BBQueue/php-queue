<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require 'common.php';

use BBQueue\Queue\Persistence\Redis;
use BBQueue\Queue\Queue;
use BBQueue\Queue\Queue\RabbitMQ;
use BBQueue\Queue\Worker;

$redis = new Redis();
$queue = new Queue(new RabbitMQ(), $redis);
$worker = new Worker($queue, $redis);
$worker->register(EchoJob::class, EchoConsumer::class);
$worker->run();
