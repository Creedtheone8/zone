<script>
	// Data from Laravel Blade - mapping between user types and roles
	let rolesByUserType = '{!! $rolesByUserType !!}';
	let userTypesByRole = '{!! $userTypesByRole !!}';
	
	// Parse the JSON data from Laravel
	rolesByUserType = JSON.parse(rolesByUserType);
	userTypesByRole = JSON.parse(userTypesByRole);
	
	// Extract all role IDs that are associated with user types
	const userTypeRoles = Object.values(rolesByUserType);
	
	// Convert role IDs to integers and filter out invalid values
	// This ensures we're working with numeric role IDs for comparison
	const parsedUserTypeRoles = userTypeRoles.map(item => {
		const parsed = parseInt(item, 10);
		return isNaN(parsed) ? null : parsed;
	});
	
	// DOM selectors for the form elements
	const userTypeIdElSelector = 'select[name="user_type_id"].select2_from_array';
	const rolesElsSelector = 'input[type="checkbox"][name="roles_show[]"]';
	const permissionsElsSelector = 'input[type="checkbox"][name="permissions_show[]"]';
	
	// Flag to prevent circular event triggering
	let isUpdating = false; // isProgrammatic
	
	onDocumentReady((event) => {
		// Set up user type dropdown change handler
		const userTypeIdEl = document.querySelector(userTypeIdElSelector);
		if (userTypeIdEl) {
			$(userTypeIdEl).on("change", e => handleUserTypeChange(e.target));
		}
		
		// Set up role checkbox change handlers
		const rolesEls = document.querySelectorAll(rolesElsSelector);
		if (rolesEls.length <= 0) {
			return;
		}
		
		// Exit early if no user type roles are configured
		if (userTypeRoles.length <= 0) {
			return;
		}
		
		// Attach change event listeners to all role checkboxes
		rolesEls.forEach((roleEl) => {
			roleEl.addEventListener("change", e => handleRoleChange(e.target));
		});
	});
	
	/**
	 * When user type dropdown changes, update the role checkboxes
	 */
	function handleUserTypeChange(userTypeEl)
	{
		// Prevent circular updates
		if (isUpdating) return;
		
		if (!userTypeEl) return;
		
		const userTypeValue = userTypeEl.value;
		if (!userTypeValue) return;
		
		// Set flag to prevent circular event triggering
		isUpdating = true;
		
		// Uncheck all role checkboxes first
		uncheckAllRoles();
		
		// Check the role for this user type
		const roleForUserType = rolesByUserType[userTypeValue] ?? null;
		if (roleForUserType) {
			checkRole(roleForUserType);
		}
		
		// Clear the flag after updates are complete
		isUpdating = false;
	}
	
	/**
	 * When role checkbox changes, update other roles and user type dropdown
	 */
	function handleRoleChange(roleEl)
	{
		// Prevent circular updates
		if (isUpdating) return;
		
		// Get role value, fallback to data-id attribute if value is empty
		const roleValue = roleEl.value ?? roleEl.getAttribute('data-id');
		
		// Store the checkbox state before any DOM manipulations to prevent interference
		const isChecked = roleEl.checked;
		
		// Validate this is a role we handle
		// Validate that this role is configured in our user type system
		const parsedRoleValue = parseInt(roleValue, 10);
		if (!parsedUserTypeRoles.includes(parsedRoleValue)) {
			return;
		}
		
		// If checking this role, uncheck all others (radio button behavior)
		if (isChecked) {
			uncheckAllRolesExcept(roleValue);
		}
		
		// Update user type dropdown
		updateUserTypeDropdown(roleValue, isChecked);
		
		// Run any additional actions needed when role changes
		runAdditionalRoleActions(roleEl);
		
		// Clear the flag after updates are complete
		isUpdating = false;
	}
	
	/**
	 * Uncheck all role checkboxes
	 */
	function uncheckAllRoles()
	{
		userTypeRoles.forEach((roleId) => {
			const roleEl = document.querySelector(`${rolesElsSelector}[value="${roleId}"]`);
			if (roleEl) {
				roleEl.checked = false;
				roleEl.dispatchEvent(new Event("change"));
			}
		});
	}
	
	/**
	 * Uncheck all roles except the specified one
	 */
	function uncheckAllRolesExcept(exceptRoleValue)
	{
		userTypeRoles.forEach((roleId) => {
			if (roleId !== exceptRoleValue) {
				const roleEl = document.querySelector(`${rolesElsSelector}[value="${roleId}"]`);
				if (roleEl) {
					roleEl.checked = false;
				}
			}
		});
	}
	
	/**
	 * Check a specific role checkbox and trigger its additional actions
	 */
	function checkRole(roleValue)
	{
		const roleEl = document.querySelector(`${rolesElsSelector}[value="${roleValue}"]`);
		if (roleEl) {
			roleEl.checked = true;
			roleEl.dispatchEvent(new Event("change"));
			
			// Run additional actions for this role
			// runAdditionalRoleActions(roleEl);
		}
	}
	
	/**
	 * Update the user type dropdown based on role selection
	 */
	function updateUserTypeDropdown(roleValue, isChecked)
	{
		const userTypeEl = document.querySelector(userTypeIdElSelector);
		if (!userTypeEl) return;
		
		// Set user type value based on role
		const userTypeId = userTypesByRole[roleValue] || '';
		userTypeEl.value = isChecked ? userTypeId : '';
		
		/*
		 * Trigger change event for select2 to update the UI
		 * This ensures the select2 dropdown reflects the programmatic value change
		 * https://stackoverflow.com/a/36084475
		 */
		userTypeEl.dispatchEvent(new Event("change"));
	}
	
	/**
	 * Run any additional actions when a role changes
	 * Put all your extra logic here - easy to find and modify
	 */
	function runAdditionalRoleActions(roleEl)
	{
		// Example: Reset permissions
		/*
		 const permissionsEls = document.querySelectorAll(permissionsElsSelector);
		 permissionsEls.forEach((permissionEl) => {
		 permissionEl.checked = false;
		 permissionEl.disabled = false;
		 });
		 */
		
		// Example: Log the change
		// console.log('Role changed:', roleEl.value, 'checked:', roleEl.checked);
		
		// Add any other logic you need here.
		// This is where you put code that needs to run every time a role changes
	}
</script>
