<?php

namespace BBQueue\Queue;

interface QueueInterface
{
    /**
     * @param JobInterface $job
     * @return mixed
     */
    public function queue(JobInterface $job);
}
