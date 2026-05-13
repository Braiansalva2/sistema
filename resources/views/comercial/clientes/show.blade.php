<div style="color:#111 !important;">

    <h5 style="color:#111 !important; font-weight:700; margin-bottom:12px;">Empresa</h5>

    <div style="margin-bottom:8px;">
        <strong>Razón Social:</strong>
        <span style="margin-left:6px;">{{ $empresa->razon_social }}</span>
    </div>

    <div style="margin-bottom:8px;">
        <strong>CUIT:</strong>
        <span style="margin-left:6px;">{{ $empresa->cuit }}</span>
    </div>

    @if($empresa->observaciones)
        <div style="margin-bottom:12px;">
            <strong>Observaciones:</strong><br>
            <span>{{ $empresa->observaciones }}</span>
        </div>
    @endif

    <hr>

    <h6 style="color:#111 !important; font-weight:700;">Referentes</h6>

    @if($empresa->referentes->count())
        <ul class="list-group mb-3">
            @foreach($empresa->referentes as $ref)
                <li class="list-group-item">
                    <strong>{{ $ref->nombre }} {{ $ref->apellido }}</strong>
                    @if($ref->cargo) – {{ $ref->cargo }} @endif
                    <br>
                    {{ $ref->telefono }}<br>
                    {{ $ref->correo }}
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">Sin referentes</p>
    @endif

    <hr>

    <h6 style="color:#111 !important; font-weight:700;">Documentación</h6>

    @if($empresa->documentos->count())
        <ul class="list-group">
            @foreach($empresa->documentos as $doc)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        {{ $doc->nombre_documento }}
                        @if($doc->fecha_vencimiento)
                            <small class="text-muted">
                                (vence {{ $doc->fecha_vencimiento }})
                            </small>
                        @endif
                    </span>

                  <button class="btn btn-sm btn-outline-primary"
        onclick="verDocumento(
            '{{ asset('storage/'.$doc->archivo) }}',
            '{{ $doc->nombre_documento }}'
        )">
   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
</svg>
</button>

                </li>
            @endforeach
        </ul> 
        <hr>

<h6 class="fw-bold">Vista del documento</h6>

<div id="contenedorDocumento"
     class="border rounded p-2 bg-light"
     style="display:none;">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <strong id="tituloDocumento"></strong>
        <button class="btn btn-sm btn-outline-secondary"
                onclick="cerrarDocumento()">
            X
        </button>
    </div>

    <iframe id="iframeDocumento"
            src=""
            style="width:100%; height:500px; border:0;">
    </iframe>
</div>

    @else
        <p class="text-muted">Sin documentos</p>
    @endif

</div>
<script>
function verDocumento(url, nombre) {
    document.getElementById('iframeDocumento').src = url;
    document.getElementById('tituloDocumento').innerText = nombre || 'Documento';
    document.getElementById('contenedorDocumento').style.display = 'block';
}

function cerrarDocumento() {
    document.getElementById('iframeDocumento').src = '';
    document.getElementById('contenedorDocumento').style.display = 'none';
}
</script> 
