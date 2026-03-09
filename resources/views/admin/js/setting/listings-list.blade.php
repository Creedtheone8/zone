<script>
	onDocumentReady((event) => {
		let showLeftSidebarEl = document.querySelector("input[type=checkbox][name=show_left_sidebar]");
		if (showLeftSidebarEl) {
			toggleLeftSidebarFields(showLeftSidebarEl);
			showLeftSidebarEl.addEventListener("change", e => toggleLeftSidebarFields(e.target));
		}
		
		let hideDateEl = document.querySelector("input[type=checkbox][name=hide_date]");
		if (hideDateEl) {
			toggleDateFields(hideDateEl);
			hideDateEl.addEventListener("change", e => toggleDateFields(e.target));
		}
		
		let extendedSearchesEl = document.querySelector("input[type=checkbox][name=cities_extended_searches]");
		if (extendedSearchesEl) {
			toggleExtendedSearchesFields(extendedSearchesEl);
			extendedSearchesEl.addEventListener("change", e => toggleExtendedSearchesFields(e.target));
		}
	});
	
	function toggleLeftSidebarFields(showLeftSidebarEl) {
		let action = showLeftSidebarEl.checked ? "show" : "hide";
		setElementsVisibility(action, ".show-search-sidebar");
	}
	
	function toggleDateFields(hideDateEl) {
		let action = !hideDateEl.checked ? "show" : "hide";
		setElementsVisibility(action, ".date-field");
	}
	
	function toggleExtendedSearchesFields(extendedSearchesEl) {
		let action = extendedSearchesEl.checked ? "show" : "hide";
		setElementsVisibility(action, ".extended-searches");
	}
</script>
