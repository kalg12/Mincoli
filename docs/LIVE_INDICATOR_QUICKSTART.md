# Guía Rápida - Indicador de Live Activo

## ¿Qué se agregó?

Un indicador visual profesional que aparece en el header al lado del logo, mostrando cuando hay una transmisión en vivo activa. Los usuarios pueden hacer clic para ver una vista previa con los productos destacados en vivo.

## Características Principales

✓ **Indicador Visual Llamativo**: Botón pulsante al lado del logo
✓ **Efecto de Animación**: Punto rojo parpadeante que atrae la atención
✓ **Modal de Vista Previa**: Muestra información y productos destacados
✓ **Responsive**: Se adapta a dispositivos móviles
✓ **Actualizaciones en Tiempo Real**: Usa Livewire para sincronización automática
✓ **Fácil de Usar**: Interfaz intuitiva para usuarios y administradores

## Instrucciones de Uso Rápido

### Para Desarrolladores

#### 1. Crear una transmisión en vivo

```bash
php artisan live:manage create --title="Mi Transmisión" --platform="Instagram Live" --url="https://..."
```

#### 2. Iniciar una transmisión

```bash
php artisan live:manage start --id=1
```

#### 3. Detener una transmisión

```bash
php artisan live:manage stop --id=1
```

#### 4. Listar todas las transmisiones

```bash
php artisan live:manage list
```

### Para Administradores en Panel

Si tienes un panel de administración, puedes:

1. **Crear sesión de live**

```php
use App\Models\LiveSession;

$live = LiveSession::create([
    'title' => 'Especial de Verano',
    'platform' => 'Instagram Live',
    'live_url' => 'https://www.instagram.com/mincolimx/live/',
]);
```

2. **Agregar productos destacados**

```php
use App\Models\LiveProductHighlight;

LiveProductHighlight::create([
    'live_session_id' => $live->id,
    'product_id' => 1,
    'description' => 'Descripción personalizada',
    'position' => 1,
]);
```

3. **Iniciar la transmisión**

```php
$live->start();
```

4. **Detener la transmisión**

```php
$live->end();
```

## Estructura de Archivos Creados

```
app/
├── Livewire/
│   └── LiveIndicator.php (Componente Livewire)
├── Observers/
│   └── LiveSessionObserver.php (Observer para eventos)
└── Console/Commands/
    └── ManageLiveSession.php (Comando Artisan)

resources/
├── views/livewire/
│   └── live-indicator.blade.php (Vista del componente)
└── css/
    └── live-indicator.css (Estilos)

database/
├── factories/
│   └── LiveSessionFactory.php (Factory para pruebas)
└── seeders/
    └── LiveSessionSeeder.php (Seeder para datos de prueba)

docs/
└── LIVE_INDICATOR_DOCUMENTATION.md (Documentación completa)
```

## Archivos Modificados

- `resources/views/partials/header.blade.php` - Agregado el componente
- `app/Providers/AppServiceProvider.php` - Registrado el observer
- `resources/css/app.css` - Importado el CSS

## Pruebas Rápidas

### Opción 1: Usar el Seeder

```bash
# Agregar el seeder a DatabaseSeeder.php
php artisan db:seed --class=LiveSessionSeeder
```

### Opción 2: Crear manualmente

```bash
php artisan live:manage create
# Sigue las indicaciones interactivas
```

## Cómo se ve

### En Desktop

El indicador aparece así: [🔴 EN VIVO] (pulsando)

- Al lado del logo
- Con animación de pulsación
- Texto claramente visible

### En Mobile

El indicador aparece así: [🔴] (solo icono)

- Al lado del logo
- Ícono más pequeño para ahorrar espacio

## Archivos de Soporte

- Documentación completa: `docs/LIVE_INDICATOR_DOCUMENTATION.md`
- Factory para testing: `database/factories/LiveSessionFactory.php`
- Seeder para datos de prueba: `database/seeders/LiveSessionSeeder.php`

## Solución Rápida de Problemas

| Problema                     | Solución                                                |
| ---------------------------- | ------------------------------------------------------- |
| No aparece el indicador      | Verificar que `is_live = true` y `starts_at <= ahora()` |
| Modal no se abre             | Limpiar caché: `php artisan cache:clear`                |
| Estilos no funcionan         | Compilar: `npm run build`                               |
| No se actualizan los cambios | Verificar que Livewire esté correctamente instalado     |

## Próximas Mejoras

- [ ] Integración de chat en vivo
- [ ] Notificaciones cuando comienza una transmisión
- [ ] Contador de espectadores
- [ ] Historial de transmisiones
- [ ] Integración con YouTube, Twitch, etc.

---

¿Necesitas más ayuda? Consulta la documentación completa en `docs/LIVE_INDICATOR_DOCUMENTATION.md`
