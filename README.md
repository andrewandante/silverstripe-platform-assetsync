# SilverStripe-Platform Asset Sync

For use exclusively on SilverStripe platform. This is designed to allow transfer of assets between environments, where the size of the assets snapshot is so large as to timeout the process/

## Installation

Add to `composer.json` under `require`
`"silverstripeltd/silverstripe-platform-assetsync": "dev-master",`

Then under `repositories`

```
{
	"type": "vcs",
	"url": "git@github.com:silverstripeltd/silverstripe-platform-assetsync.git"
}
```


