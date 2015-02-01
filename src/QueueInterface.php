<?php

namespace BBQueue\Queue;

interface QueueInterface
{
    public function queue(EnvelopInterface $job);
}
