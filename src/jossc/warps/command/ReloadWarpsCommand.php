<?php

namespace jossc\warps\command;

use jossc\warps\Main;
use pocketmine\command\{
    Command,
    CommandSender
};
use pocketmine\utils\TextFormat;

class ReloadWarpsCommand extends Command {

    /*** @var Main */
    private $main;

    /*** ReloadWarpsCommand constructor.*/
    public function __construct() {
        parent::__construct(
            'reloadwarps',
            'Reload all warps'
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
        if (!$sender->isOp()) {
            $sender->sendMessage(TextFormat::RED . 'You do not have permissions!');

            return false;
        }

        $this->main->getStorage()->reload();

        $sender->sendMessage(TextFormat::GREEN . 'All warps reloaded!');

        return true;
    }
}