# POS - Documentación Técnica Detallada

## 1. Arquitectura de Base de Datos

### Diagrama de Relaciones

```
┌──────────────────────┐
│      users           │
├──────────────────────┤
│ id (PK)              │
│ name, email, etc     │
└────────────┬─────────┘
             │ 1:N
             │
    ┌────────▼─────────────────────┐
    │   pos_sessions              │
    ├─────────────────────────────┤
    │ id (PK)                     │
    │ user_id (FK) → users        │
    │ session_number (UNIQUE)     │
    │ total_sales, total_payments │
    │ status, opened_at, closed_at│
    └────────┬──────────────────┬─┘
             │ 1:N              │
             │                  │
    ┌────────▼────────────────────────────┐
    │   pos_transactions                 │
    ├────────────────────────────────────┤
    │ id (PK)                            │
    │ pos_session_id (FK)                │
    │ customer_id (FK, nullable)         │
    │ transaction_number (UNIQUE)        │
    │ subtotal, iva_total, total         │
    │ status, payment_status             │
    └────┬──────────────────────────┬────┘
         │ 1:N                      │ 1:N
         │                          │
    ┌────▼─────────────────────┐  ┌─▼──────────────────────┐
    │pos_transaction_items     │  │   pos_payments         │
    ├──────────────────────────┤  ├────────────────────────┤
    │ id (PK)                  │  │ id (PK)                │
    │ pos_transaction_id (FK)  │  │ pos_transaction_id(FK) │
    │ product_id (FK)          │  │ payment_method_id(FK)  │
    │ product_variant_id(FK)   │  │ amount                 │
    │ quantity, prices, iva    │  │ reference              │
    │ status                   │  │ status, paid_at        │
    └──────┬──────┬────────────┘  └────────────────────────┘
           │      │
           │      └─ product_variants (FK)
           │
           └─ products (FK)

    ┌──────────────────┐
    │    customers     │
    ├──────────────────┤
    │ id (PK)          │
    │ name, phone, etc │
    └──────────────────┘

    ┌────────────────────┐
    │  payment_methods   │
    ├────────────────────┤
    │ id (PK)            │
    │ name, code, etc    │
    └────────────────────┘
```

---

## 2. Modelos y Relaciones

### POSSession

```php
class POSSession extends Model {
    // Relaciones
    public function user(): BelongsTo
    public function transactions(): HasMany
}
```

**Métodos importantes:**

-   Auto-genera `session_number` al crear
-   Guarda `opened_at` automáticamente
-   Calcula `total_sales` y `total_payments` al cerrar

---

### POSTransaction

```php
class POSTransaction extends Model {
    // Relaciones
    public function posSession(): BelongsTo
    public function customer(): BelongsTo
    public function items(): HasMany
    public function payments(): HasMany

    // Atributos calculados
    public function getTotalPaidAttribute(): float
    public function getPendingAmountAttribute(): float
    public function getIsFullyPaidAttribute(): bool
}
```

**Métodos importantes:**

-   Auto-genera `transaction_number` al crear
-   Los atributos `total_paid`, `pending_amount`, `is_fully_paid` se calculan dinámicamente
-   `status` flujo: pending → reserved → completed
-   `payment_status` flujo: pending → partial → completed

---

### POSTransactionItem

```php
class POSTransactionItem extends Model {
    // Relaciones
    public function posTransaction(): BelongsTo
    public function product(): BelongsTo
    public function variant(): BelongsTo

    // Atributos calculados
    public function getProductNameAttribute(): string
    public function getProductSkuAttribute(): string
    public function getProductBarcodeAttribute(): ?string
}
```

**Métodos importantes:**

-   Los atributos `product_name`, `product_sku`, `product_barcode` se calculan automáticamente
-   Si tiene variante, devuelve datos de la variante; sino del producto base
-   `status` flujo: reserved → pending_shipment → shipped → completed

---

### POSPayment

```php
class POSPayment extends Model {
    // Relaciones
    public function posTransaction(): BelongsTo
    public function paymentMethod(): BelongsTo
}
```

**Métodos importantes:**

-   Simple pero crucial para el control de pagos
-   Soporta múltiples pagos por transacción

---

## 3. Controladores - Métodos Clave

### POSController

#### `index()` - Dashboard

-   Obtiene sesión activa del usuario
-   Carga items pendientes por enviar
-   Calcula estadísticas del día
-   Retorna vista con datos

```php
GET /dashboard/pos → pos.index
```

#### `openSession()` / `storeSession()`

-   Verifica si ya hay sesión abierta
-   Si existe, redirige a sesión activa
-   Si no, crea nueva sesión

```php
GET  /dashboard/pos/session/open     → pos.session.open
POST /dashboard/pos/session           → pos.session.store
```

#### `createTransaction()` / `storeTransaction()`

-   Crea transacción vinculada a sesión
-   Opción de cliente existente o crear nuevo
-   Estados iniciales: pending, reserved_at = now()

```php
GET  /dashboard/pos/{session}/transaction/create
POST /dashboard/pos/{session}/transaction
```

#### `editTransaction()` - Panel Principal

-   Carga transacción con todas sus relaciones
-   Renderiza búsqueda de productos
-   Panel lateral de resumen y pagos

```php
GET /dashboard/pos/transaction/{id}
```

#### `searchProduct()` - Búsqueda AJAX

-   Busca por SKU, barcode o nombre
-   Soporta variantes
-   Retorna JSON con detalles

```php
GET /dashboard/pos/search-product?q=...
Retorna: JSON array de productos
```

#### `addItem()` - Agregar Producto

-   Valida producto y cantidad
-   Calcula precios e IVA
-   Crea POSTransactionItem

```php
POST /dashboard/pos/transaction/{id}/item
Body: { product_id, product_variant_id, quantity }
```

#### `updateItemQuantity()` - Actualizar Cantidad

-   Recalcula totales del item
-   Actualiza transacción

```php
PATCH /dashboard/pos/transaction/{id}/item/{item}/quantity
Body: { quantity }
```

#### `removeItem()` - Remover Producto

-   Elimina item
-   Actualiza totales de transacción

```php
DELETE /dashboard/pos/transaction/{id}/item/{item}
```

#### `recordPayment()` - Registrar Pago

-   Crea POSPayment
-   Actualiza payment_status de transacción
-   Calcula total pagado

```php
POST /dashboard/pos/transaction/{id}/payment
Body: { payment_method_id, amount, reference, notes }
```

#### `printTicket()` - Generar Ticket

-   Retorna vista ticket.blade.php
-   Optimizado para impresoras térmicas
-   Incluye logo, detalles, pagos

```php
GET /dashboard/pos/transaction/{id}/ticket
```

#### `completeTransaction()` - Completar Apartado

-   Marca transacción como completed
-   Cambia items a pending_shipment
-   Redirige a dashboard

```php
POST /dashboard/pos/transaction/{id}/complete
```

#### `closeSession()` - Cerrar Sesión

-   Calcula totales
-   Marca sesión como closed
-   Registra closed_at

```php
POST /dashboard/pos/session/{id}/close
```

#### `pendingShipments()` - Ver Pendientes

-   Obtiene todos los items pending_shipment
-   Con paginación
-   Información completa del cliente

```php
GET /dashboard/pos/pending-shipments
```

#### `markAsShipped()` / `markAsCompleted()`

-   Actualiza estado del item
-   shipped: en tránsito
-   completed: entregado

```php
PATCH /dashboard/pos/item/{item}/shipped
PATCH /dashboard/pos/item/{item}/completed
```

---

## 4. Métodos Privados

### `updateTransactionTotals(POSTransaction)`

```php
private function updateTransactionTotals($transaction): void
```

-   Suma subtotales de todos los items
-   Suma IVA de todos los items
-   Calcula total = subtotal + iva
-   Actualiza la transacción

Se llama cada vez que se agrega, actualiza o remueve un item.

---

## 5. API Controller - Métodos

### POSApiController

#### `searchProducts()` - AJAX Search

```php
GET /api/pos/search-products?q=...
Retorna: JSON [ { id, name, sku, barcode, price, variants[] } ]
```

#### `getProduct()` - Detalles

```php
GET /api/pos/products/{product}
Retorna: JSON con detalles y variantes
```

#### `getTransaction()` - Obtener Transacción

```php
GET /api/pos/transactions/{transaction}
Retorna: JSON con items, pagos, totales
```

#### `getPendingItems()` - Items Pendientes

```php
GET /api/pos/pending-items
Retorna: JSON array de items pending_shipment
```

---

## 6. Vistas - Estructura

### `index.blade.php` - Dashboard

-   Estadísticas del día (3 cards)
-   Botones rápidos (nueva transacción, cerrar sesión)
-   Tabla de items pendientes con paginación
-   Indicador de sesión activa

### `transaction/edit.blade.php` - Panel Principal

-   Grid 2/3 + 1/3 (izquierda/derecha)
-   **Izquierda:**
    -   Info del cliente
    -   Buscador de productos AJAX
    -   Tabla de items con acciones
-   **Derecha (sticky):**
    -   Resumen de totales
    -   Historial de pagos
    -   Formulario agregar pago
    -   Botones finales

### `ticket.blade.php` - Ticket

-   HTML/CSS optimizado para impresoras
-   80mm de ancho (estándar)
-   Logo, transacción, cliente, items, totales
-   Auto-imprime al cargar

### `pending-shipments.blade.php` - Gestión Envíos

-   Tabla completa con paginación
-   Columnas: transacción, cliente, teléfono, producto, SKU, cantidad, estado
-   Botones inline para "Enviado" y "Completado"
-   Filtrada por status = 'pending_shipment'

---

## 7. Flujo de Datos

### Crear Nueva Transacción

```
1. Usuario clic en "Nueva Transacción"
2. GET /dashboard/pos/{session}/transaction/create
3. Vista muestra formulario (cliente, notas)
4. Usuario envía formulario
5. POST /dashboard/pos/{session}/transaction
6. Controlador:
   - Valida datos
   - Crea cliente si es necesario
   - Crea POSTransaction (status: pending)
   - Redirige a edit
7. GET /dashboard/pos/transaction/{id}
8. Vista edit muestra panel de edición
```

### Agregar Producto

```
1. Usuario escribe en búsqueda
2. AJAX GET /dashboard/pos/search-product?q=...
3. Retorna JSON con productos
4. Usuario selecciona producto
5. Prompt pide cantidad
6. POST /dashboard/pos/transaction/{id}/item
7. Controlador:
   - Valida producto y cantidad
   - Calcula precios
   - Crea POSTransactionItem
   - Actualiza totales de transacción
8. Página recarga
9. Item aparece en tabla
```

### Registrar Pago

```
1. Usuario ingresa monto en panel lateral
2. Selecciona método de pago
3. POST /dashboard/pos/transaction/{id}/payment
4. Controlador:
   - Valida monto
   - Crea POSPayment (status: completed)
   - Recalcula total_paid
   - Actualiza payment_status
5. Página recarga
6. Pago aparece en lista
```

### Completar Apartado

```
1. Usuario clic "Completar Apartado"
2. POST /dashboard/pos/transaction/{id}/complete
3. Controlador:
   - Marca transacción como completed
   - Cambia todos items a pending_shipment
   - Redirige a dashboard
4. Usuario puede imprimir ticket antes
```

---

## 8. Validaciones

### En POSController

**storeTransaction():**

-   customer_id: nullable, debe existir
-   customer_name: nullable, max 255
-   customer_phone: nullable, max 20

**addItem():**

-   product_id: required, exists in products
-   product_variant_id: nullable, exists in product_variants
-   quantity: required, integer, min 1, max 1000

**updateItemQuantity():**

-   quantity: required, integer, min 1, max 1000

**recordPayment():**

-   payment_method_id: nullable, exists in payment_methods
-   amount: required, numeric, min 0.01
-   reference: nullable, max 255

---

## 9. Seguridad

### Protecciones Implementadas

1. **Autenticación** - Middleware `auth`
2. **Verificación de Sesión**
    ```php
    if ($session->user_id !== auth()->id()) abort(403);
    ```
3. **Verificación de Transacción**
    ```php
    if ($transaction->posSession->user_id !== auth()->id()) abort(403);
    ```
4. **Validación CSRF** - Automático en formularios
5. **Validación de Datos** - Regex, exists, etc.

---

## 10. Performance

### Índices en Base de Datos

```sql
-- pos_sessions
INDEX user_id
INDEX status

-- pos_transactions
INDEX (pos_session_id, status)
INDEX customer_id
INDEX payment_status

-- pos_transaction_items
INDEX (pos_transaction_id, status)
INDEX product_id

-- pos_payments
INDEX (pos_transaction_id, status)
INDEX payment_method_id
```

### Eager Loading

```php
// Evita N+1 queries
$items->load(['posTransaction.customer', 'product', 'variant'])
$transaction->load(['items', 'payments', 'customer'])
```

### Paginación

```php
// Listas largas usan paginación (15-20 items por página)
POSTransactionItem::...->paginate(15)
```

---

## 11. Extensibilidad

### Para Agregar Funcionalidades

**Nuevo estado de item:**

1. Modificar migration: agregar valor a enum
2. Usar en controlador: `$item->status = 'nuevo_estado'`
3. Agregar lógica en pendingShipments()

**Nuevo tipo de pago:**

1. Agregar a payment_methods
2. El sistema lo detecta automáticamente
3. Aparece en dropdown

**Nuevas estadísticas:**

1. Calcular en POSController@index()
2. Pasar a vista en array $stats
3. Renderizar en index.blade.php

**Reportes:**

1. Crear nuevo método en POSController
2. Agregar ruta
3. Crear nueva vista

---

## 12. Cálculos Automáticos

### Cuando se agrega un item:

```
unit_price = variant.price ?? product.sale_price ?? product.price
subtotal = unit_price * quantity
iva_amount = subtotal * (iva_rate / 100)
total = subtotal + iva_amount
```

### Cuando se actualiza cantidad:

```
subtotal = unit_price * new_quantity
iva_amount = subtotal * (iva_rate / 100)
total = subtotal + iva_amount
```

### Totales de transacción:

```
transaction.subtotal = SUM(items.subtotal)
transaction.iva_total = SUM(items.iva_amount)
transaction.total = subtotal + iva_total
```

### Pagos:

```
total_paid = SUM(payments.amount WHERE status='completed')
pending_amount = transaction.total - total_paid
payment_status = pending | partial | completed
```

---

## 13. Estados y Transiciones

### Estados de Item

```
reserved          → primera vez que se agrega
pending_shipment  → cuando se completa la transacción
shipped           → cuando se marca como enviado
completed         → cuando se marca como completado
cancelled         → si se cancela
```

### Estados de Transacción

```
pending    → recién creada
reserved   → cuando se completa y se va a enviar
completed  → finalizada
cancelled  → si se cancela
```

### Estados de Pago

```
pending    → registrado pero no validado
completed  → confirmado y listo
```

---

## 14. Archivos Importantes

### Configuración

-   `routes/web.php` - Rutas POS
-   `composer.json` - Auto-load de helpers

### Helpers

-   `app/Helpers/CurrencyHelper.php` - Formato de moneda

### Base de Datos

-   `database/migrations/2025_12_26_*` - Migraciones POS

### Documentación

-   `docs/POS.md` - Documentación completa
-   `docs/POS_CAMBIOS.md` - Resumen de cambios
-   `QUICKSTART_POS.md` - Guía rápida

---

**Fin de documentación técnica.**
