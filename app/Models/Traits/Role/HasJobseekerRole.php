<?php
/*
 * JobClass - Job Board Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com/jobclass
 * Author: Mayeul Akpovi (BeDigit - https://bedigit.com)
 *
 * LICENSE
 * -------
 * This software is provided under a license agreement and may only be used or copied
 * in accordance with its terms, including the inclusion of the above copyright notice.
 * As this software is sold exclusively on CodeCanyon,
 * please review the full license details here: https://codecanyon.net/licenses/standard
 */

namespace App\Models\Traits\Role;

use App\Models\Role;

trait HasJobseekerRole
{
	/**
	 * Default jobseekers role
	 *
	 * @return string
	 */
	public static function getJobseekerRole(): string
	{
		return 'jobseeker';
	}
	
	/**
	 * Get jobseekers role (from DB)
	 *
	 * @return \App\Models\Role|null
	 */
	public static function getJobseekerRoleFromDb(): ?Role
	{
		try {
			return Role::where('name', Role::getJobseekerRole())->first();
		} catch (\Throwable $e) {
			return null;
		}
	}
	
	/**
	 * Check jobseeker role
	 * NOTE: Must use try {...} catch {...}
	 *
	 * @return bool
	 */
	public static function checkJobseekerRole(): bool
	{
		$role = Role::getJobseekerRoleFromDb();
		
		return !empty($role);
	}
	
	/**
	 * Ensure jobseeker role exists
	 *
	 * @return \App\Models\Role|null
	 */
	public static function ensureJobseekerRoleExists(): ?Role
	{
		try {
			return Role::firstOrCreate(['name' => Role::getJobseekerRole()]);
		} catch (\Throwable $e) {
			return null;
		}
	}
}
