<?php

declare(strict_types=1);

namespace ColinHDev\CPlotClaimAddon;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class CPlotClaimAddon extends PluginBase {
    use SingletonTrait;

    public function onLoad() : void {
        self::setInstance($this);
    }
}