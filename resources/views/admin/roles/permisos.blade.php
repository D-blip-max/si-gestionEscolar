@extends('adminlte::page')

@section('title', 'Asignación de Permisos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-user-tag text-primary"></i> Permisos para el Rol:
            <span class="badge badge-primary">{{ $rol->name }}</span>
        </h1>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> Volver a Roles
        </a>
    </div>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin.roles.update_permisos', $rol->id) }}" method="POST">
                @csrf
                <div class="card card-outline card-primary shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="fas fa-shield-alt"></i> Matriz de Permisos RBAC por Paquete Temático</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-check-all-global">
                                <i class="fas fa-check-double"></i> Seleccionar Todos
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary ml-1" id="btn-uncheck-all-global">
                                <i class="fas fa-times"></i> Desmarcar Todos
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="accordion" id="accordionPermisos">
                            @php $indexPkg = 0; @endphp
                            @foreach ($permisos as $modulo => $grupoPermisos)
                                @php
                                    $indexPkg++;
                                    $collapseId = 'collapse_pkg_' . $indexPkg;
                                @endphp
                                <div class="card card-outline card-secondary mb-3 shadow-sm border">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2" id="heading_{{ $indexPkg }}">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link text-dark font-weight-bold p-0 text-left" type="button" data-toggle="collapse" data-target="#{{ $collapseId }}" aria-expanded="true" aria-controls="{{ $collapseId }}">
                                                <i class="fas fa-folder-open text-warning mr-1"></i> {{ $modulo }}
                                                <span class="badge badge-secondary ml-2">{{ count($grupoPermisos) }} permisos</span>
                                            </button>
                                        </h5>
                                        <div>
                                            <button type="button" class="btn btn-xs btn-outline-success btn-toggle-pkg" data-target-group="{{ $collapseId }}" data-action="check">
                                                <i class="fas fa-check"></i> Marcar
                                            </button>
                                            <button type="button" class="btn btn-xs btn-outline-secondary btn-toggle-pkg ml-1" data-target-group="{{ $collapseId }}" data-action="uncheck">
                                                <i class="fas fa-undo"></i> Desmarcar
                                            </button>
                                        </div>
                                    </div>

                                    <div id="{{ $collapseId }}" class="collapse show" aria-labelledby="heading_{{ $indexPkg }}">
                                        <div class="card-body py-3">
                                            <div class="row">
                                                @foreach ($grupoPermisos as $permiso)
                                                    <div class="col-md-4 col-sm-6 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input perm-checkbox {{ $collapseId }}"
                                                                id="permiso_{{ $permiso->id }}"
                                                                name="permisos[]"
                                                                value="{{ $permiso->id }}"
                                                                {{ $rol->hasPermissionTo($permiso->name) ? 'checked' : '' }}>
                                                            <label class="custom-control-label font-weight-normal text-sm" for="permiso_{{ $permiso->id }}">
                                                                <code>{{ $permiso->name }}</code>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-footer bg-white text-right">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary shadow-sm">
                            <i class="fas fa-save"></i> Guardar Permisos
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Alternar todos los permisos de un paquete específico
            $('.btn-toggle-pkg').on('click', function() {
                var targetClass = $(this).data('target-group');
                var action = $(this).data('action');
                $('.' + targetClass).prop('checked', action === 'check');
            });

            // Global check all
            $('#btn-check-all-global').on('click', function() {
                $('.perm-checkbox').prop('checked', true);
            });

            // Global uncheck all
            $('#btn-uncheck-all-global').on('click', function() {
                $('.perm-checkbox').prop('checked', false);
            });
        });
    </script>
@stop
