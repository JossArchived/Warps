<?php

namespace jossc\warps;

use jossc\warps\command\SetWarpCommand;
use jossc\warps\listener\EventListener;
use jossc\warps\manager\FormManager;
use jossc\warps\storage\WarpsStorage;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase {

    /*** @var Main */
    private static $instance;
    /*** @var WarpsStorage */
    private $storage;
    /*** @var FormManager */
    private $formManager;

    public function onEnable()
    {
        parent::onEnable();

        $this->saveDefaultConfig();

        self::$instance = $this;
        $this->storage = new WarpsStorage();
        $this->formManager = new FormManager();

        $this->reloadWarpsConfig();

        $this->getServer()->getCommandMap()->register(
            "setwarp",
            new SetWarpCommand()
        );

        $this->getServer()->getPluginManager()->registerEvents(
            new EventListener(),
            $this
        );

        $this->getLogger()->info(TextFormat::GREEN . "This plugin has been enabled!");
    }

    public function reloadWarpsConfig(): void {
        $config = $this->getConfig();

        if (!$config->exists("warps")) {
            return;
        }

        $storage = $this->storage;

        $warps = $config->get("warps");

        foreach ($warps as $warpData) {
            $warpDataArray = explode(";", $warpData);

            $storage->add($warpDataArray[0], $warpDataArray[1]);
        }
    }

    /*** @return Main */
    public static function getInstance(): Main
    {
        return self::$instance;
    }

    /*** @return WarpsStorage */
    public function getStorage(): WarpsStorage
    {
        return $this->storage;
    }

    /*** @return FormManager */
    public function getFormManager(): FormManager
    {
        return $this->formManager;
    }

    public function onDisable()
    {
        parent::onDisable();

        $this->getLogger()->info(TextFormat::RED . "This plugin has been disabled!");
    }
}