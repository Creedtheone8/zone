@php
	$authUser = auth()->check() ? auth()->user() : null;
	$authUserId = !empty($authUser) ? $authUser->getAuthIdentifier() : 0;
	$isJobseekerUser = doesUserHaveJobseekerPermission($authUser);
	
	$post ??= [];
@endphp
<div class="items-details">
	<div class="row">
		<div class="col-12 mt-4">
			<div class="items-details-info jobs-details-info enable-long-words from-wysiwyg">
				<h5 class="fs-5 fw-bold">{{ trans('global.listing_details') }}</h5>
				
				{{-- Description --}}
				<div class="px-2">
					{!! data_get($post, 'description') !!}
				</div>
				
				@if (!empty(data_get($post, 'company_description')))
					{{-- Company Description --}}
					<h5 class="title-3 fw-bold mt-4">{{ trans('global.Company Description') }}</h5>
					<div class="px-2">
						{!! data_get($post, 'company_description') !!}
					</div>
				@endif
			</div>
		</div>
		
		@include('front.post.show.partials.details.summary')
		@include('front.post.show.partials.details.actions')
		
		<div class="col-12 mt-4">
			{{-- Tags --}}
			@if (!empty(data_get($post, 'tags')))
				<div class="row">
					<div class="col-12">
						<h5 class="fs-5 fw-bold">{{ trans('global.Tags') }}</h5>
						@foreach(data_get($post, 'tags') as $iTag)
							<span class="d-inline-block border border-inverse bg-body-tertiary rounded-1 py-1 px-2 my-1 me-1">
								<a href="{{ urlGen()->tag($iTag) }}" class="{{ linkClass() }}">
									{{ $iTag }}
								</a>
							</span>
						@endforeach
					</div>
				</div>
			@endif
		</div>
		
		<div class="col-12 mt-4 border-top pt-3">
			<div class="hstack gap-3 text-start">
				@if (!empty($authUser))
					@if ($authUserId == data_get($post, 'user_id'))
						<a class="btn btn-outline-primary" href="{{ urlGen()->editPost($post) }}">
							<i class="fa-regular fa-pen-to-square"></i> {{ trans('global.Edit') }}
						</a>
					@else
						@if ($isJobseekerUser)
							{!! genEmailContactBtn($post) !!}
						@endif
					@endif
				@else
					{!! genEmailContactBtn($post) !!}
				@endif
				{!! genPhoneNumberBtn($post) !!}
				&nbsp;<small>{{-- or. Send your CV to: foo@bar.com --}}</small>
			</div>
		</div>
	
	</div>
</div>
