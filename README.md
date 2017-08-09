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
