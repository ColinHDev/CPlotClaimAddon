<?php

declare(strict_types=1);

namespace ColinHDev\CPlotClaimAddon;

use ColinHDev\CPlot\utils\ParseUtils;
use pocketmine\block\Block;
use pocketmine\data\bedrock\BiomeIds;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class ResourceManager {
    use SingletonTrait;

    /** @phpstan-var BiomeIds::* */
    private int $fallbackBiomeID;
    /** @phpstan-var array<string, BiomeIds::*> */
    private array $biomeIDs;

    private Block $fallbackBorderBlock;
    /** @phpstan-var array<string, Block> */
    private array $borderBlocks;

    private Block $fallbackWallBlock;
    /** @phpstan-var array<string, Block> */
    private array $wallBlocks;

    public function __construct() {
        CPlotClaimAddon::getInstance()->saveResource("config.yml");
        /** @phpstan-var array{"biome.fallback": string, "biome.worlds": array<string, string>, "borderBlock.fallback": string, "borderBlock.worlds": array<string, string>, "wallBlock.fallback": string, "wallBlock.worlds": array<string, string>} $configData */
        $configData = (new Config(CPlotClaimAddon::getInstance()->getDataFolder() . "config.yml", Config::YAML))->getAll();

        $this->fallbackBiomeID = $this->parseBiomeIDFromString($configData["biome.fallback"]);
        foreach ($configData["biome.worlds"] as $worldName => $biomeString) {
            $this->biomeIDs[$worldName] = $this->parseBiomeIDFromString($biomeString);
        }

        $this->fallbackBorderBlock = $this->parseBlockFromString($configData["borderBlock.fallback"]);
        foreach ($configData["borderBlock.worlds"] as $worldName => $blockString) {
            $this->borderBlocks[$worldName] = $this->parseBlockFromString($blockString);
        }

        $this->fallbackWallBlock = $this->parseBlockFromString($configData["wallBlock.fallback"]);
        foreach ($configData["wallBlock.worlds"] as $worldName => $blockString) {
            $this->wallBlocks[$worldName] = $this->parseBlockFromString($blockString);
        }
    }

    /**
     * @phpstan-return BiomeIds::*
     */
    private function parseBiomeIDFromString(string $biomeIdentifier) : int {
        $biomeIdentifier = strtoupper($biomeIdentifier);
        if (defined(BiomeIds::class . "::" . $biomeIdentifier)) {
            $constant = constant(BiomeIds::class . "::" . $biomeIdentifier);
            if (is_int($constant)) {
                /** @phpstan-var BiomeIds::* $constant */
                return $constant;
            }
        }
        throw new \RuntimeException("Could not parse biome from string: " . $biomeIdentifier);
    }

    private function parseBlockFromString(string $blockIdentifier) : Block {
        $block = ParseUtils::parseBlockFromString($blockIdentifier);
        if ($block === null) {
            throw new \RuntimeException("Could not parse block from string: " . $blockIdentifier);
        }
        return $block;
    }

    /**
     * @phpstan-return BiomeIds::*
     */
    public function getBiomeIDForWorld(string $worldName) : int {
        return $this->biomeIDs[$worldName] ?? $this->fallbackBiomeID;
    }

    public function getBorderBlockForWorld(string $worldName) : Block {
        return $this->borderBlocks[$worldName] ?? $this->fallbackBorderBlock;
    }

    public function getWallBlockForWorld(string $worldName) : Block {
        return $this->wallBlocks[$worldName] ?? $this->fallbackWallBlock;
    }
}