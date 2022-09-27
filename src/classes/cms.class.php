<?php
use Core\Error;

/**
 * Kernel class
 *
 * @package		Classes
 * @subpackage	Kernel
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

class Kernel
{
    public static $tpl;
    public static $css;
    public static $js;
    public static $token;

    protected static $messages;

/**
 * Przechowywanie informacji o meta tagach
 *
 * @var	string	$meta_title			Przechowuje title strony
 * @var string	$meta_description	Przechodwuje description strony
 * @var string	$meta_keywords		Przechowuje keywords strony
 */

    public static $meta_title;
    public static $meta_description;
    public static $meta_keywords;

    public static $meta_index = false;
    public static $meta_follow = false;

    public static $ModuleName = "Pulpit";
    public static $CkEditor = false;
    public static $Alertify = false;

/**
 * Kernel::$GoogleMaps = true	- Włączenie ładowania plików dla map google
 *
 * @var	boolean	$GoogleMaps		Jeżeli ustawione na true ładuje pliki odpowiedzialne za mapę google
 */

    public static $GoogleMaps = false;

/**
 * Jeżeli ustawione na true checkboxy będą wyglądały tradycyjne
 *
 * @category	Administration Panel
 * @var 		boolean	$CheckBox
 */

    public static $CheckBox = false;

/**
 * Przechowuje adres url pliku graficznego domyślnie wstawianego jako zdjęcie podczas
 * udostępniania strony na facebooku
 * @return string
 */

    public static $facebook_image = null;
    public static $TopButtons = [];

    public function __construct()
    {
    }

    protected static $components;

    protected static function hasComponentID($id, $name)
    {
        if (empty(self::$components[$id]['id'])) {
            return false;
        }

        $key = array_search($id, array_column(self::$components, 'id'));
        if (!empty($key)) {
            Error::show(
                'RegisterComponent Duplicate found #' . $id,
                'You are trying to registerComponent to existing ID in ' . $name . '<br/>but it has been registered in: ' . self::$components[$key]['name']
                );
            exit;
        }
    }

    public static function registerComponent($id, $name, $file)
    {
        if (self::hasComponentID($id, $name) == false) {
            self::$components[$id] = [
                'id' => $id,
                'name' => $name,
                'file' => (is_null($file) ? null : $file)
            ];
            sort(self::$components);
        }
    }

    public static function getComponentID($name)
    {
        $component_id = null;

        if (!empty(self::$components)) {
            foreach (self::$components as $k=>$i) {
                if ($i['file'] == $name) {
                    $component_id = $i['id'];
                }
            }
        }

        return $component_id;
    }

    public static function readComponents($id = null)
    {
        if (!empty(Form::$post['component'])) {
            foreach (self::$components as $k=>$i) {
                if (Form::$post['component'] == $i['file'] || str_replace(";", "", Form::$post['component'] == $i['file'])) {
                    self::$components[$k]['selected'] = true;
                }
            }

            sort(self::$components);
        }

        if (is_null($id)) {
            if (!empty(self::$components)) {
                return self::$components;
            }
        } else {
            foreach (self::$components as $item) {
                if ($item['id'] == $id) {
                    return $item['file'];
                }
            }
        }
    }

    protected static function csscompress($files, $minfile)
    {
        global $app_url, $app_path;

        $buffer = "";
        if (!empty($files) && is_array($files)) {
            foreach ($files as $file) {
                $buffer .= file_get_contents($file);
            }
        } else {
            $buffer = file_get_contents($files);
        }

        // Remove comments
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        // Remove space after colons
        $buffer = str_replace(': ', ':', $buffer);
        // Remove whitespace
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

        file_put_contents($app_path . $minfile, $buffer);
    }
    public static function compress($files, $minfile, $type = 'css')
    {
        global $app_url, $app_path;

        if (file_exists($app_path . $minfile)) {
            $create_time = filectime($app_path . $minfile);
            if (($create_time + 86400) <= time()) {
                unlink($app_path . $minfile);
                self::csscompress($files, $minfile);
            }

            echo($app_url . $minfile);
            exit;
        } else {
            self::csscompress($files, $minfile);
            echo($app_url . $minfile);
        }
    }

    public static function generateIdent($limit = 5)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $limit)), 0, $limit);
    }

    public static function httpPost($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /*
    * Parsowanie pliku changelog.xml w celu wyświetlenia informacji w nim zawartych
    */
    public static function changeLog($limit = null)
    {
        global $app_path, $app_url;
        $num=0;
        if (file_exists($app_path . "changelog.xml")) {
            $content = file_get_contents($app_path . "changelog.xml") . PHP_EOL;
            $xml = simplexml_load_string($content);
            if (!empty($xml)) {
                foreach ($xml as $k=>$i) {
                    $newxml[] = $i;
                    $num++;
                }
                $xml = array_reverse($newxml);
                return $xml;
            }
        }
    }

    /*
     * Odczytywanie ostatniej wersji oprogramowania z pliku changelog.xml
     */

    public static function getVersion()
    {
        $xml = self::changeLog();
        return $xml[0];
    }

    public static function getFullDateName($date)
    {
        $day = date('j', strtotime($date));
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));

        return $day . ' ' . self::getMonthName($month) . ' ' . $year;
    }

    public static function getMonthName($month)
    {
        if ($month == 1) {
            return "Styczeń";
        }
        if ($month == 2) {
            return "Luty";
        }
        if ($month == 3) {
            return "Marzec";
        }
        if ($month == 4) {
            return "Kwiecień";
        }
        if ($month == 5) {
            return "Maj";
        }
        if ($month == 6) {
            return "Czerwiec";
        }
        if ($month == 7) {
            return "Lipiec";
        }
        if ($month == 8) {
            return "Sierpień";
        }
        if ($month == 9) {
            return "Wrzesień";
        }
        if ($month == 10) {
            return "Październik";
        }
        if ($month == 11) {
            return "Listopad";
        }
        if ($month == 12) {
            return "Grudzień";
        }
    }

    public static function array2csv($array)
    {
        if (is_array($array)) {
            foreach ($array as $k=>$i) {
                if (!empty($i)) {
                    if (isset($csv)) {
                        $csv .= $i . ";";
                    } else {
                        $csv = $i . ";";
                    }
                }
            }
            if (isset($csv)) {
                return $csv;
            }
        }
    }

    public static function csv2array($csv, $also_empty = false)
    {
        $csv = explode(";", $csv);
        if (!empty($csv)) {
            foreach ($csv as $i) {
                if ((!empty($i)) || ($also_empty == true)) {
                    $array[] = $i;
                }
            }
            return $array;
        }
    }

    public static function html_decode($string)
    {
        return html_entity_decode(html_entity_decode($string));
    }

    public static function addPath($o = null)
    {
        if (!empty($o)) {
            self::$tpl['path'][] = array(
                'name' => (isset($o['name']) ? $o['name'] : 'brak danych'),
                'url' => (isset($o['url']) ? $o['url'] : false),
                'main' => (isset($o['main']) ? $o['main'] : false)
            );
        }
    }

    public static function viewPath()
    {
        if (!empty(self::$tpl['path'])) {
            $html[] = '<ol class="breadcrumb mb-0 px-0">';
            foreach (self::$tpl['path'] as $key=>$item) {
                if ($item['main'] == true) {
                    $html[] = '<li class="breadcrumb-item active"><a>'.$item['name'].'</a></li>';
                } else {
                    $html[] = '<li class="breadcrumb-item"><a href="'.$item['url'].'">'.$item['name'].'</a></li>';
                }

                unset($classAdd);
            }
            $html[] = '</ol>';
            return implode($html);
        }
    }

    public static function addMeta($title, $description = null, $keywords = null, $index = false, $follow = false)
    {
        self::$meta_title = $title;
        self::$meta_description = (!empty($description) ? $description : $title);
        self::$meta_keywords = (!empty($keywords) ? $keywords : null);
        self::$meta_index = (!empty($index) ? $index : true);
        self::$meta_follow = (!empty($follow) ? $follow : true);
    }

    public static function getMeta()
    {
        if (self::$meta_index == true || self::$meta_index == "TRUE") {
            self::$meta_index = 'index';
        } else {
            self::$meta_index = 'noindex';
        }

        if (self::$meta_follow == true || self::$meta_follow == "TRUE") {
            self::$meta_follow = 'follow';
        } else {
            self::$meta_follow = 'nofollow';
        }

        return array(
            "title" => self::$meta_title,
            "description" => self::$meta_description,
            "keywords" => self::$meta_keywords,
            "robots" => ((self::$meta_index == true) ? "index" : "noindex") . "," . ((self::$meta_follow == true) ? "follow" : "nofollow"),
            "index" => self::$meta_index,
            "follow" => self::$meta_follow
        );
    }

    public static function setMessage($type = "NOTICE", $text = "", $error = null)
    {
        if (is_array($text)) {
            $text = implode("<br/>", $text);
        }
        self::$messages[$type] = $text . (!empty($error) ? '<br/>' . implode('<br/>', $error) : '');
        self::storeMessage();
    }

    public static function clearMessages()
    {
		if( !empty( $_SESSION['admin_message'] )) {
        	unset($_SESSION['admin_message']);
		}
    }

    public static function getMessage($type = "NOTICE")
    {
        self::restoreMessage();
        if (isset(self::$messages[$type])) {
            return self::$messages[$type];
        }
    }

    private static function storeMessage()
    {
        $_SESSION['admin_message'] = self::$messages;
    }

    private static function restoreMessage()
    {
        if (!empty($_SESSION['admin_message'])) {
            self::$messages = $_SESSION['admin_message'];
        }
    }

    public static function module($name)
    {
        self::$tpl['module'] = $name;
    }

    public static function template($file)
    {
        self::$tpl['file'] = $file;
    }

    public static function schema($file)
    {
        self::$tpl['schema'] = $file;
    }

    public static function get_view()
    {
        global $app_path;

        if (!empty(self::$tpl['module']) && !empty(self::$tpl['file'])) {
            return $app_path . 'modules/' . self::$tpl['module'] . '/frontend/views/' .  self::$tpl['file'];
        }
    }

    public static function getTpl()
    {
        if (!empty(self::$tpl)) {
            return self::$tpl;
        }
    }

    public static function getIp()
    {
        if (isset($_SERVER)):
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            } else:
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        endif;
        return $realip;
    }

    public static function rewrite($text)
    {
        $string = strtolower($text);
        $polskie = array(',', ' - ',' ','ę', 'Ę', 'ó', 'Ó', 'Ą', 'ą', 'Ś', 's', 'ł', 'Ł', 'ż', 'Ż', 'Ź', 'ź', 'ć', 'Ć', 'ń', 'Ń','-',"'","/","?", '"', ":", 'ś', '!','.', '&', '&amp;', '#', ';', '[',']','domena.pl', '(', ')', '`', '%', '”', '„', '…');
        $miedzyn = array('-','-','-','e', 'e', 'o', 'o', 'a', 'a', 's', 's', 'l', 'l', 'z', 'z', 'z', 'z', 'c', 'c', 'n', 'n','-',"","","","","",'s','','', '', '', '', '', '', '', '', '', '', '', '', '');
        $string = str_replace($polskie, $miedzyn, $string);

        $string = preg_replace('/[\-]+/', '-', $string);
        $string = trim($string, '-');
        $string = stripslashes($string);
        $string = urlencode($string);

        $encoded = array(
            "%E4%98","%E4%99","%E3%B3","%E3%93","%E4%85","%E4%84",
            "%E5%9B","%E5%9A","%E5%82","%E5%81","%E5%BE","%E5%BB",
            "%E5%BA","%E5%B9","%E4%87","%E4%86","%E5%84","%E5%83"
        );
        $new = array(
            "e","e","o","o","a","a",
            "s","s","l","l","z","z",
            "z","z","c","c","n","n"
        );

        $string = str_replace($encoded, $new, $string);

        return $string;
    }

    public static function callModule($module, $command, $options = null)
    {
        global $app_path;
        $check = explode(".", $module);
        if (count($check) > 1) {
            $module = $check['0'];
            $file = $check['1'];
        }

        $url = $app_path . "modules/" . $module . "/" . (isset($file) ? $file : $module) . ".website.php";
        if (file_exists($url)) {
            /**return [
                "module" => $module,
                "command" => $command,
                "options" => (!empty($options) ? $options : null),
                "file" => $file,
                "url" => $url
            ];**/
        } else {
            throw new Exception('File ' . $url . ' not found');
        }
    }

    public static function createLog($filename, $data, $nl = true)
    {
        global $app_path;
        if ($nl == true) {
            file_put_contents($app_path . "logs/" . $filename, "[".date('Y-m-d H:i:s')."] " . $data . "\r\n", FILE_APPEND);
        } else {
            file_put_contents($app_path . "logs/" . $filename, $data, FILE_APPEND);
        }
    }

    public static function log($filename, $data)
    {
        global $app_path;

        $now = date('Y-m-d');

        if (!is_dir($app_path . "logs/")) {
            @mkdir($app_path . "logs/");
        }

        if (is_dir($app_path . "logs/" . $now . "/") == false) {
            @mkdir($app_path . "logs/" . $now . "/");
        }

        file_put_contents($app_path . "logs/" . $now . '/' . $filename, "[".date('Y-m-d H:i:s')."] " . $data . "\r\n", FILE_APPEND);
    }

    public static function access($module, $account = null)
    {
        global $app_admin_url;

        if (is_null($account)) {
            $account = ['0','1'];
        }

        if ((strstr(Modules\Admins\Backend\Admins::$auth['access'], $module) != true) and (in_array(Modules\Admins\Backend\Admins::$auth['type'], $account) !== true)) {
            Kernel::setMessage("ERROR", "Dostęp zabroniony dla Twojego konta");
            Kernel::redirect($app_admin_url . "start.html");
        } else {
            return true;
        }
    }

    public static function loadClass($file)
    {
        global $app_path;
        if (file_exists($app_path . $file)) {
            include_once $app_path . $file;
        }
    }

    public static function getCss()
    {
        if (isset(self::$css)) {
            return self::$css;
        } else {
            return false;
        }
    }

    public static function setCss($file, $path = false, $admin = false)
    {
        global $app_url;

        if ($path == "outside") {
            self::$css[$file] = $file;
        } elseif ($path == false) {
            self::$css[$file] = $app_url . 'templates/' . ($admin == true ? 'admin/' : 'website/') . 'css/' . $file;
        } else {
            self::$css[$file] = $app_url . 'modules/' . $path . '/css/' . $file;
        }
    }

    public static function getJs()
    {
        if (isset(self::$js)) {
            return self::$js;
        } else {
            return false;
        }
    }

    public static function setJs($file, $path = false, $admin = false)
    {
        global $app_url;

        if ($path == "outside") {
            self::$js[$file] = $file;
        } elseif ($path == false) {
            self::$js[$file] = $app_url . 'templates/' . ($admin == true ? 'admin/' : 'website/') . 'js/' . $file;
        } else {
            self::$js[$file] = $app_url . 'modules/' . $path . '/js/' . $file;
        }
    }

    public static function redirect($link, $code = null)
    {
        if (!is_null($code)) {
            if ($code == 404) {
                header('HTTP/1.1 '.$code.' Not Found');
                header("Refresh:0; url=" . $link);
                exit();
            }
        	header("HTTP/1.1 ".$code." See Other");
		}

        header("Location: ".$link);
        exit;
    }

    public static function generateToken($strenght)
    {
        global $config, $request;

        if (!$request->post) {
            self::$token = md5(uniqid($config['salt'] . "|" . time(), true));
            $_SESSION['token'] = self::$token;
        }

        if (empty($_SESSION['token'])) {
            self::$token = md5(uniqid($config['salt'] . "|" . time(), true));
            $_SESSION['token'] = self::$token;
        } else {
            self::$token = $_SESSION['token'];
        }

        return self::$token;
    }

    public static function real_escape($value)
    {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

        return str_replace($search, $replace, $value);
    }

    public static function wysiwyg($id = 'editor', $num = 1, $type = "basic", $files = true, $width = 700, $height = 385, $bg = "white")
    {
        global $app_url;

        switch ($type) {
            case "basic":
                $toolbar = ",toolbar:[['Styles','JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock','Bold','Italic','Underline', 'SpellChecker','-','NumberedList','BulletedList'],['Link','Unlink'],['Undo', 'Redo','-','SelectAll']]";
            break;

            case "full":
                $toolbar = ",toolbar_Full:[['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ,'Styles','Bold','Italic','Underline', 'SpellChecker','Scayt','-','NumberedList','BulletedList'],['Link','Unlink'],['Undo', 'Redo','-','SelectAll']]";
            break;

            case "mini":
                $toolbar = ",toolbar:[['Styles','Bold','Italic','Underline', 'SpellChecker','Scayt','-','NumberedList','BulletedList'],['Link','Unlink'],['Undo', 'Redo','-','SelectAll']]";
            break;

            case "newsletter":
                $toolbar = ",toolbar:[['Source','Bold','Italic','Underline', 'SpellChecker','Scayt','-','NumberedList','BulletedList'],['Link','Unlink'],['Undo', 'Redo','-','SelectAll']]";
            break;
        }

        $class = '';
        $CountNumber=0;
        while ($CountNumber < $num) {
            $CountNumber++;
            if ($num == 1) {
                $class = "$('#".$id."').ckeditor(config".$id.");".PHP_EOL;
            } else {
                $class .= "$('".$id.$CountNumber."').ckeditor(config);".PHP_EOL;
            }
        }

        if ($files == true) {
            $filemanager = ",filebrowserBrowseUrl:'".$app_url."include/js/ckeditor/filemanager/index.html'";
        }

        $wysiwyg = "<script type=\"text/javascript\">var config".$id." = { entities_latin:false,autogrow: true,linkShowAdvancedTab: false,scayt_autoStartup: false,enterMode: Number(2) ".$filemanager.$toolbar." }; $(document).ready(function() {  ".$class." });</script>";

        echo($wysiwyg);
    }

    public static function currency($amount)
    {
        return str_replace(".", ",", sprintf("%2.2f", $amount));
    }

    public static function getAvatecNews($limit = 3)
    {
        $entrys = array();
        $summary = '';

        if (!preg_match('^http:^', "http://www.avatec.pl/xml/accomodation.xml")) {
            return false;
        } else {
            $feed_uri = "http://www.avatec.pl/xml/accomodation.xml";
        }

        $file = $feed_uri;
        if (!$x = @simplexml_load_file($file)) {
            return;
        }
        $k=0;
        if (!empty($x)) {
            foreach ($x->channel->item as $item) {
                if ($k<($limit)) {
                    $post[$k]['date']  = (string) $item->pubDate;
                    $post[$k]['ts']    = strtotime($item->pubDate);
                    $post[$k]['link']  = (string) $item->link;
                    $post[$k]['title'] = (string) $item->title;
                    $post[$k]['text']  = (string) $item->description;
                    $post[$k]['image'] = (string) $item->image;

                    $summary = preg_replace("/<img[^>]*>/", "", $post[$k]['text']);
                    $summary = preg_replace("/^(<br[ ]?\/>)*/", "", $summary);
                    $summary = preg_replace("/(<br[ ]?\/>)*$/", "", $summary);

                    // Truncate summary line to 100 characters
                    if (strlen($summary) > ($max_len = 100)) {
                        $summary = substr($summary, 0, $max_len) . '...';
                    }
                    $post[$k]['summary'] = $summary;
                    $k++;
                }
            }
            return $post;
        }
    }

	public static function json_encode( $string )
	{
		return json_encode( $string , JSON_UNESCAPED_UNICODE | JSON_ERROR_UTF8);
	}

	public static function SummerNote($id, $o = null)
    {
        if (empty($o['height'])) {
            $o['height'] = 250;
        }
        $html[] = '<script type="text/javascript">$(document).ready(function() {';
        if (!is_array($id)) {
            $html[] = '$("' . $id . '").summernote({height: ' . $o['height'] . ',lang: \'pl-PL\',popover: {image: [' .
                '[\'imagesize\', [\'imageSize100\',\'imageSize50\',\'imageSize25\']],' .
                '[\'float\', [\'floatLeft\',\'floatRight\',\'floatNone\']],[\'remove\', [\'removeMedia\']],[\'custom\', [\'imageTitle\']],' .
                ']},callbacks: { onImageUpload: function( file ) {' .
                    'summernote_uploader( file, "' . $id . '" );},
					onMediaDelete: function(target) { summernote_delete_file(target[0].src); }' .
                '}});';
        }
        $html[] = '});</script>';

        return implode($html);
    }
}
