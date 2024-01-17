<?php

namespace App;

class Auto
{
    private $speed = 0;

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function setSpeed(int $input)
    {
        $this->speed = $input;
        return $this;
    }
}