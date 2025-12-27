<?php

if (!function_exists('currency')) {
    /**
     * Formato de moneda para Colombia (COP)
     */
    function currency($amount, $symbol = '$'): string
    {
        return $symbol . number_format($amount, 2, ',', '.');
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Formato alternativo de moneda
     */
    function formatCurrency($amount): string
    {
        return '$' . number_format($amount, 2);
    }
}
