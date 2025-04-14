@extends('layouts.app')

@section('content')
@include('layouts.pagos-navbar')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Editar Pago</h2>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('pagos.update', $pago) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reserva_id">Reserva</label>
                                    <select class="form-control @error('reserva_id') is-invalid @enderror" id="reserva_id" name="reserva_id" required>
                                        <option value="{{ $pago->reserva_id }}" selected>
                                            {{ $pago->reserva->user->name }} - {{ $pago->reserva->servicio->nombre }} ({{ $pago->reserva->fecha }})
                                        </option>
                                    </select>
                                    @error('reserva_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estilista_id">Estilista</label>
                                    <select class="form-control @error('estilista_id') is-invalid @enderror" id="estilista_id" name="estilista_id" required>
                                        <option value="">Seleccione un estilista</option>
                                        @foreach($estilistas as $estilista)
                                            <option value="{{ $estilista->id }}" {{ $pago->estilista_id == $estilista->id ? 'selected' : '' }}>
                                                {{ $estilista->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('estilista_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="metodo_pago">Método de Pago</label>
                                    <select class="form-control @error('metodo_pago') is-invalid @enderror" id="metodo_pago" name="metodo_pago" required>
                                        <option value="">Seleccione un método</option>
                                        @foreach($metodosPago as $metodo)
                                            <option value="{{ $metodo }}" {{ $pago->metodo_pago == $metodo ? 'selected' : '' }}>
                                                {{ $metodo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('metodo_pago')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="importe">Importe</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" step="0.01" class="form-control @error('importe') is-invalid @enderror" id="importe" name="importe" value="{{ old('importe', $pago->importe) }}" required>
                                    </div>
                                    @error('importe')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_pago">Fecha de Pago</label>
                                    <input type="datetime-local" class="form-control @error('fecha_pago') is-invalid @enderror" id="fecha_pago" name="fecha_pago" value="{{ old('fecha_pago', $pago->fecha_pago->format('Y-m-d\TH:i')) }}" required>
                                    @error('fecha_pago')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Guardar Cambios
                                </button>
                                <a href="{{ route('pagos.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 