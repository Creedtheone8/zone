@php
	$keywords = rawurldecode(request()->input('q', request()->input('keyword')));
@endphp
@include('front.common.spacer')
<div class="container">
	<form id="search"
	      name="search"
	      action="{{ urlGen()->companies() }}"
	      method="GET"
	      data-csrf-token="{{ csrf_token() }}"
	>
		<div class="row m-0">
			<div class="col-sm-10 col-12 px-0">
				<input name="q"
				       class="form-control form-control-lg keyword"
				       type="text"
				       placeholder="{{ trans('global.company_name') }}"
				       value="{{ $keywords }}"
				>
			</div>
			
			<div class="col-sm-2 col-12 ps-sm-1 px-0 mt-sm-0 mt-1 d-grid">
				<button class="btn btn-primary btn-lg"><i class="fa-solid fa-magnifying-glass"></i></button>
			</div>
		</div>
	</form>
</div>
