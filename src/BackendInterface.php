<?php

namespace BBQueue\Queue;

interface BackendInterface extends LoopAwareInterface
{
    /**
     * @param EnvelopInterface $job
     */
    public function queue(EnvelopInterface $job);
}
