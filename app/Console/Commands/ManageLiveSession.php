<?php

namespace App\Console\Commands;

use App\Models\LiveSession;
use Illuminate\Console\Command;

class ManageLiveSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'live:manage
                            {action : Acción a realizar (start, stop, create, list)}
                            {--id= : ID de la sesión de live}
                            {--title= : Título de la sesión}
                            {--platform= : Plataforma (Instagram Live, Facebook Live, etc)}
                            {--url= : URL de la transmisión}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gestiona las sesiones de transmisión en vivo';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = $this->argument('action');

        return match ($action) {
            'start' => $this->startLive(),
            'stop' => $this->stopLive(),
            'create' => $this->createLive(),
            'list' => $this->listLive(),
            default => $this->error("Acción no válida: $action"),
        };
    }

    /**
     * Iniciar una transmisión en vivo
     */
    private function startLive(): int
    {
        $id = $this->option('id');

        if (!$id) {
            $this->error('Se requiere la opción --id');
            return 1;
        }

        $live = LiveSession::find($id);

        if (!$live) {
            $this->error("Sesión de live con ID $id no encontrada");
            return 1;
        }

        $url = $this->option('url') ?? $live->live_url;

        $live->start($url);

        $this->info("✓ Transmisión en vivo iniciada: {$live->title}");
        return 0;
    }

    /**
     * Detener una transmisión en vivo
     */
    private function stopLive(): int
    {
        $id = $this->option('id');

        if (!$id) {
            $this->error('Se requiere la opción --id');
            return 1;
        }

        $live = LiveSession::find($id);

        if (!$live) {
            $this->error("Sesión de live con ID $id no encontrada");
            return 1;
        }

        $live->end();

        $this->info("✓ Transmisión en vivo detenida: {$live->title}");
        return 0;
    }

    /**
     * Crear una nueva transmisión en vivo
     */
    private function createLive(): int
    {
        $title = $this->option('title') ?? $this->ask('Título de la transmisión');
        $platform = $this->option('platform') ?? $this->ask('Plataforma de transmisión (ej: Instagram Live, Facebook Live)');
        $url = $this->option('url') ?? $this->ask('URL de la transmisión (opcional)', '');

        $live = LiveSession::create([
            'title' => $title,
            'platform' => $platform,
            'live_url' => $url ?: null,
            'is_live' => false,
            'starts_at' => null,
            'ends_at' => null,
        ]);

        $this->info("✓ Sesión de live creada exitosamente");
        $this->info("ID: {$live->id}");
        $this->info("Título: {$live->title}");
        $this->info("Plataforma: {$live->platform}");
        return 0;
    }

    /**
     * Listar todas las transmisiones en vivo
     */
    private function listLive(): int
    {
        $lives = LiveSession::orderBy('created_at', 'desc')->get();

        if ($lives->isEmpty()) {
            $this->info('No hay sesiones de live registradas');
            return 0;
        }

        $this->table(
            ['ID', 'Título', 'Estado', 'Plataforma', 'Inicio', 'Fin'],
            $lives->map(function ($live) {
                return [
                    $live->id,
                    substr($live->title, 0, 30) . (strlen($live->title) > 30 ? '...' : ''),
                    $live->is_live ? '🔴 EN VIVO' : '⚫ INACTIVO',
                    $live->platform ?? 'N/A',
                    $live->starts_at?->format('d/m/Y H:i') ?? 'N/A',
                    $live->ends_at?->format('d/m/Y H:i') ?? 'N/A',
                ];
            })->toArray()
        );

        return 0;
    }
}
