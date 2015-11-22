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
     * @return Track
     */
    public static function hydrate($identifier)
    {
        return new self($identifier);
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

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return (string) $this;
    }
}
