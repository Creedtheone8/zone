<?php
/**
 * Schedule Run Command Wrapper
 *
 * This file is a wrapper for the "artisan schedule:run" command.
 * @see \App\Console\Kernel
 *
 * PURPOSE:
 * On shared hosting environments, cron jobs often require a direct file path
 * instead of running artisan commands. This wrapper allows you to run Laravel's
 * task scheduler using a simple file path.
 *
 * USAGE:
 * - Direct execution: php /full/path/to/extras/cmd/schedule-run.php
 * - Cron job example: * * * * * /usr/bin/php /home/user/public_html/extras/cmd/schedule-run.php
 *
 * IMPORTANT:
 * This command should run every minute (* * * * *) in your cron configuration.
 * Laravel's scheduler will internally determine which tasks should actually execute
 * based on their individual schedules defined in app/Console/Kernel.php
 *
 * This is equivalent to running: php artisan schedule:run
 */

// Set command-line arguments to simulate running: php artisan schedule:run
$_SERVER['argv'] = [
	'artisan',
	'schedule:run',
];

// Bootstrap and execute the artisan command
require __DIR__ . '/../../artisan';
