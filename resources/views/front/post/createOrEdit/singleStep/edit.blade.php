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
	$post ??= [];
	$selectedCompany ??= [];
	
	$companies ??= [];
	$postTypes ??= [];
	$salaryTypes ??= [];
	$countries ??= [];
	
	$postCatParentId = data_get($post, 'category.parent_id');
	$postCatParentId = (empty($postCatParentId)) ? data_get($post, 'category.id', 0) : $postCatParentId;
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
							<i class="fa-solid fa-pen-to-square"></i> {{ trans('global.update_my_ad') }} -&nbsp;
							<a href="{{ urlGen()->post($post) }}"
							   class="{{ linkClass() }}"
							   data-bs-placement="top"
							   data-bs-toggle="tooltip"
							   title="{!! data_get($post, 'title') !!}"
							>
								{!! str(data_get($post, 'title'))->limit(45) !!}
							</a>
						</h3>
						
						<div class="row d-flex justify-content-center">
							<div class="col-md-10 col-sm-12 col-xs-12">
								
								<form id="payableForm"
								      action="{{ url()->current() }}"
								      method="POST"
								      enctype="multipart/form-data"
								      class="{{ unsavedFormGuard() }}"
								>
									@csrf
									@method('PUT')
									
									<input type="hidden" name="post_id" value="{{ data_get($post, 'id') }}">
									<input type="hidden" name="payable_id" value="{{ data_get($post, 'id') }}">
									
									<div class="row">
										{{-- COMPANY --}}
										<div class="my-4 col-md-12">
											<h5 class="w-100 mb-0 fw-bold fs-5 border rounded p-2">
												<i class="bi bi-briefcase"></i> {{ trans('global.company_information') }}
											</h5>
										</div>
										
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
											$customHint = '<a id="companyFormLink" href="' . $editCompanyUrl . '" class="btn btn-primary">';
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
										
										
										{{-- POST --}}
										<div class="my-4 col-md-12">
											<h5 class="w-100 mb-0 fw-bold fs-5 border rounded p-2">
												<i class="bi bi-card-checklist"></i> {{ trans('global.listing_details') }}
											</h5>
										</div>
										
										{{-- category_id --}}
										@php
											$categoryIdError = (isset($errors) && $errors->has('category_id')) ? ' is-invalid' : '';
											$catSelectionUrl = url('browsing/categories/select');
											
											$categoryId = old('category_id', data_get($post, 'category.id'));
											
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
											'value'       => data_get($post, 'title'),
											'hint'        => trans('global.A great title needs at least 60 characters.'),
										])
										
										{{-- description --}}
										@include('helpers.forms.fields.wysiwyg', [
											'label'       => trans('global.Description'),
											'name'        => 'description',
											'placeholder' => trans('global.enter_your_message'),
											'required'    => true,
											'value'       => data_get($post, 'description'),
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
											'value'           => data_get($post, 'post_type_id'),
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
											'value'           => data_get($post, 'salary_type_id'),
										])
										
										{{-- salary_min --}}
										@php
											$currencySymbol = config('currency.symbol', 'X');
											$salaryMin = old('salary_min', data_get($post, 'salary_min'));
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
											$salaryMax = old('salary_max', data_get($post, 'salary_max'));
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
											'value'    => data_get($post, 'negotiable'),
										])
										
										{{-- start_date --}}
										@include('helpers.forms.fields.daterangepicker-date', [
											'label'         => trans('global.Start Date'),
											'id'            => 'startDate',
											'name'          => 'start_date',
											'placeholder'   => trans('global.Start Date'),
											'required'      => false,
											'value'         => data_get($post, 'start_date'),
											'prefix'        => '<i class="bi bi-calendar2-check"></i>',
											'referenceDate' => data_get($post, 'created_at'),
											'baseClass'     => ['wrapper' => 'mb-3 col-md-8'],
										])
										
										{{-- country_code --}}
										<input id="countryCode" name="country_code"
										       type="hidden"
										       value="{{ data_get($post, 'country_code') ?? config('country.code') }}"
										>
										
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
												$adminType = (in_array($adminType, ['0', '1', '2'])) ? $adminType : 0;
												$relAdminType = (in_array($adminType, ['1', '2'])) ? $adminType : 1;
												$adminCode = data_get($post, 'city.subadmin' . $relAdminType . '_code', 0);
												$adminCode = data_get($post, 'city.subAdmin' . $relAdminType . '.code', $adminCode);
												$adminName = data_get($post, 'city.subAdmin' . $relAdminType . '.name');
												$cityId = data_get($post, 'city.id', 0);
												$cityName = data_get($post, 'city.name');
												$fullCityName = !empty($adminName) ? $cityName . ', ' . $adminName : $cityName;
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
											       value="{{ old('selected_city_name', $fullCityName) }}"
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
											'value'       => data_get($post, 'application_url'),
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
											'options'     => data_get($post, 'tags'),
											'hint'        => $tagHint,
										])
										
										<div class="my-4 col-md-12">
											<h5 class="w-100 mb-0 fw-bold fs-5 border rounded p-2">
												<i class="bi bi-person-circle"></i> {{ trans('global.contact_information') }}
											</h5>
										</div>
										
										{{-- contact_name --}}
										@include('helpers.forms.fields.text', [
											'label'       => trans('global.Contact Name'),
											'id'          => 'contactName',
											'name'        => 'contact_name',
											'placeholder' => trans('global.Contact Name'),
											'required'    => true,
											'value'       => data_get($post, 'contact_name'),
											'prefix'      => '<i class="fa-regular fa-user"></i>',
											'suffix'      => null,
											'baseClass'   => ['wrapper' => 'mb-3 col-md-8'],
										])
										
										{{-- auth_field (as notification channel) --}}
										@php
											$authFields = getAuthFields(true);
											$authFieldOptions = collect($authFields)
												->map(fn($item, $key) => ['value' => $key, 'text' => $item])
												->toArray();
											
											$usersCanChooseNotifyChannel = isUsersCanChooseNotifyChannel();
											$authFieldValue = data_get($post, 'auth_field') ?? getAuthField();
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
											<input id="{{ $authFieldValue }}AuthField" name="auth_field" type="hidden" value="{{ $authFieldValue }}">
										@endif
										
										@php
											$forceToDisplay = isBothAuthFieldsCanBeDisplayed() ? ' force-to-display' : '';
										@endphp
										
										{{-- email --}}
										@include('helpers.forms.fields.email', [
											'label'       => trans('global.Contact Email'),
											'id'          => 'email',
											'name'        => 'email',
											'required'    => (getAuthField() == 'email'),
											'placeholder' => trans('global.email_address'),
											'value'       => data_get($post, 'email'),
											'prefix'      => '<i class="fa-regular fa-envelope"></i>',
											'suffix'      => null,
											'baseClass'   => ['wrapper' => 'mb-3 col-md-8'],
											'wrapper'     => ['class' => "auth-field-item{$forceToDisplay}"],
										])
										
										{{-- phone --}}
										@php
											$phoneValue = data_get($post, 'phone');
											$phoneCountryValue = data_get($post, 'phone_country', config('country.code'));
											
											// phone_hidden
											$phoneHiddenValue = old('phone_hidden', data_get($post, 'phone_hidden'));
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
										
										@include('front.post.createOrEdit.singleStep.partials.packages')
										
										{{-- button --}}
										<div class="col-12 mb-3 mt-5">
											<div class="row">
												<div class="col-md-6 mb-md-0 mb-2 text-start d-grid">
													<a href="{{ urlGen()->post($post) }}" class="btn btn-secondary btn-lg">
														{{ trans('global.Back') }}
													</a>
												</div>
												<div class="col-md-6 mb-md-0 mb-2 text-end d-grid">
													<button id="payableFormSubmitButton" class="btn btn-primary btn-lg payableFormSubmitButton">
														{{ trans('global.Update') }}
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
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
	<script>
		defaultAuthField = '{{ old('auth_field', $authFieldValue ?? getAuthField()) }}';
		phoneCountry = '{{ old('phone_country', ($phoneCountryValue ?? '')) }}';
	</script>
@endsection

@include('front.post.createOrEdit.partials.company-form-assets')
@include('front.post.createOrEdit.partials.form-assets')
