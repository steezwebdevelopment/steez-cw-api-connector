# steez-cw-api-connector
A WordPress plugin that connects to the Cloudways API and enables you to reset file permissions (for example). Via WP CLI. So you don't have to do it via the panel everytime.

## Add your credentials in wp-config
```
define('WP_CLOUDWAYS_EMAIL', 'xxx@xxx.xxx');
define('WP_CLOUDWAYS_API_KEY', 'xxx');
define('WP_CLOUDWAYS_SERVER_ID', 'xxx');
define('WP_CLOUDWAYS_APP_ID', 'xxx');
```

## Use the WP CLI to reset the file permissions
```
wp steez:reset_file_permissions
```

## Using Deployer.org?
I've added a task to let Deployer reset the file permissions before deploy:writable.

```
task('cloudways:reset-file-permissions', function () {
	run("cd {{release_path}}");
	run("/usr/local/bin/wp --path='{{release_path}}' plugin activate steez-cw-api-connector");
	run("/usr/local/bin/wp --path='{{release_path}}' steez:reset_file_permissions");
});
```

## What's next?
You could add more Cloudways API functions. Fork it and PR!
