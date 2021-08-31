<?php

namespace jossc\warps;

use jossc\warps\command\AddWarpCommand;
use jossc\warps\command\ReloadWarpsCommand;
use jossc\warps\command\WarpsCommand;
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

    /*** @var bool */
    private $giveItemWhenJoin = false;

    /*** @var string */
    private $itemCustomName = "Custom Name";

    public function onEnable() {
        parent::onEnable();

        $this->saveDefaultConfig();

        self::$instance = $this;
        $this->storage = new WarpsStorage();
        $this->formManager = new FormManager();

        $itemConfig = $this->getConfig()->get("item");
        $this->giveItemWhenJoin = (bool) $itemConfig["give_item_when_join"];
        $this->itemCustomName = TextFormat::colorize((string) $itemConfig["custom_name"]);

        $this->reloadWarpsConfig();

        $this->registerCommands();

        $this->getServer()->getPluginManager()->registerEvents(
            new EventListener(),
            $this
        );

        $this->getLogger()->info(TextFormat::GREEN . 'This plugin has been enabled!');
    }

    public function onDisable() {
        parent::onDisable();

        $this->getLogger()->info(TextFormat::RED . 'This plugin has been disabled!');
    }

    private function registerCommands(): void {
        $commandMap = $this->getServer()->getCommandMap();

        $commandMap->register(
            'addwarp',
            new AddWarpCommand()
        );

        $commandMap->register(
            'reloadwarps',
            new ReloadWarpsCommand()
        );

        $commandMap->register(
            'warps',
            new WarpsCommand()
        );
    }

    public function reloadWarpsConfig(): void {
        $config = $this->getConfig();

        if (!$config->exists('warps')) {
            return;
        }

        $storage = $this->storage;

        $warps = $config->get('warps');

        foreach ($warps as $warpData) {
            $warpDataArray = explode(';', $warpData);

            $storage->add($warpDataArray[0], $warpDataArray[1]);
        }
    }

    /*** @return Main */
    public static function getInstance(): Main {
        return self::$instance;
    }

    /*** @return WarpsStorage */
    public function getStorage(): WarpsStorage {
        return $this->storage;
    }

    /*** @return FormManager */
    public function getFormManager(): FormManager {
        return $this->formManager;
    }

    /*** @return bool */
    public function isGiveItemWhenJoin(): bool
    {
        return $this->giveItemWhenJoin;
    }

    /*** @return string */
    public function getItemCustomName(): string
    {
        return $this->itemCustomName;
    }
}