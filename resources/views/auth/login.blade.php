@extends('adminlte::auth.login')

@section('content_body')
    @if (session('lockout_message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Acceso bloqueado!</strong>
            <br>
            {{ session('lockout_message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @parent
@endsection