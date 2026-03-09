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
	$resumes = (array)data_get($apiResult, 'data');
	$totalResumes = (int)data_get($apiResult, 'meta.total', 0);
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
					<div class="container border rounded bg-body-tertiary p-4 p-lg-3 p-md-2">
						<div class="row border-bottom pb-3 mb-4">
							<div class="col-6">
								<h3 class="fw-bold border-bottom-0 mb-1">
									<i class="fa-regular fa-building"></i> {{ trans('global.my_resumes') }}
								</h3>
							</div>
							<div class="col-6 text-end">
								<a href="{{ url(urlGen()->getAccountBasePath() . '/resumes/create') }}" class="btn btn-outline-primary">
									<i class="fa-solid fa-plus"></i> {{ trans('global.Add a new resume') }}
								</a>
							</div>
						</div>
						
						<div class="table-responsive">
							<form name="listForm" action="{{ url(urlGen()->getAccountBasePath() . '/resumes/delete') }}" method="POST">
								@csrf
								
								<div class="d-flex justify-content-between bg-body rounded p-3 mb-3 table-action">
									<div class="text-nowrap d-flex align-items-center">
										<div class="btn-group hidden-sm" role="group">
											<button type="button" class="btn btn-sm btn-outline-primary pb-0">
												<input type="checkbox" id="checkAll" class="from-check-all">
											</button>
											<button type="button" class="btn btn-sm btn-primary from-check-all">
												{{ trans('global.Select') }}: {{ trans('global.All') }}
											</button>
										</div>
										
										<button type="submit" class="btn btn-sm btn-danger ms-1 confirm-simple-action">
											<i class="fa-regular fa-trash-can"></i> {{ trans('global.Delete') }}
										</button>
									</div>
									
									<div class="w-100 table-search">
										<div class="row">
											<label class="col-5 my-0 form-label text-end">{{ trans('global.search') }} <br>
												<a title="clear filter" class="clear-filter {{ linkClass() }}" href="#clear">
													[{{ trans('global.clear') }}]
												</a>
											</label>
											<div class="col-7 my-0">
												<input type="text" class="form-control" id="filter">
											</div>
										</div>
									</div>
								</div>
								
								<table id="addManageTable"
								       class="table mb-0 table-striped"
									   data-filter="#filter"
									   data-filter-text-only="true"
								>
									<thead>
									<tr>
										<th scope="col" data-type="numeric" data-sort-initial="true"></th>
										<th scope="col"> {{ trans('global.File') }}</th>
										<th scope="col" data-sort-ignore="true"> {{ trans('global.Name') }} </th>
										<th scope="col"> {{ trans('global.Option') }}</th>
									</tr>
									</thead>
									<tbody>
									@if (!empty($resumes) && $totalResumes > 0)
										@foreach($resumes as $key => $resume)
											@php
												$resumeId = data_get($resume, 'id');
												$resumeBaseUrl = urlGen()->getAccountBasePath() . '/resumes/' . $resumeId;
												$resumeName = data_get($resume, 'name', '--') ?? '--';
											@endphp
											<tr>
												<td style="width:2%" class="add-img-selector">
													<div class="checkbox">
														<label><input type="checkbox" name="entries[]" value="{{ data_get($resume, 'id') }}"></label>
													</div>
												</td>
												<td style="width:14%" class="add-img-td">
													<a class="btn btn-dark" href="{{ privateFileUrl(data_get($resume, 'file_path')) }}" target="_blank">
														<i class="fa-solid fa-paperclip"></i> {{ trans('global.Download') }}
													</a>
												</td>
												<td style="width:58%" class="items-details-td">
													<div>
														<p>
															{{ str($resumeName)->limit(40) }}
														</p>
													</div>
												</td>
												<td style="width:10%" class="text-nowrap">
													@if (data_get($resume, 'user_id') == $authUser->id)
														<div class="vstack gap-1">
															<a class="btn btn-primary btn-xs"
															   href="{{ url($resumeBaseUrl . '/edit') }}"
															>
																<i class="fa-regular fa-pen-to-square"></i> {{ trans('global.Edit') }}
															</a>
															
															<a class="btn btn-danger btn-xs confirm-simple-action"
															   href="{{ url($resumeBaseUrl . '/delete') }}"
															>
																<i class="fa-regular fa-trash-can"></i> {{ trans('global.Delete') }}
															</a>
														</div>
													@endif
												</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="4">
												<div class="text-center my-5">
													{{ $apiMessage ?? trans('global.no_resumes_found') }}
												</div>
											</td>
										</tr>
									@endif
									</tbody>
								</table>
							</form>
						</div>
						
						@include('vendor.pagination.api.bootstrap-5')
						
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script src="{{ url('assets/plugins/footable-jquery/2.0.1.4/footable.js?v=2-0-1') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/footable-jquery/2.0.1.4/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
	<script type="text/javascript">
		onDocumentReady((event) => {
			$('#addManageTable').footable().bind('footable_filtering', function (e) {
				const filterStatusEl = $('.filter-status');
				if (filterStatusEl.length <= 0) {
					return;
				}
				
				const selectedEl = filterStatusEl.find(':selected');
				if (filterStatusEl.length > 0) {
					return;
				}
				
				const selected = selectedEl.text();
				if (selected && selected.length > 0) {
					e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
					e.clear = !e.filter;
				}
			});
			
			/* Clear Filter OnClick */
			const clearFilterEl = document.querySelector('.clear-filter');
			clearFilterEl.addEventListener('click', (event) => {
				event.preventDefault();
				
				const filterStatusEl = document.querySelector(".filter-status");
				if (filterStatusEl) {
					filterStatusEl.value = '';
				}
				
				$('table.demo').trigger('footable_clear_filter');
			});
			
			/* Check All OnClick */
			const checkAllEls = document.querySelectorAll('.from-check-all');
			if (checkAllEls.length > 0) {
				checkAllEls.forEach(checkEl => {
					checkEl.addEventListener('click', (event) => checkAllBoxes(event.target));
				});
			}
		});
	</script>
@endsection
