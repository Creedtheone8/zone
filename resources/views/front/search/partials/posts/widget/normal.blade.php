@php
	$widget ??= [];
	$posts = (array)data_get($widget, 'posts');
	$totalPosts = (int)data_get($widget, 'totalPosts', 0);
	
	$sectionOptions ??= [];
	
	$fullHeight = $sectionOptions['full_height'] ?? '0';
	$isFullHeightEnabled = ($fullHeight == '1');
	$style = $isFullHeightEnabled ? 'height: 100vh; min-height: 100dvh;' : '';
	
	$htmlAttr = $sectionOptions['html_attributes'] ?? '';
	$htmlAttr = !empty($htmlAttr) ? " {$htmlAttr}" : '';
	
	$cssClasses = $sectionOptions['css_classes'] ?? '';
	$cssClasses = !empty($cssClasses) ? " {$cssClasses}" : '';
@endphp
@if ($totalPosts > 0)
	<div class="container{{ $cssClasses }}" style="{!! $style !!}">
		<div class="card"{!! $htmlAttr !!}>
			<div class="card-header border-bottom-0">
				<h4 class="mb-0 float-start fw-lighter">
					{!! data_get($widget, 'title') !!}
				</h4>
				<h5 class="mb-0 float-end mt-1 fs-6 fw-lighter text-uppercase">
					<a href="{{ data_get($widget, 'link') }}" class="{{ linkClass() }}">
						{{ trans('global.View more') }} <i class="fa-solid fa-bars"></i>
					</a>
				</h5>
			</div>
			
			<div class="card-body rounded py-0">
				<div class="container">
					@include('front.search.partials.posts.template.list')
				</div>
				
				@if (data_get($sectionOptions, 'show_view_more_btn') == '1')
					<div class="container border-top pt-3 mt-0 mb-3 text-center">
						<a href="{{ urlGen()->searchWithoutQuery() }}" class="{{ linkClass() }} text-uppercase">
							<i class="bi bi-box-arrow-in-right"></i> {{ trans('global.View all jobs') }}
						</a>
					</div>
				@endif
			</div>
		</div>
	</div>
@endif

@section('modal_location')
	@parent
	@include('front.layouts.partials.modal.send-by-email')
@endsection

@section('after_scripts')
    @parent
@endsection
