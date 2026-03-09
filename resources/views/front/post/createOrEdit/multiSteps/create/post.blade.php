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
	$companyInput ??= [];
	$selectedCompanyName = data_get($companyInput, 'company.name');
	
	$companies ??= collect();
	$isCompanyFormEnabled = isCompanyFormEnabled($companies);
	
	$postInput ??= [];
	
	$postTypes ??= [];
	$salaryTypes ??= [];
	$countries ??= [];
	
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
						@php
							$companyTitle = !empty($selectedCompanyName) ? $selectedCompanyName . ' » ' : '';
						@endphp
						<h3 class="fw-bold border-bottom pb-3 mb-4">
							<i class="fa-regular fa-pen-to-square"></i> {{ $companyTitle . trans('global.create_new_job') }}
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
									@honeypot
									
									<div class="row">
										
										{{-- category_id --}}
										@php
											$categoryIdError = (isset($errors) && $errors->has('category_id')) ? ' is-invalid' : '';
											$catSelectionUrl = url('browsing/categories/select');
											
											$categoryId = old('category_id', data_get($postInput, 'category_id', 0));
											
											$aModal = 'data-bs-toggle="modal"';
											$aHref = 'href="#browseCategories"';
											$aDataUrl = 'data-selection-url="' . $catSelectionUrl . '"';
											$aClass = 'class="modal-cat-link open-selection-url ' . linkClass() . '"';
											
											$customHtml = '<div id="catsContainer" class="form-control' . $categoryIdError . '">';
											$customHtml .= "<a {$aHref} {$aModal} {$aDataUrl} {$aClass}>";
											$customHtml .= trans('global.select_a_category');
											$customHtml .= '</a>';
											$customHtml .= '</div>';
											$customHtml .= '<input type="hidden" name="category_id" id="categoryId" value="' . $categoryId . '">';
										@endphp
										@include('helpers.forms.fields.html', [
											'label'    => trans('global.category'),
											'name'     => 'category_id', // <label for="name">
											'required' => true,
											'value'    => $customHtml,
										])
										
										{{-- title --}}
										@include('helpers.forms.fields.text', [
											'label'       => trans('global.Title'),
											'name'        => 'title',
											'placeholder' => trans('global.Job title'),
											'required'    => true,
											'value'       => data_get($postInput, 'title'),
											'hint'        => trans('global.A great title needs at least 60 characters.'),
										])
										
										{{-- description --}}
										@include('helpers.forms.fields.wysiwyg', [
											'label'       => trans('global.Description'),
											'name'        => 'description',
											'placeholder' => trans('global.enter_your_message'),
											'required'    => true,
											'value'       => data_get($postInput, 'description'),
											'height'      => 350,
											'attributes'  => ['rows' => 15],
											'hint'        => trans('global.Describe what makes your ad unique'),
										])
										
										{{-- post_type_id --}}
										@include('helpers.forms.fields.select2', [
											'label'           => trans('global.Job Type'),
											'id'              => 'postTypeId',
											'name'            => 'post_type_id',
											'required'        => true,
											'options'         => $postTypes,
											'optionValueName' => 'id',
											'optionTextName'  => 'name',
											'value'           => data_get($postInput, 'post_type_id'),
											'wrapper'         => ['id' => 'postTypeBloc'],
										])
										
										{{-- salary_type_id --}}
										@php
											$salaryTypeOptions = collect($salaryTypes)
												->map(function($item) {
													$value = $item['id'] ?? null;
													$text = $item['name'] ?? null;
													return [
														'value' => $value,
														'text'  => !empty($text) ? trans('global.per') . ' ' . $text : null,
													];
												})->toArray();
										@endphp
										@include('helpers.forms.fields.select2', [
											'label'           => trans('global.salary_type'),
											'id'              => 'salaryTypeId',
											'name'            => 'salary_type_id',
											'required'        => false,
											'options'         => $salaryTypeOptions,
											'value'           => data_get($postInput, 'salary_type_id'),
										])
										
										{{-- salary_min --}}
										@php
											$currencySymbol = config('currency.symbol', 'X');
											$salaryMin = old('salary_min', data_get($postInput, 'salary_min'));
											$salaryMin = \App\Helpers\Common\Num::format($salaryMin, 2, '.', '');
										@endphp
										@include('helpers.forms.fields.number', [
											'label'       => trans('global.salary_min'),
											'name'        => 'salary_min',
											'required'    => false,
											'placeholder' => trans('global.salary_min'),
											'value'       => $salaryMin,
											'step'        => getInputNumberStep((int)config('currency.decimal_places', 2)),
											'prefix'      => $currencySymbol,
											'attributes'  => ['data-bs-toggle' => 'tooltip', 'title' => trans('global.salary_min')],
											'baseClass'   => ['wrapper' => 'mb-3 col-md-6'],
											'wrapper'     => ['id' => 'minSalaryBloc'],
										])
										
										{{-- salary_max --}}
										@php
											$currencySymbol = config('currency.symbol', 'X');
											$salaryMax = old('salary_max', data_get($postInput, 'salary_max'));
											$salaryMax = \App\Helpers\Common\Num::format($salaryMax, 2, '.', '');
										@endphp
										@include('helpers.forms.fields.number', [
											'label'       => trans('global.salary_max'),
											'name'        => 'salary_max',
											'required'    => false,
											'placeholder' => trans('global.salary_max'),
											'value'       => $salaryMax,
											'step'        => getInputNumberStep((int)config('currency.decimal_places', 2)),
											'prefix'      => $currencySymbol,
											'attributes'  => ['data-bs-toggle' => 'tooltip', 'title' => trans('global.salary_max')],
											'baseClass'   => ['wrapper' => 'mb-3 col-md-6'],
											'wrapper'     => ['id' => 'maxSalaryBloc'],
										])
										
										{{-- negotiable --}}
										@include('helpers.forms.fields.checkbox', [
											'label'    => trans('global.negotiable'),
											'name'     => 'negotiable',
											'switch'   => true,
											'required' => false,
											'value'    => data_get($postInput, 'negotiable'),
										])
										
										{{-- start_date --}}
										@include('helpers.forms.fields.daterangepicker-date', [
											'label'         => trans('global.Start Date'),
											'id'            => 'startDate',
											'name'          => 'start_date',
											'placeholder'   => trans('global.Start Date'),
											'required'      => false,
											'value'         => data_get($postInput, 'start_date'),
											'prefix'        => '<i class="bi bi-calendar2-check"></i>',
											'referenceDate' => date('Y-m-d'),
											'baseClass'     => ['wrapper' => 'mb-3 col-md-8'],
										])
										
										{{-- country_code --}}
										@php
											$countryCodeOptions = collect($countries)
												->map(function($item) {
													return [
														'value'      => $item['code'] ?? null,
														'text'       => $item['name'] ?? null,
														'attributes' => ['data-admin-type' => $item['admin_type'] ?? 0],
													];
												})->toArray();
											
											$selectedCountryCode = !empty(config('ipCountry.code')) ? config('ipCountry.code') : 0;
											$selectedCountryCode = data_get($postInput, 'country_code', $selectedCountryCode);
										@endphp
										@if (empty(config('country.code')))
											@include('helpers.forms.fields.select2', [
												'label'       => trans('global.your_country'),
												'id'          => 'countryCode',
												'name'        => 'country_code',
												'required'    => true,
												'placeholder' => trans('global.select_a_country'),
												'options'     => $countryCodeOptions,
												'value'       => $selectedCountryCode,
												'hint'        => null,
												'baseClass'   => ['wrapper' => 'mb-3 col-md-8'],
											])
										@else
											<input id="countryCode" name="country_code" type="hidden" value="{{ config('country.code') }}">
										@endif
										
										@php
											$adminType = config('country.admin_type', 0);
										@endphp
										@if (config('settings.listing_form.city_selection') == 'select')
											@if (in_array($adminType, ['1', '2']))
												{{-- admin_code --}}
												@include('helpers.forms.fields.select2', [
													'label'        => trans('global.location'),
													'id'           => 'adminCode',
													'name'         => 'admin_code',
													'required'     => true,
													'placeholder'  => trans('global.select_your_location'),
													'options'      => [],
													'largeOptions' => true,
													'hint'         => null,
													'baseClass'    => ['wrapper' => 'mb-3 col-md-8'],
													'wrapper'      => ['id' => 'locationBox'],
												])
											@endif
										@else
											@php
												$adminType = data_get($postInput, 'admin_type', $adminType);
												$adminCode = data_get($postInput, 'admin_code', 0);
												$cityId = (int)data_get($postInput, 'city_id', 0);
												$cityName = data_get($postInput, 'city_name', '--');
											@endphp
											<input type="hidden"
											       id="selectedAdminType"
											       name="selected_admin_type"
											       value="{{ old('selected_admin_type', $adminType) }}"
											>
											<input type="hidden"
											       id="selectedAdminCode"
											       name="selected_admin_code"
											       value="{{ old('selected_admin_code', $adminCode) }}"
											>
											<input type="hidden"
											       id="selectedCityId"
											       name="selected_city_id"
											       value="{{ old('selected_city_id', $cityId) }}"
											>
											<input type="hidden"
											       id="selectedCityName"
											       name="selected_city_name"
											       value="{{ old('selected_city_name', $cityName) }}"
											>
										@endif
										
										{{-- city_id --}}
										@include('helpers.forms.fields.select2', [
											'label'        => trans('global.city'),
											'id'           => 'cityId',
											'name'         => 'city_id',
											'required'     => true,
											'placeholder'  => trans('global.select_a_city'),
											'options'      => [],
											'largeOptions' => true,
											'hint'         => null,
											'baseClass'    => ['wrapper' => 'mb-3 col-md-8'],
											'wrapper'      => ['id' => 'cityBox'],
										])
										
										{{-- application_url --}}
										@include('helpers.forms.fields.url', [
											'label'       => trans('global.Application URL'),
											'name'        => 'application_url',
											'placeholder' => trans('global.Application URL'),
											'value'       => data_get($postInput, 'application_url'),
											'prefix'      => '<i class="bi bi-box-arrow-up-right"></i>',
											'hint'        => trans('global.Candidates will follow this URL address to apply for the job'),
										])
										
										{{-- tags --}}
										@php
											$tagHint = trans('global.tags_hint', ['limit' => '{limit}', 'min' => '{min}', 'max' => '{max}']);
										@endphp
										@include('helpers.forms.fields.select2-tagging', [
											'label'       => trans('global.Tags'),
											'id'          => 'tags',
											'name'        => 'tags',
											'placeholder' => trans('global.enter_tags'),
											'options'     => data_get($postInput, 'tags'),
											'hint'        => $tagHint,
										])
										
										<div class="my-4 col-md-12">
											<h5 class="w-100 mb-0 fw-bold fs-5 border rounded p-2">
												<i class="bi bi-person-circle"></i> {{ trans('global.contact_information') }}
											</h5>
										</div>
										
										{{-- contact_name --}}
										@if (auth()->check())
											<input id="contactName" name="contact_name" type="hidden" value="{{ auth()->user()->name ?? null }}">
										@else
											@include('helpers.forms.fields.text', [
												'label'       => trans('global.Contact Name'),
												'id'          => 'contactName',
												'name'        => 'contact_name',
												'placeholder' => trans('global.Contact Name'),
												'required'    => true,
												'value'       => data_get($postInput, 'contact_name'),
												'prefix'      => '<i class="fa-regular fa-user"></i>',
												'suffix'      => null,
												'baseClass'   => ['wrapper' => 'mb-3 col-md-8'],
											])
										@endif
										
										{{-- auth_field (as notification channel) --}}
										@php
											$authFields = getAuthFields(true);
											$authFieldOptions = collect($authFields)
												->map(fn($item, $key) => ['value' => $key, 'text' => $item])
												->toArray();
											
											$usersCanChooseNotifyChannel = isUsersCanChooseNotifyChannel();
											$authFieldValue = data_get($postInput, 'auth_field') ?? getAuthField();
											$authFieldValue = ($usersCanChooseNotifyChannel) ? old('auth_field', $authFieldValue) : $authFieldValue;
										@endphp
										@if ($usersCanChooseNotifyChannel)
											@include('helpers.forms.fields.radio', [
												'label'      => trans('auth.notifications_channel'),
												'btnVariant' => 'secondary',
												'btnOutline' => true,
												'id'         => 'authField-',
												'name'       => 'auth_field',
												'inline'     => true,
												'required'   => true,
												'options'    => $authFieldOptions,
												'value'      => $authFieldValue,
												'attributes' => ['class' => 'auth-field-input'],
												'hint'       => trans('auth.notifications_channel_hint'),
											])
										@else
											<input id="authField-{{ $authFieldValue }}" name="auth_field" type="hidden" value="{{ $authFieldValue }}">
										@endif
										
										@php
											$forceToDisplay = isBothAuthFieldsCanBeDisplayed() ? ' force-to-display' : '';
										@endphp
										
										{{-- email --}}
										@php
											$emailValue = (auth()->check() && isset(auth()->user()->email))
												? auth()->user()->email
												: data_get($postInput, 'email');
										@endphp
										@include('helpers.forms.fields.email', [
											'label'       => trans('global.Contact Email'),
											'id'          => 'email',
											'name'        => 'email',
											'required'    => (getAuthField() == 'email'),
											'placeholder' => trans('global.email_address'),
											'value'       => $emailValue,
											'prefix'      => '<i class="fa-regular fa-envelope"></i>',
											'suffix'      => null,
											'baseClass'   => ['wrapper' => 'mb-3 col-md-8'],
											'wrapper'     => ['class' => "auth-field-item{$forceToDisplay}"],
										])
										
										{{-- phone --}}
										@php
											$phoneValue = data_get($postInput, 'phone');
											$phoneCountryValue = data_get($postInput, 'phone_country', config('country.code'));
											if (
												auth()->check()
												&& isset(auth()->user()->country_code)
												&& !empty(auth()->user()->phone)
												&& isset(auth()->user()->phone_country)
											) {
												$phoneValue = auth()->user()->phone;
												$phoneCountryValue = auth()->user()->phone_country;
											}
											
											// phone_hidden
											$phoneHiddenValue = old('phone_hidden', data_get($postInput, 'phone_hidden'));
											$phoneHiddenChecked = ($phoneHiddenValue == '1') ? ' checked' : '';
											$itiSuffix = '<input id="phoneHidden" name="phone_hidden" type="checkbox" value="1"' . $phoneHiddenChecked . '>';
											$itiSuffix .= '&nbsp;<small>' . trans('global.Hide') . '</small>';
										@endphp
										@include('helpers.forms.fields.intl-tel-input', [
											'label'       => trans('auth.phone_number'),
											'id'          => 'phone',
											'name'        => 'phone',
											'required'    => (getAuthField() == 'phone'),
											'placeholder' => null,
											'value'       => $phoneValue,
											'countryCode' => $phoneCountryValue,
											'suffix'      => $itiSuffix,
											'baseClass'   => ['wrapper' => 'mb-3 col-md-8'],
											'wrapper'     => ['class' => "auth-field-item{$forceToDisplay}"],
										])
										
										{{-- auto_registration --}}
										@if (!auth()->check())
											@if (in_array(config('settings.listing_form.auto_registration'), [1, 2]))
												@if (config('settings.listing_form.auto_registration') == 1)
													@include('helpers.forms.fields.checkbox', [
														'label'    => trans('global.I want to register by submitting this ad'),
														'name'     => 'auto_registration',
														'required' => false,
														'value'    => data_get($postInput, 'auto_registration', '1'),
														'hint'     => trans('global.You will receive your authentication information by email'),
													])
												@else
													<input type="hidden" name="auto_registration" id="auto_registration" value="1">
												@endif
											@endif
										@endif
										
										{{-- captcha --}}
										@if (!isMultipleCompaniesPerUserEnabled())
											@include('helpers.forms.fields.captcha', ['label' => trans('auth.captcha_human_verification')])
										@endif
										
										@if (!auth()->check())
											{{-- accept_terms --}}
											@include('helpers.forms.fields.checkbox', [
												'label'     => trans('global.accept_terms_label', ['attributes' => getUrlPageByType('terms')]),
												'id'        => 'acceptTerms',
												'name'      => 'accept_terms',
												'required'  => true,
												'value'     => data_get($postInput, 'accept_terms'),
												'baseClass' => ['wrapper' => 'mb-1 col-md-12'],
											])
											
											{{-- accept_marketing_offers --}}
											@include('helpers.forms.fields.checkbox', [
												'label'    => trans('global.accept_marketing_offers_label'),
												'id'       => 'acceptMarketingOffers',
												'name'     => 'accept_marketing_offers',
												'required' => false,
												'value'    => data_get($postInput, 'accept_marketing_offers'),
											])
										@endif
										
										{{-- buttons --}}
										<div class="col-12 mb-3 mt-5">
											<div class="row">
												<div class="col-md-6 mb-md-0 mb-2 text-start d-grid">
													@if ($isCompanyFormEnabled)
														<a href="{{ $previousStepUrl }}" class="btn btn-secondary btn-lg">
															{!! $previousStepLabel !!}
														</a>
													@else
														<a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg">
															{{ trans('global.Cancel') }}
														</a>
													@endif
												</div>
												<div class="col-md-6 mb-md-0 mb-2 text-end d-grid">
													<button id="nextStepBtn" class="btn btn-primary btn-lg">
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
	@include('front.post.createOrEdit.partials.category-modal')
	
	@includeWhen(!auth()->check(), 'auth.login.partials.modal')
@endsection

@include('front.post.createOrEdit.partials.form-assets')
