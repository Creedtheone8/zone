<div class="col-12">
	<div class="card">
		<div class="card-header">
			<h5 class="card-title mb-0">
				{{ trans('global.Account Details') }}
			</h5>
		</div>
		<div class="card-body">
			<div class="row d-flex justify-content-center">
				<div class="col-xl-7 col-lg-8 col-md-10 col-sm-12">
					<form name="details" action="{{ urlGen()->accountProfile() }}" method="POST" role="form">
						@csrf
						@method('PUT')
						
						<div class="row">
							@if (empty($authUser->user_type_id) || $authUser->user_type_id == 0)
								
								{{-- user_type_id --}}
								@include('helpers.forms.fields.select2', [
									'label'           => trans('global.you_are_a'),
									'id'              => 'userTypeId',
									'name'            => 'user_type_id',
									'required'        => true,
									'placeholder'     => trans('global.Select'),
									'options'         => $userTypes ?? [],
									'optionValueName' => 'id',
									'optionTextName'  => 'label',
									'value'           => $authUser->user_type_id ?? null,
								])
							
							@else
								
								{{-- gender_id --}}
								@include('helpers.forms.fields.select2', [
									'label'           => trans('global.gender'),
									'id'              => 'genderId',
									'name'            => 'gender_id',
									'required'        => false,
									'placeholder'     => trans('global.Select'),
									'options'         => $genders ?? [],
									'optionValueName' => 'id',
									'optionTextName'  => 'title',
									'value'           => $authUser->gender_id ?? null,
								])
								
								{{-- name --}}
								@include('helpers.forms.fields.text', [
									'label'       => trans('global.Name'),
									'name'        => 'name',
									'placeholder' => trans('global.enter_your_name'),
									'required'    => true,
									'value'       => $authUser->name ?? null,
								])
								
								{{-- username --}}
								@include('helpers.forms.fields.text', [
									'label'       => trans('auth.username'),
									'name'        => 'username',
									'placeholder' => trans('auth.username'),
									'required'    => true,
									'value'       => $authUser->username ?? null,
									'prefix'      => '<i class="fa-regular fa-user"></i>',
								])
								
								{{-- auth_field (as notification channel) --}}
								@php
									$authFields = getAuthFields(true);
									$authFields = collect($authFields)
										->map(fn($item, $key) => ['value' => $key, 'text' => $item])
										->toArray();
									
									$usersCanChooseNotifyChannel = isUsersCanChooseNotifyChannel(true);
									$authFieldValue = $authUser->auth_field ?? getAuthField();
									$authFieldValue = ($usersCanChooseNotifyChannel) ? old('auth_field', $authFieldValue) : $authFieldValue;
								@endphp
								@if ($usersCanChooseNotifyChannel)
									@include('helpers.forms.fields.radio', [
										'label'    => trans('auth.notifications_channel'),
										'id'       => 'authField-',
										'name'     => 'auth_field',
										'inline'   => true,
										'required' => true,
										'options'  => $authFields,
										'value'    => $authFieldValue,
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
								@include('helpers.forms.fields.email', [
									'label'       => trans('auth.email'),
									'id'          => 'email',
									'name'        => 'email',
									'required'    => (getAuthField() == 'email'),
									'placeholder' => trans('global.enter_your_email'),
									'value'       => $authUser->email ?? null,
									'prefix'      => '<i class="fa-regular fa-envelope"></i>',
									'suffix'      => null,
									'wrapper'     => ['class' => "auth-field-item{$forceToDisplay}"],
								])
								
								{{-- phone --}}
								@php
									$phoneError = (isset($errors) && $errors->has('phone')) ? ' is-invalid' : '';
									$phoneValue = $authUser->phone ?? null;
									$phoneCountryValue = $authUser->phone_country ?? config('country.code');
									
									// phone_hidden
									$phoneHiddenValue = old('phone_hidden', $authUser->phone_hidden ?? null);
									$phoneHiddenChecked = ($phoneHiddenValue == '1') ? ' checked' : '';
									$suffix = '<input id="phoneHidden" name="phone_hidden" type="checkbox" value="1"' . $phoneHiddenChecked . '>';
									$suffix .= '&nbsp;<small>' . trans('global.Hide') . '</small>';
								@endphp
								@include('helpers.forms.fields.intl-tel-input', [
									'label'       => trans('auth.phone_number'),
									'id'          => 'phone',
									'name'        => 'phone',
									'required'    => (getAuthField() == 'phone'),
									'placeholder' => null,
									'value'       => $phoneValue,
									'countryCode' => $phoneCountryValue,
									'suffix'      => $suffix,
									'wrapper'     => ['class' => "auth-field-item{$forceToDisplay}"],
								])
								
								{{-- country_code --}}
								<input name="country_code" type="hidden" value="{{ $authUser->country_code ?? null }}">
							
							@endif
							
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

@section('after_scripts')
	@parent
	<script>
		phoneCountry = '{{ old('phone_country', ($phoneCountryValue ?? '')) }}';
	</script>
@endsection
