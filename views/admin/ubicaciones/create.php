<div class="content-header">
    <h2>Registrar Ubicación del Local</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Agrega una nueva sucursal o ubicación en el mapa.</p>
</div>

<div class="admin-card">
    <div class="admin-card-title mb-3">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Información de la Sucursal
    </div>
    
    <form action="<?php echo URL_BASE; ?>admin/ubicacionesCreate" method="POST" class="mt-3">
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Nombre del Local *</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: Sucursal Centro" required>
            </div>
            
            <div class="form-group full-width">
                <label>Dirección Física</label>
                <input type="text" name="direccion" class="form-control" placeholder="Ej: Av. Principal 123 y Calle Secundaria">
            </div>
            
            <div class="form-group">
                <label>Latitud *</label>
                <input type="text" name="latitud" class="form-control" placeholder="-0.180653" required>
            </div>
            <div class="form-group">
                <label>Longitud *</label>
                <input type="text" name="longitud" class="form-control" placeholder="-78.4678382" required>
            </div>
            
            <div class="form-group">
                <label>Teléfono de Contacto</label>
                <input type="text" name="telefono" class="form-control" placeholder="0991234567">
            </div>
            <div class="form-group">
                <label>Horario de Atención</label>
                <input type="text" name="horario" class="form-control" placeholder="Lun-Vie 9:00 - 18:00">
            </div>
            
        </div>
        
        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <a href="<?php echo URL_BASE; ?>admin/ubicaciones" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar Ubicación</button>
        </div>
    </form>
</div>