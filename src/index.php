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
if ($extension) {
    try {
        $versions = php_get_fetch($src . '/' . $extension . '/', '/releases/' . $extension);
        if (count($versions) === 0) {
            die('Invalid extension or no version available.');
        }

        // var_dump($versions);
        $filtered = array_filter($versions, function ($k) {
            return $k !== "logs" && strpos($k, "rc") === false;
        },ARRAY_FILTER_USE_KEY);

        var_dump($filtered);
    } catch (Exception $exception)  {
        die($exception->getMessage());
    }
} else {
    die("Please specify a valid extension.");
}
// {extension}-{package.version}-{php.version}-{thread.safe}-{visual.c++}-{architecture}.zip