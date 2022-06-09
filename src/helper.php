<?php

declare(strict_types=1);

if (!function_exists('round_up')) {
    function round_up($number, $precision = 2)
    {
        $divider = 10 ** $precision;

        return ceil($number * $divider) / $divider;
    }
}
