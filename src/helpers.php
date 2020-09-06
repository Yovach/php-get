<?php

if (!function_exists('php_get_architecture')) {
    function php_get_architecture()
    {
        $architecture = -1;
        switch (PHP_INT_SIZE) {
            case 4:
                $architecture = 86;
                break;
            case 8:
                $architecture = 64;
                break;
        }
        return "x{$architecture}";
    }
}

if (!function_exists('php_get_content')) {
    function php_get_content($url)
    {
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: php-get'
            ]
        ];

        $context = stream_context_create($opts);
        return file_get_contents($url, false, $context);
    }
}

if (!function_exists('clean_non_dll')) {
    function clean_non_dll($directory, $ignore = false)
    {
        $files = glob("{$directory}/*");

        foreach ($files as $file) {
            if (is_dir($file)) {
                clean_non_dll($file);
            } elseif (strpos(basename($file), '.dll') === false) {
                unlink($file);
            }
        }
        if (!$ignore) {
            rmdir($directory);
        }
    }
}

if (!function_exists('get_ext_dll')) {
    function get_ext_dll($extension)
    {
        $files = glob("ext/{$extension}/*");

        $extension_file = null;
        for ($i = 0; $i < count($files) && !$extension_file; $i++) {
            $file = $files[$i];
            if (strpos(basename($file), '.dll') !== false) {
                $extension_file = $file;
            }
        }
        return $extension_file;
    }
}

if (!function_exists('log_info')) {
    function log_info($message)
    {
        echo $message;
        echo PHP_EOL;
    }
}
