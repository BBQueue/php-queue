<?php

namespace BBQueue\Queue;

interface EnvelopInterface extends \JsonSerializable
{
    /**
     * @param PersistenceInterface $persistence
     * @param JobInterface $job
     * @param EnvelopInterface $predecessor
     */
    //public function __construct(PersistenceInterface $persistence, JobInterface $job, EnvelopInterface $predecessor = null);

    /**
     * @return mixed
     */
    public function done();

    /**
     * @return EnvelopInterface
     */
    public function getPredecessor();

    /**
     * @return TrackInterface
     */
    public function getTrack();

    /**
     * @return JobInterface
     */
    public function getJob();
}
