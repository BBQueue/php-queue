<?php

namespace BBQueue\Queue;

interface PersistenceInterface extends LoopAwareInterface
{
    /**
     * @param EnvelopInterface $track
     * @param array $payload
     * @return void
     */
    public function store(EnvelopInterface $track, array $payload);

    /**
     * @param EnvelopInterface $track
     * @return void
     */
    public function done(EnvelopInterface $track);

    /**
     * @param EnvelopInterface $track
     * @param EnvelopInterface $successor
     * @return void
     */
    public function chain(EnvelopInterface $track, EnvelopInterface $successor);
}
