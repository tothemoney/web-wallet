<?php
require_once 'i18n.class.php';
$host = '.tmychain.org';

if ($_SESSION['xlang']) {
    setcookie("hl", $_SESSION['xlang'], time() + 31 * 24 * 60 * 60, '/', '.' . $_SERVER['HTTP_HOST']);
    setcookie("hl", $_SESSION['xlang'], time() + 31 * 24 * 60 * 60, '/');
    $lang = $_SESSION['xlang'];
}

if ($_COOKIE['hl']) {
    $lang = $_COOKIE['hl'];
    $_SESSION['xlang'] = $lang;
}

if ($_GET['lang']) {
    $lang = $_GET['lang'];
    setcookie("hl", $_GET['lang'], time() + 31 * 24 * 60 * 60, '/', '.' . $_SERVER['HTTP_HOST']);
    setcookie("hl", $_GET['lang'], time() + 31 * 24 * 60 * 60, '/');
    $_SESSION['xlang'] = $lang;
}

if ($lang) {
    i18n::init($lang);
} else {
    //TODO:
    /*$lang2 = $lang = $_SESSION['xlang'] ? $_SESSION['xlang'] : strtolower(getCountryByIp($_SERVER['REMOTE_ADDR']));
    setcookie("geo", $lang2, time() + 31 * 24 * 60 * 60, '/');
    if (!in_array($lang, i18n::getSupportedLanguages()))
        $lang = 'en';
    $_SESSION['xlang'] = $lang;
    $_SESSION['country2'] = $lang2;
    */

    i18n::init('en');
}


function setLanguageType($type = 'en')
{
    if (!in_array($type, ['en', 'ru']))
        $type = 'en';

    $_SESSION['lang'] = $type;
    setcookie("lang", $type, time() + 31 * 24 * 60 * 60, "/", $host, false, true);
}

function getLanguageType()
{

    $lang = $_COOKIE['lang'];

    if ($lang && in_array($lang, ['en', 'ru']))
        $_SESSION['lang'] = $lang;

    if ($_SESSION['lang']) {
        setcookie("lang", $lang, time() + 31 * 24 * 60 * 60, "/", $host, false, true);
        return $_SESSION['lang'];
    }

    return 'en';
}

function t($key)
{
    return i18n::translate($key);
}



function array_to_csv($data)
{
    # Generate CSV data from array
    $fh = fopen('php://temp', 'rw'); # don't create a file, attempt
    # to use memory instead

    # write out the headers
    fputcsv($fh, array_keys(current($data)));

    # write out the data
    foreach ($data as $row) {
        fputcsv($fh, $row);
    }
    rewind($fh);
    $csv = stream_get_contents($fh);
    fclose($fh);

    return $csv;
}


function csv_to_array($filename = '', $delimiter = ',')
{
    if (!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
            if (!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}

