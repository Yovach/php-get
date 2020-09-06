<?php

require_once 'downloader.php';
require_once 'helpers.php';

set_error_handler(
    function ($severity, $message, $file, $line) {
        throw new ErrorException($message, $severity, $severity, $file, $line);
    }
);

$src = 'https://windows.php.net/downloads/pecl/releases';

php_get_download($src);

$architecture = php_get_architecture(); // architecture (x64, x86)
$php_version = substr(PHP_VERSION, 0, 3); // php version (example: 7.0)
$ts = PHP_ZTS ? 'ts' : 'nts'; // thread safe
$vc = 'vc15';
$php_piece = "{$php_version}-{$ts}-{$vc}-{$architecture}";

$extension = isset($argv[1]) ? $argv[1] : null;
$ext_path = isset($argv[2]) ? $argv[2] : null;
if ($extension) {
    try {
        $versions = [];
        try {
            $versions = php_get_fetch($src . '/' . $extension . '/', '/releases/' . $extension);
        } catch (Exception $extension) {
            die('Invalid extension specified.');
        }

        if (count($versions) === 0) {
            die('Invalid extension or no version available.');
        }

        $latest_version = array_key_last($versions);

        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: php-get"
            ]
        ];

        $filtered = array_filter($versions, function ($k) {
            return $k !== "logs" && strpos($k, "rc") === false;
        }, ARRAY_FILTER_USE_KEY);

        if (!is_dir('tmp')) {
            mkdir('tmp');
        }

        $content = php_get_content($src . "/$extension/$latest_version/php_$extension-$latest_version-$php_version-$ts-vc15-$architecture.zip");

        log_info('Downloading extension..');
        file_put_contents("tmp/$extension.zip", $content);
        $zip_obj = new ZipArchive();
        $zip_obj->open("tmp/$extension.zip");
        $zip_obj->extractTo('ext/' . $extension);

        clean_non_dll("ext/{$extension}/", true);

        log_info("The \"$extension\" extension has been downloaded.");
        log_info('');
        log_info("Now follow these steps :");
        // echo "Directory 'ext/{$extension}' cleaned." . PHP_EOL;

        log_info("1. Copy the .dll file located in the \"ext/$extension\" folder at the root of the project to the \"ext\" folder located in the PHP extensions folder.");
        log_info("2. Please add \"extension=$extension\" at the end of php.ini");

        $dll_file = get_ext_dll($extension);
    } catch (Exception $exception)  {
        die($exception->getMessage());
    }
} else {
    die("Please specify a valid extension.");
}
// {extension}-{package.version}-{php.version}-{thread.safe}-{visual.c++}-{architecture}.zip