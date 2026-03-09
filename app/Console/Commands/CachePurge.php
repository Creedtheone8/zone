<?php
/*
 * LaraClassifier - Classified Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com
 * Author: Mayeul Akpovi (BeDigit - https://bedigit.com)
 *
 * LICENSE
 * -------
 * This software is provided under a license agreement and may only be used or copied
 * in accordance with its terms, including the inclusion of the above copyright notice.
 * As this software is sold exclusively on CodeCanyon,
 * please review the full license details here: https://codecanyon.net/licenses/standard
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CachePurge extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'expired-cache:purge';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete only expired cache files and clean up empty cache directories.';
	
	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$cacheDriver = config('cache.default');
		$cachePath = castToStringOrNull(config('cache.stores.file.path'));
		$cachePath = !empty($cachePath) ? $cachePath : storage_path('framework/cache/data');
		
		// 1. Initial Checks
		if ($cacheDriver !== 'file') {
			$this->error("This command only supports the 'file' cache driver. Current driver: {$cacheDriver}");
			
			return Command::FAILURE;
		}
		
		if (!File::isDirectory($cachePath)) {
			$this->info('Cache directory does not exist. Nothing to clean.');
			
			return Command::SUCCESS;
		}
		
		$this->info("Starting cache cleanup in: {$cachePath}");
		$this->newLine();
		
		// 2. Delete Expired Files
		$this->info('1/2 Deleting expired files...');
		$this->deleteExpiredFiles($cachePath);
		
		// 3. Clean up Empty Directories
		$this->info('2/2 Cleaning up empty directories...');
		$removedDirCount =$this->removeEmptyDirectories($cachePath);
		$this->info("Removed **{$removedDirCount}** empty director(ies).");
		$this->newLine();
		
		return Command::SUCCESS;
	}
	
	/**
	 * Finds and deletes all expired cache files.
	 *
	 * @param string $cachePath
	 * @return void
	 */
	protected function deleteExpiredFiles(string $cachePath): void
	{
		$deletedCount = 0;
		$skippedCount = 0;
		$now = now()->getTimestamp();
		
		// Recursively get all files in the cache directory
		$files = File::allFiles($cachePath);
		
		foreach ($files as $file) {
			$filePath = $file->getPathname();
			
			try {
				// Get the raw content of the cache file
				$content = File::get($filePath);
				
				// The first 10 characters are the UNIX timestamp of the expiration time
				$expiration = (int)substr($content, 0, 10);
				
				// If current time is greater than the expiration time, and expiration is not 0 (forever)
				if ($expiration !== 0 && $now > $expiration) {
					File::delete($filePath);
					$deletedCount++;
				} else {
					$skippedCount++;
				}
				
			} catch (\Exception $e) {
				// Ignore unreadable/corrupted files
			}
		}
		
		// Files Clean Up Summary
		$this->info("Deleted **{$deletedCount}** expired cache files.");
		$this->info("Skipped **{$skippedCount}** active cache file(s).");
		$this->newLine();
	}
	
	/**
	 * Recursively traverses and deletes empty directories within the cache path.
	 *
	 * @param string $cachePath
	 * @return int
	 */
	protected function removeEmptyDirectories(string $cachePath): int
	{
		$removedCount = 0;
		
		// Get all root directories
		$directories = File::directories($cachePath);
		
		foreach ($directories as $directory) {
			$removedCount += $this->removeEmptyDirectories($directory);
			
			// Remove directory if it's empty after recursion
			if (count(File::allFiles($directory)) === 0 && count(File::directories($directory)) === 0) {
				try {
					File::deleteDirectory($directory);
					$removedCount++;
				} catch (\Exception $e) {
					// Handle potential permission or other deletion issues
					$this->warn("Could not delete directory: {$directory}. Check permissions.");
				}
			}
		}
		
		return $removedCount;
	}
}
