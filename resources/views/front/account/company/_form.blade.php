@section('modal_location')
	@include('front.layouts.partials.modal.location')
@endsection

@php
	$companyInput ??= [];
	$company ??= [];
@endphp

<div class="col-12" id="companyFields">
	<div class="row">
		{{-- name --}}
		@php
			$companyName = data_get($company, 'name');
			$companyName = data_get($companyInput, 'company.name', $companyName);
		@endphp
		@include('helpers.forms.fields.text', [
			'label'       => trans('global.company_name'),
			'id'          => 'companyName',
			'name'        => 'company[name]',
			'placeholder' => trans('global.company_name'),
			'required'    => true,
			'value'       => $companyName,
		])
		
		{{-- logo_path --}}
		@php
			$companyLogoPath = data_get($company, 'logo_path');
			$companyLogoPath = data_get($companyInput, 'company.logo_path', $companyLogoPath);
			
			$logoData = [
				'key'  => 0,
				'path' => $companyLogoPath,
				'url'  => thumbService($companyLogoPath)->resize('picture-md')->url(),
			];
		@endphp
		@include('helpers.forms.fields.fileinput', [
			'label' => trans('global.Logo'),
			'id'    => 'logoPath',
			'name'  => 'company[logo_path]',
			'value' => $logoData,
			'pluginOptions' => [
				'previewFileType' => 'image',
				'showPreview'     => 'true',
				'dropZoneEnabled' => 'false',
				'showUpload'      => 'false',
				'showRemove'      => 'false',
			],
			'hint' => trans('global.file_types', ['file_types' => getAllowedFileFormatsHint('image')]),
		])
		
		{{-- description --}}
		@php
			$companyDescription = data_get($company, 'description');
			$companyDescription = data_get($companyInput, 'company.description', $companyDescription);
			
			$coDescHint = trans('global.Describe the company');
			$coDescHint .= ' - (' . trans('global.N characters maximum', ['number' => 1000]) . ')';
		@endphp
		@include('helpers.forms.fields.wysiwyg', [
			'label'       => trans('global.Company Description'),
			'id'          => 'companyDescription',
			'name'        => 'company[description]',
			'placeholder' => trans('global.Company Description'),
			'required'    => true,
			'value'       => $companyDescription,
			'height'      => 300,
			'attributes'    => ['rows' => 10],
			'hint'          => $coDescHint,
		])
		
		@if (!empty($company))
			{{-- country_code --}}
			@php
				$countries ??= [];
				$countryCodeOptions = collect($countries)
					->map(function($item) {
						return [
							'value'      => $item['code'] ?? null,
							'text'       => $item['name'] ?? null,
							'attributes' => ['data-admin-type' => $item['admin_type'] ?? 0],
						];
					})->toArray();
				
				$selectedCountryCode = data_get($company, 'country_code', config('country.code', 0));
			@endphp
			@include('helpers.forms.fields.select2', [
				'label'       => trans('global.country'),
				'id'          => 'countryCode',
				'name'        => 'company[country_code]',
				'required'    => false,
				'placeholder' => trans('global.select_a_country'),
				'options'     => $countryCodeOptions,
				'value'       => $selectedCountryCode,
				'hint'        => null,
			])
			
			@php
				$adminType = config('country.admin_type', 0);
			@endphp
			@if (config('settings.listing_form.city_selection') == 'select')
				@if (in_array($adminType, ['1', '2']))
					{{-- admin_code --}}
					@include('helpers.forms.fields.select2', [
						'label'        => trans('global.location'),
						'id'           => 'adminCode',
						'name'         => 'company[admin_code]',
						'required'     => true,
						'placeholder'  => trans('global.select_your_location'),
						'options'      => [],
						'largeOptions' => true,
						'hint'         => null,
						'wrapper'      => ['id' => 'locationBox'],
					])
				@endif
			@else
				@php
					$adminType = (in_array($adminType, ['0', '1', '2'])) ? $adminType : 0;
					$relAdminType = (in_array($adminType, ['1', '2'])) ? $adminType : 1;
					$adminCode = data_get($company, 'city.subadmin' . $relAdminType . '_code', 0);
					$adminCode = data_get($company, 'city.subAdmin' . $relAdminType . '.code', $adminCode);
					$adminName = data_get($company, 'city.subAdmin' . $relAdminType . '.name');
					$cityId = data_get($company, 'city.id', 0);
					$cityName = data_get($company, 'city.name');
					$fullCityName = !empty($adminName) ? $cityName . ', ' . $adminName : $cityName;
				@endphp
				<input type="hidden" id="selectedAdminType" name="selected_admin_type" value="{{ old('selected_admin_type', $adminType) }}">
				<input type="hidden" id="selectedAdminCode" name="selected_admin_code" value="{{ old('selected_admin_code', $adminCode) }}">
				<input type="hidden" id="selectedCityId" name="selected_city_id" value="{{ old('selected_city_id', $cityId) }}">
				<input type="hidden" id="selectedCityName" name="selected_city_name" value="{{ old('selected_city_name', $fullCityName) }}">
			@endif
			
			{{-- city_id --}}
			@include('helpers.forms.fields.select2', [
				'label'        => trans('global.city'),
				'id'           => 'cityId',
				'name'         => 'company[city_id]',
				'required'     => false,
				'placeholder'  => trans('global.select_a_city'),
				'options'      => [],
				'largeOptions' => true,
				'wrapper'      => ['id' => 'cityBox'],
			])
			
			{{-- address --}}
			@include('helpers.forms.fields.text', [
				'label'  => trans('global.Address'),
				'name'   => 'company[address]',
				'value'  => data_get($company, 'address'),
				'prefix' => '<i class="bi bi-geo-alt"></i>',
			])
			
			{{-- phone --}}
			@include('helpers.forms.fields.text', [
				'label'  => trans('global.phone'),
				'name'   => 'company[phone]',
				'value'  => data_get($company, 'phone'),
				'prefix' => '<i class="bi bi-telephone-inbound"></i>',
				'baseClass' => ['wrapper' => 'mb-3 col-lg-8 col-md-12'],
			])
			
			{{-- fax --}}
			@include('helpers.forms.fields.text', [
				'label'  => trans('global.Fax'),
				'name'   => 'company[fax]',
				'value'  => data_get($company, 'fax'),
				'prefix' => '<i class="bi bi-printer"></i>',
				'baseClass' => ['wrapper' => 'mb-3 col-lg-8 col-md-12'],
			])
			
			{{-- email --}}
			@include('helpers.forms.fields.email', [
				'label'  => trans('global.email'),
				'name'   => 'company[email]',
				'value'  => data_get($company, 'email'),
				'prefix' => '<i class="fa-regular fa-envelope"></i>',
				'baseClass' => ['wrapper' => 'mb-3 col-lg-8 col-md-12'],
			])
			
			{{-- website --}}
			@include('helpers.forms.fields.url', [
				'label'  => trans('global.Website'),
				'name'   => 'company[website]',
				'value'  => data_get($company, 'website'),
				'prefix' => '<i class="bi bi-globe"></i>',
			])
			
			{{-- facebook --}}
			@include('helpers.forms.fields.url', [
				'label'  => 'Facebook',
				'name'   => 'company[facebook]',
				'value'  => data_get($company, 'facebook'),
				'prefix' => '<i class="bi bi-facebook"></i>',
			])
			
			{{-- twitter --}}
			@include('helpers.forms.fields.url', [
				'label'  => 'Twitter',
				'name'   => 'company[twitter]',
				'value'  => data_get($company, 'twitter'),
				'prefix' => '<i class="bi bi-twitter-x"></i>',
			])
			
			{{-- linkedin --}}
			@include('helpers.forms.fields.url', [
				'label'  => 'Linkedin',
				'name'   => 'company[linkedin]',
				'value'  => data_get($company, 'linkedin'),
				'prefix' => '<i class="bi bi-linkedin"></i>',
			])
			
			{{-- pinterest --}}
			@include('helpers.forms.fields.url', [
				'label'  => 'Pinterest',
				'name'   => 'company[pinterest]',
				'value'  => data_get($company, 'pinterest'),
				'prefix' => '<i class="bi bi-pinterest"></i>',
			])
		@endif
	</div>
</div>

@section('after_scripts')
	@parent
	@if (!empty($company))
		@php
			$countryCode = data_get($company, 'country_code', 0);
			$adminType = config('country.admin_type', 0);
			$selectedAdminCode = data_get($company, 'city.subAdmin' . $adminType . '.code', 0);
			$selectedAdminCode = data_get($companyInput, 'admin_code', $selectedAdminCode);
			$cityId = (int)(data_get($company, 'city_id', 0));
		@endphp
		<script>
			/* Translation */
			var lang = {
				'select': {
					'country': "{{ trans('global.select_a_country') }}",
					'admin': "{{ trans('global.select_a_location') }}",
					'city': "{{ trans('global.select_a_city') }}"
				}
			};
	
			/* Locations */
			var countryCode = '{{ old('company.country_code', $countryCode) }}';
			var adminType = '{{ $adminType }}';
			var selectedAdminCode = '{{ old('company.admin_code', $selectedAdminCode) }}';
			var cityId = '{{ (int)old('company.city_id', $cityId) }}';
		</script>
		@if (config('settings.listing_form.city_selection') == 'select')
			<script src="{{ url('assets/js/app/d.select.location.js') . vTime() }}"></script>
		@else
			<script src="{{ url('assets/js/app/browse.locations.js') . vTime() }}"></script>
			<script src="{{ url('assets/js/app/d.modal.location.js') . vTime() }}"></script>
		@endif
	@endif
@endsection
