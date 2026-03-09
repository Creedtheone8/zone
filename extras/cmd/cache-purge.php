<?php
/**
 * Cache Purge Command Wrapper
 *
 * This file is a wrapper for the "artisan expired-cache:purge" command.
 * @see \App\Console\Commands\CachePurge
 *
 * PURPOSE:
 * On shared hosting environments, cron jobs often require a direct file path
 * instead of running artisan commands. This wrapper allows you to schedule
 * the cache purge command using a simple file path.
 *
 * USAGE:
 * - Direct execution: php /full/path/to/extras/cmd/cache-purge.php
 * - Cron job example: 0 3 * * * /usr/bin/php /home/user/public_html/extras/cmd/cache-purge.php
 *
 * This is equivalent to running: php artisan expired-cache:purge
 */

// Set command-line arguments to simulate running: php artisan expired-cache:purge
$_SERVER['argv'] = [
	'artisan',
	'expired-cache:purge',
];

// Bootstrap and execute the artisan command
require __DIR__ . '/../../artisan';
