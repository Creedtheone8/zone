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

trait HasRecruiterPermissions
{
	/**
	 * Default recruiters permissions
	 *
	 * @return array<int, string>
	 */
	public static function getRecruiterPermissions(): array
	{
		return [
			'companies.view',
			'companies.create',
			'companies.edit',
			'companies.delete',
			
			'posts.view',
			'posts.create',
			'posts.edit',
			'posts.delete',
		];
	}
	
	/**
	 * Get recruiters permissions (from DB)
	 *
	 * @return array
	 */
	public static function getRecruiterPermissionsFromDb(): array
	{
		$permissionList = [];
		$permissions = collect();
		try {
			$permissionList = Permission::getRecruiterPermissions();
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
	 * Check recruiter permissions
	 * NOTE: Must use try {...} catch {...}
	 *
	 * @return bool
	 */
	public static function checkRecruiterPermissions(): bool
	{
		$permissions = Permission::getRecruiterPermissionsFromDb();
		
		return !empty($permissions);
	}
	
	/**
	 * Check recruiter role & permissions
	 *
	 * @param \App\Models\Role|null $role
	 * @return bool
	 */
	public static function checkRecruiterRoleAndPermissions(?Role $role = null): bool
	{
		$doesRoleExist = !empty($role);
		$doesRoleExist = $doesRoleExist || Role::checkRecruiterRole();
		
		if (!$doesRoleExist || !Permission::checkRecruiterPermissions()) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Reset recruiter permissions
	 * NOTE: Must use try {...} catch {...}
	 *
	 * @param \App\Models\Role|null $role
	 * @return void
	 */
	public static function resetRecruiterPermissions(?Role $role = null): void
	{
		try {
			// Create the default recruiter role
			$role = empty($role) ? Role::ensureRecruiterRoleExists() : $role;
			if (empty($role)) return;
			
			$permissionList = Permission::getRecruiterPermissions();
			
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
			
			// Create default recruiter permissions
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
	 * Ensure recruiter role and permissions exist
	 * NOTE: Return the Role object
	 *
	 * @return \App\Models\Role|null
	 */
	public static function ensureRecruiterRoleAndPermissionsExist(): ?Role
	{
		$role = empty($role) ? Role::ensureRecruiterRoleExists() : $role;
		if (!Permission::checkRecruiterRoleAndPermissions($role)) {
			Permission::resetRecruiterPermissions($role);
		}
		
		return $role;
	}
	
	/**
	 * Synchronize recruiter role assignments to ensure data consistency.
	 *
	 * Repairs role inconsistencies by assigning the correct recruiter role to all users
	 * with user_type_id = UserType::RECRUITER->value. First resets default permissions if needed, then checks
	 * for consistency. If issues are found, removes existing roles and reassigns the
	 * proper recruiter role to each recruiter user.
	 *
	 * NOTE: Must use try {...} catch {...}
	 *
	 * USAGE: Used to upgrade the app's data.
	 *
	 * @return void
	 */
	public static function syncRecruiterRoles(): void
	{
		// Ensure recruiter role and permissions exist
		$role = Permission::ensureRecruiterRoleAndPermissionsExist();
		if (empty($role) || empty($role->name)) return;
		
		if (Permission::doAllRecruitersHaveCorrectRole()) return;
		
		// Auto define default recruiter(s)
		try {
			// Temporarily disable the lazy loading prevention
			preventLazyLoadingForModelRelations(false);
			
			// Assign the recruiter role to the users with "user_type_id == UserType::RECRUITER->value"
			if (Schema::hasColumn((new User)->getTable(), 'user_type_id')) {
				$recruiters = User::query()->where('user_type_id', UserType::RECRUITER->value)->get();
				if ($recruiters->count() > 0) {
					foreach ($recruiters as $recruiter) {
						$recruiter->removeRole($role->name);
						$recruiter->assignRole($role->name);
					}
				}
			}
			
			// Re-enable the lazy loading prevention if needed
			preventLazyLoadingForModelRelations();
		} catch (\Throwable $e) {
		}
	}
	
	/**
	 * Check if all recruiters have their corresponding role properly assigned.
	 *
	 * Verifies that every user with user_type_id = UserType::RECRUITER->value has the correct
	 * recruiter role assigned. Returns true if all recruiters have consistent
	 * role assignments, false otherwise or on error.
	 *
	 * @return bool
	 */
	public static function doAllRecruitersHaveCorrectRole(): bool
	{
		try {
			$recruiters = User::query()->where('user_type_id', UserType::RECRUITER->value);
			$recruitersWithRole = $recruiters->clone()->role(Role::getRecruiterRole());
			
			return ($recruiters->count() === $recruitersWithRole->count());
		} catch (\Throwable $e) {
			return false;
		}
	}
}
