@php
	use App\Enums\BootstrapColor;
	
	$posts ??= [];
	$totalPosts ??= 0;
	
	$city ??= null;
	$cat ??= null;
	
	$authUser = auth()->check() ? auth()->user() : null;
	$isJobseekerUser = doesUserHaveJobseekerPermission($authUser);
	
	$isFromSearchCompany ??= false;
@endphp
@if (!empty($posts) && $totalPosts > 0)
	<div class="container px-0 pt-3 list-view">
		@foreach($posts as $key => $post)
			@php
				// Get Package Info
				$premiumClass = '';
				$premiumBadge = '';
				$logoContainerPsClass = '';
				if (data_get($post, 'featured') == 1) {
					if (!empty(data_get($post, 'payment.package'))) {
						$ribbonColor = data_get($post, 'payment.package.ribbon');
						$ribbonColorClass = BootstrapColor::Badge->getColorClass($ribbonColor);
						$packageShortName = data_get($post, 'payment.package.short_name');
						
						$premiumClass = ' bg-warning-subtle py-3 rounded premium-post';
						$premiumBadge = ' <span class="badge rounded-pill ' . $ribbonColorClass . ' fs-6 fw-normal float-end">';
						$premiumBadge .= $packageShortName;
						$premiumBadge .= '</span>';
						$logoContainerPsClass = ' ps-3';
					}
				}
				
				$postId = data_get($post, 'id');
				$postUrl = urlGen()->post($post);
				$parentCatUrl = null;
				if (!empty(data_get($post, 'category.parent'))) {
					$parentCatUrl = urlGen()->category(data_get($post, 'category.parent'), null, $city);
				}
				$catUrl = urlGen()->category(data_get($post, 'category'), null, $city);
				$locationUrl = urlGen()->city(data_get($post, 'city'), null, $cat);
				
				$borderBottom = !$loop->last ? ' border-bottom pb-3' : '';
			@endphp
			
			<div class="row{{ $borderBottom }} mb-3 item-list">
				<div class="col-12">
					<div class="row{{ $premiumClass }}">
						{{-- Logo --}}
						<div class="col-sm-2 col-12 d-flex justify-content-center p-0 company-logo">
							<div class="container px-0{{ $logoContainerPsClass }} position-relative">
								<a href="{{ $postUrl }}">
									<img class="img-fluid img-thumbnail w-100 h-auto"
									     src="{{ data_get($post, 'logo_url.medium') }}"
									     alt="{{ data_get($post, 'company_name') }}"
									>
								</a>
							</div>
						</div>
						
						{{-- Details --}}
						<div class="col-sm-10 col-12">
							{{-- Title --}}
							<h4 class="fs-4 fw-bold">
								<a href="{{ $postUrl }}" class="{{ linkClass() }}">
									{{ str(data_get($post, 'title'))->limit(70) }}
								</a>{!! $premiumBadge !!}
							</h4>
							
							{{-- Company Name --}}
							<h5 class="fs-5 px-0">
								<i class="bi bi-building"></i>
								@if (!empty(data_get($post, 'company_id')))
									<a href="{{ urlGen()->company(data_get($post, 'company_id')) }}" class="{{ linkClass('body-emphasis') }}">
										{{ data_get($post, 'company_name') }}
									</a>
								@else
									{{ data_get($post, 'company_name') }}
								@endif
							</h5>
							
							@php
								$showPostInfo = (
									!config('settings.listings_list.hide_post_type')
									|| !config('settings.listings_list.hide_date')
									|| !config('settings.listings_list.hide_category')
									|| !config('settings.listings_list.hide_location')
									|| !config('settings.listings_list.hide_salary')
								);
							@endphp
							@if ($showPostInfo)
								<div class="container px-0 text-secondary">
									<ul class="list-inline mb-0">
										@if (!config('settings.listings_list.hide_date'))
											<li class="list-inline-item">
												<i class="fa-regular fa-clock"></i> {!! data_get($post, 'created_at_formatted') !!}
											</li>
										@endif
										@if (!config('settings.listings_list.hide_category'))
											<li class="list-inline-item">
												<i class="bi bi-folder"></i>&nbsp;
												@if (!empty(data_get($post, 'category.parent')))
													<a href="{!! urlGen()->category(data_get($post, 'category.parent'), null, $city ?? null) !!}"
													   class="{{ linkClass() }}"
													>
														{{ data_get($post, 'category.parent.name') }}
													</a>&nbsp;&raquo;&nbsp;
												@endif
												<a href="{!! urlGen()->category(data_get($post, 'category'), null, $city ?? null) !!}"
												   class="{{ linkClass() }}"
												>
													{{ data_get($post, 'category.name') }}
												</a>
											</li>
										@endif
										@if (!config('settings.listings_list.hide_location'))
											<li class="list-inline-item">
												<i class="bi bi-geo-alt"></i>&nbsp;
												<a href="{!! urlGen()->city(data_get($post, 'city'), null, $cat ?? null) !!}"
												   class="{{ linkClass() }}"
												>
													{{ data_get($post, 'city.name') }}
												</a> {{ data_get($post, 'distance_info') }}
											</li>
										@endif
										@if (!config('settings.listings_list.hide_post_type'))
											<li class="list-inline-item">
												<i class="bi bi-tag"></i> {{ data_get($post, 'postType.name') }}
											</li>
										@endif
										@if (!config('settings.listings_list.hide_salary'))
											<li class="list-inline-item">
												<i class="bi bi-cash-coin"></i>&nbsp;
												{!! data_get($post, 'salary_formatted') !!}
												@if (!empty(data_get($post, 'salaryType')))
													{{ trans('global.per') }} {{ data_get($post, 'salaryType.name') }}
												@endif
											</li>
										@endif
									</ul>
								</div>
							@endif
							
							@if (!config('settings.listings_list.hide_excerpt'))
								<div class="container px-0 pt-2">
									{!! str(multiLinesStringCleaner(data_get($post, 'description')))->limit(180) !!}
								</div>
							@endif
							
							<div class="container px-0 mt-2">
								<ul class="list-inline mb-0">
									@php
										$savedByLoggedUser = (bool)data_get($post, 'p_saved_by_logged_user');
									@endphp
									@if (!empty($authUser))
										@if ($isJobseekerUser)
											@if ($savedByLoggedUser)
												<li class="list-inline-item saved-job" id="{{ $postId }}">
													<a class="saved-job {{ linkClass() }}" id="saved-{{ $postId }}" href="">
														<span class="bi bi-heart-fill"></span> {{ trans('global.Saved Job') }}
													</a>
												</li>
											@else
												<li class="list-inline-item" id="{{ $postId }}">
													<a class="save-job {{ linkClass() }}" id="save-{{ $postId }}" href="">
														<span class="bi bi-heart"></span> {{ trans('global.Save Job') }}
													</a>
												</li>
											@endif
										@endif
									@else
										<li class="list-inline-item" id="{{ $postId }}">
											<a class="save-job {{ linkClass() }}" id="save-{{ $postId }}" href="">
												<span class="bi bi-heart"></span> {{ trans('global.Save Job') }}
											</a>
										</li>
									@endif
									<li class="list-inline-item">
										<a class="email-job {{ linkClass() }}"
										   data-bs-toggle="modal"
										   data-id="{{ $postId }}"
										   href="#sendByEmail"
										   id="email-{{ $postId }}"
										>
											<i class="fa-regular fa-envelope"></i> {{ trans('global.Email Job') }}
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>
@else
	<div class="py-5 text-center w-100">
		@if ($isFromSearchCompany)
			{{ trans('global.No jobs were found for this company') }}
		@else
			{{ trans('global.no_result_refine_your_search') }}
		@endif
	</div>
@endif

@section('modal_location')
	@parent
	@include('front.layouts.partials.modal.send-by-email')
@endsection

@section('after_scripts')
	@parent
	<script>
		/* Favorites Translation */
		var lang = {
			labelSavePostSave: "{!! trans('global.Save Job') !!}",
			labelSavePostRemove: "{{ trans('global.Saved Job') }}",
			loginToSavePost: "{!! trans('global.Please log in to save the Ads') !!}",
			loginToSaveSearch: "{!! trans('global.Please log in to save your search') !!}"
		};
		
		onDocumentReady((event) => {
			/* Get Post ID */
			const emailJobEls = document.querySelectorAll('.email-job');
			emailJobEls.forEach((element) => {
				element.addEventListener('click', (e) => {
					const hiddenPostIdEl = document.querySelector('input[type=hidden][name=post_id]');
					if (hiddenPostIdEl) {
						let clickedEl = (e.target.tagName.toLowerCase() === 'i')
							? e.target.parentElement
							: e.target;
						
						hiddenPostIdEl.value = clickedEl.dataset.id;
					}
				});
			});
			
			@if (isset($errors) && $errors->any())
			@if (old('sendByEmailForm')=='1')
			{{-- Re-open the modal if error occured --}}
			const sendByEmailEl = document.getElementById('sendByEmail');
			if (sendByEmailEl) {
				let sendByEmail = new bootstrap.Modal(sendByEmailEl, {});
				sendByEmail.show();
			}
			@endif
			@endif
		})
	</script>
@endsection
