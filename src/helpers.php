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