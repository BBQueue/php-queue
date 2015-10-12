<?php

namespace BBQueue\Queue;

interface TrackInterface extends \JsonSerializable
{
    /**
     * @return Track
     */
    public static function create();

    /**
     * @return string
     */
    public function __toString();
}
