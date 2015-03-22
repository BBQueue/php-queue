<?php

namespace BBQueue\Queue;

class Envelope implements EnvelopInterface
{
    /**
     * @var PersistenceInterface
     */
    protected $persistence;

    /**
     * @var JobInterface
     */
    protected $job;

    /**
     * @var EnvelopInterface
     */
    protected $predecessor;

    /**
     * @var TrackInterface
     */
    protected $track;

    /**
     * @param PersistenceInterface $persistence
     * @param JobInterface $job
     * @param EnvelopInterface $predecessor
     */
    public function __construct(PersistenceInterface $persistence, JobInterface $job, EnvelopInterface $predecessor = null)
    {
        $this->persistence = $predecessor;
        $this->job = $job;
        $this->predecessor = $predecessor;
        $this->track = Track::create();

        $this->store();
    }

    protected function store()
    {
        $this->persistence->store($this, $this->job->getPayload());

        if ($this->predecessor !== null) {
            $this->persistence->chain($this->predecessor, $this);
        }
    }

    public function done()
    {
        $this->persistence->done($this);
    }

    /**
     * @return EnvelopInterface
     */
    public function getPredecessor()
    {
        return $this->predecessor;
    }

    /**
     * @return TrackInterface
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * @return JobInterface
     */
    public function getJob()
    {
        return $this->job;
    }
}
