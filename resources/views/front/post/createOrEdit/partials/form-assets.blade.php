@php
	use App\Helpers\Common\Date;
	
	$companyInput ??= [];
	$postInput ??= [];
	$post ??= [];
	$admin ??= [];
	
	$postId = data_get($post, 'id') ?? '';
	$postTypeId = data_get($postInput, 'post_type_id', 0);
	$postTypeId = data_get($post, 'post_type_id', $postTypeId);
	
	$countryCode = data_get($postInput, 'country_code', config('country.code', 0));
	$countryCode = data_get($post, 'country_code', $countryCode);
	
	$adminType = config('country.admin_type', 0);
	
	$selectedAdminCode = data_get($postInput, 'admin_code', 0);
	$selectedAdminCode = data_get($admin, 'code', $selectedAdminCode);
	
	$cityId = data_get($postInput, 'city_id');
	$cityId = data_get($post, 'city_id', $cityId);
@endphp
@section('modal_location')
	@include('front.layouts.partials.modal.location')
@endsection

@push('before_helpers_scripts_stack')
	@include('front.common.js.payment-scripts')
	
	<script>
		/* Translation */
		var lang = {
			'select': {
				'country': "{{ trans('global.select_a_country') }}",
				'admin': "{{ trans('global.select_a_location') }}",
				'city': "{{ trans('global.select_a_city') }}"
			},
			'price': "{{ trans('global.Price') }}",
			'salary': "{{ trans('global.Salary') }}",
			'nextStepBtnLabel': {
				'next': "{{ trans('global.Next') }}",
				'submit': "{{ trans('global.Update') }}"
			}
		};
		
		var stepParam = 0;
		
		/* Category */
		var categoryWasSelected = false;
		@if ($errors->isNotEmpty() || !empty($postId))
			categoryWasSelected = true;
		@endif
		
		/* Locations */
		var countryCode = '{{ old('country_code', $countryCode) }}';
		var adminType = '{{ $adminType }}';
		var selectedAdminCode = '{{ old('admin_code', $selectedAdminCode) }}';
		var cityId = '{{ old('city_id', $cityId) }}';
		
		/* Packages */
		var packageIsEnabled = false;
		@if (isset($packages, $paymentMethods) && $packages->count() > 0 && $paymentMethods->count() > 0)
			packageIsEnabled = true;
		@endif
	</script>
	
	<script src="{{ url('assets/js/app/d.modal.category.js') . vTime() }}"></script>
	@if (config('settings.listing_form.city_selection') == 'select')
		<script src="{{ url('assets/js/app/d.select.location.js') . vTime() }}"></script>
	@else
		<script src="{{ url('assets/js/app/browse.locations.js') . vTime() }}"></script>
		<script src="{{ url('assets/js/app/d.modal.location.js') . vTime() }}"></script>
	@endif
@endpush
