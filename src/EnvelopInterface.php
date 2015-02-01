<?php

namespace BBQueue\Queue;

interface EnvelopInterface
{
    public function __construct(JobInterface $job);
}
