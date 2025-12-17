# 📊 Indicadores de Producto

## En el Listado Admin

### Estado del Stock (Primera etiqueta)

Indica la disponibilidad del producto:

| Color          | Estado     | Significado                         |
| -------------- | ---------- | ----------------------------------- |
| 🔴 **Rojo**    | Agotado    | Stock = 0 (no disponible en tienda) |
| 🟠 **Naranja** | Stock Bajo | Stock ≤ 5 unidades                  |
| 🟢 **Verde**   | Disponible | Stock > 5 unidades                  |

### Estado de Publicación (Segunda etiqueta)

Indica si el producto está visible o no:

| Color           | Estado       | Significado                      |
| --------------- | ------------ | -------------------------------- |
| 🔵 **Azul**     | Published    | Visible en tienda                |
| ⚫ **Gris**     | Draft        | No visible, aún en edición       |
| 🟡 **Amarillo** | Out of Stock | Marcado manualmente como agotado |

---

## En la Tienda (Frontend)

Los productos se muestran con:

-   ✅ **Disponible** = `stock > 0`
-   ❌ **Agotado** = `stock = 0`

> **Nota:** El estado de publicación solo es para el admin. En la tienda se respeta el nivel de stock.

---

## Relación Stock vs Status

**El stock es independiente del status:**

-   Un producto puede estar `Published` pero `Agotado` (stock = 0)
-   Un producto puede estar `Draft` y mostrar "Agotado" en tienda
-   El campo `status` es solo organizativo en el admin

> Verifica siempre el **valor de stock** para saber si tiene inventario.
