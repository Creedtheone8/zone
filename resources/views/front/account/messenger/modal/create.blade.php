@php
	$post ??= [];
	$resumes ??= [];
	$totalResumes ??= 0;
	$lastResume ??= [];
	
	$fiTheme = config('larapen.core.fileinput.theme', 'bs5');
	
	$actionUrl = url(urlGen()->getAccountBasePath() . '/messages/posts/' . data_get($post, 'id'));
@endphp
<form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" role="form">
	@csrf
	@honeypot
	<div class="modal fade" id="applyJob" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg modal-dialog-scrollable">
			<div class="modal-content">
				
				<div class="modal-header px-3">
					<h4 class="modal-title fs-5 fw-bold">
						<i class="bi bi-envelope"></i> {{ trans('global.Contact Recruiter') }}
					</h4>
					
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ trans('global.Close') }}"></button>
				</div>
				
				<div class="modal-body">
					@if (isset($errors) && $errors->any() && old('messageForm')=='1')
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ trans('global.Close') }}"></button>
							<ul class="mb-0 list-unstyled">
								@foreach($errors->all() as $error)
									<li class="lh-lg"><i class="bi bi-check-lg me-1"></i>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					<input type="hidden" name="country_code" value="{{ config('country.code') }}">
					<input type="hidden" name="post_id" value="{{ data_get($post, 'id') }}">
					<input type="hidden" name="messageForm" value="1">
					
					<div class="row d-flex justify-content-center">
						<div class="col-md-10 col-sm-12 col-xs-12">
							<div class="row">
								@php
									$authUser = auth()->check() ? auth()->user() : null;
									$isNameCanBeHidden = (!empty($authUser));
									$isEmailCanBeHidden = (!empty($authUser) && !empty($authUser->email));
									$isPhoneCanBeHidden = (!empty($authUser) && !empty($authUser->phone));
									$authFieldValue = data_get($post, 'auth_field', getAuthField());
								@endphp
								
								{{-- name --}}
								@if ($isNameCanBeHidden)
									<input type="hidden" name="name" value="{{ $authUser->name ?? null }}">
								@else
									@include('helpers.forms.fields.text', [
										'label'       => trans('global.Name'),
										'id'          => 'fromName',
										'name'        => 'name',
										'placeholder' => trans('global.enter_your_name'),
										'required'    => true,
										'value'       => $authUser->name ?? null,
									])
								@endif
								
								{{-- email --}}
								@if ($isEmailCanBeHidden)
									<input type="hidden" name="email" value="{{ $authUser->email ?? null }}">
								@else
									@include('helpers.forms.fields.email', [
										'label'       => trans('auth.email'),
										'id'          => 'fromEmail',
										'name'        => 'email',
										'required'    => ($authFieldValue == 'email'),
										'placeholder' => trans('global.enter_your_email'),
										'value'       => $authUser->email ?? null,
										'attributes'  => ['data-valid-type' => 'email'],
										'prefix'      => '<i class="fa-regular fa-envelope"></i>',
										'suffix'      => null,
										'baseClass'   => ['wrapper' => 'mb-3 col-lg-12'],
									])
								@endif
								
								{{-- phone --}}
								@if ($isPhoneCanBeHidden)
									<input type="hidden" name="phone" value="{{ $authUser->phone ?? null }}">
									<input name="phone_country" type="hidden" value="{{ $authUser->phone_country ?? config('country.code') }}">
								@else
									@php
										$phoneValue = $authUser->phone ?? null;
										$phoneCountryValue = $authUser->phone_country ?? config('country.code');
										$phoneRequiredClass = ($authFieldValue == 'phone') ? ' required' : '';
									@endphp
									@include('helpers.forms.fields.intl-tel-input', [
										'label'       => trans('auth.phone_number'),
										'id'          => 'fromPhone',
										'name'        => 'phone',
										'required'    => ($authFieldValue == 'phone'),
										'placeholder' => trans('auth.phone_number'),
										'value'       => $phoneValue,
										'attributes'  => ['maxlength' => 60],
										'countryCode' => $phoneCountryValue,
										'baseClass'   => ['wrapper' => 'mb-3 col-lg-12'],
									])
								@endif
								
								{{-- auth_field --}}
								<input name="auth_field" type="hidden" value="{{ $authFieldValue }}">
								
								{{-- body --}}
								@include('helpers.forms.fields.textarea', [
									'label'       => trans('global.Message') . ' <span class="text-count">(500 max)</span>',
									'id'          => 'body',
									'name'        => 'body',
									'placeholder' => trans('global.enter_your_message'),
									'required'    => true,
									'value'       => null,
									'attributes'    => ['rows' => 5],
									'pluginOptions' => ['height' => 150],
								])
								
								{{-- file_path --}}
								@php
									$newResume = [
										'value' => 0,
										'text'  => '[+] ' . trans('global.New Resume'),
									];
									
									$resumes ??= [];
									$resumesOptions = collect($resumes)
										->map(function($item) {
											$value = $item['id'] ?? null;
											$text = $item['name'] ?? null;
											
											$filePath = $item['file_path'] ?? null;
											if (!empty($filePath)) {
												$url = privateFileUrl($filePath);
												$text .= ' - ';
												$text .= '<a href="' . $url . '" target="_blank" class="' . linkClass() . '">';
												$text .= trans('global.Download');
												$text .= '</a>';
											}
											
											return [
												'value' => $value,
												'text'  => $text,
											];
										})
										->push($newResume)
										->toArray();
									
									$selectedResume = !empty($lastResume) ? data_get($lastResume, 'id') : 0;
								@endphp
								@include('helpers.forms.fields.radio', [
									'label'           => trans('global.Resume'),
									'id'              => 'resumeId-',
									'name'            => 'resume_id',
									'inline'          => false,
									'required'        => false,
									'options'         => $resumesOptions,
									'value'           => $selectedResume,
									'hint'            => trans('global.Select a Resume'),
									'wrapper'         => ['id' => 'resumeId'],
								])
								{{--
								<div class="mb-2">
									<label class="control-label" for="file_path">{{ trans('global.Resume') }} </label>
									<div class="form-text text-muted">{!! trans('global.Select a Resume') !!}</div>
									<div id="resumeId" class="mb-2">
										@php
											$selectedResume = 0;
										@endphp
										@if (!empty($resumes) && $totalResumes > 0)
											@foreach ($resumes as $iResume)
												@php
													$iResume = $iResume ?? [];
													$iResumeId = data_get($iResume, 'id');
													$selectedResume = (old('resume_id', 0) == $iResumeId)
														? $iResumeId
														: (!empty($lastResume) ? data_get($lastResume, 'id') : 0);
												@endphp
												<div class="form-check pt-2">
													<input id="resumeId{{ $iResumeId }}"
													       name="resume_id"
														   value="{{ $iResumeId }}"
														   type="radio"
														   class="form-check-input{{ $resumeIdError }}" @checked($selectedResume == $iResumeId)
													>
													<label class="form-check-label" for="resumeId{{ $iResumeId }}">
														{{ data_get($iResume, 'name') }} -
														<a href="{{ privateFileUrl(data_get($iResume, 'file_path')) }}" target="_blank">
															{{ trans('global.Download') }}
														</a>
													</label>
												</div>
											@endforeach
										@endif
										<div class="form-check pt-2">
											<input id="resumeId0"
												   name="resume_id"
												   value="0"
												   type="radio"
												   class="form-check-input{{ $resumeIdError }}" @checked($selectedResume == 0)
											>
											<label class="form-check-label" for="resumeId0">
												{{ '[+] ' . trans('global.New Resume') }}
											</label>
										</div>
									</div>
								</div>
								--}}
								
								<div class="mb-3 col-md-12">
									@include('front.account.resume._form', ['originForm' => 'message'])
								</div>
								
								{{-- captcha --}}
								@include('helpers.forms.fields.captcha', ['label' => trans('auth.captcha_human_verification')])
							</div>
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary float-end">{{ trans('global.send_message') }}</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('global.Cancel') }}</button>
				</div>
			</div>
		</div>
	</div>
</form>
@section('after_scripts')
    @parent
	
	<script>
		@if (auth()->check())
			phoneCountry = '{{ old('phone_country', ($phoneCountryValue ?? '')) }}';
		@endif
		
		{{-- Resume --}}
		@php
			$lastResumeId = data_get($lastResume, 'id', 0);
			$lastResumeId = old('resume_id', $lastResumeId);
			$lastResumeId = !empty($lastResumeId) ? (int)$lastResumeId : 0;
		@endphp
		let lastResumeId = {{ $lastResumeId }};
		
		onDocumentReady((event) => {
			{{-- Re-open the modal if error occured --}}
			@if (isset($errors) && $errors->any())
				@if ($errors->any() && old('messageForm') == '1')
					const applyJobEl = document.getElementById('applyJob');
					if (applyJobEl) {
						const applyJobModal = new bootstrap.Modal(applyJobEl, {});
						applyJobModal.show();
					}
				@endif
			@endif
			
			{{-- Resume --}}
			getResume(lastResumeId);
			const resumeIdInputEls = document.querySelectorAll('#resumeId input');
			resumeIdInputEls.forEach((input) => {
				input.addEventListener('click', (event) => getResume(event.target.value));
				input.addEventListener('change', (event) => getResume(event.target.value));
			});
		});
	</script>
@endsection
