<?php
/**
 * Common functions class
 *
 * @package		Classes
 * @subpackage	Common
 * @author		Grzegorz Miskiewicz <biuro@avatec.pl>
 * @license		Property license
 *
 * <p>Ten produkt jest licencjonowany
 * Możesz modyfikować te pliki jednak nie możesz usuwać oryginalnych komentarzy
 * w szczególności informacji o autorze tego oprogramowania</p>
 *
 * <p>W przypadku indywidualnego modyfikowania tego pliku autor nie ponosi
 * odpowiedzialności za wszelkie błędy i/lub wady tego oprogramowania.</p>
 */

class Common
{

	public static function get_ip()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }

	protected static $MonthsNames = [
		1 => [ "Styczeń", "Sty" ],
		2 => [ "Luty", "Lut"],
		3 => [ "Marzec", "Mar" ],
		4 => [ "Kwiecień", "Kwi" ],
		5 => [ "Maj", "Maj" ],
		6 => [ "Czerwiec", "Cze" ],
		7 => [ "Lipiec", "Lip" ],
		8 => [ "Sierpień", "Sie" ],
		9 => [ "Wrzesień", "Wrz" ],
		10 => [ "Październik", "Paź" ],
		11 => [ "Listopad", "Lis" ],
		12 => [ "Grudzień", "Gru" ]
	];

	public static function getMonthName( $number = null, $short_level = 0 )
	{
		if( is_null( $number )) {
			foreach(self::$MonthsNames as $mn => $ma) {
				$Select[] = [
					'id' => $mn,
					'name' => $ma[$short_level]
				];
			}

			if(!empty($Select)) {
				return $Select;
			}
		} else {
			foreach(self::$MonthsNames as $mn => $ma) {

				if( $mn == $number ) {
					return $ma[$short_level];
				}
			}
		}
	}

	public static function getMonthNumber( $month )
	{
		if( !empty( $month )) {
			foreach(self::$MonthsNames as $mn => $ma) {
				$month_check = Kernel::rewrite( $ma[0] );
				if( $month_check == $month ) {
					$mn = (($mn<=9) ? '0' . $mn : $mn);
					return $mn;
				}
			}
		}
	}

	protected static $WeeksNames = [
		1 => [ "Poniedziałek", "Pon", "Pn" ],
		2 => [ "Wtorek", "Wto" , "Wt" ],
		3 => [ "Środa", "Śro" , "Śr" ],
		4 => [ "Czwartek", "Czw" , "Cz" ],
		5 => [ "Piątek", "Pią" , "Pt" ],
		6 => [ "Sobota", "Sob" , "So" ],
		7 => [ "Niedziela", "Nie" , "Nd" ]
	];

	public static function getWeekName( $number = null, $short_level = 0 )
	{
		if( is_null( $number )) {
			foreach(self::$WeeksNames as $mn => $ma) {
				$Select[] = [
					'id' => $mn,
					'name' => $ma[$short_level]
				];
			}

			if(!empty($Select)) {
				return $Select;
			}
		} else {
			foreach(self::$WeeksNames as $mn => $ma) {

				if( $mn == $number ) {
					return $ma[$short_level];
				}
			}
		}
	}

	public static function dateAsText( $date )
	{
		$ParsedDate = [
			'year' 	=> date('Y' , strtotime( $date )),
			'month' => date('m' , strtotime( $date )),
			'day' 	=> date('j' , strtotime( $date ))
		];

		return $ParsedDate['day'] . ' ' . self::getMonthName( $ParsedDate['month'] ) . ' ' . $ParsedDate['year'];
	}

	public static function random_string( $limit = 5 )
	{
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle(str_repeat($pool, $limit)), 0, $limit);
	}

	public static function getIconByMime( $mime )
	{
		switch( $mime )
		{
			case "application/pdf": // PDF
				return 'fa-file-pdf-o';
			break;

			case "application/zip": // ZIP
				return 'fa-file-archive-o';
			break;

			case "application/msword":
				return 'fa-file-word-o';
			break;

			case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
				return 'fa-file-word-o';
			break;

			case "application/vnd.oasis.opendocument.text":
				return 'fa-file-word-o';
			break;

			case "application/vnd.oasis.opendocument.spreadsheet":
				return 'fa-file-excel-o';
			break;

			case "application/vnd.ms-office":
				return 'fa-file-excel-o';
			break;

			case "application/octet-stream":
				return 'fa-file-excel-o';
			break;

			default:
				return 'fa-file-o';
			break;
		}
	}

	public static function tooltip($text)
	{
		return 'data-toggle="tooltip" data-title="' . $text . '" data-container="body"';
	}

}
