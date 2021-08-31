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

class ReloadWarpsCommand extends Command {

    /*** @var Main */
    private $main;

    /*** ReloadWarpsCommand constructor.*/
    public function __construct()
    {
        parent::__construct(
            "reloadwarps",
            "Reload all warps"
        );

        $this->main = Main::getInstance();
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        $this->main->reloadWarpsConfig();

        $sender->sendMessage(TextFormat::GREEN . "All warps reloaded!");

        return true;
    }
}