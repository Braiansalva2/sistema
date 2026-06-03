<div class="row g-3">

    <div class="col-md-12">
        <label class="form-label">Nombre del grupo</label>
        <input type="text"
               name="nombre"
               class="form-control"
               value="{{ old('nombre', $grupo->nombre ?? '') }}"
               placeholder="Ej: Roster Mayo 2026"
               required>
    </div>

    <div class="col-md-12">
        <label class="form-label">Empleados</label>

        @php
            $seleccionados = old(
                'empleado_ids',
                isset($grupo)
                    ? $grupo->empleados->pluck('id')->toArray()
                    : []
            );
        @endphp
       <select name="empleado_ids[]"
                class="form-select"
                multiple
                size="10"
                required>

            @foreach($empleados as $empleado)
                <option value="{{ $empleado->id }}"
                    {{ in_array($empleado->id, $seleccionados) ? 'selected' : '' }}>
                    {{ $empleado->apellido }} {{ $empleado->nombre }}
                    - DNI: {{ $empleado->dni }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Días trabajo</label>
        <input type="number"
               name="modalidad_trabajo"
               class="form-control"
               value="{{ old('modalidad_trabajo', $grupo->modalidad_trabajo ?? 14) }}"
               required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Días descanso</label>
        <input type="number"
               name="modalidad_descanso"
               class="form-control"
               value="{{ old('modalidad_descanso', $grupo->modalidad_descanso ?? 14) }}"
               required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Fecha subida</label>
        <input type="date"
               name="fecha_subida"
               class="form-control"
               value="{{ old('fecha_subida', isset($grupo) ? $grupo->fecha_subida->format('Y-m-d') : '') }}"
               required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Fecha bajada</label>
        <input type="date"
               name="fecha_bajada"
               class="form-control"
               value="{{ old('fecha_bajada', isset($grupo) ? $grupo->fecha_bajada->format('Y-m-d') : '') }}"
               required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
            <option value="Activo"
                {{ old('estado', $grupo->estado ?? 'Activo') == 'Activo' ? 'selected' : '' }}>
                Activo
            </option>
            <option value="Inactivo"
                {{ old('estado', $grupo->estado ?? 'Activo') == 'Inactivo' ? 'selected' : '' }}>
                Inactivo
            </option>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones"
                  class="form-control"
                  rows="3">{{ old('observaciones', $grupo->observaciones ?? '') }}</textarea>
    </div>

</div>