<form name="sendByEmailForm" action="{{ url('send-by-email') }}" method="POST" role="form">
	@csrf
	<div class="modal fade" id="sendByEmail" tabindex="-1" aria-labelledby="sendByEmailLabel" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				
				<div class="modal-header px-3">
					<h4 class="modal-title fs-5 fw-bold" id="sendByEmailLabel">
						<i class="fa-regular fa-flag"></i> {{ trans('global.Send by Email') }}
					</h4>
					
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ trans('global.Close') }}"></button>
				</div>
				
				<div class="modal-body">
					@if (isset($errors) && $errors->any() && old('sendByEmailFormSubmitted')=='1')
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ trans('global.Close') }}"></button>
							<ul class="mb-0 list-unstyled">
								@foreach($errors->all() as $error)
									<li class="lh-lg"><i class="bi bi-check-lg me-1"></i>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					{{-- sender_email --}}
					@if (auth()->check() && isset(auth()->user()->email))
						<input type="hidden" name="sender_email" value="{{ auth()->user()->email }}">
					@else
						@php
							$senderEmailError = (isset($errors) && $errors->has('sender_email')) ? ' is-invalid' : '';
						@endphp
						<div class="form-group required mb-3">
							<label for="sender_email" class="control-label">{{ trans('global.Your Email') }} <sup>*</sup></label>
							<div class="input-group">
								<span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
								<input name="sender_email"
								       type="text"
								       data-valid-type="email"
								       maxlength="60"
								       class="form-control{{ $senderEmailError }}"
								       value="{{ old('sender_email') }}"
								>
							</div>
						</div>
					@endif
					
					{{-- recipient_email --}}
					@php
						$recipientEmailError = (isset($errors) && $errors->has('recipient_email')) ? ' is-invalid' : '';
					@endphp
					<div class="form-group required mb-3">
						<label for="recipient_email" class="control-label">{{ trans('global.Recipient Email') }} <sup>*</sup></label>
						<div class="input-group">
							<span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
							<input name="recipient_email"
							       type="text"
							       data-valid-type="email"
							       maxlength="60"
							       class="form-control{{ $recipientEmailError }}"
							       value="{{ old('recipient_email') }}"
							>
						</div>
					</div>
					
					<input type="hidden" name="post_id" value="{{ old('post_id') }}">
					<input type="hidden" name="sendByEmailFormSubmitted" value="1">
				</div>
				
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">{{ trans('global.Send') }}</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('global.Cancel') }}</button>
				</div>
			</div>
		</div>
	</div>
</form>
