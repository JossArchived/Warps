<?php

namespace jossc\warps\manager;

use jossc\warps\utils\LocationUtils;
use jossc\warps\warp\Warp;
use lib\FormAPI\elements\Button;
use lib\FormAPI\window\{
    CustomWindowForm,
    SimpleWindowForm
};
use jossc\warps\Main;
use jossc\warps\storage\WarpsStorage;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class FormManager {

    /*** @var Main */
    private $main;

    /*** @var WarpsStorage */
    private $storage;

    /*** FormManager constructor.*/
    public function __construct() {
        $this->main = Main::getInstance();
        $this->storage = $this->main->getStorage();
    }

    /*** @param Player $player */
    public function showWarpsMenu(Player $player): void {
        $form = new SimpleWindowForm(
            'warps',
            'Warps List'
        );

        $storage = $this->storage;

        $warps = $storage->getWarps();

        if ($storage->isEmpty()) {
            $player->sendMessage(TextFormat::RED . 'There are no warps to see!');

            return;
        }

        foreach ($warps as $value) {
            /**@var Warp $warp*/
            $warp = $storage->get($value->getId());

            if (is_null($warp)) {
                continue;
            }

            $form->addButton(
                $warp->getId(),
                TextFormat::BOLD . TextFormat::DARK_GREEN . strtoupper($warp->getId()) . "\n" .
                TextFormat::RESET . TextFormat::DARK_GRAY . 'Click to go'
            );
        }

        $form->addHandler(function (Player $player, Button $button) {
            /**@var Warp $warp*/
            $warp = $this->storage->get($button->getName());

            if (is_null($warp)) {
                $player->sendMessage(TextFormat::RED . 'That warp does not exist!');

                return;
            }

            $player->sendMessage(TextFormat::GREEN . 'Teleporting...');

            $player->teleport($warp->getLocation());
        });

        $form->showTo($player);
    }

    /*** @param Player $player */
    public function showAddWarpMenu(Player $player): void {
        $form = new CustomWindowForm(
            'warps_add',
            'Add new Warp'
        );

        $form->addInput('id', 'Please insert a warp id:');

        $form->addHandler(function (Player $player, CustomWindowForm $form) {
            $warp = $form->getElement('id');
            $warpId = $warp->getFinalValue();

            if (strlen($warpId) === 0) {
                $player->sendMessage(TextFormat::RED . 'Please, insert a valid name or identifier!');

                return;
            }

            $storage = $this->storage;

            if ($storage->contains($warpId)) {
                $player->sendMessage(TextFormat::RED . 'That warp id already exist!');

                return;
            }

            $locationToString = LocationUtils::locationToString($player->getLocation());

            $warps = $this->main->getConfig()->get('warps');
            array_push($warps, "$warpId;$locationToString");

            $storage->set($warps);

            $player->sendMessage(
                TextFormat::GREEN . "You have successfully created the warp: $warpId!"
            );
        });

        $form->showTo($player);
    }

    /*** @param Player $player */
    public function showRemoveWarpMenu(Player $player): void {
        $form = new CustomWindowForm(
            'warps',
            'Warps List'
        );

        $storage = $this->storage;

        $warps = $storage->getWarps();

        if ($storage->isEmpty()) {
            $player->sendMessage(TextFormat::RED . 'There are no warps to see!');

            return;
        }

        $warpsWithId = [];

        foreach ($warps as $warp) {
            /**@var Warp $warp*/
            array_push($warpsWithId, $warp->getId());
        }

        $form->addDropdown("warp", "Select the warp to remove:", $warpsWithId);

        $form->addHandler(function (Player $player, CustomWindowForm $form) {
            $warp = $form->getElement("warp");

            $warpId = $warp->getFinalValue();

            $storage = $this->storage;

            if (!$storage->contains($warpId)) {
                $player->sendMessage(TextFormat::RED . 'That warp does not exist!');

                return;
            }

            $storage->removeFromStorage($warpId);

            $player->sendMessage(
                TextFormat::GREEN . "You have successfully removed the warp: $warpId!"
            );
        });

        $form->showTo($player);
    }
}