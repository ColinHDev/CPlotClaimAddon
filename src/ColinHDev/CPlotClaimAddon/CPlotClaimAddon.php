<?php

declare(strict_types=1);

namespace ColinHDev\CPlotClaimAddon;

use ColinHDev\CPlotClaimAddon\listener\PlotEventListener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class CPlotClaimAddon extends PluginBase {
    use SingletonTrait;

    public function onLoad() : void {
        self::setInstance($this);
    }

    public function onEnable() : void {
        ResourceManager::getInstance();
        $this->getServer()->getPluginManager()->registerEvents(new PlotEventListener(), $this);
    }
}