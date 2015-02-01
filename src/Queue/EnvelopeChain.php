<?php

namespace BBQueue\Queue;

class EnvelopeChain implements EnvelopeChainInterface
{
    protected $chain = [];

    public function then(JobInterface $job)
    {
        $this->chain[] = $job;
    }

    public function conditional()
    {
        //
    }

    public function enqueue()
    {
        //
    }
}
