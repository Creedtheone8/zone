@section('modal_location')
	@include('front.layouts.partials.modal.location')
@endsection

<div class="col-12" id="companyFields">
	<div class="row">
		{{-- name --}}
		@include('helpers.forms.fields.text', [
			'label'       => trans('global.company_name'),
			'id'          => 'companyName',
			'name'        => 'company[name]',
			'placeholder' => trans('global.company_name'),
			'required'    => true,
			'value'       => null,
		])
		
		{{-- logo_path --}}
		@include('helpers.forms.fields.fileinput', [
			'label' => trans('global.Logo'),
			'id'    => 'logoPath',
			'name'  => 'company[logo_path]',
			'pluginOptions' => [
				'previewFileType' => 'image',
				'showPreview'     => 'true',
				'dropZoneEnabled' => 'false',
				'showUpload'      => 'false',
				'showRemove'      => 'false',
			],
			'hint' => trans('global.file_types', ['file_types' => getAllowedFileFormatsHint('image')]),
		])
		
		{{-- description --}}
		@php
			$coDescHint = trans('global.Describe the company');
			$coDescHint .= ' - (' . trans('global.N characters maximum', ['number' => 1000]) . ')';
		@endphp
		@include('helpers.forms.fields.textarea', [
			'label'       => trans('global.Company Description'),
			'id'          => 'companyDescription',
			'name'        => 'company[description]',
			'placeholder' => trans('global.Company Description'),
			'required'    => true,
			'value'       => null,
			'attributes'    => ['rows' => 10],
			'pluginOptions' => ['height' => 200],
			'hint'          => $coDescHint,
		])
	</div>
</div>
