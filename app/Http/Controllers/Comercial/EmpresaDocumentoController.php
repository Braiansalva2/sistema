<?php
namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;
use App\Models\EmpresaDocumento;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaDocumentoController extends Controller
{
    public function update(Request $request, EmpresaDocumento $documento)
    {
        $data = $request->only([
            'tipo_documento',
            'nombre_documento',
            'fecha_vencimiento',
        ]);

        if ($request->hasFile('archivo')) {
            Storage::disk('public')->delete($documento->archivo);
            $data['archivo'] = $request->file('archivo')
                ->store('empresas/documentos', 'public');
        }

        $documento->update($data);

        return back()->with('success', 'Documento actualizado');
    }

    public function destroy(EmpresaDocumento $documento)
    {
        Storage::disk('public')->delete($documento->archivo);
        $documento->delete();

        return back()->with('success', 'Documento eliminado');
    }

    public function store(Request $request, Empresa $empresa)
{
    $request->validate([
        'archivo' => 'required|file|mimes:pdf,jpg,png|max:5120',
    ]);

    $path = $request->file('archivo')
        ->store('empresas/documentos', 'public');

  $empresa->documentos()->create([
    'tipo_documento' => $request->tipo_documento,
    'nombre_documento' => $request->nombre_documento
        ?? $request->file('archivo')->getClientOriginalName(),
    'archivo' => $path,
    'fecha_vencimiento' => $request->fecha_vencimiento,
    'estado' => 'vigente',
    'created_by' => auth()->id(),
]);

    return back()->with('success', 'Documento agregado');
}

}
