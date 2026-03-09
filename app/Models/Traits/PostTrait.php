<?php
/*
 * JobClass - Job Board Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com/jobclass
 * Author: Mayeul Akpovi (BeDigit - https://bedigit.com)
 *
 * LICENSE
 * -------
 * This software is provided under a license agreement and may only be used or copied
 * in accordance with its terms, including the inclusion of the above copyright notice.
 * As this software is sold exclusively on CodeCanyon,
 * please review the full license details here: https://codecanyon.net/licenses/standard
 */

namespace App\Models\Traits;

use App\Helpers\Common\Date;
use App\Helpers\Common\Date\TimeZoneManager;
use App\Helpers\Common\Files\Storage\StorageDisk;
use App\Http\Controllers\Web\Admin\Panel\Library\Panel;
use App\Models\Post;
use Spatie\Feed\FeedItem;

trait PostTrait
{
	// ===| ADMIN PANEL METHODS |===
	
	public function crudTitleColumn(?Panel $xPanel = null, array $column = []): string
	{
		$out = getPostUrl($this);
		
		if (!empty($this->archived_at)) {
			$out .= '<br>';
			$out .= '<span class="badge bg-secondary">';
			$out .= trans('admin.Archived');
			$out .= '</span>';
		}
		
		return $out;
	}
	
	public function crudLogoColumn(?Panel $xPanel = null, array $column = []): string
	{
		$defaultLogoUrl = thumbParam(config('larapen.media.picture'))->url();
		
		// Get logo
		$logoUrl = $this->logo_url_small ?? $defaultLogoUrl;
		$style = ' style="width:auto; max-height:90px;"';
		$out = '<img src="' . $logoUrl . '" data-bs-toggle="tooltip" title="' . $this->title . '"' . $style . '>';
		
		// Add a link to the listing
		$url = dmUrl($this->country_code ?? null, urlGen()->postPath($this));
		
		return '<a href="' . $url . '" target="_blank">' . $out . '</a>';
	}
	
	public function crudPictureColumn(?Panel $xPanel = null, array $column = []): string
	{
		$defaultPictureUrl = thumbParam(config('larapen.media.picture'))->url();
		
		// Get the first picture
		$pictureUrl = $this->picture_url_small ?? $defaultPictureUrl;
		$style = ' style="width:auto; max-height:90px;"';
		$out = '<img src="' . $pictureUrl . '" data-bs-toggle="tooltip" title="' . $this->title . '"' . $style . ' class="img-rounded">';
		
		// Get ad URL
		$url = dmUrl($this->country_code ?? null, urlGen()->postPath($this));
		
		// Add a link to the listing
		return '<a href="' . $url . '" target="_blank">' . $out . '</a>';
	}
	
	public function crudCompanyNameColumn(?Panel $xPanel = null, array $column = []): string
	{
		$companyName = $this->company_name ?? 'Company Name';
		$userName = $this->contact_name ?? 'Guest';
		
		$out = '';
		
		// Company Name
		$out .= $companyName;
		
		// User Name
		$out .= '<br>';
		$out .= '<small>';
		$out .= trans('admin.By_') . ' ';
		if (!empty($this->user)) {
			$url = urlGen()->adminUrl('users/' . $this->user->getKey() . '/edit');
			$tooltip = ' data-bs-toggle="tooltip" title="' . $this->user->name . '"';
			
			$out .= '<a href="' . $url . '"' . $tooltip . '>';
			$out .= $userName;
			$out .= '</a>';
		} else {
			$out .= $userName;
		}
		$out .= '</small>';
		
		return $out;
	}
	
	public function crudCityColumn(?Panel $xPanel = null, array $column = []): string
	{
		$out = $this->crudCountryColumn();
		$out .= ' - ';
		if (!empty($this->city)) {
			$out .= '<a href="' . urlGen()->city($this->city) . '" target="_blank">' . $this->city->name . '</a>';
		} else {
			$out .= $this->city_id ?? 0;
		}
		
		return $out;
	}
	
	public function crudReviewedColumn(?Panel $xPanel = null, array $column = []): string
	{
		return ajaxCheckboxDisplay($this->{$this->primaryKey}, $this->getTable(), 'reviewed_at', ($this->reviewed_at ?? null));
	}
	
	public function crudFeaturedColumn(?Panel $xPanel = null, array $column = []): string
	{
		$out = '-';
		if (config('addons.offlinepayment.installed')) {
			$opTool = '\extras\addons\offlinepayment\app\Helpers\OpTools';
			if (class_exists($opTool)) {
				$out = $opTool::featuredCheckboxDisplay(
					$this->{$this->primaryKey},
					$this->getTable(),
					'featured',
					($this->featured ?? null)
				);
			}
		}
		
		return $out;
	}
	
	// ===| OTHER METHODS |===
	
	public static function getFeedItems()
	{
		$perPage = (int)config('settings.pagination.per_page', 50);
		
		$countryCode = (config('addons.domainmapping.installed'))
			? config('country.code')
			: request()->input('country');
		
		$cacheParams = [
			'action'      => 'get.listings',
			'reviewed'    => true,
			'unarchived'  => true,
			'country'     => $countryCode,
			'perPage'     => $perPage,
			'orderByDesc' => 'id',
		];
		
		return caching()->remember(Post::class, $cacheParams, function () use ($countryCode, $perPage) {
			$posts = Post::query()
				->reviewed()
				->unarchived()
				->when(!empty($countryCode), fn ($query) => $query->where('country_code', '=', $countryCode))
				->take($perPage)
				->orderByDesc('id');
			
			return $posts->get();
		});
	}
	
	public function toFeedItem(): FeedItem
	{
		$title = $this->title ?? 'Untitled';
		$title .= !empty($this->city) ? ' - ' . $this->city->name : '';
		$title .= !empty($this->country) ? ', ' . $this->country->name : '';
		$summary = multiLinesStringCleaner($this->description ?? 'Description');
		$link = urlGen()->post($this, true);
		
		return FeedItem::create()
			->id($link)
			->title($title)
			->summary($summary)
			->category($this?->category?->name ?? '')
			->updated($this->updated_at ?? Date::format(now(TimeZoneManager::getContextualTimeZone()), 'datetime'))
			->link($link)
			->authorName($this->contact_name ?? 'Unknown author');
	}
	
	public static function getLogo(?string $value)
	{
		if (empty($value)) return $value;
		
		$disk = StorageDisk::getDisk();
		
		// OLD PATH
		$oldBase = 'pictures/';
		$newBase = 'files/';
		if (str_contains($value, $oldBase)) {
			$value = $newBase . last(explode($oldBase, $value));
		}
		
		// NEW PATH
		if (str_ends_with($value, '/')) {
			return $value;
		}
		
		if (!$disk->exists($value)) {
			$value = config('larapen.media.picture');
		}
		
		return $value;
	}
}
