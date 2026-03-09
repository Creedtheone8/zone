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
	$resume ??= [];
	$resumeId = $resume['id'] ?? 0;
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
					@if (isset($errors) && $errors->any())
						<div class="alert alert-danger">
							<h5 class="fw-bold text-danger-emphasis mb-3">
								{{ trans('global.validation_errors_title') }}
							</h5>
							<ul class="mb-0 list-unstyled">
								@foreach ($errors->all() as $error)
									<li class="lh-lg"><i class="bi bi-check-lg me-1"></i>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					<div class="container border rounded bg-body-tertiary p-4 p-lg-3 p-md-2">
						<h3 class="fw-bold border-bottom pb-3 mb-4">
							<i class="fa-solid fa-paperclip"></i> {{ trans('global.Edit the resume') }}
						</h3>
						
						<div class="mb-3 pe-3 float-end">
							<a href="{{ url(urlGen()->getAccountBasePath() . '/resumes') }}" class="{{ linkClass() }}">
								<i class="bi bi-arrow-90deg-left"></i> {{ trans('global.my_resumes') }}
							</a>
						</div>
						<div style="clear: both;"></div>
						
						<div class="panel-group" id="accordion">
							
							{{-- RESUME --}}
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0">
										{{ trans('global.Resume') }}
									</h5>
								</div>
								<div class="card-body">
									
									<div class="row d-flex justify-content-center">
										<div class="col-xl-8 col-lg-9 col-md-10 col-sm-12">
											<form name="resume"
											      action="{{ url(urlGen()->getAccountBasePath() . '/resumes/' . $resumeId) }}"
											      method="POST"
											      enctype="multipart/form-data"
											      role="form"
											>
												@csrf
												@method('PUT')
												
												<input name="panel" type="hidden" value="resumePanel">
												<input name="resume_id" type="hidden" value="{{ $resumeId }}">
												
												<div class="row">
													@include('front.account.resume._form')
													
													{{-- button --}}
													<div class="col-12 mb-3 mt-3">
														<div class="row">
															<div class="col-md-12">
																<button type="submit" class="btn btn-primary">{{ trans('global.Update') }}</button>
															</div>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
								
								</div>
							</div>
						
						</div>
					
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
