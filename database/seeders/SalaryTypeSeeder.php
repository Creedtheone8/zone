<?php

namespace Database\Seeders;

use App\Helpers\Common\NestedSetSeeder;
use App\Models\SalaryType;
use Illuminate\Database\Seeder;

class SalaryTypeSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$entries = [
			[
				'name'   => [
					'en' => 'hour',
					'fr' => 'heure',
					'es' => 'hora',
					'ar' => 'ساعة',
					'de' => 'stunde',
					'it' => 'ora',
					'ru' => 'час',
					'nl' => 'uur',
					'nb' => 'time',
					'uk' => 'година',
					'pl' => 'godzina',
					'ro' => 'oră',
					'el' => 'ώρα',
					'pt' => 'hora',
					'da' => 'time',
					'sv' => 'timme',
					'fi' => 'tunti',
					'hu' => 'óra',
					'sr' => 'сат',
					'cs' => 'hodina',
					'bg' => 'час',
					'hr' => 'sat',
					'et' => 'tund',
					'lt' => 'valanda',
					'lv' => 'stunda',
					'sk' => 'hodina',
					'sl' => 'ura',
					'is' => 'klukkustund',
					'sq' => 'orë',
				],
			],
			[
				'name'   => [
					'en' => 'day',
					'fr' => 'jour',
					'es' => 'día',
					'ar' => 'يوم',
					'de' => 'tag',
					'it' => 'giorno',
					'ru' => 'день',
					'nl' => 'dag',
					'nb' => 'dag',
					'uk' => 'день',
					'pl' => 'dzień',
					'ro' => 'zi',
					'el' => 'ημέρα',
					'pt' => 'dia',
					'da' => 'dag',
					'sv' => 'dag',
					'fi' => 'päivä',
					'hu' => 'nap',
					'sr' => 'дан',
					'cs' => 'den',
					'bg' => 'ден',
					'hr' => 'dan',
					'et' => 'päev',
					'lt' => 'diena',
					'lv' => 'diena',
					'sk' => 'deň',
					'sl' => 'dan',
					'is' => 'dagur',
					'sq' => 'ditë',
				],
			],
			[
				'name'   => [
					'en' => 'month',
					'fr' => 'mois',
					'es' => 'mes',
					'ar' => 'شهر',
					'de' => 'monat',
					'it' => 'mese',
					'ru' => 'месяц',
					'nl' => 'maand',
					'nb' => 'måned',
					'uk' => 'місяць',
					'pl' => 'miesiąc',
					'ro' => 'lună',
					'el' => 'μήνας',
					'pt' => 'mês',
					'da' => 'måned',
					'sv' => 'månad',
					'fi' => 'kuukausi',
					'hu' => 'hónap',
					'sr' => 'месец',
					'cs' => 'měsíc',
					'bg' => 'месец',
					'hr' => 'mjesec',
					'et' => 'kuu',
					'lt' => 'mėnuo',
					'lv' => 'mēnesis',
					'sk' => 'mesiac',
					'sl' => 'mesec',
					'is' => 'mánuður',
					'sq' => 'muaj',
				],
			],
			[
				'name'   => [
					'en' => 'year',
					'fr' => 'année',
					'es' => 'año',
					'ar' => 'عام',
					'de' => 'jahr',
					'it' => 'anno',
					'ru' => 'год',
					'nl' => 'jaar',
					'nb' => 'år',
					'uk' => 'рік',
					'pl' => 'rok',
					'ro' => 'an',
					'el' => 'έτος',
					'pt' => 'ano',
					'da' => 'år',
					'sv' => 'år',
					'fi' => 'vuosi',
					'hu' => 'év',
					'sr' => 'година',
					'cs' => 'rok',
					'bg' => 'година',
					'hr' => 'godina',
					'et' => 'aasta',
					'lt' => 'metai',
					'lv' => 'gads',
					'sk' => 'rok',
					'sl' => 'leto',
					'is' => 'ár',
					'sq' => 'vit',
				],
			],
		];
		
		// Add or update columns
		$entries = collect($entries)
			->map(function ($item) {
				$item['parent_id'] = null; // Doesn't exist as DB column
				$item['lft'] = 0;
				$item['rgt'] = 0;
				$item['depth'] = 0;
				
				$item['active'] = 1;
				
				return $item;
			})->toArray();
		
		$tableName = (new SalaryType())->getTable();
		
		$startPosition = NestedSetSeeder::getNextRgtValue($tableName);
		NestedSetSeeder::insertEntries($tableName, $entries, $startPosition);
	}
}
