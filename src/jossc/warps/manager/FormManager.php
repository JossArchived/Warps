<?php

namespace jossc\warps\manager;

use lib\FormAPI\window\CustomWindowForm;
use lib\FormAPI\window\SimpleWindowForm;
use jossc\warps\Main;
use jossc\warps\storage\WarpsStorage;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class FormManager {

    /*** @var Main */
    private $main;
    /*** @var WarpsStorage */
    private $storage;

    public function __construct()
    {
        $this->main = Main::getInstance();
        $this->storage = $this->main->getStorage();
    }

    public function showWarpsMenu(Player $player): void {
        $form = new SimpleWindowForm(
            "warps",
            "Warps"
        );

        $storage = $this->storage;

        $warps = $storage->getWarps();

        if (count($warps) == 0) {
            $player->sendMessage(TextFormat::RED . "There are no warps to see!");

            return;
        }

        foreach ($warps as $value) {
            $warp = $storage->get($value->getId());

            if (is_null($warp)) {
                continue;
            }

            $form->addButton(
                $warp->getId(),
                TextFormat::BOLD . TextFormat::DARK_GREEN . strtoupper($warp->getId()) . "\n" .
                TextFormat::RESET . TextFormat::DARK_GRAY . "Click to go"
            );
        }

        $form->showTo($player);
    }

    public function showAddWarpMenu(Player $player): void {
        $form = new CustomWindowForm(
            "warps_add",
            "Add new Warp"
        );

        $form->addInput("id", "Please insert a warp id:");

        $form->showTo($player);
    }
}