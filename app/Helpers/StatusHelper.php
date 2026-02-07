<?php

if (!function_exists('translatePaymentStatus')) {
    /**
     * Traduce el estado de pago al español
     */
    function translatePaymentStatus(string $status): string
    {
        $statusLabels = [
            'paid' => 'Pagado',
            'pending' => 'Pendiente',
            'partially_paid' => 'Pago Parcial',
            'completed' => 'Completado',
            'partial' => 'Pago Parcial',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
            'failed' => 'Fallido',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
        ];

        return $statusLabels[$status] ?? ucfirst($status);
    }
}

if (!function_exists('translatePaymentStatusUppercase')) {
    /**
     * Traduce el estado de pago al español en mayúsculas
     */
    function translatePaymentStatusUppercase(string $status): string
    {
        return strtoupper(translatePaymentStatus($status));
    }
}

if (!function_exists('translateOrderStatus')) {
    /**
     * Traduce el estado de orden al español
     */
    function translateOrderStatus(string $status): string
    {
        $statusLabels = [
            'paid' => 'Pagado',
            'pending' => 'Pendiente',
            'partially_paid' => 'Pago Parcial',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'completed' => 'Completado',
        ];

        return $statusLabels[$status] ?? ucfirst($status);
    }
}
