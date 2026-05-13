<?php

namespace App\Exports;

use App\Models\Unidad;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class UnidadesExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $filtros;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
    }

    public function collection()
    {
        $query = Unidad::with(['marca', 'modelo', 'tipoVehiculo', 'legajos']);

        // FILTROS (si querés agregar más después)
        if (!empty($this->filtros['estado'])) {
            $query->where('estado', $this->filtros['estado']);
        }

        $unidades = $query->get();

        return $unidades->map(function ($u) {

            $vtv = $u->legajos->first(function ($doc) {
                return strtolower($doc->tipo_documento) === 'vtv';
            });

            $poliza = $u->legajos->first(function ($doc) {
                return strtolower($doc->tipo_documento) === 'poliza';
            });

            return [
                'Cod Interno'         => $u->cod_interno,
                'Marca'               => $u->marca->nombre,
                'Modelo'              => $u->modelo->nombre,
                'Año'                 => $u->anio,
                'Estado'              => $u->estado,
                'VTV Vencimiento'     => optional($vtv)->fecha_vencimiento,
                'Póliza Vencimiento'  => optional($poliza)->fecha_vencimiento,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Cod Interno',
            'Marca',
            'Modelo',
            'Año',
            'Estado',
            'VTV Vencimiento',
            'Póliza Vencimiento'
        ];
    }
}
