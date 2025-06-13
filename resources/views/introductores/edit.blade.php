{{-- resources/views/introductores/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Introductor')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('introductores.index') }}">Introductores</a></li>
<li class="breadcrumb-item">{{ $introductor->razon_social }}</li>
<li class="breadcrumb-item active">Editar</li>
@endsection

@section('header')
<h1 class="h2"><i class="bi bi-pencil"></i> Editar Introductor</h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pencil"></i> Editar: {{ $introductor->razon_social }}
                </h5>
            </div>
            <div class="card-body">
                <form action="/introductores/{{ $introductor->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="razon_social" class="form-label">Razón Social *</label>
                            <input type="text" class="form-control @error('razon_social') is-invalid @enderror" 
                                   id="razon_social" name="razon_social" 
                                   value="{{ old('razon_social', $introductor->razon_social) }}" required>
                            @error('razon_social')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="cuit" class="form-label">CUIT *</label>
                            <input type="text" class="form-control @error('cuit') is-invalid @enderror" 
                                   id="cuit" name="cuit" 
                                   value="{{ old('cuit', $introductor->cuit_formateado) }}" 
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
                                  id="direccion" name="direccion" rows="2" required>{{ old('direccion', $introductor->direccion) }}</textarea>
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" name="telefono" 
                                   value="{{ old('telefono', $introductor->telefono) }}">
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" 
                                   value="{{ old('email', $introductor->email) }}">
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
                                   value="{{ old('habilitacion_municipal', $introductor->habilitacion_municipal) }}">
                            @error('habilitacion_municipal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo" 
                                       value="1" {{ old('activo', $introductor->activo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">
                                    Activo
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/introductores" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver a Lista
                        </a>
                        <div>
                            <a href="/introductores/{{ $introductor->id }}" class="btn btn-outline-primary me-2">
                                <i class="bi bi-eye"></i> Ver Detalle
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Actualizar Introductor
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Información adicional -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Información del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            @if($introductor->created_at)
                            <dt class="col-sm-6">Creado:</dt>
                            <dd class="col-sm-6">{{ $introductor->created_at->format('d/m/Y H:i') }}</dd>
                            @endif
                            
                            @if($introductor->updated_at && $introductor->created_at && $introductor->updated_at->ne($introductor->created_at))
                            <dt class="col-sm-6">Última modificación:</dt>
                            <dd class="col-sm-6">{{ $introductor->updated_at->format('d/m/Y H:i') }}</dd>
                            @endif
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-6">Total introducciones:</dt>
                            <dd class="col-sm-6">
                                <span class="badge bg-primary">{{ $introductor->introducciones()->count() }}</span>
                            </dd>
                            
                            <dt class="col-sm-6">Estado actual:</dt>
                            <dd class="col-sm-6">
                                @if($introductor->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
                
                @if($introductor->introducciones()->count() > 0)
                <div class="alert alert-info mt-3" role="alert">
                    <i class="bi bi-info-circle"></i>
                    <strong>Nota:</strong> Este introductor tiene {{ $introductor->introducciones()->count() }} 
                    introducción(es) registrada(s). Ten cuidado al modificar los datos principales.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Función para formatear CUIT
function formatCuit(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 11) {
        value = value.substring(0, 11);
        value = value.replace(/(\d{2})(\d{8})(\d{1})/, '$1-$2-$3');
    }
    input.value = value;
}
</script>
@endsection