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

class RemoveWarpCommand extends Command {

    /*** @var FormManager */
    private $formManager;

    /*** RemoveWarpCommand constructor.*/
    public function __construct() {
        parent::__construct(
            'removewarp',
            'Remove a warp '
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
            $sender->sendMessage(TextFormat::RED . 'Please, use this command in-game!');

            return false;
        }

        if (!$sender->isOp()) {
            $sender->sendMessage(TextFormat::RED . 'You do not have permissions!');

            return false;
        }

        $this->formManager->showRemoveWarpMenu($sender->getPlayer());

        return true;
    }
}