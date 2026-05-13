<?php

namespace App\Http\Controllers\Trafico;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use App\Models\ModeloVehiculo;
use App\Models\TipoVehiculo;
use App\Models\Unidad;
use App\Models\EmpresaTercerizada;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class UnidadController extends Controller
{
 public function index()
{
    if (request()->ajax()) {

        $unidades = Unidad::with([
            'marca',
            'modelo',
            'tipoVehiculo',
            'empresaTercerizada',
            'legajos'
        ])->get();

        $unidades = $unidades->map(function ($u) {

            // VTV
            $vtv = $u->legajos->first(fn($d) =>
                strtolower($d->tipo_documento) === 'vtv'
            );

            // PÓLIZA
            $poliza = $u->legajos->first(fn($d) =>
                strtolower($d->tipo_documento) === 'poliza'
            );

            return [
                'id'            => $u->id,
                'cod_interno'   => $u->cod_interno,
                'dominio'       => $u->dominio,
                'color'         => $u->color,

                // RELACIONES
                'marca'         => $u->marca,
                'modelo'        => $u->modelo,
                'tipo_vehiculo' => $u->tipoVehiculo,
                'empresa_tercerizada' => $u->empresaTercerizada,

                // DATOS GENERALES
                'anio'          => $u->anio,
                'km_actual'     => $u->km_actual,
                'estado'        => $u->estado,
                'origen'        => $u->origen,

                // DATOS TÉCNICOS
                'capacidad_kg'  => $u->capacidad_kg,
                'largo_total'   => $u->largo_total,
                'ancho'         => $u->ancho,
                'alto'          => $u->alto,

                // FECHAS
                'fecha_alta'    => $u->fecha_alta,
                'fecha_baja'    => $u->fecha_baja,

                // OTROS
                'observaciones' => $u->observaciones,

                // DOCUMENTACIÓN
                'vtv_vto'       => optional($vtv)->fecha_vencimiento,
                'poliza_vto'    => optional($poliza)->fecha_vencimiento,
            ];
        });

        return response()->json(['data' => $unidades]);
    }

    return view('trafico.index');
}



    public function create()
    {
        return view('trafico.unidades.create', [
            'marcas' => Marca::all(),
            'modelos' => ModeloVehiculo::all(),
            'tipos'  => TipoVehiculo::all(),
             'empresas' => \App\Models\EmpresaTercerizada::all()
        ]);
    }

   public function store(Request $request)
{
    $request->validate([
        'cod_interno' => 'required|unique:unidades,cod_interno',
        'marca_id' => 'required|exists:marcas,id',
        'modelo_id' => 'required|exists:modelos,id',
        'tipo_vehiculo_id' => 'required|exists:tipos_vehiculos,id',
        'anio' => 'required|integer',
        'estado' => 'required|in:activo,inactivo,baja,taller',

        // Origen
        'origen' => 'required|in:propio,tercerizado',
        'empresa_tercerizada_id' => 'nullable|required_if:origen,tercerizado|exists:empresas_tercerizadas,id',

        // Datos técnicos (opcionales)
        'capacidad_kg' => 'nullable|integer|min:0',
        'largo_total'  => 'nullable|numeric|min:0',
        'alto'         => 'nullable|numeric|min:0',
        'ancho'        => 'nullable|numeric|min:0',
    ]);

    $data = $request->all();

    /*
     |--------------------------------------------------------------------------
     | Limpieza de datos según lógica de negocio
     |--------------------------------------------------------------------------
     */

    // Si es propio → no debe guardar empresa tercerizada
    if ($data['origen'] === 'propio') {
        $data['empresa_tercerizada_id'] = null;
    }

    // Si NO es vehículo pesado → limpiar datos técnicos
    $tipo = \App\Models\TipoVehiculo::find($data['tipo_vehiculo_id']);

    if ($tipo) {
        $nombreTipo = strtolower(
            iconv('UTF-8', 'ASCII//TRANSLIT', $tipo->nombre)
        );

        $clavesPesados = ['camion', 'semi', 'acoplado', 'carreton', 'bitren', 'tractor'];

        $esPesado = false;
        foreach ($clavesPesados as $clave) {
            if (str_contains($nombreTipo, $clave)) {
                $esPesado = true;
                break;
            }
        }

        if (!$esPesado) {
            $data['capacidad_kg'] = null;
            $data['largo_total']  = null;
            $data['alto']         = null;
            $data['ancho']        = null;
        }
    }

    Unidad::create($data);

    return redirect()
        ->route('trafico.unidades.index')
        ->with('success', 'Unidad registrada correctamente.');
}


    /* ============================
       EDITAR
       ============================ */
public function edit($id)
{
    $unidad = Unidad::findOrFail($id);

    $marcas   = Marca::orderBy('nombre')->get();
    $tipos    = TipoVehiculo::orderBy('nombre')->get();
    $empresas = EmpresaTercerizada::orderBy('nombre')->get();

    // modelos SOLO de la marca actual
    $modelosActuales = ModeloVehiculo::where('marca_id', $unidad->marca_id)->get();

    return view('trafico.unidades.edit', compact(
        'unidad',
        'marcas',
        'tipos',
        'empresas',
        'modelosActuales'
    ));
}



    /* ============================
       ACTUALIZAR
       ============================ */
 public function update(Request $request, $id)
{
    $unidad = Unidad::findOrFail($id);

    $request->validate([
        'cod_interno' => "required|unique:unidades,cod_interno,{$id}",
        'marca_id' => 'required|exists:marcas,id',
        'modelo_id' => 'required|exists:modelos,id',
        'tipo_vehiculo_id' => 'required|exists:tipos_vehiculos,id',
        'anio' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        'estado' => 'required|in:activo,inactivo,baja,taller',

        'origen' => 'required|in:propio,tercerizado',
        'empresa_tercerizada_id' => 'nullable|required_if:origen,tercerizado|exists:empresas_tercerizadas,id',

        'capacidad_kg' => 'nullable|integer|min:0',
        'largo_total'  => 'nullable|numeric|min:0',
        'alto'         => 'nullable|numeric|min:0',
        'ancho'        => 'nullable|numeric|min:0',
    ]);

    $data = $request->all();

    /* ==========================================================
       ORIGEN → EMPRESA TERCERIZADA
       ========================================================== */
    if ($data['origen'] === 'propio') {
        $data['empresa_tercerizada_id'] = null;
    }

    /* ==========================================================
       VALIDAR DATOS TÉCNICOS SEGÚN TIPO DE VEHÍCULO
       (misma lógica que en JS, sin falsos positivos)
       ========================================================== */
    $tipo = \App\Models\TipoVehiculo::find($data['tipo_vehiculo_id']);

    if ($tipo) {
        // normalizar texto (minúsculas + sin tildes)
        $nombreTipo = strtolower(
            iconv('UTF-8', 'ASCII//TRANSLIT', $tipo->nombre)
        );

        // separar en palabras → evita "camioneta" ≠ "camion"
        $palabras = preg_split('/\s+/', $nombreTipo, -1, PREG_SPLIT_NO_EMPTY);

        $clavesPesados = [
            'camion',
            'semi',
            'acoplado',
            'carreton',
            'bitren',
            'tractor'
        ];

        $esPesado = false;
        foreach ($palabras as $palabra) {
            if (in_array($palabra, $clavesPesados)) {
                $esPesado = true;
                break;
            }
        }

        // si NO es pesado → limpiar datos técnicos
        if (!$esPesado) {
            $data['capacidad_kg'] = null;
            $data['largo_total']  = null;
            $data['alto']         = null;
            $data['ancho']        = null;
        }
    }

    $unidad->update($data);

    return redirect()
        ->route('trafico.unidades.index')
        ->with('success', 'Unidad actualizada correctamente.');
}


    /* ============================
       ELIMINAR
       ============================ */
    public function destroy($id)
    {
        Unidad::destroy($id);

        return response()->json(['success' => true]);
    }

    public function getModelos($marca_id)
    {
        return ModeloVehiculo::where('marca_id', $marca_id)->get();
    }

private function queryUnidadesFiltradas(Request $request)
{
    $query = Unidad::with(['marca', 'modelo', 'tipoVehiculo', 'legajos']);

    // 🔎 SEARCH GLOBAL (DataTables)
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('cod_interno', 'like', "%$search%")
              ->orWhere('dominio', 'like', "%$search%")
              ->orWhereHas('marca', fn($m) =>
                  $m->where('nombre', 'like', "%$search%")
              )
              ->orWhereHas('modelo', fn($m) =>
                  $m->where('nombre', 'like', "%$search%")
              )
              ->orWhereHas('tipoVehiculo', fn($t) =>
                  $t->where('nombre', 'like', "%$search%")
              );
        });
    }

    return $query->get();
}


public function exportCSV(Request $request)
{
    $unidades = $this->queryUnidadesFiltradas($request);

    $filename = 'unidades_' . now()->format('Ymd_His') . '.csv';

    header("Content-Type: text/csv; charset=UTF-8");
    header("Content-Disposition: attachment; filename={$filename}");

    $output = fopen("php://output", "w");

    // BOM
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

   // CABECERA
fputcsv($output, ['REPORTE DE UNIDADES - SISTEMA GVH'], ';');
fputcsv($output, ['Fecha de generación', now()->format('d/m/Y H:i')], ';');

// Filtros legibles
$filtros = [];
if ($request->filled('search')) {
    $filtros[] = 'Búsqueda: "' . $request->search . '"';
}

fputcsv($output, ['Filtros aplicados', implode(' | ', $filtros)], ';');

// Línea vacía
fputcsv($output, [''], ';');

// ENCABEZADOS
fputcsv($output, [
    'COD INTERNO',
    'MARCA',
    'MODELO',
    'AÑO',
    'ESTADO',
    'VTV (VENC.)',
    'PÓLIZA (VENC.)'
], ';');


    foreach ($unidades as $u) {

        $vtv = $u->legajos->first(fn($d) => strtolower($d->tipo_documento) === 'vtv');
        $poliza = $u->legajos->first(fn($d) => strtolower($d->tipo_documento) === 'poliza');

       fputcsv($output, [
    $u->cod_interno,
    optional($u->marca)->nombre,
    optional($u->modelo)->nombre,
    $u->anio,
    strtoupper($u->estado),
    optional($vtv)?->fecha_vencimiento
        ? \Carbon\Carbon::parse($vtv->fecha_vencimiento)->format('d/m/Y')
        : '-',
    optional($poliza)?->fecha_vencimiento
        ? \Carbon\Carbon::parse($poliza->fecha_vencimiento)->format('d/m/Y')
        : '-',
], ';');

    }

    fclose($output);
    exit;
}


public function exportPDF(Request $request)
{
    $unidades = $this->queryUnidadesFiltradas($request);

    $pdf = Pdf::loadView('trafico.unidades.pdf', [
        'unidades' => $unidades,
        'fecha'    => now(),
        'filtros'  => $request->query()
    ])->setPaper('A4', 'landscape');

    return $pdf->download('reporte_unidades.pdf');
}


}
