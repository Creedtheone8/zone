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

@section('wizard')
	@include('front.post.createOrEdit.multiSteps.partials.wizard')
@endsection

@php
	$post ??= [];
	$selectedCompany ??= [];
	
	$companies ??= [];
	$postTypes ??= [];
	$salaryTypes ??= [];
	$countries ??= [];
	
	$postCatParentId = data_get($post, 'category.parent_id');
	$postCatParentId = empty($postCatParentId) ? data_get($post, 'category.id', 0) : $postCatParentId;
	
	// Get steps URLs & labels
	$previousStepUrl ??= null;
	$previousStepLabel ??= null;
	$formActionUrl ??= request()->fullUrl();
	$nextStepUrl ??= '/';
	$nextStepLabel ??= trans('global.submit');
@endphp

@section('content')
	@include('front.common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">
				
				@include('front.post.partials.notification')
				
				<div class="col-md-9">
					<div class="container border rounded bg-body-tertiary p-4 p-lg-3 p-md-2 mb-sm-3">
						<h3 class="fw-bold border-bottom pb-3 mb-4">
							<i class="fa-solid fa-pen-to-square"></i> {{ trans('global.company_information') }}
							-&nbsp;<a href="{{ urlGen()->post($post) }}"
							          class="{{ linkClass() }}"
							          data-bs-placement="top"
							          data-bs-toggle="tooltip"
							          title="{!! data_get($post, 'title') !!}"
							>{!! str(data_get($post, 'title'))->limit(45) !!}</a>
						</h3>
						
						<div class="row d-flex justify-content-center">
							<div class="col-md-10 col-sm-12 col-xs-12">
								
								<form id="payableForm"
								      action="{{ $formActionUrl }}"
								      method="POST"
								      enctype="multipart/form-data"
								      class="{{ unsavedFormGuard() }}"
								>
									@csrf
									@method('PUT')
									
									<input type="hidden" name="post_id" value="{{ data_get($post, 'id') }}">
									
									<div class="row">
										
										{{-- company_id --}}
										@php
											$firstOption = [
												'value'      => 0,
												'text'       => '[+] ' . trans('global.New Company'),
												'attributes' => ['data-logo' => null],
											];
											$companyOptions = collect($companies)
												->map(function($item) {
													return [
															'value'      => $item['id'] ?? null,
															'text'       => $item['name'] ?? null,
															'attributes' => ['data-logo' => $item['logo_url']['small'] ?? null],
														];
												})
												->prepend($firstOption)
												->toArray();
											
											$selectedCompanyId = data_get($selectedCompany, 'id', 0);
										@endphp
										@include('helpers.forms.fields.select2', [
											'label'       => trans('global.Select a Company'),
											'id'          => 'companyId',
											'name'        => 'company_id',
											'required'    => true,
											// 'placeholder' => '[+] ' . trans('global.New Company'),
											'options'     => $companyOptions,
											'value'       => $selectedCompanyId,
											'hint'        => null,
											'baseClass'   => ['wrapper' => 'mb-3 col-md-12'],
										])
										
										{{-- logo (HTML) --}}
										@php
											$customHtml = '<div id="logoFieldValue" class="mb-3"></div>';
											
											$editCompanyUrl = url(urlGen()->getAccountBasePath() . '/companies/0/edit');
											$customHint = '<a id="companyFormLink" href="' . $editCompanyUrl . '" class="btn btn-outline-primary">';
											$customHint .= '<i class="fa-regular fa-pen-to-square"></i> ' . trans('global.Edit the Company');
											$customHint .= '</a>';
										@endphp
										@include('helpers.forms.fields.html', [
											'label'       => null,
											'value'       => $customHtml,
											'hint'        => $customHint,
											'wrapper'     => ['id' => 'logoField']
										])
										
										@include('front.account.company._form', ['originForm' => 'post'])
										
										{{-- buttons --}}
										<div class="col-12 mb-3 mt-5">
											<div class="row">
												<div class="col-md-6 mb-md-0 mb-2 text-start d-grid">
													<a href="{{ $previousStepUrl }}" class="btn btn-secondary btn-lg">
														{!! $previousStepLabel !!}
													</a>
												</div>
												<div class="col-md-6 mb-md-0 mb-2 text-end d-grid">
													<button id="nextStepBtn" class="btn btn-primary btn-lg payableFormSubmitButton">
														{!! $nextStepLabel !!}
													</button>
												</div>
											</div>
										</div>
									
									</div>
								</form>
							
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-3 reg-sidebar">
					@include('front.post.createOrEdit.partials.right-sidebar')
				</div>
				
			</div>
		</div>
	</div>
@endsection

@include('front.post.createOrEdit.partials.company-form-assets')
