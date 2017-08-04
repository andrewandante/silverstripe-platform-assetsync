<?php

class SyncAssetsFromEnvironmentTask extends BuildTask
{

    /**
     * @param SS_HTTPRequest $request
     * @return bool
     */
    public function run($request = null)
    {
        $log = function ($output) {
            echo Director::is_cli() ? ($output.PHP_EOL) : ($output.'<br>');
        };

        if (!defined('SSP_ASSET_SYNC_SOURCE')) {
            $log('SSP_ASSET_SYNC_SOURCE must be defined!');
            return false;
        }

        $delay = $request->getVar('delay');
        if (!$delay || !is_numeric($delay)) {
            $log('Param "delay" must be int representing number of hours to delay execution by.');
        }
        $delay *= 60 * 60; // convert hours to seconds
        $queueTime = date('Y-m-d H:i:s', time() + $delay);

        $syncJob = new SyncAssetsFromEnvironmentJob();
        singleton('QueuedJobService')->queueJob($syncJob, $queueTime);
        $log('Asset sync job queued for '.$queueTime);


    }
}