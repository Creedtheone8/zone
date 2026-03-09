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

namespace App\Models\Traits\Permission;

use App\Enums\UserType;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

trait HasJobseekerPermissions
{
	/**
	 * Default jobseekers permissions
	 *
	 * @return array<int, string>
	 */
	public static function getJobseekerPermissions(): array
	{
		return [
			'resumes.view',
			'resumes.create',
			'resumes.edit',
			'resumes.delete',
			
			'posts.view',
			
			'saved-posts.view',
			'saved-posts.create',
			'saved-posts.edit',
			'saved-posts.delete',
			
			'saved-search.view',
			'saved-search.create',
			'saved-search.edit',
			'saved-search.delete',
		];
	}
	
	/**
	 * Get jobseekers permissions (from DB)
	 *
	 * @return array
	 */
	public static function getJobseekerPermissionsFromDb(): array
	{
		$permissionList = [];
		$permissions = collect();
		try {
			$permissionList = Permission::getJobseekerPermissions();
			if (!empty($permissionList)) {
				$permissions = Permission::whereIn('name', $permissionList)->get();
			}
		} catch (\Throwable $e) {
		}
		
		if (empty($permissionList) || $permissions->count() <= 0) {
			return [];
		}
		
		if (count($permissionList) !== $permissions->count()) {
			return [];
		}
		
		return $permissions->toArray();
	}
	
	/**
	 * Check jobseeker permissions
	 * NOTE: Must use try {...} catch {...}
	 *
	 * @return bool
	 */
	public static function checkJobseekerPermissions(): bool
	{
		$permissions = Permission::getJobseekerPermissionsFromDb();
		
		return !empty($permissions);
	}
	
	/**
	 * Check jobseeker role & permissions
	 *
	 * @param \App\Models\Role|null $role
	 * @return bool
	 */
	public static function checkJobseekerRoleAndPermissions(?Role $role = null): bool
	{
		$doesRoleExist = !empty($role);
		$doesRoleExist = $doesRoleExist || Role::checkJobseekerRole();
		
		if (!$doesRoleExist || !Permission::checkJobseekerPermissions()) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Reset jobseeker permissions
	 * NOTE: Must use try {...} catch {...}
	 *
	 * @param \App\Models\Role|null $role
	 * @return void
	 */
	public static function resetJobseekerPermissions(?Role $role = null): void
	{
		try {
			// Ensure jobseeker role exists
			$role = empty($role) ? Role::ensureJobseekerRoleExists() : $role;
			if (empty($role)) return;
			
			$permissionList = Permission::getJobseekerPermissions();
			
			// Remove all current permissions & their relationship
			$permissionsOld = Permission::whereIn('name', $permissionList)->get();
			foreach ($permissionsOld as $permissionOld) {
				if ($permissionOld->roles()->count() > 0) {
					$permissionOld->roles()->detach();
				}
				if (!in_array($permissionOld->name, $permissionList)) {
					$permissionOld->delete();
				}
			}
			
			// Create default jobseeker permissions
			if (!empty($permissionList)) {
				foreach ($permissionList as $permissionName) {
					$permission = Permission::firstOrCreate(['name' => $permissionName]);
					$role->givePermissionTo($permission);
				}
			}
		} catch (\Exception $e) {
		}
	}
	
	/**
	 * Ensure jobseeker role and permissions exist
	 * NOTE: Return the Role object
	 *
	 * @return \App\Models\Role|null
	 */
	public static function ensureJobseekerRoleAndPermissionsExist(): ?Role
	{
		$role = empty($role) ? Role::ensureJobseekerRoleExists() : $role;
		if (!Permission::checkJobseekerRoleAndPermissions($role)) {
			Permission::resetJobseekerPermissions($role);
		}
		
		return $role;
	}
	
	/**
	 * Synchronize jobseeker role assignments to ensure data consistency.
	 *
	 * Repairs role inconsistencies by assigning the correct jobseeker role to all users
	 * with user_type_id = UserType::JOBSEEKER->value. First resets default permissions if needed, then checks
	 * for consistency. If issues are found, removes existing roles and reassigns the
	 * proper jobseeker role to each jobseeker user.
	 *
	 * NOTE: Must use try {...} catch {...}
	 *
	 * USAGE: Used to upgrade the app's data.
	 *
	 * @return void
	 */
	public static function syncJobseekerRoles(): void
	{
		// Ensure jobseeker role and permissions exist
		$role = Permission::ensureJobseekerRoleAndPermissionsExist();
		if (empty($role) || empty($role->name)) return;
		
		if (Permission::doAllJobseekersHaveCorrectRole()) return;
		
		// Auto define default jobseeker(s)
		try {
			// Temporarily disable the lazy loading prevention
			preventLazyLoadingForModelRelations(false);
			
			// Assign the jobseeker role to the users with "user_type_id == UserType::JOBSEEKER->value"
			if (Schema::hasColumn((new User)->getTable(), 'user_type_id')) {
				$jobseekers = User::query()->where('user_type_id', UserType::JOBSEEKER->value)->get();
				if ($jobseekers->count() > 0) {
					foreach ($jobseekers as $jobSeeker) {
						$jobSeeker->removeRole($role->name);
						$jobSeeker->assignRole($role->name);
					}
				}
			}
			
			// Re-enable the lazy loading prevention if needed
			preventLazyLoadingForModelRelations();
		} catch (\Throwable $e) {
		}
	}
	
	/**
	 * Check if all jobseekers have their corresponding role properly assigned.
	 *
	 * Verifies that every user with user_type_id = UserType::JOBSEEKER->value has the correct
	 * jobseeker role assigned. Returns true if all jobseekers have consistent
	 * role assignments, false otherwise or on error.
	 *
	 * @return bool
	 */
	public static function doAllJobseekersHaveCorrectRole(): bool
	{
		try {
			$jobseekers = User::query()->where('user_type_id', UserType::JOBSEEKER->value);
			$jobseekersWithRole = $jobseekers->clone()->role(Role::getJobseekerRole());
			
			return ($jobseekers->count() === $jobseekersWithRole->count());
		} catch (\Throwable $e) {
			return false;
		}
	}
}
