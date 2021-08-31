<?php

namespace jossc\warps\warp;

use pocketmine\level\Location;

class Warp {

    /*** @var string */
    private $id;

    /*** @var Location */
    private $location;

    public function __construct(string $id, Location $location)
    {
        $this->id = $id;
        $this->location = $location;
    }

    /*** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /*** @return Location */
    public function getLocation(): Location
    {
        return $this->location;
    }
}