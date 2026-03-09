{{--
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
--}}
@extends('front.layouts.master')

@php
	$apiResult ??= [];
	$apiExtra ??= [];
	$count = (array)data_get($apiExtra, 'count');
	$posts = (array)data_get($apiResult, 'data');
	$totalPosts = (int)data_get($apiResult, 'meta.total', 0);
	$tags = (array)data_get($apiExtra, 'tags');
	
	$postTypes ??= [];
	$orderByOptions ??= [];
	$displayModes ??= [];
	
	$authUser = auth()->check() ? auth()->user() : null;
	$isJobseekerUser = doesUserHaveJobseekerPermission($authUser);
	
	$isLeftSidebarEnabled = (config('settings.listings_list.show_left_sidebar') == '1');
	$breakpointKey = config('settings.listings_list.left_sidebar_offcanvas', 'sm');
	$pageBreakpoint = getSerpOffcanvasBreakpoint($breakpointKey, $isLeftSidebarEnabled);
	
	$hideOnXsOrLower = 'd-none d-sm-block';
@endphp

@section('search')
	@parent
	@include('front.search.partials.form')
@endsection

@section('content')
	<div class="main-container">
		
		@include('front.search.partials.breadcrumbs')
		
		@if (config('settings.listings_list.show_cats_in_top'))
			@if (!empty($cats))
				<div class="container mb-2 {{ $hideOnXsOrLower }}">
					<div class="row p-0 m-0">
						<div class="col-12 p-0 m-0 border-top"></div>
					</div>
				</div>
			@endif
			@include('front.search.partials.categories')
		@endif
		
		@if (!empty($topAdvertising))
			@include('front.layouts.partials.advertising.top', ['paddingTopExists' => true])
			@php
				$paddingTopExists = false;
			@endphp
		@else
			@php
				if (isset($paddingTopExists) && $paddingTopExists) {
					$paddingTopExists = false;
				}
			@endphp
		@endif
		
		
		<div class="container">
			<div class="row">
				
				{{-- Sidebar --}}
				@if ($isLeftSidebarEnabled)
					@include('front.search.partials.sidebar', ['pageBreakpoint' => $pageBreakpoint])
				@endif
				
				{{-- Content --}}
				@php
					$rightColSize = data_get($pageBreakpoint, 'rightColSize') ?? ($isLeftSidebarEnabled ? 'col-md-9' : 'col-md-12');
					$showInlineOnSmallScreen = data_get($pageBreakpoint, 'showInlineOnSmallScreen') ?? ' d-inline-block d-md-none';
				@endphp
				<div class="{{ $rightColSize }} mb-4">
					<div class="container bg-body border rounded">
						<div class="row">
							<div class="col-xl-12 p-3">
								<h5 class="mb-0 fs-5 fw-bold">
									{{ data_get($count, '0') }} {{ trans('global.Jobs Found') }}
								</h5>
							</div>
						</div>
						
						{{-- Breadcrumb --}}
						<div class="row py-3 border-top">
							<div class="col-12 d-flex align-items-center justify-content-between px-3">
								<h4 class="mb-0 fs-6 breadcrumb-list clearfix">
									{!! (isset($htmlTitle)) ? $htmlTitle : '' !!}
								</h4>
								
								@if (!empty(request()->all()))
									<div>
										<a class="{{ linkClass() }}" href="{!! urlGen()->searchWithoutQuery() !!}">
											<i class="bi bi-x-lg"></i> {{ trans('global.Clear all') }}
										</a>
									</div>
								@endif
							</div>
						</div>
						
						<div class="row">
							{{-- Filters, OrderBy & Display Mode --}}
							<div class="col-sm-12 px-1 py-2 bg-body-tertiary border-top border-bottom">
								<ul class="list-inline m-0 p-0 text-end">
									{{-- Filter (Show/Hide Sidebar) | d-inline-block d-sm-inline-block d-md-none --}}
									@if ($isLeftSidebarEnabled)
										<li class="list-inline-item px-2{{ $showInlineOnSmallScreen }}">
											<a href="#"
											   class="text-uppercase {{ linkClass() }} navbar-toggler"
											   data-bs-toggle="offcanvas"
											   data-bs-target="#smallScreenSidebar"
											   aria-controls="smallScreenSidebar"
											   aria-label="Toggle navigation"
											>
												<i class="fa-solid fa-bars"></i> {{ trans('global.Filters') }}
											</a>
										</li>
									@endif
									
									{{-- OrderBy --}}
									<li class="list-inline-item px-2">
										<div class="dropdown">
											<a href="#" class="dropdown-toggle text-uppercase {{ linkClass() }}" data-bs-toggle="dropdown" aria-expanded="false">
												{{ trans('global.Sort by') }}
											</a>
											<ul class="dropdown-menu dropdown-menu-end">
												@if (!empty($orderByOptions))
													@foreach($orderByOptions as $option)
														@if (data_get($option, 'condition'))
															@php
																$currentUrl = request()->fullUrl();
																$currentUrlWithoutOrderBy = urlBuilder($currentUrl)->removeParameter('orderBy')->toString();
																
																$optionQuery = (array)data_get($option, 'query');
																$optionUrl = urlBuilder($currentUrl)->setParameters($optionQuery)->toString();
																
																$optionParams = urlBuilder($optionUrl)->getAllParameters();
																$optionParams = collect($optionParams)->sortKeys()->toArray();
																$currentParams = urlBuilder(request()->fullUrl())->getAllParameters();
																$currentParams = collect($currentParams)->sortKeys()->toArray();
																
																$optionUrl = ($optionUrl == $currentUrlWithoutOrderBy) ? '#' : $optionUrl;
																$activeClass = ($optionParams == $currentParams) ? ' active' : '';
															@endphp
															<li>
																<a href="{!! $optionUrl !!}" class="dropdown-item{{ $activeClass }}" rel="nofollow">
																	{{ data_get($option, 'label') }}
																</a>
															</li>
														@endif
													@endforeach
												@endif
											</ul>
										</div>
									</li>
								</ul>
							</div>
						</div>
						
						<div class="row jobs-list">
							<div class="col-12">
								<div class="container">
									@include('front.search.partials.posts.template.list')
								</div>
							</div>
						</div>
					</div>
					
					@php
						$keyword = request()->query('q');
						$searchCanBeSaved = (!empty($keyword) && data_get($count, '0') > 0 && $isJobseekerUser);
					@endphp
					@if ($searchCanBeSaved)
						<div class="container border-bottom py-2 mt-3 border rounded fs-5 fw-bold text-center">
							<a id="saveSearch"
							   href=""
							   data-search-url="{!! request()->fullUrlWithoutQuery(['_token', 'location']) !!}"
							   data-results-count="{{ data_get($count, '0') }}"
							   class="{{ linkClass() }}"
							>
								<i class="bi bi-bell"></i> {{ trans('global.Save Search') }}
							</a>
						</div>
					@endif
					
					@include('vendor.pagination.api.bootstrap-5')
					
				</div>
			</div>
		</div>
		
		{{-- Advertising --}}
		@include('front.layouts.partials.advertising.bottom')
		
		{{-- Promo Post Button --}}
		@include('front.search.partials.call-to-action')
		
		{{-- Category Description --}}
		@include('front.search.partials.category-description')
		
		{{-- Show Posts Tags --}}
		@include('front.search.partials.tags')
		
	</div>
	
	@includeWhen(!auth()->check(), 'auth.login.partials.modal')
@endsection

@section('modal_location')
	@parent
	@include('front.layouts.partials.modal.location')
@endsection

@section('after_scripts')
	<script>
		onDocumentReady((event) => {
			{{-- postType --}}
			const postTypeEls = document.querySelectorAll('#postType a');
			if (postTypeEls.length > 0) {
				postTypeEls.forEach((element) => {
					element.addEventListener('click', (event) => {
						event.preventDefault();
						
						const goToUrl = event.target.getAttribute('href');
						if (goToUrl) {
							redirect(goToUrl);
						}
					});
				});
			}
		});
	</script>
@endsection
