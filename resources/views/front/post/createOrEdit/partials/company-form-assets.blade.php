@php
	$companyInput ??= [];
	$postInput ??= [];
	$post ??= [];
	$admin ??= [];
	
	$postId = data_get($post, 'id') ?? '';
	$postTypeId = data_get($postInput, 'post_type_id', 0);
	$postTypeId = data_get($post, 'post_type_id', $postTypeId);
	
	$countryCode = data_get($postInput, 'country_code', config('country.code', 0));
	$countryCode = data_get($post, 'country_code', $countryCode);
@endphp
@section('modal_location')
	@include('front.layouts.partials.modal.location')
@endsection

@push('before_helpers_scripts_stack')
	<script>
		/* Company */
		var selectedCompanyId = '{{ old('company_id', ($selectedCompanyId ?? 0)) }}';
		
		onDocumentReady((event) => {
			/* Company */
			getCompany(selectedCompanyId);
			const companyIdEl = document.getElementById('companyId');
			if (companyIdEl) {
				$(companyIdEl).on('click', (e) => getCompany(e.target.value));
				$(companyIdEl).on('change', (e) => getCompany(e.target.value));
			}
			
			/* Company logo's button */
			const companyFormLinkEl = document.getElementById('companyFormLink');
			if (companyFormLinkEl) {
				companyFormLinkEl.addEventListener('click', (e) => {
					let companyLink = e.target.getAttribute('href');
					if (companyLink.indexOf('/new/') !== -1) {
						e.preventDefault();
						getCompany(0);
						
						return false;
					}
				});
			}
		});
	</script>
@endpush
