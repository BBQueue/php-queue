<?php

namespace BBQueue\Queue;

interface TrackInterface
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
