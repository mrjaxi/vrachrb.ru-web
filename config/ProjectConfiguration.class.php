<?php
if (!function_exists('hex2bin')) {
    function hex2bin($data)
    {
        static $old;
        if ($old === null) {
            $old = version_compare(PHP_VERSION, '5.2', '<');
        }
        $isobj = false;
        if (is_scalar($data) || (($isobj = is_object($data)) && method_exists($data, '__toString'))) {
            if ($isobj && $old) {
                ob_start();
                echo $data;
                $data = ob_get_clean();
            } else {
                $data = (string)$data;
            }
        } else {
            trigger_error(__FUNCTION__ . '() expects parameter 1 to be string, ' . gettype($data) . ' given', E_USER_WARNING);
            return;
        }
        $len = strlen($data);
        if ($len % 2) {
            trigger_error(__FUNCTION__ . '(): Hexadecimal input string must have an even length', E_USER_WARNING);
            return false;
        }
        if (strspn($data, '0123456789abcdefABCDEF') != $len) {
            trigger_error(__FUNCTION__ . '(): Input string must be hexadecimal string', E_USER_WARNING);
            return false;
        }
        return pack('H*', $data);
    }
}
require_once __DIR__ . '/../vendor/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
    public function setup()
    {
        $this->enablePlugins('sfDoctrinePlugin');
        $this->enablePlugins('sfImageTransformPlugin');
        $this->enablePlugins('sfCaptchaGDPlugin');
        $this->enablePlugins('doAuthPlugin');
        $this->enablePlugins('sfRUtilsPlugin');
        $this->enablePlugins('sfSphinxPlugin');
        $this->enablePlugins('csSettingsPlugin');
    }

}


class ProjectUtils
{
    public static function base64url_encode($data)
    {
        return exec("echo -n '" . $data . "' | openssl md5 -binary | openssl base64 | tr +/ -_ | tr -d =");
    }

    public static function nohup($file)
    {
        return exec('nohup ' . $file . ' > /dev/null 2>&1 &');
    }

    public static function nohup_task($task)
    {
        return ProjectUtils::nohup(sfConfig::get('sf_root_dir') . '/symfony ' . $task);
    }

    public static function getDiagnosticCount()
    {
        $protocols = Doctrine_Query::create()
            ->select("COUNT(p.id) AS dcount")
            ->from("Protocol p")
            ->where("p.protocol_date > NOW()")
            ->fetchOne();
        return $protocols->getDcount();
    }

    public static function getColorDate($date, $period_type, $check = false)
    {
        $date_str = '';
        $color = '#000000';
        $cfg_key = 'none';
        if ((!$check && $period_type == 'inner') || ($check && ($period_type == 'tc_once' || $period_type == 'tc'))) {
            return $date_str;
        }
        if ($date == '') {
            $date_str = '<span style="font-size:11px">не&nbsp;пройден</span>';
        } elseif ($date == '2970-01-01') {
            $cfg_key = 'good';
            $date_str = '<span style="font-size:11px">не&nbsp;требуется</span>';
        } else {
            $diff = floor((strtotime($date) - time()) / 3600 / 24);
            $date_str = implode('.', array_reverse(explode('-', $date)));
            if ($diff > 0 && $diff <= 30) {
                $cfg_key = 'warn';
                $date_str .= '<br /><span class="small_gray">через&nbsp;' . $diff . '&nbsp;' . Page::niceRusEnds($diff, 'день', 'дня', 'дней') . '</span>';
            } elseif ($diff > 30) {
                $cfg_key = 'good';
            } elseif ($diff <= 0) {
                $cfg_key = 'bad';
            }
        }
        $cfg = sfConfig::get('app_course_' . $cfg_key);
        return '<span style="color:' . $cfg['color'] . '">' . $date_str . '</span>';
    }


    public static function post($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $r = curl_exec($ch);
        curl_close($ch);
        file_put_contents('/www/vrach.log', print_r($r, true), FILE_APPEND);
        return $r;
    }

    public static function pushPost($param)
    {
        $param['title'] = Notice::noticeType($param['type']);
        $data = '{"to" : "' . $param['token'] . '","notification" : {"body" : "' . $param['body'] . '","title" : "' . $param['title'] . '"},"data" : {"title" : "Беседа","url" : "' . sfConfig::get('app_push_url') . '/conversation' . ($param['profile'] == 's' ? '_doctor' : '') . '/' . $param['id'] . '?clear=true"}}';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json", "Authorization:key=" . sfConfig::get('app_push_key')));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $r = curl_exec($ch);
        curl_close($ch);

        file_put_contents(sfConfig::get('sf_log_dir') . '/push_post_log.txt', time() . ' ' . $data . ' ' . $r . "\n", FILE_APPEND);

        return $r;
    }

    public static function wsPub($ch, $data)
    {
        if (array_key_exists('HTTP_HOST', $_SERVER)) {
            if ($_SERVER['HTTP_HOST'] != '' && $_SERVER['HTTP_HOST'] != 'null') {
                ProjectUtils::post('http://' . $_SERVER['HTTP_HOST'] . '/lp-pub/?id=' . $ch, json_encode($data));
            }
        }
    }

}


class ProjectUlils
{
    public static function post($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

    public static function wsPub($ch, $data)
    {
        if (array_key_exists('HTTP_HOST', $_SERVER)) {
            if ($_SERVER['HTTP_HOST'] != '' && $_SERVER['HTTP_HOST'] != 'null') {
                ProjectUlils::post('https://' . $_SERVER['HTTP_HOST'] . '/ws_pub?id=' . $ch, json_encode($data));
            }
        }
    }

    public static function parseXlsx($filename)
    {
        $tmpdir = sfConfig::get('sf_data_dir') . '/tmp/' . time();
        mkdir($tmpdir);
        exec('unzip ' . $filename . ' -d ' . $tmpdir);
        $sharedStringsArr = array();
        if (file_exists($tmpdir . '/xl/sharedStrings.xml')) {
            $xml = simplexml_load_file($tmpdir . '/xl/sharedStrings.xml');
            foreach ($xml->children() as $item) {
                $sharedStringsArr[] = trim((string)$item->t);
            }
        }
        $sheets = glob($tmpdir . '/xl/worksheets/*.xml');
        $i = 0;
        $out = array();
        foreach ($sheets as $sheet) {
            $xml = simplexml_load_file($sheet);
            $file = basename($sheet);
            $row = 0;
            foreach ($xml->sheetData->row as $item) {
                $out[$file][$row] = array();
                $cell = 0;
                foreach ($item as $child) {
                    $attr = $child->attributes();
                    $value = isset($child->v) ? (string)$child->v : false;
                    $out[$file][$row][$cell] = trim(isset($attr['t']) ? $sharedStringsArr[$value] : $value);
                    $cell++;
                }
                $row++;
                $i++;
            }
        }
        return $out;
    }

    public static function generateUuid()
    {
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        $uuid = substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
        return $uuid;
    }

    public static function arrayKeysFilter($items = array(), $key = 'id')
    {
        $ret = array();
        foreach ($items as $item) {
            $ret[] = $item[$key];
        }
        return $ret;
    }

    public static function cubeRecreate()
    {
        exec('nohup ' . sfConfig::get('sf_root_dir') . '/symfony cube:recreate > /dev/null 2>&1 &');

        file_put_contents(sfConfig::get('sf_log_dir') . '/nohup-' . time(), 'nohup ' . sfConfig::get('sf_root_dir') . '/symfony cube:recreate > /dev/null 2>&1 &');
    }
}
