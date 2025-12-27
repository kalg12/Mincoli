# Sección POS - Sistema de Ventas y Apartados

## Descripción General

La sección POS (Punto de Venta) es un módulo completo diseñado para gestionar ventas en vivo, especialmente para tus transmisiones en vivo en redes sociales. Permite:

-   ✅ Crear **transacciones de apartado** (reservas)
-   ✅ **Buscar productos** por SKU, Barcode o nombre
-   ✅ Gestionar **múltiples métodos de pago** y **abonos parciales**
-   ✅ **Generar tickets** en PDF/Impresora térmica
-   ✅ Marcar productos como **"descontados"** (reservados)
-   ✅ Controlar **pendientes de envío** con estados

## Modelos de Datos

### 1. POSSession

Representa una sesión/jornada de trabajo en el POS.

**Campos principales:**

-   `session_number`: Identificador único (POS-YYYYMMDDHHmmss-XXXX)
-   `user_id`: Usuario que abrió la sesión
-   `total_sales`: Total de ventas en la sesión
-   `total_payments`: Total de pagos recibidos
-   `status`: 'open' o 'closed'
-   `opened_at`, `closed_at`: Timestamps

**Relaciones:**

-   `user()`: Usuario que abrió la sesión
-   `transactions()`: Todas las transacciones en la sesión

---

### 2. POSTransaction

Representa una transacción de apartado/venta.

**Campos principales:**

-   `pos_session_id`: Sesión a la que pertenece
-   `customer_id`: Cliente (nullable)
-   `transaction_number`: Identificador único (TXN-YYYYMMDDHHmmss-XXXXX)
-   `subtotal`, `iva_total`, `total`: Montos
-   `status`: 'pending', 'reserved', 'completed', 'cancelled'
-   `payment_status`: 'pending', 'partial', 'completed'
-   `reserved_at`, `completed_at`: Timestamps

**Relaciones:**

-   `posSession()`: Sesión a la que pertenece
-   `customer()`: Cliente asociado
-   `items()`: Items (productos) en la transacción
-   `payments()`: Pagos registrados

**Atributos calculados:**

-   `total_paid`: Total pagado hasta ahora
-   `pending_amount`: Monto pendiente
-   `is_fully_paid`: Boolean si está totalmente pagado

---

### 3. POSTransactionItem

Representa un producto/variante en una transacción.

**Campos principales:**

-   `pos_transaction_id`: Transacción a la que pertenece
-   `product_id`: Producto
-   `product_variant_id`: Variante (opcional)
-   `quantity`: Cantidad
-   `unit_price`, `iva_rate`, `subtotal`, `iva_amount`, `total`: Cálculos
-   `status`: 'reserved', 'pending_shipment', 'shipped', 'completed', 'cancelled'

**Relaciones:**

-   `posTransaction()`: Transacción
-   `product()`: Producto
-   `variant()`: Variante del producto

**Atributos calculados:**

-   `product_name`: Nombre del producto o variante
-   `product_sku`: SKU del producto o variante
-   `product_barcode`: Barcode del producto o variante

---

### 4. POSPayment

Representa un pago o abono en una transacción.

**Campos principales:**

-   `pos_transaction_id`: Transacción a la que pertenece
-   `payment_method_id`: Método de pago usado
-   `amount`: Monto del pago
-   `reference`: Número de comprobante/referencia (opcional)
-   `status`: 'pending', 'completed'
-   `paid_at`: Cuándo se registró el pago

**Relaciones:**

-   `posTransaction()`: Transacción
-   `paymentMethod()`: Método de pago

---

## Migraciones Creadas

```bash
2025_12_26_000001_create_pos_sessions_table.php
2025_12_26_000002_create_pos_transactions_table.php
2025_12_26_000003_create_pos_transaction_items_table.php
2025_12_26_000004_create_pos_payments_table.php
```

## Rutas Disponibles

### Dashboard POS

```
GET    /dashboard/pos                              → pos.index (Dashboard)
GET    /dashboard/pos/session/open                 → pos.session.open (Abrir sesión)
POST   /dashboard/pos/session                      → pos.session.store (Crear sesión)
POST   /dashboard/pos/session/{id}/close           → pos.session.close (Cerrar sesión)
```

### Transacciones

```
GET    /dashboard/pos/{session}/transaction/create → pos.transaction.create
POST   /dashboard/pos/{session}/transaction        → pos.transaction.store
GET    /dashboard/pos/transaction/{id}             → pos.transaction.edit
POST   /dashboard/pos/transaction/{id}/complete    → pos.transaction.complete
```

### Items y Pagos

```
POST   /dashboard/pos/transaction/{id}/item        → pos.item.add
DELETE /dashboard/pos/transaction/{id}/item/{item} → pos.item.remove
PATCH  /dashboard/pos/transaction/{id}/item/{item}/quantity → pos.item.updateQuantity
POST   /dashboard/pos/transaction/{id}/payment     → pos.payment.store
```

### Tickets y Envíos

```
GET    /dashboard/pos/transaction/{id}/ticket      → pos.ticket.print (Imprimir ticket)
GET    /dashboard/pos/pending-shipments            → pos.pending-shipments.index
PATCH  /dashboard/pos/item/{item}/shipped          → pos.item.shipped
PATCH  /dashboard/pos/item/{item}/completed        → pos.item.completed
```

---

## Controladores

### POSController (`app/Http/Controllers/POSController.php`)

Controlador principal que maneja:

-   Abrir/cerrar sesiones
-   Crear transacciones
-   Agregar/editar items
-   Registrar pagos
-   Generar tickets
-   Gestionar envíos

### POSApiController (`app/Http/Controllers/Api/POSApiController.php`)

API para búsquedas y datos AJAX:

-   `searchProducts()`
-   `getProduct()`
-   `getTransaction()`
-   `getPendingItems()`

---

## Vistas Creadas

```
resources/views/pos/
├── index.blade.php                (Dashboard)
├── open-session.blade.php          (Abrir sesión)
├── session-active.blade.php        (Sesión activa)
├── pending-shipments.blade.php     (Items pendientes)
├── ticket.blade.php                (Ticket de venta)
└── transaction/
    ├── create.blade.php            (Nueva transacción)
    └── edit.blade.php              (Editar transacción)
```

---

## Flujo de Trabajo

### 1. Abrir Sesión

```
GET /dashboard/pos/session/open
POST /dashboard/pos/session
```

### 2. Crear Transacción

```
GET /dashboard/pos/{session}/transaction/create
POST /dashboard/pos/{session}/transaction
```

### 3. Agregar Productos

```
- Buscar producto por SKU/Barcode
- Seleccionar cantidad
- POST /dashboard/pos/transaction/{id}/item
```

### 4. Registrar Pago(s)

```
- Seleccionar método de pago
- Ingresar monto
- POST /dashboard/pos/transaction/{id}/payment
```

### 5. Completar Apartado

```
- POST /dashboard/pos/transaction/{id}/complete
- Items se marcan como "pending_shipment"
```

### 6. Gestionar Envíos

```
GET /dashboard/pos/pending-shipments
- Marcar como "shipped" o "completed"
```

---

## Características Principales

### 🔍 Búsqueda de Productos

-   Por **SKU** del producto base o variante
-   Por **Barcode** del producto base o variante
-   Por **nombre** del producto
-   Resultados instantáneos en AJAX

### 🛒 Carrito/Transacción

-   Agregar múltiples productos
-   Soporta **variantes** (talla, color, etc.)
-   Actualizar cantidades en tiempo real
-   Calcular automáticamente IVA y totales
-   Remover items fácilmente

### 💰 Control de Pagos

-   Registrar **pagos parciales/abonos**
-   Múltiples **métodos de pago** por transacción
-   Estados: pendiente, parcial, completado
-   Referencia de pago (comprobante, etc.)

### 🎫 Ticket de Venta

-   Diseño profesional para impresoras térmicas
-   Logo de la tienda (apple-touch-icon.png)
-   Información del cliente
-   Detalles de productos, IVA, total
-   Resumen de pagos
-   Auto-impresión al generar

### 📦 Control de Envíos

-   Vista centralizada de "pendientes por enviar"
-   Estados: reserved → pending_shipment → shipped → completed
-   Información del cliente y contacto
-   SKU/Barcode para fácil ubicación
-   Marcar como enviado o completado con un clic

### 🔐 Seguridad

-   Verificación de propiedad de sesión (user_id)
-   Solo usuarios autenticados pueden acceder
-   Verificación de transacción pertenece a usuario

---

## Helpers y Utilidades

### `currency()` Helper

```php
currency(1000.50)  // Returns: $1.000,50 (formato colombiano)
```

Auto-cargado en `app/Helpers/CurrencyHelper.php`

---

## Próximas Acciones

### ✅ Completado

-   Modelos y relaciones
-   Migraciones
-   Controladores
-   Rutas
-   Vistas principales
-   Tickets de venta
-   Sistema de pagos

### 📋 Por Hacer (Opcional)

-   [ ] Reportes de POS por sesión/usuario
-   [ ] Dashboard con estadísticas más detalladas
-   [ ] Exportación de transacciones a Excel
-   [ ] Resumen de ventas por método de pago
-   [ ] Historial de clientes
-   [ ] Devoluciones/cancelaciones desde POS
-   [ ] Integración con gateway de pago
-   [ ] App móvil para POS

---

## Notas Importantes

### Logo

-   Ubicado en: `public/apple-touch-icon.png`
-   Se usa automáticamente en tickets
-   Si no existe, se muestra un recuadro gris

### Métodos de Pago

-   Asegúrate de que estén creados en la tabla `payment_methods`
-   El seeder ya existe: `PaymentMethodSeeder`

### IVA

-   El IVA se calcula automáticamente del campo `iva_rate` en productos
-   Se suma al subtotal para obtener el total

### Estados de Items

-   **reserved**: Apartado, está reservado
-   **pending_shipment**: Listo para enviar
-   **shipped**: Ya fue enviado
-   **completed**: Entregado al cliente
-   **cancelled**: Cancelado

### Estados de Transacción

-   **pending**: Creada, sin completar
-   **reserved**: Apartado completado
-   **completed**: Transacción finalizada
-   **cancelled**: Cancelada

---

## Testing

Para probar el POS:

1. Migrar: `php artisan migrate`
2. Seedear: `php artisan db:seed --class=PaymentMethodSeeder`
3. Crear algunos productos y variantes
4. Ir a: `http://tu-app/dashboard/pos`
5. Abrir una sesión
6. Crear transacciones
7. Agregar productos
8. Registrar pagos

---

## Soporte

Para reportar issues o mejorar el módulo, contacta al equipo de desarrollo.
