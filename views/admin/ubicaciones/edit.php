<div class="content-header">
    <h2>Editar Ubicación del Local</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Actualiza los datos de esta sucursal.</p>
</div>

<div class="admin-card">
    <div class="admin-card-title mb-3">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Información de la Sucursal
    </div>
    
    <form action="<?php echo URL_BASE; ?>admin/ubicacionesEdit/<?php echo $ubicacion['id']; ?>" method="POST" class="mt-3">
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Nombre del Local *</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($ubicacion['nombre']); ?>" required>
            </div>
            
            <div class="form-group full-width">
                <label>Dirección Física</label>
                <input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($ubicacion['direccion'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label>Latitud *</label>
                <input type="text" name="latitud" class="form-control" value="<?php echo htmlspecialchars($ubicacion['latitud'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Longitud *</label>
                <input type="text" name="longitud" class="form-control" value="<?php echo htmlspecialchars($ubicacion['longitud'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Teléfono de Contacto</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($ubicacion['telefono'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Horario de Atención</label>
                <input type="text" name="horario" class="form-control" value="<?php echo htmlspecialchars($ubicacion['horario'] ?? ''); ?>">
            </div>
            
        </div>
        
        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <a href="<?php echo URL_BASE; ?>admin/ubicaciones" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar Ubicación</button>
        </div>
    </form>
</div>
