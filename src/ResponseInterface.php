<?php

namespace BBQueue\Queue;

interface ResponseInterface
{
    public function respond(TrackInterface $track, array $message);
}
