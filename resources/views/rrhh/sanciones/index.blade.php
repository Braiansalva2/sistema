@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3" style="color:#a04518;">
        <i class="bi bi-exclamation-octagon me-2"></i>
        Sanciones de {{ $empleado->nombre }} {{ $empleado->apellido }}
    </h3>

    <a href="{{ route('rrhh.sanciones.create', $empleado->id) }}"
       class="btn text-white mb-3 fw-semibold"
       style="background-color:#a04518;">
        <i class="bi bi-plus-circle me-1"></i> Nueva sanción
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($sanciones->count() == 0)

        <div class="alert alert-warning text-center">
            No hay sanciones registradas.
        </div>

    @else

        <table class="table table-hover table-bordered shadow-sm bg-white">
            <thead class="text-center" style="background:#fbe9e1;">
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Documento</th>
                    <th width="150">Acciones</th>
                </tr>
            </thead>

            <tbody class="text-center">
                @foreach ($sanciones as $s)
                <tr>
                    <td>{{ $s->fecha_sancion }}</td>

                    <td>{{ ucfirst($s->tipo_sancion) }}</td>

                    <td>
                        <span class="badge 
                            {{ $s->estado == 'vigente' ? 'bg-warning text-dark' : 'bg-success' }}">
                            {{ ucfirst($s->estado) }}
                        </span>
                    </td>

                    <td>
                        @if($s->documento_path)
                            <button class="btn btn-link p-0 text-primary"
                                    onclick="verDocumento('{{ Storage::url($s->documento_path) }}')">
                                Ver PDF
                            </button>
                        @else
                            <span class="text-muted">Sin archivo</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('rrhh.sanciones.edit', [$empleado->id, $s->id]) }}"
                           class="btn btn-warning btn-sm">
                            Editar
                        </a>

                        <form action="{{ route('rrhh.sanciones.destroy', [$empleado->id, $s->id]) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Eliminar sanción?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

    @endif

</div>


<!-- ✅ MODAL PARA PREVISUALIZAR PDF -->
<div class="modal fade" id="modalPDF" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                    Vista previa del documento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <iframe id="pdfFrame" src="" style="width:100%; height:80vh;" frameborder="0"></iframe>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
function verDocumento(url) {
    document.getElementById('pdfFrame').src = url;
    new bootstrap.Modal(document.getElementById('modalPDF')).show();
}
</script>
@endpush
