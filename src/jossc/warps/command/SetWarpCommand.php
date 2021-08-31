<?php

namespace jossc\warps\command;

use jossc\warps\Main;
use jossc\warps\manager\FormManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SetWarpCommand extends Command {

    /*** @var FormManager */
    private $formManager;

    public function __construct()
    {
        parent::__construct(
            "setwarp",
            "Set a new warp location",
            "/setwarps",
        );

        $this->formManager = Main::getInstance()->getFormManager();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!($sender instanceof Player)) {
            $sender->sendMessage(TextFormat::RED . "Please, use this command in-game!");

            return false;
        }

        $this->formManager->showAddWarpMenu($sender->getPlayer());

        return true;
    }
}