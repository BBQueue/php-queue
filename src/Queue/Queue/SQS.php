<?php

namespace BBQueue\Queue\Backend;

use BBQueue\Queue\JobInterface;
use BBQueue\Queue\QueueInterface;

class SQS implements QueueInterface
{
    public function queue(JobInterface $job)
    {
        //
    }
}