# Esquema de Base de Datos - Tienda en Línea

## Resumen General

Sistema completo de tienda en línea con soporte para:

-   Catálogo de productos con variantes e inventario
-   Gestión de clientes y direcciones
-   Carritos de compra (clientes y anónimos)
-   Proceso completo de pedidos y ventas
-   Sistema de envíos con diferentes zonas
-   Pagos múltiples y financiamiento
-   Ofertas y promociones
-   Sesiones en vivo (Live Shopping)
-   Gestión de contenido (páginas, políticas, configuración)
-   Tracking y análisis
-   Reportes y cortes de caja

---

## 📦 CATÁLOGO / TIENDA

### Categories (Categorías)

**Tabla:** `categories`
**Modelo:** `App\Models\Category`

| Campo      | Tipo     | Descripción            |
| ---------- | -------- | ---------------------- |
| id         | bigint   | ID autoincremental     |
| name       | string   | Nombre de la categoría |
| slug       | string   | Slug único para URLs   |
| is_active  | boolean  | Estado activo/inactivo |
| created_at | datetime | Fecha de creación      |
| updated_at | datetime | Fecha de actualización |

**Relaciones:**

-   `hasMany` → Products
-   `hasManyThrough` → InventoryMovements
-   `hasMany` → WeeklyCutDetails

**Índices:** `slug` (unique), `is_active`

---

### Products (Productos)

**Tabla:** `products`
**Modelo:** `App\Models\Product`

| Campo       | Tipo          | Descripción                        |
| ----------- | ------------- | ---------------------------------- |
| id          | bigint        | ID autoincremental                 |
| category_id | bigint        | FK a categories                    |
| name        | string        | Nombre del producto                |
| slug        | string        | Slug único para URLs               |
| description | text          | Descripción del producto           |
| sku         | string        | SKU único                          |
| barcode     | string        | Código de barras (único)           |
| price       | decimal(10,2) | Precio de venta                    |
| cost        | decimal(10,2) | Costo del producto                 |
| iva_rate    | decimal(5,2)  | Tasa de IVA (default: 16.00)       |
| is_active   | boolean       | Producto activo                    |
| is_featured | boolean       | Producto destacado                 |
| created_at  | datetime      | Fecha de creación                  |
| updated_at  | datetime      | Fecha de actualización             |
| deleted_at  | datetime      | Fecha de eliminación (soft delete) |

**Relaciones:**

-   `belongsTo` → Category
-   `hasMany` → ProductVariants
-   `hasMany` → ProductImages
-   `hasMany` → InventoryMovements
-   `hasMany` → CartItems
-   `hasMany` → OrderItems
-   `hasMany` → OfferItems
-   `hasMany` → LiveProductHighlights

**Métodos útiles:**

-   `getTotalStockAttribute()` - Suma el stock de todas las variantes
-   `calculateIva(float $basePrice)` - Calcula el IVA para un precio

**Índices:** `category_id`, `sku` (unique), `barcode` (unique), `is_active`, `is_featured`

---

### ProductVariants (Variantes de Producto)

**Tabla:** `product_variants`
**Modelo:** `App\Models\ProductVariant`

| Campo      | Tipo          | Descripción                        |
| ---------- | ------------- | ---------------------------------- |
| id         | bigint        | ID autoincremental                 |
| product_id | bigint        | FK a products                      |
| name       | string        | Nombre de la variante              |
| size       | string        | Talla/tamaño (opcional)            |
| color      | string        | Color (opcional)                   |
| sku        | string        | SKU único de la variante           |
| barcode    | string        | Código de barras (opcional)        |
| price      | decimal(10,2) | Precio específico (opcional)       |
| stock      | int           | Cantidad en stock                  |
| created_at | datetime      | Fecha de creación                  |
| updated_at | datetime      | Fecha de actualización             |
| deleted_at | datetime      | Fecha de eliminación (soft delete) |

**Relaciones:**

-   `belongsTo` → Product
-   `hasMany` → ProductImages
-   `hasMany` → InventoryMovements
-   `hasMany` → CartItems
-   `hasMany` → OrderItems
-   `hasMany` → OfferItems
-   `hasMany` → LiveProductHighlights

**Métodos útiles:**

-   `getEffectivePriceAttribute()` - Retorna precio de variante o precio del producto
-   `hasStock(int $quantity)` - Verifica si hay stock suficiente

**Índices:** `product_id`, `sku` (unique), `stock`

---

### ProductImages (Imágenes de Producto)

**Tabla:** `product_images`
**Modelo:** `App\Models\ProductImage`

| Campo      | Tipo     | Descripción                      |
| ---------- | -------- | -------------------------------- |
| id         | bigint   | ID autoincremental               |
| product_id | bigint   | FK a products                    |
| variant_id | bigint   | FK a product_variants (opcional) |
| url        | string   | URL de la imagen                 |
| position   | int      | Orden de visualización           |
| created_at | datetime | Fecha de creación                |
| updated_at | datetime | Fecha de actualización           |

**Relaciones:**

-   `belongsTo` → Product
-   `belongsTo` → ProductVariant (opcional)

**Índices:** `product_id`, `variant_id`

---

### InventoryMovements (Movimientos de Inventario)

**Tabla:** `inventory_movements`
**Modelo:** `App\Models\InventoryMovement`

| Campo          | Tipo     | Descripción                      |
| -------------- | -------- | -------------------------------- |
| id             | bigint   | ID autoincremental               |
| product_id     | bigint   | FK a products                    |
| variant_id     | bigint   | FK a product_variants (opcional) |
| type           | enum     | Tipo: in, out, adjust            |
| quantity       | int      | Cantidad del movimiento          |
| reason         | string   | Razón del movimiento             |
| reference_type | string   | Tipo de referencia (modelo)      |
| reference_id   | bigint   | ID de la referencia              |
| created_by     | bigint   | FK a users                       |
| created_at     | datetime | Fecha de creación                |

**Relaciones:**

-   `belongsTo` → Product
-   `belongsTo` → ProductVariant (opcional)
-   `belongsTo` → User (created_by)

**Métodos útiles:**

-   `getReferenceable()` - Obtiene el modelo de referencia

**Índices:** `product_id`, `variant_id`, `type`, `reference_type`

---

## 👥 CLIENTES / DIRECCIONES

### Customers (Clientes)

**Tabla:** `customers`
**Modelo:** `App\Models\Customer`

| Campo      | Tipo     | Descripción                        |
| ---------- | -------- | ---------------------------------- |
| id         | bigint   | ID autoincremental                 |
| phone      | string   | Teléfono (único)                   |
| name       | string   | Nombre del cliente                 |
| email      | string   | Email (opcional)                   |
| created_at | datetime | Fecha de creación                  |
| updated_at | datetime | Fecha de actualización             |
| deleted_at | datetime | Fecha de eliminación (soft delete) |

**Relaciones:**

-   `hasMany` → CustomerAddresses
-   `hasMany` → Carts
-   `hasMany` → Orders
-   `hasMany` → Payments

**Índices:** `phone` (unique), `email`

---

### CustomerAddresses (Direcciones de Cliente)

**Tabla:** `customer_addresses`
**Modelo:** `App\Models\CustomerAddress`

| Campo       | Tipo     | Descripción                    |
| ----------- | -------- | ------------------------------ |
| id          | bigint   | ID autoincremental             |
| customer_id | bigint   | FK a customers                 |
| label       | string   | Etiqueta (Casa, Oficina, etc.) |
| street      | string   | Calle                          |
| ext_number  | string   | Número exterior                |
| int_number  | string   | Número interior (opcional)     |
| colony      | string   | Colonia                        |
| city        | string   | Ciudad                         |
| state       | string   | Estado                         |
| zip         | string   | Código postal                  |
| references  | text     | Referencias adicionales        |
| is_default  | boolean  | Dirección por defecto          |
| created_at  | datetime | Fecha de creación              |
| updated_at  | datetime | Fecha de actualización         |

**Relaciones:**

-   `belongsTo` → Customer

**Métodos útiles:**

-   `setAsDefault()` - Establece esta dirección como predeterminada
-   `getFormattedAddressAttribute()` - Retorna dirección formateada

**Índices:** `customer_id`, `is_default`

---

## 🛒 CARRITO

### Carts (Carritos de Compra)

**Tabla:** `carts`
**Modelo:** `App\Models\Cart`

| Campo       | Tipo     | Descripción                  |
| ----------- | -------- | ---------------------------- |
| id          | bigint   | ID autoincremental           |
| customer_id | bigint   | FK a customers (opcional)    |
| session_id  | string   | ID de sesión para anónimos   |
| status      | enum     | active, converted, abandoned |
| expires_at  | datetime | Fecha de expiración          |
| created_at  | datetime | Fecha de creación            |
| updated_at  | datetime | Fecha de actualización       |

**Relaciones:**

-   `belongsTo` → Customer (opcional)
-   `hasMany` → CartItems

**Métodos útiles:**

-   `getSubtotalAttribute()` - Calcula subtotal
-   `getTotalIvaAttribute()` - Calcula IVA total
-   `getTotalAttribute()` - Calcula total
-   `hasExpired()` - Verifica si expiró
-   `markAsConverted()` - Marca como convertido a orden
-   `markAsAbandoned()` - Marca como abandonado

**Índices:** `customer_id`, `session_id`, `status`, `expires_at`

---

### CartItems (Items del Carrito)

**Tabla:** `cart_items`
**Modelo:** `App\Models\CartItem`

| Campo      | Tipo          | Descripción                      |
| ---------- | ------------- | -------------------------------- |
| id         | bigint        | ID autoincremental               |
| cart_id    | bigint        | FK a carts                       |
| product_id | bigint        | FK a products                    |
| variant_id | bigint        | FK a product_variants (opcional) |
| quantity   | int           | Cantidad                         |
| unit_price | decimal(10,2) | Precio unitario capturado        |
| created_at | datetime      | Fecha de creación                |
| updated_at | datetime      | Fecha de actualización           |

**Relaciones:**

-   `belongsTo` → Cart
-   `belongsTo` → Product
-   `belongsTo` → ProductVariant (opcional)

**Métodos útiles:**

-   `getSubtotalAttribute()` - Cantidad × precio
-   `getIvaAmountAttribute()` - Calcula IVA
-   `getTotalAttribute()` - Subtotal + IVA

**Índices:** `cart_id`, `product_id`, `variant_id`

---

## 📋 PEDIDOS / VENTAS

### Orders (Órdenes/Pedidos)

**Tabla:** `orders`
**Modelo:** `App\Models\Order`

| Campo         | Tipo          | Descripción                                                                   |
| ------------- | ------------- | ----------------------------------------------------------------------------- |
| id            | bigint        | ID autoincremental                                                            |
| customer_id   | bigint        | FK a customers (opcional)                                                     |
| order_number  | string        | Número de orden (único)                                                       |
| status        | enum          | draft, pending, paid, partially_paid, shipped, delivered, cancelled, refunded |
| channel       | enum          | web, live                                                                     |
| subtotal      | decimal(10,2) | Subtotal de productos                                                         |
| iva_total     | decimal(10,2) | Total de IVA                                                                  |
| shipping_cost | decimal(10,2) | Costo de envío                                                                |
| total         | decimal(10,2) | Total de la orden                                                             |
| notes         | text          | Notas adicionales                                                             |
| placed_at     | datetime      | Fecha de colocación                                                           |
| created_at    | datetime      | Fecha de creación                                                             |
| updated_at    | datetime      | Fecha de actualización                                                        |

**Relaciones:**

-   `belongsTo` → Customer (opcional)
-   `hasMany` → OrderItems
-   `hasOne` → Shipment
-   `hasMany` → OrderStatusHistories
-   `hasOne` → OrderFinancing
-   `hasMany` → Payments

**Métodos útiles:**

-   `getTotalPaidAttribute()` - Total pagado
-   `getRemainingAttribute()` - Monto pendiente
-   `changeStatus(string $newStatus, ?string $note)` - Cambia estado con historial
-   `isPaid()` - Verifica si está pagado
-   `canBeCanceled()` - Verifica si se puede cancelar

**Boot:** Genera `order_number` automáticamente (ORD-YmdHis-####)

**Índices:** `customer_id`, `order_number` (unique), `status`, `channel`, `placed_at`

---

### OrderItems (Items de Orden)

**Tabla:** `order_items`
**Modelo:** `App\Models\OrderItem`

| Campo      | Tipo          | Descripción                      |
| ---------- | ------------- | -------------------------------- |
| id         | bigint        | ID autoincremental               |
| order_id   | bigint        | FK a orders                      |
| product_id | bigint        | FK a products                    |
| variant_id | bigint        | FK a product_variants (opcional) |
| quantity   | int           | Cantidad                         |
| unit_price | decimal(10,2) | Precio unitario                  |
| iva_amount | decimal(10,2) | Monto de IVA                     |
| total      | decimal(10,2) | Total del item                   |
| created_at | datetime      | Fecha de creación                |
| updated_at | datetime      | Fecha de actualización           |

**Relaciones:**

-   `belongsTo` → Order
-   `belongsTo` → Product
-   `belongsTo` → ProductVariant (opcional)

**Métodos útiles:**

-   `getSubtotalAttribute()` - Cantidad × precio unitario

**Índices:** `order_id`, `product_id`, `variant_id`

---

### OrderStatusHistories (Historial de Estados)

**Tabla:** `order_status_histories`
**Modelo:** `App\Models\OrderStatusHistory`

| Campo       | Tipo     | Descripción        |
| ----------- | -------- | ------------------ |
| id          | bigint   | ID autoincremental |
| order_id    | bigint   | FK a orders        |
| from_status | string   | Estado anterior    |
| to_status   | string   | Estado nuevo       |
| note        | text     | Nota del cambio    |
| created_at  | datetime | Fecha del cambio   |

**Relaciones:**

-   `belongsTo` → Order

**Índices:** `order_id`, `to_status`

---

## 🚚 ENVÍOS

### Shipments (Envíos)

**Tabla:** `shipments`
**Modelo:** `App\Models\Shipment`

| Campo           | Tipo          | Descripción                                                  |
| --------------- | ------------- | ------------------------------------------------------------ |
| id              | bigint        | ID autoincremental                                           |
| order_id        | bigint        | FK a orders                                                  |
| carrier         | string        | Paquetería/transportista                                     |
| tracking_number | string        | Número de rastreo                                            |
| status          | enum          | pending, shipped, in_transit, delivered, returned, cancelled |
| zone_type       | enum          | cdmx, edomex, republica, extendida                           |
| shipping_cost   | decimal(10,2) | Costo del envío                                              |
| shipped_at      | datetime      | Fecha de envío                                               |
| delivered_at    | datetime      | Fecha de entrega                                             |
| created_at      | datetime      | Fecha de creación                                            |
| updated_at      | datetime      | Fecha de actualización                                       |

**Relaciones:**

-   `belongsTo` → Order

**Métodos útiles:**

-   `markAsShipped(?string $trackingNumber)` - Marca como enviado
-   `markAsDelivered()` - Marca como entregado
-   `isInTransit()` - Verifica si está en tránsito
-   `isDelivered()` - Verifica si fue entregado

**Índices:** `order_id`, `status`, `zone_type`

---

## 💳 PAGOS / FINANCIAMIENTO

### PaymentMethods (Métodos de Pago)

**Tabla:** `payment_methods`
**Modelo:** `App\Models\PaymentMethod`

| Campo      | Tipo     | Descripción            |
| ---------- | -------- | ---------------------- |
| id         | bigint   | ID autoincremental     |
| name       | enum     | card, transfer         |
| is_active  | boolean  | Método activo          |
| created_at | datetime | Fecha de creación      |
| updated_at | datetime | Fecha de actualización |

**Relaciones:**

-   `hasMany` → Payments

**Índices:** `name`, `is_active`

---

### PaymentPlans (Planes de Financiamiento)

**Tabla:** `payment_plans`
**Modelo:** `App\Models\PaymentPlan`

| Campo                 | Tipo     | Descripción            |
| --------------------- | -------- | ---------------------- |
| id                    | bigint   | ID autoincremental     |
| name                  | string   | Nombre del plan        |
| frequency             | enum     | weekly, biweekly       |
| installments_count    | int      | Número de cuotas       |
| days_between_payments | int      | Días entre pagos       |
| is_active             | boolean  | Plan activo            |
| created_at            | datetime | Fecha de creación      |
| updated_at            | datetime | Fecha de actualización |

**Relaciones:**

-   `hasMany` → OrderFinancings

**Métodos útiles:**

-   `getDisplayNameAttribute()` - Nombre formateado con frecuencia

**Índices:** `is_active`

---

### OrderFinancings (Financiamientos de Orden)

**Tabla:** `order_financings`
**Modelo:** `App\Models\OrderFinancing`

| Campo           | Tipo          | Descripción                   |
| --------------- | ------------- | ----------------------------- |
| id              | bigint        | ID autoincremental            |
| order_id        | bigint        | FK a orders                   |
| payment_plan_id | bigint        | FK a payment_plans            |
| down_payment    | decimal(10,2) | Enganche                      |
| financed_amount | decimal(10,2) | Monto financiado              |
| start_date      | date          | Fecha de inicio               |
| due_date        | date          | Fecha de vencimiento          |
| status          | enum          | active, paid, late, cancelled |
| created_at      | datetime      | Fecha de creación             |
| updated_at      | datetime      | Fecha de actualización        |

**Relaciones:**

-   `belongsTo` → Order
-   `belongsTo` → PaymentPlan

**Métodos útiles:**

-   `getTotalAmountAttribute()` - Enganche + financiado
-   `isOverdue()` - Verifica si está vencido
-   `markAsPaid()` - Marca como pagado

**Índices:** `order_id`, `payment_plan_id`, `status`

---

### Payments (Pagos)

**Tabla:** `payments`
**Modelo:** `App\Models\Payment`

| Campo       | Tipo          | Descripción                     |
| ----------- | ------------- | ------------------------------- |
| id          | bigint        | ID autoincremental              |
| order_id    | bigint        | FK a orders                     |
| customer_id | bigint        | FK a customers (opcional)       |
| method_id   | bigint        | FK a payment_methods            |
| amount      | decimal(10,2) | Monto del pago                  |
| paid_at     | datetime      | Fecha de pago                   |
| reference   | string        | Referencia del pago             |
| status      | enum          | pending, paid, failed, refunded |
| created_at  | datetime      | Fecha de creación               |
| updated_at  | datetime      | Fecha de actualización          |

**Relaciones:**

-   `belongsTo` → Order
-   `belongsTo` → Customer (opcional)
-   `belongsTo` → PaymentMethod
-   `hasOne` → Receipt

**Métodos útiles:**

-   `markAsPaid(?string $reference)` - Marca como pagado
-   `markAsFailed()` - Marca como fallido
-   `markAsRefunded()` - Marca como reembolsado
-   `isPaid()` - Verifica si está pagado

**Índices:** `order_id`, `customer_id`, `method_id`, `status`, `paid_at`

---

### Receipts (Comprobantes)

**Tabla:** `receipts`
**Modelo:** `App\Models\Receipt`

| Campo      | Tipo     | Descripción        |
| ---------- | -------- | ------------------ |
| id         | bigint   | ID autoincremental |
| payment_id | bigint   | FK a payments      |
| code       | string   | Código único       |
| file_url   | string   | URL del archivo    |
| type       | enum     | pdf, image         |
| created_at | datetime | Fecha de creación  |

**Relaciones:**

-   `belongsTo` → Payment

**Índices:** `payment_id`, `code` (unique)

---

## 🎁 OFERTAS / PROMOCIONES

### Offers (Ofertas)

**Tabla:** `offers`
**Modelo:** `App\Models\Offer`

| Campo       | Tipo     | Descripción            |
| ----------- | -------- | ---------------------- |
| id          | bigint   | ID autoincremental     |
| title       | string   | Título de la oferta    |
| description | text     | Descripción            |
| starts_at   | datetime | Fecha de inicio        |
| ends_at     | datetime | Fecha de fin           |
| is_active   | boolean  | Oferta activa          |
| created_at  | datetime | Fecha de creación      |
| updated_at  | datetime | Fecha de actualización |

**Relaciones:**

-   `hasMany` → OfferItems

**Métodos útiles:**

-   `isCurrentlyActive()` - Verifica vigencia actual
-   `getDaysRemainingAttribute()` - Días restantes

**Índices:** `is_active`, `starts_at`, `ends_at`

---

### OfferItems (Items en Oferta)

**Tabla:** `offer_items`
**Modelo:** `App\Models\OfferItem`

| Campo          | Tipo          | Descripción                      |
| -------------- | ------------- | -------------------------------- |
| id             | bigint        | ID autoincremental               |
| offer_id       | bigint        | FK a offers                      |
| product_id     | bigint        | FK a products                    |
| variant_id     | bigint        | FK a product_variants (opcional) |
| discount_type  | enum          | percent, fixed                   |
| discount_value | decimal(10,2) | Valor del descuento              |
| created_at     | datetime      | Fecha de creación                |

**Relaciones:**

-   `belongsTo` → Offer
-   `belongsTo` → Product
-   `belongsTo` → ProductVariant (opcional)

**Métodos útiles:**

-   `calculateDiscountAmount(float $basePrice)` - Calcula monto de descuento
-   `calculateFinalPrice(float $basePrice)` - Calcula precio final

**Índices:** `offer_id`, `product_id`, `variant_id`

---

## 📹 LIVES

### LiveSessions (Sesiones en Vivo)

**Tabla:** `live_sessions`
**Modelo:** `App\Models\LiveSession`

| Campo      | Tipo     | Descripción                        |
| ---------- | -------- | ---------------------------------- |
| id         | bigint   | ID autoincremental                 |
| title      | string   | Título de la sesión                |
| platform   | enum     | facebook, tiktok, instagram, other |
| live_url   | string   | URL de la transmisión              |
| is_live    | boolean  | En vivo actualmente                |
| starts_at  | datetime | Fecha de inicio                    |
| ends_at    | datetime | Fecha de fin                       |
| created_at | datetime | Fecha de creación                  |
| updated_at | datetime | Fecha de actualización             |

**Relaciones:**

-   `hasMany` → LiveProductHighlights

**Métodos útiles:**

-   `start(?string $liveUrl)` - Inicia la sesión
-   `end()` - Termina la sesión
-   `getDurationMinutesAttribute()` - Duración en minutos
-   `isScheduled()` - Verifica si está programado

**Índices:** `is_live`, `platform`, `starts_at`

---

### LiveProductHighlights (Productos Destacados en Live)

**Tabla:** `live_product_highlights`
**Modelo:** `App\Models\LiveProductHighlight`

| Campo           | Tipo   | Descripción                      |
| --------------- | ------ | -------------------------------- |
| id              | bigint | ID autoincremental               |
| live_session_id | bigint | FK a live_sessions               |
| product_id      | bigint | FK a products                    |
| variant_id      | bigint | FK a product_variants (opcional) |
| position        | int    | Orden de presentación            |

**Relaciones:**

-   `belongsTo` → LiveSession
-   `belongsTo` → Product
-   `belongsTo` → ProductVariant (opcional)

**Índices:** `live_session_id`, `product_id`, `variant_id`

---

### LivePurchaseGuides (Guías de Compra)

**Tabla:** `live_purchase_guides`
**Modelo:** `App\Models\LivePurchaseGuide`

| Campo        | Tipo     | Descripción            |
| ------------ | -------- | ---------------------- |
| id           | bigint   | ID autoincremental     |
| video_url    | string   | URL del video guía     |
| text         | text     | Texto de la guía       |
| whatsapp_url | string   | URL de WhatsApp        |
| cart_url     | string   | URL del carrito        |
| offers_url   | string   | URL de ofertas         |
| is_active    | boolean  | Guía activa            |
| created_at   | datetime | Fecha de creación      |
| updated_at   | datetime | Fecha de actualización |

**Índices:** `is_active`

---

## 📄 PÁGINAS / POLÍTICAS / SETTINGS

### Pages (Páginas del Sitio)

**Tabla:** `pages`
**Modelo:** `App\Models\Page`

| Campo      | Tipo     | Descripción                         |
| ---------- | -------- | ----------------------------------- |
| id         | bigint   | ID autoincremental                  |
| key        | string   | Clave única (about, shipping, etc.) |
| title      | string   | Título de la página                 |
| content    | longtext | Contenido HTML/Markdown             |
| is_active  | boolean  | Página activa                       |
| created_at | datetime | Fecha de creación                   |
| updated_at | datetime | Fecha de actualización              |

**Métodos útiles:**

-   `findByKey(string $key)` - Busca página por clave

**Índices:** `key` (unique), `is_active`

---

### Policies (Políticas)

**Tabla:** `policies`
**Modelo:** `App\Models\Policy`

| Campo      | Tipo     | Descripción                            |
| ---------- | -------- | -------------------------------------- |
| id         | bigint   | ID autoincremental                     |
| key        | string   | Clave única (terms, privacy, warranty) |
| title      | string   | Título de la política                  |
| content    | longtext | Contenido HTML/Markdown                |
| is_active  | boolean  | Política activa                        |
| created_at | datetime | Fecha de creación                      |
| updated_at | datetime | Fecha de actualización                 |

**Métodos útiles:**

-   `findByKey(string $key)` - Busca política por clave

**Índices:** `key` (unique), `is_active`

---

### SiteSettings (Configuraciones del Sitio)

**Tabla:** `site_settings`
**Modelo:** `App\Models\SiteSetting`

| Campo      | Tipo     | Descripción                                          |
| ---------- | -------- | ---------------------------------------------------- |
| id         | bigint   | ID autoincremental                                   |
| group      | string   | Grupo (header, social, schedule, whatsapp, branding) |
| key        | string   | Clave dentro del grupo                               |
| value      | json     | Valor en formato JSON                                |
| created_at | datetime | Fecha de creación                                    |
| updated_at | datetime | Fecha de actualización                               |

**Métodos útiles:**

-   `get(string $group, string $key, $default)` - Obtiene configuración
-   `set(string $group, string $key, $value)` - Establece configuración
-   `getGroup(string $group)` - Obtiene todas las configuraciones de un grupo

**Índices:** `group` + `key` (unique), `group`

---

### Banners (Banners Promocionales)

**Tabla:** `banners`
**Modelo:** `App\Models\Banner`

| Campo      | Tipo     | Descripción            |
| ---------- | -------- | ---------------------- |
| id         | bigint   | ID autoincremental     |
| title      | string   | Título del banner      |
| text       | text     | Texto del banner       |
| link_url   | string   | URL de destino         |
| position   | int      | Orden de visualización |
| is_active  | boolean  | Banner activo          |
| created_at | datetime | Fecha de creación      |
| updated_at | datetime | Fecha de actualización |

**Métodos útiles:**

-   `active()` - Scope para banners activos ordenados

**Índices:** `position`, `is_active`

---

## 📊 TRACKING / PIXELES

### TrackingPixels (Píxeles de Seguimiento)

**Tabla:** `tracking_pixels`
**Modelo:** `App\Models\TrackingPixel`

| Campo      | Tipo     | Descripción                 |
| ---------- | -------- | --------------------------- |
| id         | bigint   | ID autoincremental          |
| platform   | enum     | meta, tiktok, other         |
| pixel_id   | string   | ID del píxel                |
| is_active  | boolean  | Píxel activo                |
| settings   | json     | Configuraciones adicionales |
| created_at | datetime | Fecha de creación           |
| updated_at | datetime | Fecha de actualización      |

**Índices:** `platform`, `is_active`

---

## 📈 CORTES / REPORTES

### WeeklyCuts (Cortes Semanales)

**Tabla:** `weekly_cuts`
**Modelo:** `App\Models\WeeklyCut`

| Campo      | Tipo     | Descripción               |
| ---------- | -------- | ------------------------- |
| id         | bigint   | ID autoincremental        |
| week_start | date     | Fecha de inicio de semana |
| week_end   | date     | Fecha de fin de semana    |
| created_by | bigint   | FK a users                |
| notes      | text     | Notas del corte           |
| created_at | datetime | Fecha de creación         |

**Relaciones:**

-   `belongsTo` → User (created_by)
-   `hasMany` → WeeklyCutDetails

**Métodos útiles:**

-   `getTotalSalesAttribute()` - Total de ventas
-   `getTotalCostsAttribute()` - Total de costos
-   `getTotalIvaAttribute()` - Total de IVA
-   `getTotalNetProfitAttribute()` - Utilidad neta total
-   `getTotalOrdersAttribute()` - Total de órdenes

**Índices:** `week_start`, `week_end`

---

### WeeklyCutDetails (Detalles de Corte por Categoría)

**Tabla:** `weekly_cut_details`
**Modelo:** `App\Models\WeeklyCutDetail`

| Campo         | Tipo          | Descripción         |
| ------------- | ------------- | ------------------- |
| id            | bigint        | ID autoincremental  |
| weekly_cut_id | bigint        | FK a weekly_cuts    |
| category_id   | bigint        | FK a categories     |
| sales_total   | decimal(12,2) | Total de ventas     |
| costs_total   | decimal(12,2) | Total de costos     |
| iva_total     | decimal(12,2) | Total de IVA        |
| net_profit    | decimal(12,2) | Utilidad neta       |
| orders_count  | int           | Cantidad de órdenes |

**Relaciones:**

-   `belongsTo` → WeeklyCut
-   `belongsTo` → Category

**Métodos útiles:**

-   `getProfitMarginAttribute()` - Margen de utilidad (%)

**Índices:** `weekly_cut_id`, `category_id`

---

## 📋 Resumen de Características Implementadas

### ✅ Buenas Prácticas

-   **Foreign Keys:** Todas las relaciones con constraints adecuados
-   **Soft Deletes:** En modelos principales (productos, clientes, etc.)
-   **Índices:** En campos de búsqueda y foreign keys
-   **Casts:** Tipos de datos apropiados (decimal, boolean, datetime, json)
-   **Fillable:** Mass assignment protection
-   **Timestamps:** Control automático de created_at/updated_at
-   **Naming:** Convenciones de Laravel (snake_case, plural para tablas)

### 🔗 Relaciones Implementadas

-   One to Many (hasMany/belongsTo)
-   One to One (hasOne)
-   Has Many Through
-   Relaciones opcionales (nullable foreign keys)

### 🛠️ Métodos Útiles

-   Cálculos automáticos (totales, IVA, stock)
-   Validaciones de estado
-   Cambios de estado con historial
-   Scopes para consultas comunes
-   Accessors para atributos calculados

### 📊 Soporte para

-   E-commerce completo
-   Live Shopping
-   Pagos múltiples y financiamiento
-   Gestión de inventario
-   Sistema de ofertas y promociones
-   Tracking y analytics
-   CMS básico (páginas, políticas, configuración)
-   Reportes y cortes de caja

---

**Fecha de creación:** 15 de diciembre de 2025  
**Total de tablas:** 30  
**Total de modelos:** 30
