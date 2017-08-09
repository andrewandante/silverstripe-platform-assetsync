# SilverStripe-Platform Asset Sync

For use exclusively on SilverStripe platform. This is designed to allow transfer of assets between environments, where the size of the assets snapshot is so large as to timeout the process/

## Installation

Add to `composer.json` under `require`
`"silverstripe-platform/assetsync": "^0.1.0",`

Then under `repositories`

```
{
	"type": "vcs",
	"url": "git@github.com:andrewandante/silverstripe-platform-assetsync.git"
}
```

## How to use

The way for the user to initialise this is either a) to create the QueuedJob from the QueuedJobs admin, or b) use the dev/task. You can call this by visiting dev/tasks/SyncAssetsFromEnvironmentTask?delay=none to create a job that will run instantly, or you can delay=1 to delay by 1 hour, delay=2 to delay by 2 hours etc.

You should also have a cronjob set to run QueuedJobs in your `.platform.yml`:

```yml
crons:
  queuedJobsLarge:
    time: "*/10 * * * *"
    command: "php /var/www/mysite/www/framework/cli-script.php dev/tasks/ProcessJobQueueTask queue=large | logger -t SilverStripe_cron"
```

Which will run it for you once you have queued it up.

## Environment Variables

You'll need to define the variable `SSP_ASSET_SYNC_SOURCE`. This will be the S3 endpoint that you are syncing from. For example, `s3://<account>-rainforest-backup/<cluster>-<stack>-<environment>Xnfs/assets`.

## IAM Policy

Requires a new policy with the following:

```json
{
    "Statement": [
        {
            "Resource": "arn:aws:s3:::<bucket_name>",
            "Action": [
                "s3:ListBucket"
            ],
            "Effect": "Allow"
        },
        {
            "Resource": "arn:aws:s3:::<bucket_name>/<cluster>-<stack>-<env>Xnfs/*",
            "Action": [
                "s3:GetObject",
                "s3:GetObjectAcl"
            ],
            "Effect": "Allow"
        }
    ],
    "Version": "2012-10-17"
}
```

Add this to both the server role for environment and NFS you are running this from i.e. if I am running this from UAT, I want the policy to contain references to Prod, and add the policy to the UAT server role and the UATXNFS server role.

## Troubleshooting

Q. It ran but the assets aren't showing up in the `Files` section!
A. Try syncing files.

Q. If I run the job from the QueuedJobs admin, it just spins!
A. I think that's because it's a LONG type job, so it just spins. Seems to complete ok though.
