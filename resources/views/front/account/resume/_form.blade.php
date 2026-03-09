@php
	/* Get form origin */
	$originForm ??= null;
	
	$resume ??= [];
	
	$filePath = $resume['file_path'] ?? null;
	$fileData = [
		'key'  => 0,
		'path' => $filePath,
		'url'  => privateFileUrl($filePath),
	];
@endphp
<div class="col-12" id="resumeFields">
	<div class="row">
		@if ($originForm != 'message')
			@if (!empty($resume))
				{{-- name --}}
				@include('helpers.forms.fields.text', [
					'label'       => trans('global.Name'),
					'name'        => 'resume[name]',
					'placeholder' => trans('global.Name'),
					'required'    => true,
					'value'       => $resume['name'] ?? null,
				])
			@endif
			
			{{-- file_path --}}
			@include('helpers.forms.fields.fileinput', [
				'label'        => trans('global.your_resume'),
				'id'           => 'resumeFilePath',
				'name'         => 'resume[file_path]',
				'value'        => $fileData,
				'downloadable' => true,
				'diskName'     => 'private',
				'hint'         => trans('global.file_types', ['file_types' => getAllowedFileFormatsHint()]),
			])
		@else
			{{-- file_path --}}
			@include('helpers.forms.fields.fileinput', [
				'label'        => trans('global.resume_file'),
				'id'           => 'resumeFilePath',
				'name'         => 'resume[file_path]',
				'value'        => $fileData,
				'downloadable' => true,
				'diskName'     => 'private',
				'hint'         => trans('global.file_types', ['file_types' => getAllowedFileFormatsHint()]),
			])
		@endif
	</div>
</div>
