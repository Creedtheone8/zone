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
	$companies = (array)data_get($apiResult, 'data');
	$totalCompanies = (int)data_get($apiResult, 'meta.total', 0);
@endphp

@section('search')
	@parent
	@include('front.search.company.partials.search')
@endsection

@section('content')
	@include('front.common.spacer')
	<div class="main-container">
		<div class="container">
			
			<div class="card mb-3">
				<div class="card-header border-bottom-0">
					<h4 class="mb-0 float-start fw-bold">
						{{ trans('global.companies_list') }}
					</h4>
					<h5 class="mb-0 float-end mt-1 fs-6 fw-lighter text-uppercase">
						<a href="{{ urlGen()->searchWithoutQuery() }}" class="{{ linkClass() }}">
							{{ trans('global.Browse Jobs') }} <i class="fa-solid fa-bars"></i>
						</a>
					</h5>
				</div>
				
				<div class="card-body rounded py-0">
					@if (!empty($companies) && $totalCompanies > 0)
						<div class="row row-cols-lg-6 row-cols-md-4 row-cols-sm-3 row-cols-2 py-1 px-0 company-list">
							@foreach($companies as $key => $iCompany)
								@php
									$companyId = data_get($iCompany, 'id');
									$logoUrl = data_get($iCompany, 'logo_url.medium');
									$logoStyle = 'max-width: 80px; max-height: 60px; width: auto; height: 60px;';
									$companyName = data_get($iCompany, 'name');
									$listingsCount = data_get($iCompany, 'posts_count') ?? 0;
								@endphp
								<div class="col px-0 d-flex justify-content-center align-content-stretch">
									<div class="text-center w-100 border rounded px-3 py-4 m-1">
										<a href="{{ urlGen()->company($companyId) }}" class="{{ linkClass() }}">
											<img src="{{ $logoUrl }}" class="img-fluid img-thumbnail" alt="{{ $companyName }}" style="{!! $logoStyle !!}">
											<div class="mt-2 small">
												<span class="text-body-emphasis">{{ trans('global.Jobs at') }}</span>
												<span>{{ $companyName }}</span>
												<span class="text-secondary">({{ $listingsCount }})</span>
											</div>
										</a>
									</div>
								</div>
							@endforeach
						</div>
					@else
						<div class="col-lg-12 col-md-12 col-sm-12 col-12 w-100">
							{{ $apiMessage ?? trans('global.no_result_refine_your_search') }}
						</div>
					@endif
				</div>
			</div>
			
			@include('vendor.pagination.api.bootstrap-5')
			
		</div>
	</div>
@endsection
