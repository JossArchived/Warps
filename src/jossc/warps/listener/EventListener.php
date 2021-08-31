<?php

namespace jossc\warps\listener;

use jossc\warps\Main;
use jossc\warps\manager\FormManager;
use pocketmine\event\Listener;
use pocketmine\event\player\{
    PlayerInteractEvent,
    PlayerJoinEvent
};
use pocketmine\item\Item;

class EventListener implements  Listener {

    /*** @var Main */
    private $main;

    /*** @var FormManager */
    private $formManager;

    /*** EventListener constructor.*/
    public function __construct() {
        $this->main = Main::getInstance();

        $this->formManager = $this->main->getFormManager();
    }

    /*** @param PlayerJoinEvent $event */
    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        if ($this->main->isGiveItemWhenJoin()) {
            $player->getInventory()->clearAll();

            $warpsItem = Item::get(Item::FEATHER)->setCustomName(
                $this->main->getItemCustomName()
            );

            $player->getInventory()->setItem(4, $warpsItem);
        }
    }

    /*** @param PlayerInteractEvent $event */
    public function onInteract(PlayerInteractEvent $event): void {
        $item = $event->getItem();

        if ($item->getCustomName() === $this->main->getItemCustomName()) {
            $this->formManager->showWarpsMenu($event->getPlayer());
        }
    }
}