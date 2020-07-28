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
        $versions = php_get_fetch($src . '/' . $extension . '/', '/releases/' . $extension);
        if (count($versions) === 0) {
            die('Invalid extension or no version available.');
        }

        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: php-get"
            ]
        ];

        $filtered = array_filter($versions, function ($k) {
            return $k !== "logs" && strpos($k, "rc") === false;
        },ARRAY_FILTER_USE_KEY);

        if (!is_dir('tmp')) {
            mkdir('tmp');
        }

        echo 'Downloading extension..' . PHP_EOL;
        file_put_contents("tmp/redis.zip", php_get_content($src . "/redis/5.3.1/php_redis-5.3.1-7.4-ts-vc15-x64.zip"));
        $zip_obj = new ZipArchive();
        $zip_obj->open('tmp/redis.zip');
        $zip_obj->extractTo('ext/' . $extension);

        clean_non_dll("ext/{$extension}/", true);
        echo "Directory 'ext/{$extension}' cleaned." . PHP_EOL;

        $dll_file = get_ext_dll($extension);
        var_dump($dll_file);
    } catch (Exception $exception)  {
        die($exception->getMessage());
    }
} else {
    die("Please specify a valid extension.");
}
// {extension}-{package.version}-{php.version}-{thread.safe}-{visual.c++}-{architecture}.zip