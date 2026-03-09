@php
	// Clear Filter Button
	$clearFilterBtn = urlGen()->getTypeFilterClearLink($cat ?? null, $city ?? null);
	
	$inputPostType = [];
	if (request()->filled('type')) {
		$types = request()->query('type');
		if (is_array($types)) {
			foreach ($types as $type) {
				$inputPostType[] = $type;
			}
		} else {
			$inputPostType[] = $types;
		}
	}
@endphp
{{-- PostType --}}
<div class="container p-0 vstack gap-2">
	<h5 class="border-bottom pb-2 d-flex justify-content-between">
		<span class="fw-bold">
			{{ trans('global.Job Type') }}
		</span> {!! $clearFilterBtn !!}
	</h5>
	<ul class="mb-0 list-unstyled ps-1" id="blocPostType">
		@if (!empty($postTypes))
			@foreach($postTypes as $key => $postType)
				<li class="form-check form-switch">
					<input type="checkbox"
					       name="type[{{ $key }}]"
					       id="employment-{{ data_get($postType, 'id') }}"
					       value="{{ data_get($postType, 'id') }}"
					       class="form-check-input emp emp-type"{{ (in_array(data_get($postType, 'id'),  $inputPostType)) ? ' checked="checked"' : '' }}
					>
					<label class="form-check-label" for="employment-{{ data_get($postType, 'id') }}">
						{{ data_get($postType, 'name') }}
					</label>
				</li>
			@endforeach
		@endif
		<input type="hidden"
		       id="postTypeQueryString"
		       name="postTypeQueryString"
		       value="{{ \App\Helpers\Common\Arr::query(request()->except(['page', 'type'])) }}"
		>
	</ul>
</div>

@section('after_scripts')
	@parent
	{{-- Check out the JS code at: "../sidebar.blade.php" --}}
@endsection
