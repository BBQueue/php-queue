<?php

namespace BBQueue\Queue;

interface QueueInterface
{
    /**
     * @param EnvelopInterface $job
     *
     * @return ChainInterface
     */
    public function queue(EnvelopInterface $job);
}
