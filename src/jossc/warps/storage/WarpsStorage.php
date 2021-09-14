<?php

namespace jossc\warps\storage;

use jossc\warps\Main;
use jossc\warps\utils\LocationUtils;
use jossc\warps\warp\Warp;

class WarpsStorage {

    /*** @var Main */
    private $main;

    public function __construct()
    {
        $this->main = Main::getInstance();
    }

    /*** @var array */
    private $warps = [];

    /*** @return array */
    public function getWarps(): array {
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
    public function get(string $warpId): ?Warp {
        return !$this->contains($warpId) ? null : $this->warps[$warpId];
    }

    /*** @return int */
    public function count(): int {
        return count($this->warps);
    }

    /*** @return bool */
    public function isEmpty(): bool {
        return $this->count() === 0;
    }

    public function reload(): void {
        $config = $this->main->getConfig();

        if (!$config->exists('warps')) {
            return;
        }

        unset($this->warps);

        $this->warps = [];

        $warps = $config->get('warps');

        foreach ($warps as $warpData) {
            $warpDataArray = explode(';', $warpData);

            $this->add($warpDataArray[0], $warpDataArray[1]);
        }
    }

    /*** @param array $warps */
    public function set(array $warps): void {
        $config = $this->main->getConfig();

        $config->set('warps', $warps);
        $config->save();

        $this->reload();
    }

    /*** @param string $warpId */
    public function removeFromStorage(string $warpId): void {
        $warps = $this->main->getConfig()->get('warps');

        $warpObject = $this->get($warpId);

        if (is_null($warpObject)) {
            return;
        }

        $locationToString = LocationUtils::locationToString($warpObject->getLocation());

        $warpIdArray = array($warpId . ";" . $locationToString);

        $updatedArray = array_diff($warps, $warpIdArray);

        $this->remove($warpId);
        $this->set($updatedArray);
    }
}