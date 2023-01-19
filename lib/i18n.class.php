<?php

Class i18n
{

    protected static $i18n_seek_debug = false;
    protected static $curlang = 'en';
    protected static $url = '';
    protected static $debug = false;
    protected static $seekLog = array();
    protected static $list = array();
    protected static $langlist = array(
        'en' => 'English',
        'ru' => 'Russian'
    );
    protected $types = array(
        'id' => 'int',
        'key' => 'string',
        'value' => 'string',
        'lang' => 'string',
        'path' => 'string',
    );

    public function table()
    {
        return "i18n";
    }

    public function with()
    {
        return array();
    }

    public static function init($lang = 'en', $debug = false)
    {
        if (!in_array($lang, static::getSupportedLanguages())) {
            $lang = 'ru';
        }

        static::$curlang = $lang;
        static::$debug = $debug;
        static::$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        static::getTranslates();
    }

    public static function getLangList($lang = '')
    {

        if (!$lang)
            return static::$langlist;
        else
            return static::$langlist[$lang];
    }

    public static function getSupportedLanguages()
    {
        return array_keys(static::$langlist);
    }

    public static function getCurrentLang()
    {

        return static::$curlang;
    }

    public static function getTranslates()
    {

        if (!count(static::$list)) {
            if (!is_dir("./langs"))
                mkdir("./langs");

            if (!is_file("./langs/" . static::$curlang . ".lang")) {
                touch("./langs/" . static::$curlang . ".lang");
                file_put_contents("./langs/" . static::$curlang . ".lang", "key,value\n");
            }

            $data = csv_to_array("./langs/" . static::$curlang . ".lang");
            foreach ($data as $v) {
                if ($v['key'] && $v['value'])
                    static::$list[$v['key']] = $v['value'];
            }
        }

        return static::$list;
    }

    public static function translate($key, $debug = false)
    {
        $value = static::$list[trim($key)];

        if (static::$debug)
            if (mb_strlen(trim($key), 'UTF-8') > 1024)
                throw new Exception("key '$key' is too big for i18n");

        if (!$value) {
            if (static::$i18n_seek_debug)
                static::$seekLog[] = array('key' => $key, 'value' => $value, 'lang' => static::getCurrentLang());
            static::$list[trim($key)] = trim($key);
            i18n::saveList();
            return $key;
        }

        return $value;
    }

    public static function getSeekLog()
    {
        return static::$seekLog;
    }

    public static function saveList()
    {
        $f = [];
        $list = static::$list;
        foreach ($list as $k => $v) {
            $f[] = [
                'key' => $k,
                'value' => $v
            ];
        }

        file_put_contents("./langs/" . static::$curlang . ".lang", array_to_csv($f));
    }

}
