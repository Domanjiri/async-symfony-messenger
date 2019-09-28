<?php

namespace App\Messenger;

use Symfony\Component\Messenger\Stamp\StampInterface;

class SmsStamp implements StampInterface
{
    private $timestamp;

    private $lastTry;

    public function __construct()
    {
        // time modification
        $this->lastTry = time();
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;
        
        return $this;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp ?? time();
    }
}

