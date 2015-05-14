<?php

namespace BBQueue\Queue;

use React\EventLoop\LoopInterface;

interface LoopAwareInterface
{
    /**
     * @param LoopInterface $loop
     *
     * @return void
     */
    public function setLoop(LoopInterface $loop);

    /**
     * @param LoopInterface $loop
     *
     * @return LoopInterface
     */
    public function getLoop(LoopInterface $loop);
}
