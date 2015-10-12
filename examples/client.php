<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require 'common.php';

use BBQueue\Queue\Queue\RabbitMQ;
use BBQueue\Queue\Queue;
use BBQueue\Queue\Persistence\Redis;

$queue = new Queue(new RabbitMQ(), new Redis());
$queue->queue(new EchoJob([
    'echo' => 'foo.' . time() . '.bar.asjhudqwuo73t987*&%$*%()*)(&^(%*^%&^$#%$&%*&*&)*(&^&$%%*^%&*&)(*&*_^%(*^#*$(*&)*)',
]))->done();
