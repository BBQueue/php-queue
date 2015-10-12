<?php

namespace BBQueue\Queue\Backend;

use BBQueue\Queue\EnvelopInterface;
use BBQueue\Queue\QueueInterface;
use React\EventLoop\LoopInterface;

class Gearman implements QueueInterface
{
    public function queue(EnvelopInterface $job)
    {
        //
    }

    /**
     * @param LoopInterface $loop
     *
     * @return void
     */
    public function setLoop(LoopInterface $loop)
    {
        // TODO: Implement setLoop() method.
    }

    /**
     * @param LoopInterface $loop
     *
     * @return LoopInterface
     */
    public function getLoop(LoopInterface $loop)
    {
        // TODO: Implement getLoop() method.
    }
}