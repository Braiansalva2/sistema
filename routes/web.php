
<?php

use Illuminate\Support\Facades\Route;

// RRHH
use App\Http\Controllers\RRHH\ObraSocialController;
use App\Http\Controllers\RRHH\LegajoEmpleadoController;
use App\Http\Controllers\RRHH\EmpleadoController;
use App\Http\Controllers\RRHH\BancoController;
use App\Http\Controllers\RRHH\ContratoController;
use App\Http\Controllers\RRHH\CondicionLaboralController;
use App\Http\Controllers\RRHH\RolPuestoController;
use App\Http\Controllers\RRHH\SancionController;
use App\Http\Controllers\RRHH\VacacionController;
use App\Http\Controllers\RRHH\DescansoController;
use App\Http\Controllers\RRHH\SucursalController;
use App\Http\Controllers\RRHH\SueldoController;
use App\Http\Controllers\RRHH\MovimientoEmpleadoController;
use App\Http\Controllers\RRHH\AdelantoController;
use App\Http\Controllers\RRHH\PermisoController;
use App\Http\Controllers\RRHH\LicenciaController;
use App\Http\Controllers\RRHH\TipoPrendaController;
use App\Http\Controllers\RRHH\TipoPrendaTalleController;
use App\Http\Controllers\Empleado\AsistenciaEmpleadoController;
use App\Http\Controllers\RRHH\AsistenciaController;
use App\Http\Controllers\RRHH\RRHHOperativoController;
use App\Http\Controllers\RRHH\RosterController;

//documentacion 

use App\Http\Controllers\Documentacion\ViaticoController;
use App\Http\Controllers\Documentacion\ViaticoReporteController;

// COMERCIAL
use App\Http\Controllers\Comercial\ComercialController;
use App\Http\Controllers\Comercial\EmpresaController;
use App\Http\Controllers\Comercial\ReferenteController;
use App\Http\Controllers\Comercial\EmpresaDocumentoController;
use App\Http\Controllers\Comercial\TramoController;
use App\Http\Controllers\Comercial\TipoServicioController;
use App\Http\Controllers\Comercial\UbicacionController;


// ADMIN ACL
use App\Http\Controllers\Admin\ACL\RoleController;
use App\Http\Controllers\Admin\ACL\PermissionController;
use App\Http\Controllers\Admin\ACL\UserPermissionController;
//trafico
use App\Http\Controllers\Trafico\TraficoController;
use App\Http\Controllers\Trafico\UnidadController;
use App\Http\Controllers\Trafico\LegajoVehicularController;
use App\Http\Controllers\Trafico\EmpresaTercerizadaController;
//strix
use App\Services\StrixService;
use App\Http\Controllers\Trafico\StrixController;

// empleado
use App\Http\Controllers\Empleado\EmpleadoPortalController;
use App\Http\Controllers\Empleado\OperativoController;


/* ======================================================
|  RUTAS PÚBLICAS
====================================================== */
Route::get('/', function () {
    return view('welcome');
});

    /* ========================
    |  PANELES DE ÁREA
    ======================== */
    Route::get('/admin', fn() => view('admin.dashboard'))
        ->middleware('role:admin')
        ->name('admin.panel');

    Route::get('/rrhh', fn() => view('rrhh.dashboard'))
        ->middleware('role:rrhh')
        ->name('rrhh.panel');

    Route::get('/comercial', fn() => view('comercial.index'))
        ->middleware('role:comercial')
        ->name('comercial.panel');

/* ======================================================
|  GRUPO AUTENTICADO (SANCTUM + VERIFIED)
====================================================== */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

        /* ========================
        |  DASHBOARD GENERAL
 /* ========================
|  DASHBOARD GENERAL
======================== */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'redirect.role'])->name('dashboard');


Route::get('/redirect', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified', 'redirect.role'])->name('redirect');




    /* ======================================================
    |  ADMINISTRACIÓN ACL (SOLO ADMIN)
    ======================================================= */
    Route::prefix('admin/acl')
        ->middleware('role:admin')
        ->name('acl.')
        ->group(function () {

            Route::resource('roles', RoleController::class);
            Route::resource('permisos', PermissionController::class);

            Route::resource('usuarios', UserPermissionController::class)
                ->only(['index', 'edit']);

            // ROLES ASIGNACIÓN
            Route::put('usuarios/{id}/roles', [
                UserPermissionController::class,
                'updateRoles'
            ])->name('usuarios.roles.update');

            // PERMISOS DIRECTOS
            Route::put('usuarios/{id}/permisos', [
                UserPermissionController::class,
                'updatePermissions'
            ])->name('usuarios.permisos.update');
        });

    /* ======================================================
    |  RRHH (Admin o RRHH)
    ======================================================= */
    Route::prefix('rrhh')
        ->middleware(['role:admin|rrhh'])
        ->name('rrhh.')
        ->group(function () {

            /* ========================
            |  EMPLEADOS
            ======================== */   
            Route::get('/', [EmpleadoController::class, 'dashboard'])->name('dashboard');
            Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
            Route::get('/empleados-json', [EmpleadoController::class, 'data'])->name('empleados.json');

            Route::get('/empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create');
            Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');

            Route::get('/empleados/{id}', [EmpleadoController::class, 'show'])->name('empleados.show');
            Route::get('/empleados/{id}/edit', [EmpleadoController::class, 'edit'])->name('empleados.edit');
            Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])->name('empleados.update');
            Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');
            Route::put('/empleados/{id}/reactivar', [EmpleadoController::class, 'reactivar'])->name('empleados.reactivar');

            /* ========================
            |  LEGAJOS POR EMPLEADO
            ======================== */
            Route::prefix('empleados/{empleado}')
                ->name('empleados.')
                ->group(function () {
                    Route::resource('legajos', LegajoEmpleadoController::class);
                });


            /* ========================
            |  OTROS CRUD RRHH
            ======================== */
            Route::resource('bancos', BancoController::class)->only(['index', 'store']);
            Route::resource('contratos', ContratoController::class)->only(['index', 'store']);
            Route::resource('condiciones', CondicionLaboralController::class)->only(['index', 'store']);
            Route::resource('obras-sociales', ObraSocialController::class);
            Route::resource('roles-puestos', RolPuestoController::class)->only(['index', 'store']);
           
           
            Route::get('/rrhh/empleados/{id}/ddjj', [EmpleadoController::class, 'ddjj'])
                    ->name('empleados.ddjj');

            /* ========================
            |  SANCIONES
            ======================== */
            Route::get('/empleados/{empleado}/sanciones', [SancionController::class, 'index'])->name('sanciones.index');
            Route::get('/empleados/{empleado}/sanciones/create', [SancionController::class, 'create'])->name('sanciones.create');
            Route::post('/empleados/{empleado}/sanciones', [SancionController::class, 'store'])->name('sanciones.store');
            Route::get('/sanciones/{sancion}/edit', [SancionController::class, 'edit'])->name('sanciones.edit');
            Route::put('/sanciones/{sancion}', [SancionController::class, 'update'])->name('sanciones.update');
            Route::delete('/sanciones/{sancion}', [SancionController::class, 'destroy'])->name('sanciones.destroy');


            /* ========================
            |  DESCANSOS
            ======================== */
            Route::prefix('empleados/{empleado}')
                ->name('descansos.')
                ->group(function () {
                    Route::get('descansos', [DescansoController::class, 'index'])->name('index');
                    Route::get('descansos/create', [DescansoController::class, 'create'])->name('create');
                    Route::post('descansos', [DescansoController::class, 'store'])->name('store');
                    Route::get('descansos/{id}/edit', [DescansoController::class, 'edit'])->name('edit');
                    Route::put('descansos/{id}', [DescansoController::class, 'update'])->name('update');
                    Route::delete('descansos/{id}', [DescansoController::class, 'destroy'])->name('destroy');
                });

                /* ========================
                |  SUELDOS
                ======================== */
                Route::prefix('empleados/{empleado}')
                    ->name('sueldos.')
                    ->group(function () {
                        Route::get('sueldos', [SueldoController::class, 'index'])->name('index');
                        Route::get('sueldos/create', [SueldoController::class, 'create'])->name('create');
                        Route::post('sueldos', [SueldoController::class, 'store'])->name('store');
                        Route::get('sueldos/{id}/edit', [SueldoController::class, 'edit'])->name('edit');
                        Route::put('sueldos/{id}', [SueldoController::class, 'update'])->name('update');
                        Route::delete('sueldos/{id}', [SueldoController::class, 'destroy'])->name('destroy');
                    });
                    Route::post('/rrhh/empleados/{empleado}/sueldos', 
                        [SueldoController::class, 'store']
                        )->name('rrhh.sueldos.store');


Route::prefix('empleados/{empleado}')->group(function () {

    Route::post('movimientos',
        [MovimientoEmpleadoController::class, 'store']
    )->name('movimientos.store');

    Route::delete('movimientos/{id}',
        [MovimientoEmpleadoController::class, 'destroy']
    )->name('movimientos.destroy');

});
//============VIATICOS=============
Route::prefix('empleados/{empleado}')
    ->name('viaticos.')
    ->group(function () {

        Route::get('viaticos/create',
            [ViaticoController::class, 'create']
        )->name('create');

        Route::post('viaticos',
            [ViaticoController::class, 'store']
        )->name('store');



    });

            /* ========================
            |  VACACIONES
            ======================== */
            Route::prefix('empleados/{empleado}')
                ->name('vacaciones.')
                ->group(function () {
                    Route::get('vacaciones', [VacacionController::class, 'index'])->name('index');
                    Route::get('vacaciones/create', [VacacionController::class, 'create'])->name('create');
                    Route::post('vacaciones', [VacacionController::class, 'store'])->name('store');
                    Route::get('vacaciones/{vacacion}/edit', [VacacionController::class, 'edit'])->name('edit');
                    Route::put('vacaciones/{vacacion}', [VacacionController::class, 'update'])->name('update');
                    Route::put('vacaciones/{vacacion}/aprobar', [VacacionController::class, 'aprobar'])->name('aprobar');
                    Route::put('vacaciones/{vacacion}/rechazar', [VacacionController::class, 'rechazar'])->name('rechazar');
                    Route::delete('vacaciones/{vacacion}', [VacacionController::class, 'destroy'])->name('destroy');
                });


            /* ========================
            |  USUARIOS DEL SISTEMA (RRHH)
            ======================== */
            Route::get('/usuarios', [EmpleadoController::class, 'panelUsuarios'])->name('usuarios.index');
            Route::put('/usuarios/{user}/toggle', [EmpleadoController::class, 'toggleEstado'])->name('usuarios.toggle');
            Route::put('/usuarios/{user}/password', [EmpleadoController::class, 'cambiarPassword'])->name('usuarios.password');

            Route::get('usuarios/{user}/edit', [EmpleadoController::class, 'editUsuario'])->name('usuarios.edit');
            Route::put('usuarios/{user}', [EmpleadoController::class, 'updateUsuario'])->name('usuarios.update');
            Route::delete('usuarios/{user}', [EmpleadoController::class, 'destroyUsuario'])->name('usuarios.destroy');

            // Crear usuario desde empleado
            Route::prefix('empleados/{empleado}')
                ->name('empleados.')
                ->group(function () {
                    Route::get('/crear-usuario', [EmpleadoController::class, 'createUser'])->name('createUser');
                    Route::post('/crear-usuario', [EmpleadoController::class, 'storeUser'])->name('storeUser');
                });



                /* ========================
                |  SUCURSALES
                ======================== */
                Route::resource('sucursales', SucursalController::class)
                ->parameters(['sucursales' => 'sucursal']);


// ruta para los adelantos rrhh
             Route::prefix('adelantos')->name('adelantos.')->group(function () {
                
                   //    descontar o pago de cuota
                Route::get('/cuotas', [AdelantoController::class, 'cuotas'])
                    ->name('cuotas');

                 Route::post('/cuotas/pagar', [AdelantoController::class, 'pagarCuotas'])
                 ->name('cuotas.pagar');


                    Route::get('/excepcional/crear', [AdelantoController::class, 'crearExcepcional'])
                        ->name('excepcional.create');

                    Route::post('/excepcional/guardar', [AdelantoController::class, 'guardarExcepcional'])
                        ->name('excepcional.store');

                    Route::get('/empleados/buscar', [AdelantoController::class, 'buscarEmpleado'])
                        ->name('empleados.buscar');

                    Route::get('/empleados/{id}/historial', [AdelantoController::class, 'historialEmpleado'])
                        ->name('empleados.historial');   


                Route::get('/', [AdelantoController::class, 'index'])->name('index');

                Route::get('/{id}', [AdelantoController::class, 'show'])->name('show');

                Route::put('/{id}/aprobar', [AdelantoController::class, 'aprobar'])->name('aprobar');

                Route::put('/{id}/rechazar', [AdelantoController::class, 'rechazar'])->name('rechazar');

                Route::post('/{id}/pagar', [AdelantoController::class, 'pagar'])->name('pagar');
               
                     

                });
                Route::get( '/adelantos/{id}/imprimir', [AdelantoController::class, 'imprimir']
                        )->name('adelantos.imprimir');
// rutas para alos permisos de los empleados

                Route::get('/permisos', [PermisoController::class, 'index'])
                    ->name('permisos');

                Route::post('/permisos/{permiso}/aprobar', [PermisoController::class, 'aprobar'])
                    ->name('permisos.aprobar');

                Route::post('/permisos/{permiso}/rechazar', [PermisoController::class, 'rechazar'])
                    ->name('permisos.rechazar');



                Route::get('/licencias', [LicenciaController::class, 'index'])
                    ->name('licencias.index');

                Route::post('/licencias/{id}/aprobar', [LicenciaController::class, 'aprobar'])
                    ->name('licencias.aprobar');

                Route::post('/licencias/{id}/rechazar', [LicenciaController::class, 'rechazar'])
                    ->name('licencias.rechazar');


// rutas para prendas y tallas
                Route::get('/tipos-prenda/exportar-empleados', [TipoPrendaController::class, 'exportarEmpleados'])
                    ->name('tipos-prenda.exportar-empleados');
              
                Route::resource('tipos-prenda', TipoPrendaController::class);
                Route::resource('tipos-prenda-talles', TipoPrendaTalleController::class);
           
             

        // ruta asistencia

     
                Route::prefix('asistencias')
                    ->name('asistencias.')
                    ->group(function () {

                        Route::get('/', [AsistenciaController::class, 'index'])
                            ->name('index');

                // MATRIZ
                Route::get('/matriz', [AsistenciaController::class, 'matriz'])
                    ->name('matriz');

            });

// jornada operativa matriz rrhh
                Route::get('/operativo', [RRHHOperativoController::class, 'operativoEnVivo'])
                    ->name('operativo');

                Route::get('/matriz', [RRHHOperativoController::class, 'matriz'])
                    ->name('matriz');



                Route::get('/viajes', [RRHHOperativoController::class, 'historial'])
                    ->name('viajes');

                Route::get('/viajes/{id}', [RRHHOperativoController::class, 'detalle'])
                    ->name('viajes.detalle');


                    // exportar matriz
                Route::get('/asistencias/exportar', [AsistenciaController::class, 'exportar'])
                        ->name('asistencias.exportar');


                        // roster rutas
                Route::resource('rosters', RosterController::class);

              
        });

     /* ======================================================
    |  DOCUMENTACION (solo rol documentacion)
    ====================================================== */   

        Route::prefix('documentacion')
            ->name('documentacion.')
            ->middleware(['role:documentacion'])
            ->group(function () {

 Route::get('viaticos/reportes', [ViaticoReporteController::class, 'index'])
            ->name('viaticos.reportes');   
            
        Route::get('/viaticos/{id}/json', [ViaticoController::class, 'json'])
            ->name('documentacion.viaticos.json');
    
        Route::resource('viaticos', ViaticoController::class);

        Route::post('viaticos/{id}/extension', [ViaticoController::class, 'storeExtension'])
            ->name('viaticos.extension');

        Route::get('documentacion/viaticos/{id}', [ViaticoController::class, 'show'])
            ->name('documentacion.viaticos.show');

        Route::get('documentacion/viaticos/{id}/print', [ViaticoController::class, 'print'])
            ->name('viaticos.print');

        Route::put('viaticos/{id}/extension', 
                        [ViaticoController::class, 'updateExtension']
                    )->name('viaticos.updateExtension');



        Route::get('viaticos/{id}/print-extension', [ViaticoController::class, 'printExtension'])
            ->name('viaticos.printExtension');



            
       

    });





    /* ======================================================
    |  TRÁFICO (solo rol trafico)
    ====================================================== */

  Route::prefix('trafico')
    ->middleware('role:trafico')
    ->name('trafico.')
    ->group(function () {

        // Panel principal
        Route::get('/', [TraficoController::class, 'index'])->name('panel');

       

            // CRUD UNIDADES
        Route::get('/unidades', [UnidadController::class, 'index'])->name('unidades.index');
        Route::get('/unidades/create', [UnidadController::class, 'create'])->name('unidades.create');
        Route::post('/unidades/store', [UnidadController::class, 'store'])->name('unidades.store');
        Route::get('/unidades/{id}/edit', [UnidadController::class, 'edit'])->name('unidades.edit');
        Route::put('/unidades/{id}', [UnidadController::class, 'update'])->name('unidades.update');
        Route::delete('/unidades/{id}', [UnidadController::class, 'destroy'])->name('unidades.destroy');

        // Modelos dinámicos
        Route::get('/modelos-por-marca/{id}', 
            [\App\Http\Controllers\Trafico\UnidadController::class, 'getModelos']
        )->name('modelos.porMarca');

        // Guardar marca desde modal
        Route::post('/marcas/store', 
            [\App\Http\Controllers\Trafico\MarcaController::class, 'store']
        )->name('marcas.store');

        // Guardar modelo desde modal
        Route::post('/modelos/store', 
            [\App\Http\Controllers\Trafico\ModeloController::class, 'store']
        )->name('modelos.store');

        // Guardar tipo de vehículo desde modal
        Route::post('/tipos-vehiculo/store', 
            [\App\Http\Controllers\Trafico\TipoVehiculoController::class, 'store']
        )->name('tipos_vehiculo.store');

                // LEGJO VEHICULAR
        Route::get('/unidades/{id}/legajo', [LegajoVehicularController::class, 'index'])
            ->name('unidades.legajo');

        Route::post('/unidades/{id}/legajo/store', [LegajoVehicularController::class, 'store'])
            ->name('legajo.store');

        Route::delete('/legajo/{id}/delete', [LegajoVehicularController::class, 'destroy'])
            ->name('legajo.destroy');
            
        Route::put('/legajo/{id}/update', [LegajoVehicularController::class, 'update'])
            ->name('legajo.update');

        // EXPORTS
        Route::get('/trafico/unidades/export/csv', [UnidadController::class, 'exportCSV'])
            ->name('unidades.export.csv');

        Route::get('/trafico/unidades/export/pdf', [UnidadController::class, 'exportPDF'])
            ->name('unidades.export.pdf');
        // Guardar empresa tercerizada desde el modal
        Route::post('/empresas-tercerizadas/store', 
            [EmpresaTercerizadaController::class, 'store']
        )->name('empresas_tercerizadas.store');

        Route::get('/strix/sync', [StrixController::class, 'sync'])
        ->name('strix.sync');

        Route::get('/strix/unidades',
        [\App\Http\Controllers\Trafico\StrixController::class, 'todasLasUnidades']
            )->name('strix.unidades');

       //  MONITOREO DE FLOTA
        Route::get(
            '/unidades/monitoreo',
            [StrixController::class, 'monitoreo']
        )->name('unidades.monitoreo');

        Route::get(
            '/strix/estado',
            [\App\Http\Controllers\Trafico\StrixController::class, 'estado']
        )->name('strix.estado');

    });

    /* ======================================================
      COMERCIAL (solo rol comercial)
    ======================================================= */
        Route::prefix('comercial')
            ->middleware('role:comercial')
            ->name('comercial.')  

            ->group(function () {

        // DASHBOARD COMERCIAL  
        Route::get('/', [ComercialController::class, 'index'])
            ->name('dashboard');

        // CRUD DE EMPRESAS (CLIENTES)
        // Route::resource('clientes', EmpresaController::class);
        Route::resource('clientes', EmpresaController::class)
          ->parameters(['clientes' => 'empresa']);
        //tramos
         Route::resource('tramos', TramoController::class);

        //servicios 
        Route::post('tipos-servicio/ajax', [TipoServicioController::class, 'storeAjax'])
            ->name('tipos-servicio.storeAjax');
        //ubicaciones
        Route::post('ubicaciones/ajax', [UbicacionController::class, 'storeAjax'])
            ->name('ubicaciones.storeAjax');
        Route::resource('ubicaciones', UbicacionController::class);

        Route::resource('tipos-servicio', TipoServicioController::class);

        Route::put('referentes/{referente}', [ReferenteController::class, 'update'])
            ->name('referentes.update');

        Route::delete('referentes/{referente}', [ReferenteController::class, 'destroy'])
            ->name('referentes.destroy');

        Route::put('documentos/{documento}', [EmpresaDocumentoController::class, 'update'])
            ->name('documentos.update');

        Route::delete('documentos/{documento}', [EmpresaDocumentoController::class, 'destroy'])
            ->name('documentos.destroy');


                    // AGREGAR REFERENTE A EMPRESA
        Route::post('empresas/{empresa}/referentes', [ReferenteController::class, 'store'])
            ->name('referentes.store');

        // AGREGAR DOCUMENTO A EMPRESA
        Route::post('empresas/{empresa}/documentos', [EmpresaDocumentoController::class, 'store'])
            ->name('documentos.store');


        Route::get('cod_product', function () {
                    return view('comercial.cod_product.index');
                })->name('cod_product');
        //tramos
        Route::resource('tramos', \App\Http\Controllers\Comercial\TramoController::class);
        Route::resource('tramos', TramoController::class);
    });
    
 //  VERIFICAR CUIT ARCA
        Route::post(
            '/comercial/arca/verificar-cuit',
            [EmpresaController::class, 'verificarCuitArca']
        )->name('comercial.arca.verificar.cuit');


        Route::get('/strix/test', function (\App\Services\StrixService $strix) {

                    $accounts = $strix->getAccounts();

                    $accountId = $accounts[0]['id'];

                    $things = $strix->getThings($accountId);

                    return [
                            'cuentas' => count($accounts),
                            'vehiculos' => count($things),
                            'primer_vehiculo' => $things[0]['info']['domain'] ?? null,
                            
                        ];
});

//ruta de prueba
Route::get('/arca/test-firma', function () {

    $xml = '<test>OK</test>';
    file_put_contents(storage_path('app/arca/test.xml'), $xml);

    $cmd = sprintf(
        'openssl smime -sign -signer "%s" -inkey "%s" -outform DER -nodetach -binary < "%s"',
        base_path(env('ARCA_CERT')),
        base_path(env('ARCA_KEY')),
        storage_path('app/arca/test.xml')
    );

    $cms = shell_exec($cmd);

    dd($cms ? 'FIRMA OK' : 'ERROR FIRMA');

});

Route::get('/arca/test-wsaa', function () {
    $wsaa = new \App\Services\ArcaWsaaService();
    dd($wsaa->login());
});




// rutas para el empleado

Route::middleware(['auth:sanctum', 'verified', 'tiene_empleado'])
            ->prefix('empleado')
            ->group(function () {

            Route::get('/index', [EmpleadoPortalController::class, 'index'])
                  ->name('empleado.index');

            Route::get('/perfil', [EmpleadoPortalController::class, 'perfil'])
                  ->name('empleado.perfil');
        
            Route::get('/vacaciones', [EmpleadoPortalController::class, 'vacaciones'])
                   ->name('empleado.vacaciones');

            Route::post('/vacaciones', [EmpleadoPortalController::class, 'guardarVacaciones'])
                  ->name('empleado.vacaciones.store');

            Route::get('/adelantos', [EmpleadoPortalController::class, 'adelantos'])
                ->name('empleado.adelantos');

            Route::post('/adelantos', [EmpleadoPortalController::class, 'guardarAdelanto'])
                ->name('empleado.adelantos.store');

            Route::get('/adelantos/historial', [EmpleadoPortalController::class, 'historialAdelantos'])
                ->name('empleado.adelantos.historial');

            Route::get('/permisos', [EmpleadoPortalController::class, 'permisos'])
                ->name('empleado.permisos');

            Route::post('/permisos', [EmpleadoPortalController::class, 'guardarPermiso'])
                ->name('empleado.permisos.store');

            Route::get('/licencias', [EmpleadoPortalController::class, 'licencias'])
                ->name('empleado.licencias');

            Route::post('/licencias', [EmpleadoPortalController::class, 'guardarLicencia'])
                ->name('empleado.licencias.store');

            Route::get('/licencias/historial', [EmpleadoPortalController::class, 'historialLicencias'])
                ->name('empleado.licencias.historial');

            Route::post('/empleado/talles', [EmpleadoPortalController::class, 'guardarTalles'])
                ->name('empleado.talles.guardar');


    // asistencia 

            Route::get('/asistencia', [AsistenciaEmpleadoController::class, 'index'])
                ->name('empleado.asistencia');

            Route::post('/asistencia/marcar', [AsistenciaEmpleadoController::class, 'marcar'])
                ->name('empleado.asistencia.marcar');

            Route::post('/asistencia/consentimiento', [AsistenciaEmpleadoController::class, 'guardarConsentimiento'])
                ->name('empleado.asistencia.consentimiento');


            Route::get('/jornada', [OperativoController::class, 'index'])
                 ->name('empleado.jornada.selector');
   
   
            Route::get('/operativo/viaje', [OperativoController::class, 'viaje']) 
                    ->name('empleado.operativo.viaje');


            Route::post('/operativo/iniciar', [OperativoController::class, 'iniciar'])
                    ->name('empleado.operativo.iniciar');

            Route::post('/operativo/reportar', [OperativoController::class, 'reportar'])
                    ->name('empleado.operativo.reportar');

            Route::post('/operativo/finalizar', [OperativoController::class, 'finalizar'])
                    ->name('empleado.operativo.finalizar');



            Route::post('/empleado/operativo/consentimiento',
                    [OperativoController::class, 'guardarConsentimiento']
                )->name('empleado.operativo.consentimiento');

// para los ususarios que cumnplen rosters
           Route::get('/roster', [EmpleadoPortalController::class, 'roster'])
    ->name('empleado.roster');

    
});


});


