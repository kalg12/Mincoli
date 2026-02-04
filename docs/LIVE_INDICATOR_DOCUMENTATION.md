# Indicador de Live Activo - Documentación

## Descripción General

Se ha integrado un indicador visual de transmisión en vivo (Live) en el header de la aplicación, justo al lado del logo. Este indicador permite a los usuarios identificar rápidamente cuando hay una transmisión en curso y acceder a una vista previa con los productos destacados.

## Características

### 1. **Indicador Visual**

- Ubicación: Al lado del logo en el header
- Efecto: Pulsación continua (animación con puntos rojos parpadeantes)
- Estados:
    - **EN VIVO**: Cuando hay una transmisión activa (botón rojo/rosa con animación)
    - **SIN VIVO**: Cuando no hay transmisión (botón gris deshabilitado)

### 2. **Responsive Design**

- **Desktop**: Muestra texto "EN VIVO" + icono de transmisión
- **Mobile**: Solo muestra el icono para ahorrar espacio

### 3. **Modal de Vista Previa**

Al hacer clic en el indicador se abre un modal con:

- Título de la transmisión
- Estado en vivo (con indicador visual)
- Plataforma de transmisión
- Hora de inicio hace cuánto tiempo
- Grid de productos destacados con:
    - Imagen del producto
    - Nombre
    - Precio actual
    - Descuento (si aplica)
    - Descripción del producto
- Botones de acción:
    - "Ver Transmisión" - Redirige al enlace de la transmisión en vivo
    - "Cerrar" - Cierra el modal

## Componentes Creados/Modificados

### Nuevos Archivos

1. **`app/Livewire/LiveIndicator.php`**
    - Componente Livewire que gestiona la lógica del indicador
    - Carga la sesión de live activa
    - Maneja la apertura del modal de vista previa
    - Escucha eventos de actualización de live sessions

2. **`resources/views/livewire/live-indicator.blade.php`**
    - Vista Blade del componente
    - Renderiza el indicador y el modal
    - Implementa el diseño responsive

3. **`app/Observers/LiveSessionObserver.php`**
    - Observer que vigila cambios en el modelo LiveSession
    - Emite eventos cuando se crea, actualiza o elimina una sesión
    - Permite que el indicador se actualice en tiempo real

4. **`resources/css/live-indicator.css`**
    - Estilos CSS personalizados
    - Animaciones de pulsación y efectos visuales
    - Mejoras responsive

### Archivos Modificados

1. **`resources/views/partials/header.blade.php`**
    - Agregado el componente Livewire al lado del logo

2. **`app/Providers/AppServiceProvider.php`**
    - Registrado el observer para LiveSession

3. **`resources/css/app.css`**
    - Importado el archivo de estilos del indicador

## Cómo Usar

### Para los Administradores

#### Crear una Transmisión en Vivo

```php
use App\Models\LiveSession;

$live = LiveSession::create([
    'title' => 'Especial de Verano - Productos Destacados',
    'platform' => 'Instagram Live', // o 'Facebook Live', 'TikTok Live', etc.
    'live_url' => 'https://www.instagram.com/mincolimx/live/', // URL de la transmisión
    'is_live' => false,
    'starts_at' => null,
    'ends_at' => null,
]);
```

#### Iniciar la Transmisión

```php
$live = LiveSession::find($liveSessionId);
$live->start('https://www.instagram.com/mincolimx/live/'); // URL opcional
```

#### Detener la Transmisión

```php
$live = LiveSession::find($liveSessionId);
$live->end();
```

#### Agregar Productos Destacados

```php
use App\Models\LiveProductHighlight;

LiveProductHighlight::create([
    'live_session_id' => $liveSessionId,
    'product_id' => $productId,
    'variant_id' => $variantId, // opcional
    'description' => 'Descripción personalizada del producto en la transmisión',
    'position' => 1,
]);
```

### Para los Usuarios

1. El indicador aparecerá automáticamente cuando haya una transmisión en vivo
2. Hacer clic en el botón "EN VIVO" para ver la vista previa
3. Ver los productos destacados en la transmisión
4. Hacer clic en "Ver Transmisión" para unirse a la transmisión en vivo

## Actualización en Tiempo Real

El componente se actualiza automáticamente cuando:

- Se crea una nueva sesión de live
- Se inicia una transmisión (is_live cambia a true)
- Se detiene una transmisión (is_live cambia a false)
- Se elimina una sesión de live

Esto se logra a través del Observer que emite el evento `live-session-updated` que Livewire escucha.

## Estilos CSS

### Clases Principales

- `.live-indicator-button`: Clase del botón principal
- `.live-indicator-pulse`: Animación de pulsación del indicador
- `.live-preview-modal`: Clase del modal de vista previa
- `.live-indicator-shimmer`: Efecto de brillo

### Animaciones Incluidas

- `pulse-glow`: Efecto de brillo pulsante
- `scale-pulse`: Animación de escala suave
- `shimmer`: Efecto de parpadeo
- `slideUp`: Animación de entrada del modal

## Estructura de Base de Datos

El sistema utiliza las siguientes tablas:

### live_sessions

```sql
- id
- title (string)
- platform (string, nullable)
- live_url (string, nullable)
- is_live (boolean)
- starts_at (datetime, nullable)
- ends_at (datetime, nullable)
- created_at
- updated_at
```

### live_product_highlights

```sql
- id
- live_session_id (foreign key)
- product_id (foreign key)
- variant_id (foreign key, nullable)
- description (text, nullable)
- position (integer)
- created_at
- updated_at
```

## Funcionalidades Futuras

Posibles mejoras:

1. Notificaciones cuando comienza una transmisión
2. Chat integrado en la vista previa
3. Contador de espectadores
4. Historial de transmisiones
5. Integración con plataformas de streaming (YouTube, Twitch, etc.)
6. Estadísticas de visualización

## Solución de Problemas

### El indicador no aparece

1. Verificar que haya una sesión de live con `is_live = true`
2. Verificar que `starts_at` sea menor o igual a `now()`
3. Verificar que `ends_at` sea null o mayor a `now()`
4. Limpiar la caché: `php artisan cache:clear`

### El modal no se abre

1. Verificar que Livewire esté correctamente instalado
2. Verificar que el componente esté registrado en `AppServiceProvider`
3. Revisar la consola del navegador para errores

### Los estilos no se aplican

1. Ejecutar `npm run build` para compilar los estilos
2. Verificar que el CSS esté importado correctamente en `app.css`
3. Limpiar la caché del navegador (Ctrl+Shift+Delete)

## Soporte

Para más información sobre la integración, consultar:

- Documentación de Livewire: https://livewire.laravel.com/
- Documentación de Laravel: https://laravel.com/docs/
