<?php

namespace jossc\warps\command;

use jossc\warps\Main;
use jossc\warps\manager\FormManager;
use pocketmine\command\{
    Command,
    CommandSender
};
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class AddWarpCommand extends Command {

    /*** @var FormManager */
    private $formManager;

    /*** AddWarpCommand constructor.*/
    public function __construct()
    {
        parent::__construct(
            "addwarp",
            "Add a new warp location"
        );

        $this->formManager = Main::getInstance()->getFormManager();
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!($sender instanceof Player)) {
            $sender->sendMessage(TextFormat::RED . "Please, use this command in-game!");

            return false;
        }

        $this->formManager->showAddWarpMenu($sender->getPlayer());

        return true;
    }
}