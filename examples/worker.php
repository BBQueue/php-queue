<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require 'common.php';

use BBQueue\Queue\Persistence\Redis;
use BBQueue\Queue\Queue;
use BBQueue\Queue\Queue\RabbitMQ;
use BBQueue\Queue\Worker;
use React\EventLoop\Factory;

$loop = Factory::create();

$redis = new Redis([]);
$queue = new Queue(new RabbitMQ([
    'queue' => '/queue/foo.bar',
]), $redis, $loop);
$returnQueue = new Queue(new RabbitMQ([
    'queue' => '/queue/response.foo.bar',
]), $redis, $loop);
$worker = new Worker($queue, $redis, $returnQueue);
$worker->register(EchoJob::class, EchoConsumer::class);
$worker->run();

$loop->run();
