<?php

namespace BBQueue\Queue;

use React\EventLoop\LoopInterface;

trait LoopAwareTrait
{
    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @param LoopInterface $loop
     *
     * @return void
     */
    public function setLoop(LoopInterface $loop)
    {
        echo 'set loop: ', get_class($this), PHP_EOL;
        $this->loop = $loop;
    }

    /**
     * @return LoopInterface
     */
    public function getLoop()
    {
        return $this->loop;
    }
}
