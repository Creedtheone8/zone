<?php

namespace Database\Seeders;

use App\Helpers\Common\NestedSetSeeder;
use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
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
				'code'                  => 'en',
				'locale'                => $this->getUtf8Locale('en_US'),
				'name'                  => 'English',
				'native'                => 'English',
				'flag'                  => 'flag-icon-gb',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
				'default'               => '1', // Set to true (1) only for this entry
			],
			[
				'code'                  => 'fr',
				'locale'                => $this->getUtf8Locale('fr_FR'),
				'name'                  => 'French',
				'native'                => 'Français',
				'flag'                  => 'flag-icon-fr',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'Do MMM YYYY',
				'datetime_format'       => 'Do MMM YYYY [à] H[h]mm',
			],
			[
				'code'                  => 'es',
				'locale'                => $this->getUtf8Locale('es_ES'),
				'name'                  => 'Spanish',
				'native'                => 'Español',
				'flag'                  => 'flag-icon-es',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D [de] MMMM [de] YYYY',
				'datetime_format'       => 'D [de] MMMM [de] YYYY HH:mm',
			],
			[
				'code'                  => 'ar',
				'locale'                => $this->getUtf8Locale('ar_SA'),
				'name'                  => 'Arabic',
				'native'                => 'العربية',
				'flag'                  => 'flag-icon-sa',
				'script'                => 'Arab',
				'direction'             => 'rtl',
				'russian_pluralization' => '0',
				'date_format'           => 'DD/MMMM/YYYY',
				'datetime_format'       => 'DD/MMMM/YYYY HH:mm',
			],
			[
				'code'                  => 'de',
				'locale'                => $this->getUtf8Locale('de_DE'),
				'name'                  => 'German',
				'native'                => 'Deutsch',
				'flag'                  => 'flag-icon-de',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'dddd, D. MMMM YYYY',
				'datetime_format'       => 'dddd, D. MMMM YYYY HH:mm',
			],
			[
				'code'                  => 'it',
				'locale'                => $this->getUtf8Locale('it_IT'),
				'name'                  => 'Italian',
				'native'                => 'Italiano',
				'flag'                  => 'flag-icon-it',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY HH:mm',
			],
			[
				'code'                  => 'ru',
				'locale'                => $this->getUtf8Locale('ru_RU'),
				'name'                  => 'Russian',
				'native'                => 'Русский',
				'flag'                  => 'flag-icon-ru',
				'script'                => 'Cyrl',
				'direction'             => 'ltr',
				'russian_pluralization' => '1',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY [ г.] H:mm',
			],
			[
				'code'                  => 'nl',
				'locale'                => $this->getUtf8Locale('nl_NL'),
				'name'                  => 'Dutch',
				'native'                => 'Nederlands',
				'flag'                  => 'flag-icon-nl',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'dddd, D. MMMM YYYY',
				'datetime_format'       => 'dddd, D. MMMM YYYY HH:mm',
			],
			[
				'code'                  => 'nb',
				'locale'                => $this->getUtf8Locale('nb_NO'),
				'name'                  => 'Norwegian Bokmål',
				'native'                => 'Norsk Bokmål',
				'flag'                  => 'flag-icon-no',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'uk',
				'locale'                => $this->getUtf8Locale('uk_UA'),
				'name'                  => 'Ukrainian',
				'native'                => 'українська',
				'flag'                  => 'flag-icon-ua',
				'script'                => 'Cyrl',
				'direction'             => 'ltr',
				'russian_pluralization' => '1',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY [ г.] H:mm',
			],
			[
				'code'                  => 'pl',
				'locale'                => $this->getUtf8Locale('pl_PL'),
				'name'                  => 'Polish',
				'native'                => 'Polski',
				'flag'                  => 'flag-icon-pl',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'ro',
				'locale'                => $this->getUtf8Locale('ro_RO'),
				'name'                  => 'Romanian',
				'native'                => 'Română',
				'flag'                  => 'flag-icon-ro',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D MMMM YYYY',
				'datetime_format'       => 'D MMMM YYYY H:mm',
			],
			[
				'code'                  => 'el',
				'locale'                => $this->getUtf8Locale('el_GR'),
				'name'                  => 'Greek',
				'native'                => 'ελληνικά',
				'flag'                  => 'flag-icon-gr',
				'script'                => 'Grek',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'pt',
				'locale'                => $this->getUtf8Locale('pt_PT'),
				'name'                  => 'Portuguese',
				'native'                => 'Português',
				'flag'                  => 'flag-icon-pt',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'D [de] MMMM [de] YYYY',
				'datetime_format'       => 'D [de] MMMM [de] YYYY HH:mm',
			],
			[
				'code'                  => 'da',
				'locale'                => $this->getUtf8Locale('da_DK'),
				'name'                  => 'Danish',
				'native'                => 'Dansk',
				'flag'                  => 'flag-icon-dk',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'sv',
				'locale'                => $this->getUtf8Locale('sv_SE'),
				'name'                  => 'Swedish',
				'native'                => 'Svenska',
				'flag'                  => 'flag-icon-se',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'fi',
				'locale'                => $this->getUtf8Locale('fi_FI'),
				'name'                  => 'Finnish',
				'native'                => 'Suomi',
				'flag'                  => 'flag-icon-fi',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'hu',
				'locale'                => $this->getUtf8Locale('hu_HU'),
				'name'                  => 'Hungarian',
				'native'                => 'Magyar',
				'flag'                  => 'flag-icon-hu',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'sr',
				'locale'                => $this->getUtf8Locale('sr_RS'),
				'name'                  => 'Serbian',
				'native'                => 'српски',
				'flag'                  => 'flag-icon-rs',
				'script'                => 'Cyrl',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'cs',
				'locale'                => $this->getUtf8Locale('cs_CZ'),
				'name'                  => 'Czech',
				'native'                => 'čeština',
				'flag'                  => 'flag-icon-cz',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'bg',
				'locale'                => $this->getUtf8Locale('bg_BG'),
				'name'                  => 'Bulgarian',
				'native'                => 'български',
				'flag'                  => 'flag-icon-bg',
				'script'                => 'Cyrl',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'hr',
				'locale'                => $this->getUtf8Locale('hr_HR'),
				'name'                  => 'Croatian',
				'native'                => 'Hrvatski',
				'flag'                  => 'flag-icon-hr',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'et',
				'locale'                => $this->getUtf8Locale('et_EE'),
				'name'                  => 'Estonian',
				'native'                => 'Eesti',
				'flag'                  => 'flag-icon-ee',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'lt',
				'locale'                => $this->getUtf8Locale('lt_LT'),
				'name'                  => 'Lithuanian',
				'native'                => 'Lietuvių',
				'flag'                  => 'flag-icon-lt',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'lv',
				'locale'                => $this->getUtf8Locale('lv_LV'),
				'name'                  => 'Latvian',
				'native'                => 'Latviešu',
				'flag'                  => 'flag-icon-lv',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'sk',
				'locale'                => $this->getUtf8Locale('sk_SK'),
				'name'                  => 'Slovak',
				'native'                => 'Slovenský',
				'flag'                  => 'flag-icon-sk',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'sl',
				'locale'                => $this->getUtf8Locale('sl_SI'),
				'name'                  => 'Slovenian',
				'native'                => 'Slovenski',
				'flag'                  => 'flag-icon-si',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'is',
				'locale'                => $this->getUtf8Locale('is_IS'),
				'name'                  => 'Icelandic',
				'native'                => 'íslenska',
				'flag'                  => 'flag-icon-is',
				'script'                => 'Latn',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
			[
				'code'                  => 'sq',
				'locale'                => $this->getUtf8Locale('sq_AL'),
				'name'                  => 'Albanian',
				'native'                => 'Shqip',
				'flag'                  => 'flag-icon-al',
				'script'                => 'Aghb',
				'direction'             => 'ltr',
				'russian_pluralization' => '0',
				'date_format'           => 'MMM Do, YYYY',
				'datetime_format'       => 'MMM Do, YYYY [at] HH:mm',
			],
		];
		
		// Add or update columns
		$timezone = config('app.timezone', 'UTC');
		$entries = collect($entries)
			->map(function ($item) use ($timezone) {
				$item['default'] = $item['default'] ?? 0;
				$item['active'] = 1;
				
				$item['parent_id'] = null;
				$item['lft'] = 0;
				$item['rgt'] = 0;
				$item['depth'] = 0;
				
				$item['deleted_at'] = null;
				$item['created_at'] = now($timezone)->format('Y-m-d H:i:s');
				$item['updated_at'] = null;
				
				return $item;
			})->toArray();
		
		$tableName = (new Language())->getTable();
		
		$startPosition = NestedSetSeeder::getNextRgtValue($tableName);
		NestedSetSeeder::insertEntries($tableName, $entries, $startPosition);
	}
	
	/**
	 * @param string $locale
	 * @return string
	 */
	private function getUtf8Locale(string $locale): string
	{
		// Limit the use of this method only for locales which often produce malfunctions
		// when they don't have their UTF-8 format. e.g. the Turkish language (tr_TR).
		$localesToFix = ['tr_TR'];
		if (!in_array($locale, $localesToFix)) {
			return $locale;
		}
		
		$localesList = getLocales('installed');
		
		// Return the given locale, if installed locales list cannot be retrieved from the server
		if (empty($localesList)) {
			return $locale;
		}
		
		// Return given locale, if the database charset is not utf-8
		$dbCharset = config('database.connections.' . config('database.default') . '.charset');
		if (!str_starts_with($dbCharset, 'utf8')) {
			return $locale;
		}
		
		$utf8LocaleFound = false;
		
		$codesetList = ['UTF-8', 'utf8'];
		foreach ($codesetList as $codeset) {
			$tmpLocale = $locale . '.' . $codeset;
			if (in_array($tmpLocale, $localesList, true)) {
				$locale = $tmpLocale;
				$utf8LocaleFound = true;
				break;
			}
		}
		
		if (!$utf8LocaleFound) {
			$codesetList = ['utf-8', 'UTF8'];
			foreach ($codesetList as $codeset) {
				$tmpLocale = $locale . '.' . $codeset;
				if (in_array($tmpLocale, $localesList, true)) {
					$locale = $tmpLocale;
					break;
				}
			}
		}
		
		return $locale;
	}
}
