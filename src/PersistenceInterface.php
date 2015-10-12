<?php

namespace BBQueue\Queue;

interface PersistenceInterface extends LoopAwareInterface
{
    const STATUS_NEW        = 0;
    const STATUS_PICKEDUP   = 1;
    const STATUS_DONE       = 2;

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

    public function getPayload(TrackInterface $track);

    public function markPickedup(TrackInterface $track);
    public function markDone(TrackInterface $track);
}
