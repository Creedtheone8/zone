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

namespace App\Http\Controllers\Web\Admin\Traits\User;

use App\Enums\UserType;
use App\Http\Requests\Admin\Request;
use App\Models\Permission;
use App\Models\Role;

trait HasJobseeker
{
	/**
	 * Set jobseeker role when user type is jobseeker
	 *
	 * @param \App\Http\Requests\Admin\Request $request
	 * @return \App\Http\Requests\Admin\Request
	 */
	protected function handleJobseekerRolesFromUserTypeId(Request $request)
	{
		$isFilled = ($request->input('user_type_id') == UserType::JOBSEEKER->value);
		
		if ($isFilled) {
			$role = Role::getJobseekerRoleFromDb();
			
			if (!empty($role) && !empty($role->id)) {
				$rolesIds = $request->input('roles');
				$rolesIds = collect($rolesIds)
					->reject(function ($roleId) {
						$userTypeRoleNames = Role::getUserTypeIdRoles();
						$role = Role::find($roleId);
						
						return (!empty($role->name) && in_array($role->name, $userTypeRoleNames));
					})
					->add($role->id)
					->toArray();
				
				$request->request->set('roles', $rolesIds);
			}
		}
		
		return $request;
	}
	
	/**
	 * Set user type to jobseeker when jobseeker role is selected
	 *
	 * @param \App\Http\Requests\Admin\Request $request
	 * @return \App\Http\Requests\Admin\Request
	 */
	protected function handleJobseekerUserTypeIdFromRoles(Request $request)
	{
		$permissionList = Permission::getJobseekerPermissions();
		$permissionList = collect($permissionList)->sort()->toArray();
		
		$isFilled = false;
		
		if ($request->filled('roles')) {
			$rolesIds = $request->input('roles');
			foreach ($rolesIds as $roleId) {
				$role = Role::find($roleId);
				if (!empty($role)) {
					$permissions = collect($role->permissions)->keyBy('name')->keys()->sort()->toArray();
					if (array_values($permissions) === array_values($permissionList)) {
						$isFilled = true;
					}
				}
			}
		}
		
		if ($isFilled) {
			$request->request->set('user_type_id', UserType::JOBSEEKER->value);
		}
		
		return $request;
	}
	
	/**
	 * Set user type to jobseeker when jobseeker permissions are selected
	 *
	 * @param \App\Http\Requests\Admin\Request $request
	 * @return \App\Http\Requests\Admin\Request
	 */
	protected function handleJobseekerUserTypeIdFromPermissions(Request $request)
	{
		if ($request->filled('user_type_id')) {
			return $request;
		}
		
		$permissionList = Permission::getJobseekerPermissions();
		$permissionList = collect($permissionList)->sort()->toArray();
		
		$isFilled = false;
		
		if ($request->filled('permissions')) {
			$permissionIds = $request->input('permissions');
			$permissions = Permission::whereIn('id', $permissionIds)->pluck('name')->sort()->toArray();
			if (array_values($permissions) === array_values($permissionList)) {
				$isFilled = true;
			}
		}
		
		if ($isFilled) {
			$request->request->set('user_type_id', UserType::JOBSEEKER->value);
		}
		
		return $request;
	}
}
