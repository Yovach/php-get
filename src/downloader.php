<?php

if (!function_exists('php_get_download')) {
    function php_get_download()
    {
        if (file_exists('packages.json')) {
            unlink('packages.json');
        }

        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: php-get"
            ]
        ];

        $context = stream_context_create($opts);
        $content = file_get_contents('https://windows.php.net/downloads/pecl/releases/', false, $context);

        $dom = new DOMDocument();
        $dom->loadHTML($content);

        $links = $dom->getElementsByTagName('a');

        $packages = [];

        foreach ($links as $link) {
            if (isset($link->attributes[0]) && $link->attributes[0]->name === "href") {
                $href = $link->attributes[0];
                /** @var string $folder */
                $folder = $href->textContent;

                if (strpos($folder, "/releases/") !== false) {
                    $packages[$link->textContent] = $folder;
                }
                // var_dump($link->textContent);
            }
        }

        $fp = fopen('packages.json', 'w');
        fwrite($fp, json_encode($packages));
        fclose($fp);
    }
}