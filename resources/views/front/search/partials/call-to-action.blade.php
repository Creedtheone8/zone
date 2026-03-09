@if (!auth()->check())
	@php
		$candidateRegistrationUrl = urlBuilder(urlGen()->signUp())->setParameters(['type' => 2])->toString();
		// $startNowUrl = !doesGuestHaveAbilityToCreateListings() ? urlGen()->signInModal() : urlGen()->addPost();
	@endphp
	<div class="container mb-4">
		<div class="card bg-body-tertiary border text-secondary p-3">
			<div class="card-body text-center">
				<h3 class="fs-3 fw-bold">
					{{ trans('global.Looking for a job') }}
				</h3>
				<h5 class="fs-5 mb-4">
					{{ trans('global.Upload your Resume and easily apply to jobs from any device') }}
				</h5>
				<a href="{{ $candidateRegistrationUrl }}" class="btn btn-primary px-3">
					<i class="fa-solid fa-paperclip"></i> {{ trans('global.Add Your Resume') }}
				</a>
			</div>
		</div>
	</div>
@endif
