@extends('layouts.app')

@section('title', 'Nuevo Introductor')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('introductores.index') }}">Introductores</a></li>
<li class="breadcrumb-item active">Nuevo</li>
@endsection

@section('header')
<h1 class="h2"><i class="bi bi-plus-circle"></i> Nuevo Introductor</h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('introductores.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="razon_social" class="form-label">Razón Social *</label>
                            <input type="text" class="form-control @error('razon_social') is-invalid @enderror" 
                                   id="razon_social" name="razon_social" value="{{ old('razon_social') }}" required>
                            @error('razon_social')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="cuit" class="form-label">CUIT *</label>
                            <input type="text" class="form-control @error('cuit') is-invalid @enderror" 
                                   id="cuit" name="cuit" value="{{ old('cuit') }}" 
                                   placeholder="XX-XXXXXXXX-X" maxlength="13" required
                                   oninput="formatCuit(this)">
                            @error('cuit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección *</label>
                        <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                  id="direccion" name="direccion" rows="2" required>{{ old('direccion') }}</textarea>
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" name="telefono" value="{{ old('telefono') }}">
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="habilitacion_municipal" class="form-label">Habilitación Municipal</label>
                            <input type="text" class="form-control @error('habilitacion_municipal') is-invalid @enderror" 
                                   id="habilitacion_municipal" name="habilitacion_municipal" 
                                   value="{{ old('habilitacion_municipal') }}">
                            @error('habilitacion_municipal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo" 
                                       {{ old('activo', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">
                                    Activo
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('introductores.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Guardar Introductor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection