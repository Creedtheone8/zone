<?php
/**
 * Listings Purge Command Wrapper
 *
 * This file is a wrapper for the "artisan listings:purge" command.
 * @see \App\Console\Commands\ListingsPurge
 *
 * PURPOSE:
 * On shared hosting environments, cron jobs often require a direct file path
 * instead of running artisan commands. This wrapper allows you to schedule
 * the listings purge command using a simple file path.
 *
 * USAGE:
 * - Direct execution: php /full/path/to/extras/cmd/listings-purge.php
 * - Cron job example: 0 2 * * * /usr/bin/php /home/user/public_html/extras/cmd/listings-purge.php
 *
 * This is equivalent to running: php artisan listings:purge
 */

// Set command-line arguments to simulate running: php artisan listings:purge
$_SERVER['argv'] = [
	'artisan',
	'listings:purge',
];

// Bootstrap and execute the artisan command
require __DIR__ . '/../../artisan';
