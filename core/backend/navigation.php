<?php namespace Core\Backend;

use \Modules\Admins;

/**
 *  Obsługa menu w panelu administracyjnym
 *  Copyright 2019 Grzegorz Miśkiewicz
 *  @package Avatec Framework
 */

class Navigation
{
    public static $menu;

/**
 *  Tworzenie menu poziomu zerowego
 *  @param $lp (int) - liczba porządkowa
 *  @param $module (string) - nazwa modułu, bez polskich znaków i znaków specjalnych
 *  @param $name (string) - wyświetlana nazwa
 *  @param $path (string) - ścieżka bez przedrostka admin
 *  @param $icon (string) - kod ikony fantastic awesome icons np. fa-gears
 */

	public static function menu( $lp, $module, $name, $path = null, $icon = null )
	{
		self::$menu[ $module ] = [
			'priority' => $lp,
			'name' => $name,
			'path' => $path,
			'icon' => (!empty($icon) ? $icon : null)
		];
	}

/**
 *  Tworzenie menu podporządkowanego poziomu pierwszego
 *  @param $module (string) - nazwa modułu zgodna z menu zerowego poziomu, bez polskich znaków i znaków specjalnych
 *  @param $name (string) - wyświetlana nazwa
 *  @param $path (string) - ścieżka bez przedrostka admin
 */

	public static function submenu( $module, $name, $path )
	{
		self::$menu[ $module ]['submenu'][] = [
			'name' => $name,
			'path' => $path
		];
	}

/**
 *  Generuje linię oddzielającą
 *  @param $lp (int) - liczba porządkowa
 */

    public static function line( $lp )
    {
        self::$menu['line_' . $lp] = [
            'priority' => $lp,
            'line' => true
        ];
    }

/**
 *  Generuje nagłówek z linia oddzielającą
 *  @param $lp (int) - liczba porządkowa
 *  @param $name (string) - wyświetlana nazwa
 */

    public static function label( $lp, $name )
	{
		self::$menu[ 'label_' . $lp ] = [
			'priority' => $lp,
			'name' => $name,
			'label' => true
		];
	}

/**
 *  Zwraca wygenerowany kod HTML menu
 *  @return (string)
 */
	public static function get()
	{
		if(empty(self::$menu)) {
			return;
		}

		foreach( self::$menu as $k=>$i ) {
			self::$menu[$k]['access'] = $k;
		}

		$menu = self::$menu;

		usort(self::$menu, function($a,$b) {
            if(!empty($a['priority'])) {
                return $a['priority']-$b['priority'];
            }
		});

		global $app_admin_url;

		if(!is_null(Admins::$auth['access'])) {
			$user_access = explode(";" , Admins::$auth['access']);
		} else {
			$user_access[] = "";
		}

		$html[] = '<ul class="main">';
		foreach( self::$menu as $k=>$i )
		{
			if((in_array($i['access'], $user_access) == true) OR ($user_access[0] == '')) {
                if( !empty($i['label'])) {
                    $html[] = '<h3 class="menu-separator">' . $i['name'] . '</h3>';
                } elseif(!empty($i['line'])) {
                    $html[] = '<div class="menu-separator"></div>';
                } else {
    				$html[] = '<li rel="' . $k . '">';
    				$html[] = '<a class="' . (!empty($i['submenu']) ? 'has_sub' : '') . '" href="' . (!empty($i['path']) ? $app_admin_url . $i['path'] : '#') . '">';
    				$html[] = (!empty($i['icon']) ? '<i class="fa ' . $i['icon'].'"></i>' : '') . ' <span>'.$i['name'].'</span></a>';
    				if(!empty($i['submenu'])) {
    					$html[] = '<ul>';
    					foreach( $i['submenu'] as $si ) {
    						$html[] = '<li rel="' . $k . '"><a href="' . $app_admin_url . $si['path'] . '"><span>'.$si['name'].'</span></a></li>';
    					}
    					$html[] = '</ul>';
    				}
    				$html[] = '</li>';
                }
			}
		}
		$html[] = '</ul>';

		self::$menu = $menu;

		return implode($html);
	}
}
