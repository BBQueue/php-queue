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

    public static function create(PersistenceInterface $persistence, JobInterface $job, EnvelopInterface $predecessor = null)
    {
        $instance = new static($persistence, $job, Track::create(), $predecessor);
        $instance->store();
        return $instance;
    }

    /**
     * @param PersistenceInterface $persistence
     * @param array $data
     * @return static
     */
    public static function hydrate(PersistenceInterface $persistence, array $data)
    {
        $track = Track::hydrate($data['track']);
        return $persistence->getPayload($track)->then(function ($payload) use ($persistence, $data, $track) {
            return new static($persistence, new $data['job']($payload), $track);
        });
    }

    /**
     * @param PersistenceInterface $persistence
     * @param JobInterface $job
     * @param EnvelopInterface $predecessor
     */
    protected function __construct(PersistenceInterface $persistence, JobInterface $job, Track $track, EnvelopInterface $predecessor = null)
    {
        $this->persistence = $persistence;
        $this->job = $job;
        $this->predecessor = $predecessor;
        $this->track = $track;
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

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return [
            'job' => get_class($this->getJob()),
            'track' => $this->getTrack(),
            'predecessor' => $this->getPredecessor(),
        ];
    }
}
