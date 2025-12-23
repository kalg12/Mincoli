# Módulo de Inventario

Este documento describe el flujo de movimientos, conteos físicos, captura pública y revisión/aplicación de ajustes en el panel de administración.

## Movimientos de Inventario

-   Ubicación: Dashboard → Inventario → Movimientos.
-   Tipos: `in` (entrada), `out` (salida), `adjust` (ajuste directo al valor indicado).
-   Cada movimiento queda registrado con producto/variante, cantidad, motivo y usuario que lo creó.

## Conteos Físicos

-   Ubicación: Dashboard → Inventario → Conteos Físicos.
-   Flujo de estados:
    -   `draft`: creado, sin items cargados.
    -   `in_progress`: iniciado; el sistema congela el stock actual por producto/variante como `system_quantity`.
    -   `completed`: todos los items contados; pendiente revisión/aplicación.
    -   `reviewed`: diferencias aplicadas al stock real mediante movimientos `adjust`.

### Crear e iniciar un conteo

1. Crear un conteo (nombre + notas).
2. Desde el detalle del conteo en estado `draft`, presionar “Iniciar conteo”.
    - Se generan los `items` con `system_quantity` a partir del stock vigente en ese momento.
    - Se habilita la captura pública y se genera un `public_token`.

### Captura interna y pública

-   Interna: requiere sesión; ruta del panel “Capturar (interno)”.
-   Pública: no requiere autenticación; compartir enlace con `public_token`:
    -   Vista: listado con producto/variante, cantidad del sistema, campo editable “contado” y diferencia.
    -   Guardado: por fila, con feedback inmediato de la diferencia.
    -   Seguridad: el enlace solo funciona mientras el conteo está en `in_progress` y con `public_capture_enabled = true`.

### Completar, revisar y aplicar ajustes

1. Completar: exige que todos los items tengan `counted_quantity`.
2. Revisar: crea movimientos `adjust` por cada item con diferencia distinta de 0 y actualiza el stock a `counted_quantity`.
3. El conteo pasa a `reviewed`.

## Reporte CSV

-   Disponible en el detalle del conteo.
-   Columnas: Producto, Variante, Stock Sistema, Contado, Diferencia, Valor Diferencia.

## Consideraciones

-   El valor de diferencia se calcula usando `price` del producto/variante.
-   La captura pública guarda `counted_by` si el usuario está autenticado; de lo contrario ese campo queda nulo.
-   Enlace público: `/inventory-capture/{token}`.
