<?php

namespace App\Messenger\Event;


class SmsStored
{
    protected $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }   

    public function getId(): int
    {
        return $this->id ?? 0;
    }
}

