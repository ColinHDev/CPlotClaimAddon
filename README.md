# CPlotClaimAddon

CPlotClaimAddon is an addon for performing certain actions once a plot is claimed for the plugin [CPlot](https://github.com/ColinHDev/CPlot) for the Minecraft: Bedrock Edition server software [PocketMine-MP](https://github.com/pmmp/PocketMine-MP).

## Features
Since it is visually impossible to determine the difference between an unclaimed plot and an empty but claimed plot in CPlot, this plugin allows you to set a specific biome, border and wall which will be applied on plots once they are claimed.

## Customization
All the customization can be done in the [config.yml](resources/config.yml) file.

To set the default biome, border and wall which will be applied to all plots once they are claimed, change the `biome.fallback`, `borderBlock.fallback` and `wallBlock.fallback` settings in the config file.

If you want to have an unique setting for a specific world, you can change `biome.worlds`, `borderBlock.worlds` or `wallBlock.worlds` settings.

If you want to disable the change of the biome, border or wall for a specific world, you need to set the value of that world in the `biome.worlds`, `borderBlock.worlds` or `wallBlock.worlds` setting to the matching value of the world's settings, which you defined in CPlot's config.yml file:
- To disable the change of the biome, let the `biome.worlds` setting match `worldsettings.biome` in CPlot's config.yml file.
- To disable the change of the border, let the `borderBlock.worlds` setting match `worldsettings.borderBlock` in CPlot's config.yml file.
- To disable the change of the border, let the `wallBlock.worlds` setting match `worldsettings.roadBlock` in CPlot's config.yml file.