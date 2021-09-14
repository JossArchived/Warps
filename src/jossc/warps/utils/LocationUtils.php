<?php

namespace jossc\warps\utils;

use pocketmine\level\Location;
use pocketmine\Server;

class LocationUtils {

    /**
     * @param string $locationString
     * @return Location
     */
    public static function stringToLocation(string $locationString): Location {
        $locationArray = explode(':', $locationString);

        return new Location(
            (int) $locationArray[0],
            (int) $locationArray[1],
            (int) $locationArray[2],
            (float) $locationArray[3],
            (float) $locationArray[4],
            Server::getInstance()->getLevelByName($locationArray[5])
        );
    }

    /**
     * @param Location $location
     * @return string
     */
    public static function locationToString(Location $location): string {
        return (int) $location->x . ":" . (int) $location->y . ":" . (int) $location->z . ":$location->yaw:$location->pitch:{$location->getLevel()->getFolderName()}";
    }
}