<?php

//  trying - https://www.mitrais.com/news-updates/how-to-create-ci-cd-with-github-action-and-laravel/

namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/npm.php';
require 'contrib/rsync.php';

// /////////////////////////////////
// Config
// /////////////////////////////////

set('application', 'Practice Manager');
set('repository', '');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);
set('ssh_multiplexing', true);  // Speed up deployment
// set('default_timeout', 1000);

set('rsync_src', function () {
    return __DIR__; // If your project isn't in the root, you'll need to change this.
});

// Configuring the rsync exclusions.
// You'll want to exclude anything that you don't want on the production server.
add('rsync', [
    'exclude' => [
        '.git',
        '/vendor/',
        '/node_modules/',
        '.github',
        'deploy.php',
    ],
]);

// Set up a deployer task to copy secrets to the server.
// Grabs the dotenv file from the github secret
task('deploy:secrets', function () {
    $envContent = getenv('DOT_ENV_PRODUCTION'); // Get the content from the environment variable
    if (empty($envContent)) {
        throw new \RuntimeException('DOT_ENV_PRODUCTION is empty');
    }

    $deployPath = get('deploy_path');
    run("echo \"$envContent\" > $deployPath/shared/.env"); // Write the content to the .env file in the shared directory
})->desc('Deploy .env file');

// /////////////////////////////////
// Hosts
// /////////////////////////////////

host('dev.picsi.co')
    ->setHostname('103.6.171.211')
    ->set('remote_user', 'deployer')
    ->set('branch', 'master') // Git branch
    ->set('deploy_path', '/var/www/picsi');

// Hooks

after('deploy:failed', 'deploy:unlock');

// /////////////////////////////////
// Tasks
// /////////////////////////////////

desc('Start of Deploy the application');

task('deploy', [
    'deploy:prepare',
    'rsync',                // Deploy code & built assets
    // 'deploy:secrets',       // Deploy secrets - we can't work out why this fails
    'deploy:vendors',
    'deploy:shared',        //
    'artisan:storage:link', //
    'artisan:view:cache',   //
    'artisan:config:cache', //
    'artisan:migrate',      //
    // 'artisan:db:seed',      //
    'artisan:queue:restart', //
    'deploy:publish',      //
]);

desc('End of Deploy the application');
