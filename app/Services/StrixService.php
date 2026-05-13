<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class StrixService
{
    private string $baseUrl;
    private string $user;
    private string $password;
    private string $accountId;

    public function __construct()
    {
        $this->baseUrl  = rtrim(config('services.strix.base_url'), '/');
        $this->user     = config('services.strix.user');
        $this->password = config('services.strix.password');
        $this->accountId = config('services.strix.account_id');

        if (!$this->baseUrl || !$this->user || !$this->password || !$this->accountId) {
            throw new \RuntimeException('Configuración STRIX incompleta (.env / services.php)');
        }
    }

    /**
     * Obtener access_token STRIX
     * (API legacy .NET → parámetros por query)
     */
 public function token(): string
{
    return Cache::remember('strix_access_token', now()->addMinutes(10), function () {

        $response = Http::withBasicAuth($this->user, $this->password)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->asForm()
            ->post('https://externos.flotas.strix.com.ar/oauth/token', [
                'grant_type' => 'password',
                'user'       => $this->user,      // STRIX lo pide, pero el username real viene del Basic
                'password'   => $this->password,
                'scope'      => 'all',
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException(
                "STRIX auth error ({$response->status()}): {$response->body()}"
            );
        }
$token = $response->json('access_token');
$parts = explode('.', $token);
$payload = json_decode(base64_decode($parts[1]), true);

//dd($payload);

       return $response->json('access_token');
    });
}




    /**
     * Cliente HTTP autenticado
     */
    private function client()
    {
        return Http::withToken($this->token())
            ->acceptJson()
            ->timeout(20);
    }

    /**
     * Listar TODOS los vehículos (things) de la cuenta
     */
    public function listarThings(): array
    {
        $response = $this->client()
            ->get("{$this->baseUrl}/v1.5/accounts/{$this->accountId}/things");

        if (!$response->successful()) {
            throw new \RuntimeException(
                "STRIX listarThings error ({$response->status()}): {$response->body()}"
            );
        }

        return $response->json();
    }

    /**
     * Obtener posiciones (última o historial corto)
     */
    public function trailLocations(
        string $thingId,
        int $fromMs,
        int $toMs,
        int $limit = 1,
        int $page = 1
    ): array {
        $response = $this->client()->get(
            "{$this->baseUrl}/v1.5/things/{$thingId}/trail_locations",
            [
                '_from'  => $fromMs,
                '_to'    => $toMs,
                '_limit' => $limit,
                '_page'  => $page,
                '_type'  => 'valid',
            ]
        );

        if (!$response->successful()) {
            throw new \RuntimeException(
                "STRIX trailLocations error ({$response->status()}): {$response->body()}"
            );
        }

        return $response->json();
    }

    public function listarCuentas(): array
{
    $response = $this->client()
        ->get("{$this->baseUrl}/v1.5/accounts");

    if (!$response->successful()) {
        throw new \RuntimeException(
            "STRIX listarCuentas error ({$response->status()}): {$response->body()}"
        );
    }

    return $response->json();
}

public function listarThingsPorCuenta(string $accountId): array
{
    $response = $this->client()
        ->get("{$this->baseUrl}/v1.5/accounts/{$accountId}/things");

    if (!$response->successful()) {
        throw new \RuntimeException(
            "STRIX listarThings error ({$response->status()}): {$response->body()}"
        );
    }

    return $response->json();
}

 public function listarTodasLasUnidades(): array
    {
        $cuentas = $this->listarCuentas();

        $todasLasUnidades = [];

        foreach ($cuentas as $cuenta) {

            if (!isset($cuenta['id'])) {
                continue;
            }

            $accountId = $cuenta['id'];

            $things = $this->listarThingsPorCuenta($accountId);

            foreach ($things as $thing) {
                $todasLasUnidades[] = [
                    'account_id' => $accountId,
                    'thing_id'   => $thing['id'] ?? null,
                    'dominio'    => $thing['info']['domain'] ?? null,
                    'marca'      => $thing['info']['make'] ?? null,
                    'modelo'     => $thing['info']['model'] ?? null,
                    'anio'       => $thing['info']['year'] ?? null,
                    'estado'     => ($thing['last_signal_is_invalid'] ?? false)
                                    ? 'sin señal'
                                    : 'activo',
                    'raw'        => $thing,
                ];
            }
        }

        return $todasLasUnidades;
    }
}
