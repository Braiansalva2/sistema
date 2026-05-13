@extends('layouts.rrhh')

@section('content')

<div class="container py-4">

    <h4 class="mb-4">📄 Gestión de Permisos</h4>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table id="tablaPermisos" class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Empleado</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($permisos as $permiso)
                            <tr>

                                <td>
                                    👤 {{ $permiso->empleado->nombre }}
                                </td>

                                <td>
                                    {{ $permiso->tipo == 'dias' ? 'Días' : 'Horas' }}
                                </td>

                                <td>
                                    @if($permiso->tipo == 'dias')
                                        {{ $permiso->fecha_desde }} → {{ $permiso->fecha_hasta }}
                                    @else
                                        {{ $permiso->fecha_horas }}
                                    @endif
                                </td>

                                <td>
                                    @if($permiso->tipo == 'horas')
                                        {{ $permiso->hora_desde }} → {{ $permiso->hora_hasta }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>{{ $permiso->motivo }}</td>

                                <td>
                                    @if($permiso->estado == 'pendiente')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @elseif($permiso->estado == 'aprobado')
                                        <span class="badge bg-success">Aprobado</span>
                                    @else
                                        <span class="badge bg-danger">Rechazado</span>
                                    @endif
                                </td>

                                <td>
                                    @if($permiso->estado == 'pendiente')
                                        <form method="POST" action="{{ route('rrhh.permisos.aprobar', $permiso) }}" style="display:inline;">
                                            @csrf
                                            <button class="btn btn-success btn-sm">✔</button>
                                        </form>

                                        <form method="POST" action="{{ route('rrhh.permisos.rechazar', $permiso) }}" style="display:inline;">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">✖</button>
                                        </form>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#tablaPermisos').DataTable({
            pageLength: 10,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    });
</script>
@endsection
