# Quick Start - POS

## ⚡ Pasos Rápidos para Activar el POS

### 1️⃣ Ejecutar Migraciones

```bash
php artisan migrate
```

### 2️⃣ Limpiar Cache y Vistas

```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

**O en un solo comando:**

```bash
php artisan cache:clear && php artisan view:clear && php artisan config:clear
```

### 3️⃣ Compilar Assets (si es necesario)

```bash
npm run build
```

### 4️⃣ Verificar Que Todo Está Correcto

Acceder a:

```
http://localhost:8000/dashboard/pos
```

---

## 📱 Uso Básico

### Abrir POS

1. Ir a `/dashboard/pos`
2. Hacer clic en "Abrir Nueva Sesión"
3. Se abre una jornada de trabajo

### Crear Apartado

1. Clic en "Nueva Transacción"
2. Seleccionar cliente (o crear rápido)
3. Buscar productos por SKU/Barcode
4. Agregar cantidad
5. El sistema calcula automáticamente IVA y total

### Registrar Pago

1. En el panel lateral, ingresar monto
2. Seleccionar método de pago
3. Clic en "Registrar Pago"
4. Si es pago completo → "Completar Apartado"
5. Imprimir ticket

### Ver Pendientes

1. Ir a "Productos Pendientes por Enviar"
2. Marcar como "Enviado" o "Completado"
3. El sistema actualiza estados automáticamente

---

## 🔑 Puntos Clave

| Aspecto         | Detalle                                           |
| --------------- | ------------------------------------------------- |
| **Sesión**      | Jornada de trabajo (abierta/cerrada)              |
| **Transacción** | Apartado/venta individual                         |
| **Item**        | Producto en transacción (puede tener variante)    |
| **Pago**        | Abono/pago registrado                             |
| **Estado Item** | reserved → pending_shipment → shipped → completed |
| **Logo**        | `public/apple-touch-icon.png`                     |
| **Moneda**      | Pesos Colombianos ($)                             |

---

## ⚠️ Verificaciones Pre-Uso

-   [ ] Las migraciones se ejecutaron sin errores
-   [ ] Hay al menos un producto activo en la tienda
-   [ ] Existen métodos de pago configurados
-   [ ] El logo está en `public/apple-touch-icon.png`
-   [ ] Puedes acceder a `/dashboard/pos` sin errores

---

## 🆘 Troubleshooting

### "Class POSController not found"

```bash
php artisan cache:clear
composer dump-autoload
```

### "Table pos_sessions doesn't exist"

```bash
php artisan migrate
```

### Helper "currency()" no funciona

```bash
composer dump-autoload
php artisan cache:clear
```

### Tickets no se imprimen

-   Verificar que el navegador tiene permiso de impresión
-   Probar directamente con Ctrl+P o Cmd+P

### Búsqueda de productos no funciona

-   Verificar que el producto tiene `is_active = true`
-   Verificar que tiene SKU o barcode rellenado

---

**¡Listo para comenzar! 🚀**
