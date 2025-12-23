<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'product_id',
        'variant_id',
        'url',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product this image belongs to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant this image belongs to (if any)
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Retorna una URL apta para <img> (convierte enlaces públicos de Drive a vista directa).
     */
    public function getUrlAttribute($value)
    {
        return self::resolveDriveUrl($value);
    }

    public static function resolveDriveUrl(?string $url): ?string
    {
        if (!$url) {
            return $url;
        }

        // Si ya es un enlace directo de Drive
        if (str_contains($url, 'drive.google.com/uc?export=')) {
            return $url;
        }

        // Formato file/d/{id}/view
        if (preg_match('~drive\.google\.com/file/d/([^/]+)/~', $url, $m)) {
            return self::directDriveUrl($m[1]);
        }

        // Formato open?id=
        if (preg_match('~drive\.google\.com/open\?id=([^&]+)~', $url, $m)) {
            return self::directDriveUrl($m[1]);
        }

        // Formato uc?export=download&id=
        if (preg_match('~drive\.google\.com/uc\?[^#?]*id=([^&]+)~', $url, $m)) {
            return self::directDriveUrl($m[1]);
        }

        // Formato drive.usercontent.google.com/download?id=
        if (preg_match('~drive\.usercontent\.google\.com/download\?id=([^&]+)~', $url, $m)) {
            return self::directDriveUrl($m[1]);
        }

        // Último intento: parsear query string buscando id
        $parts = parse_url($url);
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
            if (!empty($query['id'])) {
                return self::directDriveUrl($query['id']);
            }
        }

        return $url;
    }

    private static function directDriveUrl(string $id): string
    {
        // usar download para forzar contenido directo y evitar vistas intermedias
        return 'https://drive.google.com/uc?export=download&id=' . $id;
    }
}
