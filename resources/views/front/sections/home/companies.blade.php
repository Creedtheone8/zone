@php
	$sectionOptions = $companiesOptions ?? [];
	
	$fullHeight = $sectionOptions['full_height'] ?? '0';
	$isFullHeightEnabled = ($fullHeight == '1');
	$style = $isFullHeightEnabled ? 'height: 100vh; min-height: 100dvh;' : '';
	
	$htmlAttr = $sectionOptions['html_attributes'] ?? '';
	$htmlAttr = !empty($htmlAttr) ? " $htmlAttr" : '';
	
	$cssClasses = $sectionOptions['css_classes'] ?? '';
	$cssClasses = !empty($cssClasses) ? " {$cssClasses}" : '';
	
	$sectionData ??= [];
	$featuredCompanies = (array)($sectionData['featuredCompanies'] ?? []);
	$companies = (array)($featuredCompanies['companies'] ?? []);
@endphp

@if (!empty($featuredCompanies))
	@if (!empty($companies))
		<div class="container{{ $cssClasses }} d-flex align-items-center" style="{!! $style !!}">
			<div class="card w-100"{!! $htmlAttr !!}>
				<div class="card-header border-bottom-0">
					<h4 class="mb-0 float-start fw-lighter">
						{!! data_get($featuredCompanies, 'title') !!}
					</h4>
					<h5 class="mb-0 float-end mt-1 fs-6 fw-lighter text-uppercase">
						<a href="{{ data_get($featuredCompanies, 'link') }}" class="{{ linkClass() }}">
							{{ trans('global.View more') }} <i class="fa-solid fa-bars"></i>
						</a>
					</h5>
				</div>
				
				<div class="card-body rounded py-0">
					<div class="row row-cols-lg-6 row-cols-md-4 row-cols-sm-3 row-cols-2 py-1 px-0 company-list">
						@foreach($companies as $key => $iCompany)
							@php
								$companyId = data_get($iCompany, 'id');
								$logoUrl = data_get($iCompany, 'logo_url.medium');
								$logoStyle = 'max-width: 80px; max-height: 60px; width: auto; height: 60px;';
								$companyName = data_get($iCompany, 'name');
								$listingsCount = data_get($iCompany, 'posts_count') ?? 0;
							@endphp
							<div class="col px-0 d-flex justify-content-center align-content-stretch">
								<div class="text-center w-100 border rounded px-3 py-4 m-1">
									<a href="{{ urlGen()->company($companyId) }}" class="{{ linkClass() }}">
										<img src="{{ $logoUrl }}" class="img-fluid img-thumbnail" alt="{{ $companyName }}" style="{!! $logoStyle !!}">
										<div class="mt-2 small">
											<span class="text-body-emphasis">{{ trans('global.Jobs at') }}</span>
											<span>{{ $companyName }}</span>
											<span class="text-secondary">({{ $listingsCount }})</span>
										</div>
									</a>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	@endif
@endif
