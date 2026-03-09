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
	$company ??= [];
	
	$socialMedias = [
		'linkedin' => [
			'link' => data_get($company, 'linkedin'),
			'name' => 'LinkedIn',
			'icon' => 'fa-brands fa-linkedin',
		],
		'facebook' => [
			'link' => data_get($company, 'facebook'),
			'name' => 'Facebook',
			'icon' => 'fa-brands fa-square-facebook',
		],
		'twitter' => [
			'link' => data_get($company, 'twitter'),
			'name' => 'X (Twitter)',
			'icon' => 'fa-brands fa-square-x-twitter',
		],
		'pinterest' => [
			'link' => data_get($company, 'pinterest'),
			'name' => 'Pinterest',
			'icon' => 'fa-brands fa-square-pinterest',
		],
	];
	
	$contactInfo = [
		[
			'key'    => trans('global.Address'),
			'value'  => data_get($company, 'address'),
			'isLink' => false,
		],
		[
			'key'    => trans('global.phone'),
			'value'  => data_get($company, 'phone'),
			'isLink' => false,
		],
		[
			'key'    => trans('global.Mobile Phone'),
			'value'  => data_get($company, 'mobile'),
			'isLink' => false,
		],
		[
			'key'    => trans('global.Fax'),
			'value'  => data_get($company, 'fax'),
			'isLink' => false,
		],
		[
			'key'    => trans('global.Website'),
			'value'  => data_get($company, 'website'),
			'isLink' => true,
		],
	];
@endphp

@section('search')
	@parent
	@include('front.search.partials.form')
	@include('front.search.partials.breadcrumbs')
	@include('front.layouts.partials.advertising.top')
@endsection

@section('content')
	<div class="main-container">
		<div class="container">
			
			<div class="row">
				<div class="col-md-12 mb-4">
					<div class="container border rounded py-3">
						<div class="row">
							@php
								$colDetails = 'col-12';
								$colContact = null;
								if (
									(!empty(data_get($company, 'address')))
									|| (!empty(data_get($company, 'phone')))
									|| (!empty(data_get($company, 'mobile')))
									|| (!empty(data_get($company, 'fax')))
								) {
									$colDetails = 'col-lg-8 col-md-6 col-sm-12';
									$colContact = 'col-lg-4 col-md-6 col-sm-12';
								}
							@endphp
							<div class="{{ $colDetails }}">
								<div class="row">
									<div class="col-3">
										<img
												src="{{ data_get($company, 'logo_url.medium') }}"
												class="img-fluid img-thumbnail"
												alt="{{ data_get($company, 'name') }}"
										>
									</div>
									
									<div class="col-9 vstack gap-2">
										<h3 class="m-0 p-0 link-color uppercase">
											<span class="fw-bold">{{ data_get($company, 'name') }}</span>
											@if (auth()->check())
												@if (auth()->user()->id == data_get($company, 'user_id'))
													@php
														$editPath = '/companies/' . data_get($company, 'id') . '/edit';
														$editUrl = url(urlGen()->getAccountBasePath() . $editPath);
													@endphp
													<a href="{{ $editUrl }}" class="btn btn-secondary btn-sm">
														<i class="fa-regular fa-pen-to-square"></i> {{ trans('global.Edit') }}
													</a>
												@endif
											@endif
										</h3>
										
										<div class="text-muted">
											{!! data_get($company, 'description') !!}
										</div>
										
										<div class="seller-social-list">
											<ul class="list-inline share-this-post">
												@foreach($socialMedias as $key => $item)
													@if (!empty(data_get($item, 'link')))
														<li class="list-inline-item">
															<a href="{{ data_get($item, 'link') }}" class="fs-3 {{ linkClass('body-emphasis') }}" target="_blank">
																<i class="{{ data_get($item, 'icon') }}"></i>
															</a>
														</li>
													@endif
												@endforeach
											</ul>
										</div>
									</div>
								</div>
							</div>
							
							@if (!empty($colContact))
								<div class="{{ $colContact }}">
									<div class="bg-body-tertiary rounded border p-3">
										<h4 class="fw-bold mb-3">
											{{ trans('global.Contact Information') }}
										</h4>
										<dl class="row mb-0">
											@foreach($contactInfo as $item)
												@if (!empty(data_get($item, 'value')))
													<dt class="col-sm-3">{{ data_get($item, 'key') }}:</dt>
													<dd class="col-sm-9">
														@if (data_get($item, 'isLink'))
															<a href="{{ data_get($item, 'value') }}" target="_blank">
																{!! data_get($item, 'value') !!}
															</a>
														@else
															{!! data_get($item, 'value') !!}
														@endif
													</dd>
												@endif
											@endforeach
										</dl>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>
				
				<div class="col-md-12 mb-4">
					<div class="container border rounded mb-4">
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
						
						<div class="row jobs-list">
							<div class="col-12">
								<div class="container">
									@include('front.search.partials.posts.template.list')
								</div>
							</div>
						</div>
						
						@if (
							request()->filled('q')
							&& request()->query('q') != ''
							&& (int)data_get($count, 'all') > 0
						)
							<div class="row text-center">
								<div class="col-12 border-top py-3 mt-3">
									<a id="saveSearch"
									   data-name="{!! request()->fullUrlWithoutQuery(['_token', 'location']) !!}"
									   data-count="{{ data_get($count, '0') }}"
									   class="{{ linkClass() }}"
									>
										<i class="bi bi-bell"></i> {{ trans('global.Save Search') }}
									</a>
								</div>
							</div>
						@endif
						
					</div>
					
					@include('vendor.pagination.api.bootstrap-5')
				</div>
				
				{{-- Advertising --}}
				@include('front.layouts.partials.advertising.bottom')
			</div>
		
		</div>
	</div>
@endsection
