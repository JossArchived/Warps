<?php

namespace lib\FormAPI\response;

use lib\FormAPI\window\CustomWindowForm;
use lib\FormAPI\window\WindowForm;
use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;

class PlayerWindowResponse extends PlayerEvent
{

    /** @var WindowForm */
    private $form;

    public function __construct(Player $player, WindowForm $form)
    {
        $this->player = $player;
        $this->form = $form;
    }

    /**
     * @return WindowForm
     */
    public function getForm(): WindowForm
    {
        return $this->form;
    }
}