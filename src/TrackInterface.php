<?php

namespace BBQueue\Queue;

interface TrackInterface
{
    /**
     * @return Track
     */
    public function create();

    /**
     * @return string
     */
    public function __toString();
}
