<div class="col-12 mt-4">
	<h5 class="fs-5 fw-bold">
		{{ trans('global.summary') }}
	</h5>
	<div class="card mb-0 border-0">
		<div class="card-body">
			<ul class="list-unstyled mb-0 lh-lg">
				@if (!empty(data_get($post, 'start_date')))
					<li>
						<p class="m-0">
							<strong>{{ trans('global.Start Date') }}:</strong>&nbsp;
							{{ data_get($post, 'start_date') }}
						</p>
					</li>
				@endif
				<li>
					<p class="m-0">
						<strong>{{ trans('global.Company') }}:</strong>&nbsp;
						@if (!empty(data_get($post, 'company_id')))
							<a href="{!! urlGen()->company(data_get($post, 'company_id')) !!}" class="link-primary text-decoration-none">
								{{ data_get($post, 'company_name') }}
							</a>
						@else
							{{ data_get($post, 'company_name') }}
						@endif
					</p>
				</li>
				<li>
					<p class="m-0">
						<strong>{{ trans('global.Salary') }}:</strong>&nbsp;
						@if (data_get($post, 'salary_min') > 0 || data_get($post, 'salary_max') > 0)
							@if (data_get($post, 'salary_min') > 0)
								{!! \App\Helpers\Common\Num::money(data_get($post, 'salary_min')) !!}
							@endif
							@if (data_get($post, 'salary_max') > 0)
								@if (data_get($post, 'salary_min') > 0)
									&nbsp;-&nbsp;
								@endif
								{!! \App\Helpers\Common\Num::money(data_get($post, 'salary_max')) !!}
							@endif
						@else
							{!! \App\Helpers\Common\Num::money('--') !!}
						@endif
						@if (!empty(data_get($post, 'salaryType')))
							{{ trans('global.per') }} {{ data_get($post, 'salaryType.name') }}
						@endif
						
						@if (data_get($post, 'negotiable') == 1)
							&nbsp;<span class="badge rounded-pill text-bg-info">{{ trans('global.negotiable') }}</span>
						@endif
					</p>
				</li>
				<li>
					@if (!empty(data_get($post, 'postType')))
						@php
							$params = [
								'type' => [
									0 => data_get($post, 'postType.id')
								],
							];
							$postTypeSearchUrl = urlBuilder(urlGen()->searchWithoutQuery())
								->setParameters($params)
								->toString();
						@endphp
						<p class="m-0">
							<strong>{{ trans('global.Job Type') }}:</strong>&nbsp;
							<a href="{{ $postTypeSearchUrl }}" class="link-primary text-decoration-none">
								{{ data_get($post, 'postType.name') }}
							</a>
						</p>
					@endif
				</li>
				<li>
					<p class="m-0">
						<strong>{{ trans('global.location') }}:</strong>&nbsp;
						<a href="{!! urlGen()->city(data_get($post, 'city')) !!}" class="link-primary text-decoration-none">
							{{ data_get($post, 'city.name') }}
						</a>
					</p>
				</li>
			</ul>
		</div>
	</div>
</div>
