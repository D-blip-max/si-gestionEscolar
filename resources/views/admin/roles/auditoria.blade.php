@extends('adminlte::page')

@section('title', 'Auditoría de Modificación de Calificaciones')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-shield-alt text-danger"></i> Registro de Auditoría de Calificaciones</h1>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> Volver a Roles
        </a>
    </div>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-danger shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i> Historial Inmutable de Cambios de Notas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info-circle"></i> Auditoría Automática</h5>
                        Este registro almacena de manera inmutable cualquier modificación realizada en las actas de calificaciones de los módulos, identificando la nota previa, la nueva nota asentada y el usuario responsable.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-sm">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>ID Calificación</th>
                                    <th>Nota Anterior</th>
                                    <th>Nota Nueva</th>
                                    <th>Diferencia</th>
                                    <th>Modificado Por</th>
                                    <th>Fecha y Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($auditorias as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $auditorias->firstItem() + $index }}</td>
                                        <td class="text-center"><b>#{{ $item->calificacion_id ?? '-' }}</b></td>
                                        <td class="text-center text-muted">
                                            <span class="badge badge-light border font-weight-bold" style="font-size: 1rem;">
                                                {{ $item->nota_anterior }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $item->nota_nueva >= 51 ? 'badge-success' : 'badge-danger' }} font-weight-bold" style="font-size: 1rem;">
                                                {{ $item->nota_nueva }}
                                            </span>
                                        </td>
                                        <td class="text-center font-weight-bold {{ ($item->nota_nueva - $item->nota_anterior) >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ ($item->nota_nueva - $item->nota_anterior) > 0 ? '+' : '' }}{{ round($item->nota_nueva - $item->nota_anterior, 2) }} pts
                                        </td>
                                        <td>
                                            <i class="fas fa-user-circle"></i> {{ $item->usuario->name ?? 'Usuario Sistema' }}
                                            <small class="text-muted d-block">{{ $item->usuario->email ?? '' }}</small>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <i class="far fa-clock"></i> {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <p class="mb-0">No se registran alteraciones extemporáneas en las calificaciones.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $auditorias->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

