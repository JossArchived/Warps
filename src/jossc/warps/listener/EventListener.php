<?php

namespace jossc\warps\listener;

use lib\FormAPI\response\PlayerWindowResponse;
use lib\FormAPI\window\CustomWindowForm;
use lib\FormAPI\window\SimpleWindowForm;
use jossc\warps\Main;
use jossc\warps\manager\FormManager;
use jossc\warps\storage\WarpsStorage;
use jossc\warps\utils\LocationUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

class EventListener implements  Listener {

    const WARPS_CUSTOM_NAME = TextFormat::BOLD . TextFormat::GREEN . "Warps";

    /*** @var Main */
    private $main;
    /*** @var FormManager */
    private $formManager;
    /*** @var WarpsStorage */
    private $storage;

    public function __construct() {
        $this->main = Main::getInstance();

        $this->formManager = $this->main->getFormManager();
        $this->storage = $this->main->getStorage();
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        $player->getInventory()->clearAll();

        $warpsItem = Item::get(Item::FEATHER)->setCustomName(
            self::WARPS_CUSTOM_NAME
        );

        $player->getInventory()->setItem(4, $warpsItem);
    }

    public function onInteract(PlayerInteractEvent $event): void {
        $item = $event->getItem();

        if ($item->getCustomName() === self::WARPS_CUSTOM_NAME) {
            $this->formManager->showWarpsMenu($event->getPlayer());
        }
    }

    public function onWindowResponse(PlayerWindowResponse $event): void {
        $player = $event->getPlayer();
        $form = $event->getForm();

        $storage = $this->storage;

        if ($form instanceof SimpleWindowForm) {
            if ($form->getName() != "warps") {
                return;
            }

            if ($form->isClosed()) {
                return;
            }

            $button = $form->getClickedButton();

            $warp = $storage->get($button->getName());

            if (is_null($warp)) {
                $player->sendMessage(TextFormat::RED . "That warp does not exist!");

                return;
            }

            $player->sendMessage(TextFormat::GREEN . "Teleporting...");

            $player->teleport($warp->getLocation());

            return;
        }

        $location = $player->getLocation();

        if ($form instanceof CustomWindowForm) {
            if ($form->getName() != "warps_add") {
                return;
            }

            if ($form->isClosed()) {
                return;
            }

            $warpId = $form->getElement("id");

            if ($storage->contains($warpId->getFinalValue())) {
                $player->sendMessage(TextFormat::RED . "That warp id already exist!.");

                return;
            }

            $main = $this->main;

            $config = $main->getConfig();

            $locationToString = LocationUtils::locationToString($location);

            $warps = $config->get("warps");
            array_push($warps, "{$warpId->getFinalValue()};$locationToString");

            $config->set("warps", $warps);
            $config->save();

            $main->reloadWarpsConfig();

            $player->sendMessage(
                TextFormat::GREEN . "You have successfully created the warp: {$warpId->getFinalValue()}!"
            );
        }
    }
}