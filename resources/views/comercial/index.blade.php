@extends('layouts.comercial')

@section('title', 'Panel Comercial')

@section('content')
<div class="card shadow-sm border-0" style="border-left: 5px solid #a04518;">
    <div class="card-body">
        <h3 class="fw-bold" style="color: #a04518;">Área Comercial</h3>
        <p class="text-muted">Bienvenido al panel comercial del Sistema de Gestión GVH.</p>

        <div class="row mt-4">

            <!-- CLIENTES -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3 text-center"
                     style="background-color: #f9e1cf; border-bottom: 4px solid #a04518;">
                    <i class="bi bi-people fs-1" style="color: #a04518;"></i>
                    <h5 class="mt-2" style="color: #a04518;">Clientes</h5>
                    <p class="text-muted small">Gestión de clientes y contactos.</p>
                    <a href="{{ route('comercial.clientes.index') }}"
                       class="btn text-white btn-sm"
                       style="background-color: #a04518;">
                       Ingresar
                    </a>
                </div>
            </div>

            <!-- PRESUPUESTOS -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3 text-center"
                     style="background-color: #f9e1cf; border-bottom: 4px solid #a04518;">
                    <i class="bi bi-file-earmark-text fs-1" style="color: #a04518;"></i>
                    <h5 class="mt-2" style="color: #a04518;">Presupuestos</h5>
                    <p class="text-muted small">Cargar y administrar presupuestos.</p>
                    <a href="#"
                       class="btn text-white btn-sm"
                       style="background-color: #a04518;">
                       Ingresar
                    </a>
                </div>
            </div>

            <!-- COTIZACIONES -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3 text-center"
                     style="background-color: #f9e1cf; border-bottom: 4px solid #a04518;">
                    <i class="bi bi-currency-dollar fs-1" style="color: #a04518;"></i>
                    <h5 class="mt-2" style="color: #a04518;">Codigo de Productos</h5>
                    <p class="text-muted small">Consulta y actualización de tarifas.</p>
                    <a href="#"
                       class="btn text-white btn-sm"
                       style="background-color: #a04518;">
                       Ingresar
                    </a>
                </div>
            </div>

              <div class="col-md-4">
                <div class="card shadow-sm border-0 p-3 text-center"
                     style="background-color: #f9e1cf; border-bottom: 4px solid #a04518;">
                    <i class="bi bi-currency-dollar fs-1" style="color: #a04518;"></i>
                    <h5 class="mt-2" style="color: #a04518;">Tramos - Tarifas</h5>
                    <p class="text-muted small">Consulta y actualización de tarifas.</p>
                    <a href="#"
                       class="btn text-white btn-sm"
                       style="background-color: #a04518;">
                       Ingresar
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection 
