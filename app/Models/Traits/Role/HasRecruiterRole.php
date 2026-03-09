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

trait HasRecruiterRole
{
	/**
	 * Default recruiters role
	 *
	 * @return string
	 */
	public static function getRecruiterRole(): string
	{
		return 'recruiter';
	}
	
	/**
	 * Get recruiters role (from DB)
	 *
	 * @return \App\Models\Role|null
	 */
	public static function getRecruiterRoleFromDb(): ?Role
	{
		try {
			return Role::where('name', Role::getRecruiterRole())->first();
		} catch (\Throwable $e) {
			return null;
		}
	}
	
	/**
	 * Check recruiter role
	 * NOTE: Must use try {...} catch {...}
	 *
	 * @return bool
	 */
	public static function checkRecruiterRole(): bool
	{
		$role = Role::getRecruiterRoleFromDb();
		
		return !empty($role);
	}
	
	/**
	 * Ensure recruiter role exists
	 *
	 * @return \App\Models\Role|null
	 */
	public static function ensureRecruiterRoleExists(): ?Role
	{
		try {
			return Role::firstOrCreate(['name' => Role::getRecruiterRole()]);
		} catch (\Throwable $e) {
			return null;
		}
	}
}
