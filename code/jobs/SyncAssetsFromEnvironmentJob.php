<?php

class SyncAssetsFromEnvironmentJob extends AbstractQueuedJob
{
    public function getTitle() {
        return sprintf("Sync assets from %s", SSP_ASSET_SYNC_SOURCE);
    }

    public function getJobType()
    {
        return QueuedJob::LARGE;
    }

    public function process()
    {
        $command = [];
        $command[] = 'flock -n -e /var/run/prods3assets.lock';
        $command[] = '"/usr/local/bin/aws s3 sync --only-show-errors';
        $command[] = SSP_ASSET_SYNC_SOURCE;
        $command[] = ASSETS_PATH;
        $command[] = '--exclude *.snapshot.restore*';
        $command[] = '--exclude *.snapshot.store*';
        $command[] = '&& chown -R www-data:www-data';
        $command[] = ASSETS_PATH;

        exec(implode(' ', $command));

    }


}