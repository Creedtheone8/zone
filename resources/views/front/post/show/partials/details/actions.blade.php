@php
	$linkClass = linkClass();
@endphp
@if (
	!empty(data_get($post, 'company'))
	|| !empty($user)
	|| ((empty($authUserId) || ($authUserId != data_get($post, 'user_id'))) && isVerifiedPost($post))
)
	<div class="col-12 mt-4 posts-action">
		<h5 class="fs-5 fw-bold">
			{{ trans('global.actions') }}
		</h5>
		<ul class="list-unstyled mb-0 lh-lg">
			@if (!empty(data_get($post, 'company')))
				<li>
					<a href="{{ urlGen()->company(data_get($post, 'company.id')) }}" class="{{ $linkClass }}">
						<i class="fa-regular fa-building"></i> {{ trans('global.More jobs by company', ['company' => data_get($post, 'company.name')]) }}
					</a>
				</li>
			@endif
			
			@if (!empty($user))
				<li>
					<a href="{{ urlGen()->user($user) }}" class="{{ $linkClass }}">
						<i class="bi bi-person-rolodex"></i> {{ trans('global.More jobs by user', ['user' => data_get($user, 'name')]) }}
					</a>
				</li>
			@endif
			
			@if (empty($authUserId) || ($authUserId != data_get($post, 'user_id')))
				@if (isVerifiedPost($post))
					@php
						$postId = data_get($post, 'id');
						$savedByLoggedUser = (bool)data_get($post, 'p_saved_by_logged_user');
					@endphp
					<li id="{{ $postId }}">
						<a class="make-favorite {{ $linkClass }}" href="">
							@if (!empty($authUser))
								@if ($isJobseekerUser)
									@if ($savedByLoggedUser)
										<i class="bi bi-heart-fill"></i> {{ trans('global.Saved Job') }}
									@else
										<i class="bi bi-heart"></i> {{ trans('global.Save Job') }}
									@endif
								@endif
							@else
								<i class="bi bi-heart"></i> {{ trans('global.Save Job') }}
							@endif
						</a>
					</li>
					<li>
						<a href="{{ urlGen()->reportPost($post) }}" class="{{ $linkClass }}">
							<i class="fa-regular fa-flag"></i> {{ trans('global.Report abuse') }}
						</a>
					</li>
				@endif
			@endif
		</ul>
	</div>
@endif
