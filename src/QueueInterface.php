<?php

namespace BBQueue\Queue;

interface QueueInterface extends LoopAwareInterface
{
    /**
     * @param EnvelopInterface $job
     *
     * @return ChainInterface
     */
    public function queue(EnvelopInterface $job);
}
