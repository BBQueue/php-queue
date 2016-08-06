<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require 'common.php';

use BBQueue\Queue\Client;
use BBQueue\Queue\Queue\RabbitMQ;
use BBQueue\Queue\Queue;
use BBQueue\Queue\Persistence\Redis;
use React\EventLoop\Factory;

$loop = Factory::create();
$redis = new Redis([]);
$queue = new Queue(new RabbitMQ([
    'queue' => '/queue/foo.bar',
]), $redis, $loop);
$returnQueue = new Queue(new RabbitMQ([
    'queue' => '/queue/response.foo.bar',
]), $redis, $loop);

$client = new Client($queue, $redis, $returnQueue);
$client->queue(new EchoJob([
    'echo' => 'foo.' . time() . '.bar.asjhudqwuo73t987*&%$*%()*)(&^(%*^%&^$#%$&%*&*&)*(&^&$%%*^%&*&)(*&*_^%(*^#*$(*&)*)',
]))->then(function ($a) {
    var_export($a);
});

$loop->run();
