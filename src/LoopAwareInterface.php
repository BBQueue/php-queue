<?php

namespace BBQueue\Queue;

use React\EventLoop\LoopInterface;

interface LoopAwareInterface
{
    public function setLoop(LoopInterface $loop);
}
