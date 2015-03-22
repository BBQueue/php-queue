<?php

namespace BBQueue\Queue;

class Track implements TrackInterface
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @return Track
     */
    public static function create()
    {
        return new self(uniqid('', true) . '_' . gethostname() . '_' . posix_getpid() . '_' . time());
    }

    /**
     * @param string $identifier
     */
    protected function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->identifier;
    }
}
