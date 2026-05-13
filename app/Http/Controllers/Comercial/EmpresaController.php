<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ArcaPadronService;

use App\Models\Empresa;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Referente;
use App\Models\EmpresaDocumento;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::orderBy('razon_social')->get();

        return view('comercial.clientes.index', compact('empresas'));
    }

    public function create()
    {
        return view('comercial.clientes.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'razon_social' => 'required|string|max:255',
            'cuit' => 'required|string|max:20|unique:empresas,cuit',
            'tipo_persona' => 'nullable|string|max:100',
            'condicion_iva' => 'nullable|string|max:100',
            'estado_fiscal' => 'nullable|string|max:100',
            'actividad_principal' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',

            'documentos.*.archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::transaction(function () use ($request) {

         $empresa = Empresa::create([
    'razon_social' => $request->razon_social,
    'cuit' => $request->cuit,
    'tipo_persona' => $request->tipo_persona,
    'condicion_iva' => $request->condicion_iva,
    'estado_fiscal' => $request->estado_fiscal,
    'actividad_principal' => $request->actividad_principal,
    'observaciones' => $request->observaciones,
    'estado' => 'activa',
]);

            if ($request->hasFile('logo')) {
                $empresa->logo = $request->file('logo')
                    ->store('empresas/logos', 'public');
                $empresa->save();
            }

            if ($request->has('referentes')) {
                foreach ($request->referentes as $i => $ref) {
                    Referente::create([
                        'empresa_id' => $empresa->id,
                        'nombre' => $ref['nombre'] ?? null,
                        'apellido' => $ref['apellido'] ?? null,
                        'cargo' => $ref['cargo'] ?? null,
                        'telefono' => $ref['telefono'] ?? null,
                        'correo' => $ref['correo'] ?? null,
                        'es_principal' => $i === 0,
                    ]);
                }
            }

            if ($request->has('documentos')) {
                foreach ($request->documentos as $i => $doc) {

                    if (!isset($doc['archivo'])) {
                        continue;
                    }

                    $path = $doc['archivo']->store(
                        'empresas/documentos',
                        'public'
                    );

                    EmpresaDocumento::create([
                        'empresa_id' => $empresa->id,
                        'tipo_documento' => $doc['tipo'] ?? 'otro',
                        'nombre_documento' => $doc['nombre'] ?? $doc['archivo']->getClientOriginalName(),
                        'archivo' => $path,
                        'fecha_vencimiento' => $doc['fecha_vencimiento'] ?? null,
                        'estado' => !empty($doc['fecha_vencimiento']) ? 'vigente' : 'pendiente',
                        'created_by' => auth()->id(),
                    ]);
                }
            }
        });

        return redirect()
            ->route('comercial.clientes.index')
            ->with('success', 'Empresa creada correctamente');
    }

    public function edit(Empresa $empresa)
    {
        $empresa->load(['referentes', 'documentos']);

        return view('comercial.clientes.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        // dd($request->all());

        $request->validate([
            'razon_social' => 'required|string|max:255',
            'cuit' => 'required|string|max:20|unique:empresas,cuit,' . $empresa->id,
            'tipo_persona' => 'nullable|string|max:100',
            'condicion_iva' => 'nullable|string|max:100',
            'estado_fiscal' => 'nullable|string|max:100',
            'actividad_principal' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $empresa->update([
            'razon_social' => $request->razon_social,
            'cuit' => $request->cuit,
            'tipo_persona' => $request->tipo_persona,
            'condicion_iva' => $request->condicion_iva,
            'estado_fiscal' => $request->estado_fiscal,
            'actividad_principal' => $request->actividad_principal,
            'observaciones' => $request->observaciones,
        ]);

        if ($request->hasFile('logo')) {
            if ($empresa->logo) {
                Storage::disk('public')->delete($empresa->logo);
            }

            $empresa->logo = $request->file('logo')
                ->store('empresas/logos', 'public');

            $empresa->save();
        }

        return redirect()
            ->route('comercial.clientes.edit', $empresa)
            ->with('success', 'Empresa actualizada correctamente');
    }

    public function show(Empresa $empresa)
    {
        $empresa->load(['referentes', 'documentos']);

        return view('comercial.clientes.show', compact('empresa'));
    }

    public function destroy(Empresa $empresa)
    {
        foreach ($empresa->documentos as $doc) {
            if ($doc->archivo && Storage::disk('public')->exists($doc->archivo)) {
                Storage::disk('public')->delete($doc->archivo);
            }
        }

        $empresa->delete();

        return redirect()
            ->route('comercial.clientes.index')
            ->with('success', 'Empresa eliminada correctamente');
    }

    public function verificarCuitArca(Request $request)
    {
        //
    }

    // private function mapearIva($persona)
    // {
    //     if (!isset($persona->impuestos)) {
    //         return null;
    //     }

    //     foreach ($persona->impuestos as $imp) {
    //         if ((string) $imp->descripcion === 'IVA') {
    //             return (string) $imp->descripcion;
    //         }
    //     }

    //     return null;
    // }
}