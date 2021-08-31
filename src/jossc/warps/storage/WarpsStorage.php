<?php

namespace jossc\warps\storage;

use jossc\warps\utils\LocationUtils;
use jossc\warps\warp\Warp;

class WarpsStorage {

    /*** @var array */
    private $warps = [];

    /*** @return array */
    public function getWarps(): array
    {
        return $this->warps;
    }

    /**
     * @param string $warpId
     * @return bool
     */
    public function contains(string $warpId): bool {
        return isset($this->warps[$warpId]);
    }

    /**
     * @param string $warpId
     * @param string $locationString
     */
    public function add(string $warpId, string $locationString) {
        $this->warps[$warpId] = new Warp($warpId, LocationUtils::stringToLocation($locationString));
    }

    /*** @param string $warpId */
    public function remove(string $warpId): void {
        unset($this->warps[$warpId]);
    }

    /**
     * @param string $warpId
     * @return Warp|null
     */
    public function get(string $warpId): ?Warp
    {
        return !$this->contains($warpId) ? null : $this->warps[$warpId];
    }
}