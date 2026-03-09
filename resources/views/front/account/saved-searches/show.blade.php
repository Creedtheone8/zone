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
	$savedSearch ??= [];
	
	$apiMessage = $apiMessagePosts ?? null;
	$apiResult = $apiResultPosts ?? [];
	$posts = (array)data_get($apiResult, 'data');
	$totalPosts = (int)data_get($apiResult, 'meta.total');
	
	$apiExtraPosts ??= [];
	$query = (array)data_get($apiExtraPosts, 'preSearch.query');
@endphp

@section('content')
	@include('front.common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					@include('front.account.partials.sidebar')
				</div>
				
				<div class="col-md-9">
					<div class="container border rounded bg-body-tertiary p-4 p-lg-3 p-md-2 pb-0 pb-lg-0 pb-md-0 mb-3">
						<h2 class="fw-bold border-bottom pb-3 mb-4">
							<i class="bi bi-bell"></i> {{ trans('global.Saved search') }} #{{ data_get($savedSearch, 'id') }}
						</h2>
						
						<div class="row">
							<div class="col-12 mb-3 text-end">
								<i class="bi bi-arrow-90deg-left"></i> <a href="{{ url(urlGen()->getAccountBasePath() . '/saved-searches') }}" class="{{ linkClass() }}">
									{{ trans('global.saved_searches') }}
								</a>
							</div>
							<div class="col-12 mb-3">
								@php
									$searchLink = urlBuilder(urlGen()->search($query))
										->removeParameters(['page'])
										->toString();
								@endphp
								<span class="fs-6">
									<strong>{{ trans('global.search') }}:</strong> <a href="{{ $searchLink }}"
									                                       class="{{ linkClass() }}"
									                                       target="_blank"
									>{{ $searchLink }}</a>
								</span>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								@if (!empty($posts) && $totalPosts > 0)
									<div class="container px-0 pt-3 list-view">
										@foreach($posts as $key => $post)
											@php
												// Get Package Info
												$premiumClass = '';
												$premiumBadge = '';
												if (data_get($post, 'featured') == 1) {
													if (!empty(data_get($post, 'payment.package'))) {
														$premiumClass = ' bg-warning-subtle py-3 rounded premium-post';
														$premiumBadge = ' <span class="badge bg-dark float-end">' . data_get($post, 'payment.package.short_name') . '</span>';
													}
												}
												
												$postId = data_get($post, 'id');
												$postUrl = urlGen()->post($post);
												$parentCatUrl = null;
												if (!empty(data_get($post, 'category.parent'))) {
													$parentCatUrl = urlGen()->category(data_get($post, 'category.parent'));
												}
												$catUrl = urlGen()->category(data_get($post, 'category'));
												$locationUrl = urlGen()->city(data_get($post, 'city'));
												
												$borderBottom = !$loop->last ? ' border-bottom pb-3' : '';
											@endphp
											<div class="row{{ $borderBottom }} mb-3 d-flex align-items-stretch item-list">
												<div class="col-sm-2 col-12 d-flex justify-content-center p-0 main-image">
													<div class="container position-relative">
														<a href="{{ urlGen()->post($post) }}">
															<img src="{{ data_get($post, 'logo_url.medium') }}"
															     class="img-fluid img-thumbnail w-100 h-auto"
															     alt="{{ data_get($post, 'company_name') }}"
															>
														</a>
													</div>
												</div>
												
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
												</div>
											</div>
										@endforeach
									</div>
								@else
									<div class="py-5 text-center w-100">
										{{ $apiMessagePosts ?? trans('global.Please select a saved search to show the result') }}
									</div>
								@endif
							</div>
						</div>
					</div>
					
					@include('vendor.pagination.api.bootstrap-5')
				</div>
			</div>
		</div>
	</div>
@endsection
