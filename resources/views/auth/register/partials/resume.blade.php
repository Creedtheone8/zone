<div class="col-12" id="resumeFields">
	<div class="row">
		
		{{-- file_path --}}
		@include('helpers.forms.fields.fileinput', [
			'label' => trans('global.your_resume'),
			'id'    => 'resumeFilePath',
			'name'  => 'resume[file_path]',
			'hint'  => trans('global.file_types', ['file_types' => getAllowedFileFormatsHint()]),
		])
		
	</div>
</div>
