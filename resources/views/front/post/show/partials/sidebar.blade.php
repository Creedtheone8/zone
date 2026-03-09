@php
	$authUser = auth()->check() ? auth()->user() : null;
	$authUserId = !empty($authUser) ? $authUser->getAuthIdentifier() : 0;
	$isJobseekerUser = doesUserHaveJobseekerPermission($authUser);
	
	$post ??= [];
	$user ??= [];
	$countPackages ??= 0;
	$countPaymentMethods ??= 0;

	$isPostOwner = (!empty($authUserId) && $authUserId == data_get($post, 'user_id'));
	
	// Google Maps
	$isMapEnabled = (config('settings.listing_page.show_listing_on_googlemap') == '1');
	$useGeocodingApi = (config('settings.other.google_maps_integration_type') == 'geocoding');
	$mapsJavascriptApiKey = config('services.google_maps_platform.maps_javascript_api_key');
	$mapsEmbedApiKey = config('services.google_maps_platform.maps_embed_api_key');
	$geocodingApiKey = config('services.google_maps_platform.geocoding_api_key');
	$useAsyncGeocoding = (config('settings.other.use_async_geocoding') == '1');
	
	$mapsEmbedApiKey ??= $mapsJavascriptApiKey;
	$geocodingApiKey ??= $mapsJavascriptApiKey;
	$geocodingApiKey = $useAsyncGeocoding ? $geocodingApiKey : $mapsJavascriptApiKey;
	
	$mapHeight = 250;
	$city = data_get($post, 'city', []);
	$geoMapAddress = getItemAddressForMap($city);
	
	$mapsEmbedApiUrl = getGoogleMapsEmbedApiUrl($mapsEmbedApiKey, $geoMapAddress);
	$geocodingApiUrl = getGoogleMapsApiUrl($geocodingApiKey, $useAsyncGeocoding);
	
	$linkClass = linkClass();
@endphp
<aside class="vstack gap-md-4 gap-3">
	<div class="card">
		<div class="card-header fw-bold">
			{{ trans('global.company_information') }}
		</div>
		<div class="card-body text-center">
			<div class="container p-0 border-bottom pb-3 mb-3">
				<div class="mb-2">
					@if (!empty(data_get($post, 'company')))
						<a href="{{ urlGen()->company(data_get($post, 'company.id')) }}">
							<img
									src="{{ data_get($post, 'logo_url.medium') }}"
									class="img-fluid img-thumbnail"
									alt="Logo {{ data_get($post, 'company_name') }}"
							>
						</a>
					@else
						<img
								src="{{ data_get($post, 'logo_url.medium') }}"
								class="img-fluid img-thumbnail"
								alt="Logo {{ data_get($post, 'company_name') }}"
						>
					@endif
				</div>
				@if (!empty(data_get($post, 'company')))
					<h4 class="m-0 fs-4">
						<a href="{{ urlGen()->company(data_get($post, 'company.id')) }}" class="{{ $linkClass }}">
							{{ data_get($post, 'company.name') }}
						</a>
					</h4>
				@else
					<h4 class="m-0 fs-5 fw-bold">{{ data_get($post, 'company_name') }}</h4>
				@endif
				<p class="small text-secondary mt-2">
					{{ trans('global.location') }}:&nbsp;
					<strong>
						<a href="{!! urlGen()->city(data_get($post, 'city')) !!}" class="{{ $linkClass }}">
							{{ data_get($post, 'city.name') }}
						</a>
					</strong>
				</p>
				@if (!config('settings.listing_page.hide_date'))
					@if (!empty($user) && !empty(data_get($user, 'created_at_formatted')))
						<p>{{ trans('global.Joined') }}: <strong>{!! data_get($user, 'created_at_formatted') !!}</strong></p>
					@endif
				@endif
				@if (!empty(data_get($post, 'company')))
					@if (!empty(data_get($post, 'company.website')))
						<p class="small text-secondary">
							{{ trans('global.Web') }}:
							<strong>
								<a href="{{ data_get($post, 'company.website') }}"
								   class="{{ $linkClass }}"
								   target="_blank"
								   rel="nofollow"
								>
									{{ getUrlHost(data_get($post, 'company.website')) }}
								</a>
							</strong>
						</p>
					@endif
				@endif
			</div>
			
			{{-- Author Additional Info (for Guests & Non-Owner Users) --}}
			@php
				$evActionClass = 'border-top-0';
			@endphp
			<div class="container p-0 {{ $evActionClass }} d-grid gap-2">
				@if (!empty($authUser))
					@if ($isPostOwner)
						<a href="{{ urlGen()->editPost($post) }}" class="btn btn-primary">
							<i class="fa-regular fa-pen-to-square"></i> {{ trans('global.Update the details') }}
						</a>
						@if (isMultipleStepsFormEnabled())
							@if ($countPackages > 0 && $countPaymentMethods > 0)
								<a href="{{ url('posts/' . data_get($post, 'id') . '/payment') }}" class="btn btn-success">
									<i class="fa-regular fa-circle-check"></i> {{ trans('global.Make It Premium') }}
								</a>
							@endif
						@endif
						@if (empty(data_get($post, 'archived_at')) && isVerifiedPost($post))
							<a href="{{ url(urlGen()->getAccountBasePath() . '/posts/list/' . data_get($post, 'id') . '/offline') }}"
							   class="btn btn-warning confirm-simple-action"
							>
								<i class="fa-solid fa-eye-slash"></i> {{ trans('global.put_it_offline') }}
							</a>
						@endif
						@if (!empty(data_get($post, 'archived_at')))
							<a href="{{ url(urlGen()->getAccountBasePath() . '/posts/archived/' . data_get($post, 'id') . '/repost') }}"
							   class="btn btn-info confirm-simple-action"
							>
								<i class="fa-solid fa-recycle"></i> {{ trans('global.re_post_it') }}
							</a>
						@endif
					@else
						@if ($isJobseekerUser)
							{!! genEmailContactBtn($post, true) !!}
						@endif
						{!! genPhoneNumberBtn($post, true) !!}
					@endif
					@php
						try {
							if (doesUserHavePermission($authUser, \App\Models\Permission::getStaffPermissions())) {
								$btnUrl = urlGen()->adminUrl('blacklists/add') . '?';
								$btnQs = (!empty(data_get($post, 'email'))) ? 'email=' . data_get($post, 'email') : '';
								$btnQs = (!empty($btnQs)) ? $btnQs . '&' : $btnQs;
								$btnQs = (!empty(data_get($post, 'phone'))) ? $btnQs . 'phone=' . data_get($post, 'phone') : $btnQs;
								$btnUrl = $btnUrl . $btnQs;
								
								if (!isDemoDomain($btnUrl)) {
									$btnText = trans('admin.ban_the_user');
									$btnHint = $btnText;
									if (!empty(data_get($post, 'email')) && !empty(data_get($post, 'phone'))) {
										$btnHint = trans('admin.ban_the_user_email_and_phone', [
											'email' => data_get($post, 'email'),
											'phone' => data_get($post, 'phone'),
										]);
									} else {
										if (!empty(data_get($post, 'email'))) {
											$btnHint = trans('admin.ban_the_user_email', ['email' => data_get($post, 'email')]);
										}
										if (!empty(data_get($post, 'phone'))) {
											$btnHint = trans('admin.ban_the_user_phone', ['phone' => data_get($post, 'phone')]);
										}
									}
									$tooltip = ' data-bs-toggle="tooltip" data-bs-placement="bottom" title="' . $btnHint . '"';
									
									$btnOut = '<a href="'. $btnUrl .'" class="btn btn-outline-danger confirm-simple-action"'. $tooltip .'>';
									$btnOut .= $btnText;
									$btnOut .= '</a>';
									
									echo $btnOut;
								}
							}
						} catch (\Throwable $e) {}
					@endphp
				@else
					{!! genEmailContactBtn($post, true) !!}
					{!! genPhoneNumberBtn($post, true) !!}
				@endif
			</div>
		</div>
	</div>
	
	{{-- Google Maps --}}
	@if ($isMapEnabled)
		<div class="card">
			<div class="card-header fw-bold">
				{{ trans('global.location_map') }}
			</div>
			<div class="card-body text-start p-0">
				<div class="posts-googlemaps">
					@if ($useGeocodingApi)
						<div id="googleMaps" style="width: 100%; height: {{ $mapHeight }}px;"></div>
					@else
						<iframe id="googleMaps"
						        width="100%"
						        height="{{ $mapHeight }}"
						        src="{{ $mapsEmbedApiUrl }}"
						        loading="lazy"
						        style="border:0;"
						        allowfullscreen
						></iframe>
					@endif
				</div>
			</div>
		</div>
	@endif
	
	{{-- Social Media Sharing --}}
	@if (isVerifiedPost($post))
		@include('front.layouts.partials.social.horizontal')
	@endif
	
	{{-- Tips for candidates --}}
	@php
		$tips = [
			trans('global.Check if the offer matches your profile'),
			trans('global.Check the start date'),
			trans('global.Meet the employer in a professional location'),
		];
	@endphp
	<div class="card">
		<div class="card-header fw-bold">
			{{ trans('global.Tips for candidates') }}
		</div>
		<div class="card-body text-start">
			<ul class="list-unstyled">
				@foreach($tips as $tip)
					<li><i class="bi bi-check-lg"></i> {{ $tip }}</li>
				@endforeach
			</ul>
			@php
				$tipsLinkAttributes = getUrlPageByType('tips');
			@endphp
			@if (!str_contains($tipsLinkAttributes, 'href="#"') && !str_contains($tipsLinkAttributes, 'href=""'))
				<p>
					<a class="float-end {{ linkClass() }}" {!! $tipsLinkAttributes !!}>
						{{ trans('global.Know more') }} <i class="fa-solid fa-angles-right"></i>
					</a>
				</p>
			@endif
		</div>
	</div>
</aside>

@section('after_scripts')
	@parent
	@if ($isMapEnabled)
		@if ($useGeocodingApi)
			{{-- Google Geocoding API script --}}
			@if (!empty($geocodingApiUrl))
				<script async defer src="{{ $geocodingApiUrl }}"></script>
			@endif
			
			{{-- JS code to append the map --}}
			<script>
				var geocodingApiKey = '{{ $geocodingApiKey }}';
				var locationAddress = '{{ $geoMapAddress }}';
				var locationMapElId = 'googleMaps';
				var locationMapId = '{{ generateUniqueCode(16) }}';
			</script>
			@if ($useAsyncGeocoding)
				<script src="{{ url('assets/js/app/google-maps-async.js') }}"></script>
			@else
				<script src="{{ url('assets/js/app/google-maps.js') }}"></script>
			@endif
		@endif
	@endif
@endsection
