<?php

if (!function_exists('FormatAmount')) {
    function formatAmount($amount)
    {
        return number_format($amount, 0,"",".");
    }
}
