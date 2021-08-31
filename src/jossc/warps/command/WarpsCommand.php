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

class WarpsCommand extends Command {

    /*** @var FormManager */
    private $formManager;

    /*** AddWarpCommand constructor.*/
    public function __construct()
    {
        parent::__construct(
            'warps',
            'See a warps list'
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

        $this->formManager->showWarpsMenu($sender->getPlayer());

        return true;
    }
}