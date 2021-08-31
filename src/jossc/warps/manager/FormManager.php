<?php

namespace jossc\warps\manager;

use jossc\warps\utils\LocationUtils;
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
    public function __construct()
    {
        $this->main = Main::getInstance();
        $this->storage = $this->main->getStorage();
    }

    /*** @param Player $player */
    public function showWarpsMenu(Player $player): void {
        $form = new SimpleWindowForm(
            'warps',
            'Warps List',
            '',
            function (Player $player, Button $button) {
                $warp = $this->storage->get($button->getName());

                if (is_null($warp)) {
                    $player->sendMessage(TextFormat::RED . 'That warp does not exist!');

                    return;
                }

                $player->sendMessage(TextFormat::GREEN . 'Teleporting...');

                $player->teleport($warp->getLocation());
            }
        );

        $storage = $this->storage;

        $warps = $storage->getWarps();

        if (count($warps) == 0) {
            $player->sendMessage(TextFormat::RED . 'There are no warps to see!');

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
                TextFormat::RESET . TextFormat::DARK_GRAY . 'Click to go'
            );
        }

        $form->showTo($player);
    }

    /*** @param Player $player */
    public function showAddWarpMenu(Player $player): void {
        $form = new CustomWindowForm(
            'warps_add',
            'Add new Warp',
            '',
            function (Player $player, CustomWindowForm $form) {
                $warpId = $form->getElement('id');
                $warp = $warpId->getFinalValue();

                if (strlen($warp) === 0) {
                    $player->sendMessage(TextFormat::RED . 'Please, insert a valid name or identifier!');

                    return;
                }

                if ($this->storage->contains($warp)) {
                    $player->sendMessage(TextFormat::RED . 'That warp id already exist!');

                    return;
                }

                $main = $this->main;

                $config = $main->getConfig();

                $locationToString = LocationUtils::locationToString($player->getLocation());

                $warps = $config->get('warps');
                array_push($warps, "$warp;$locationToString");

                $config->set('warps', $warps);
                $config->save();

                $main->reloadWarpsConfig();

                $player->sendMessage(
                    TextFormat::GREEN . "You have successfully created the warp: $warp!"
                );
            }
        );

        $form->addInput('id', 'Please insert a warp id:');

        $form->showTo($player);
    }
}