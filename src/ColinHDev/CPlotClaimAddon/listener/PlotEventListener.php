<?php

declare(strict_types=1);

namespace ColinHDev\CPlotClaimAddon\listener;

use ColinHDev\CPlot\event\PlotAsyncEvent;
use ColinHDev\CPlot\event\PlotClaimAsyncEvent;
use ColinHDev\CPlot\event\PlotClearedAsyncEvent;
use ColinHDev\CPlot\event\PlotMergedAsyncEvent;
use ColinHDev\CPlotClaimAddon\ResourceManager;
use pocketmine\event\Listener;
use SOFe\AwaitGenerator\Await;

class PlotEventListener implements Listener {

    /**
     * @handleCancelled false
     * @priority MONITOR
     */
    public function onPlotClaim(PlotClaimAsyncEvent $event) : void {
        $this->onPlotEvent($event);
    }

    /**
     * @priority MONITOR
     */
    public function onPlotCleared(PlotClearedAsyncEvent $event) : void {
        if (!$event->getPlot()->hasPlotOwner()) {
            $event->continue();
            return;
        }
        $this->onPlotEvent($event);
    }

    /**
     * @priority MONITOR
     */
    public function onPlotMerged(PlotMergedAsyncEvent $event) : void {
        if (!$event->getPlot()->hasPlotOwner()) {
            $event->continue();
            return;
        }
        $this->onPlotEvent($event);
    }

    private function onPlotEvent(PlotAsyncEvent $event) : void {
        Await::f2c(static function() use($event) : \Generator {
            $plot = $event->getPlot();
            $worldName = $plot->getWorldName();
            $worldSettings = $plot->getWorldSettings();
            $resourceManager = ResourceManager::getInstance();

            $biomeID = $resourceManager->getBiomeIDForWorld($worldName);
            if ($biomeID !== $worldSettings->getBiomeID()) {
                yield from Await::promise(
                    static fn($resolve) => $plot->setBiome($biomeID, $resolve, $resolve)
                );
            }

            $borderBlock = $resourceManager->getBorderBlockForWorld($worldName);
            if (!$borderBlock->isSameState($worldSettings->getBorderBlock())) {
                yield from Await::promise(
                    static fn($resolve) => $plot->setBorderBlock($borderBlock, $resolve, $resolve)
                );
            }

            $wallBlock = $resourceManager->getWallBlockForWorld($worldName);
            if (!$wallBlock->isSameState($worldSettings->getRoadBlock())) {
                yield from Await::promise(
                    static fn($resolve) => $plot->setWallBlock($wallBlock, $resolve, $resolve)
                );
            }

            $event->continue();
        });
    }
}