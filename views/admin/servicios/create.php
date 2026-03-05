<div class="content-header">
    <h2>Nuevo Servicio Técnico</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Registra un nuevo tipo de servicio o reparación.</p>
</div>

<div class="admin-card">
    <div class="admin-card-title">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg> 
        Información del Servicio
    </div>
    
    <form action="<?php echo URL_BASE; ?>admin/serviciosCreate" method="POST" class="mt-3">
        <div class="form-grid">
            <div class="form-group full-width">
                <label for="nombre">Nombre del Servicio</label>
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej: Cambio de Pantalla" required>
            </div>

            <div class="form-group full-width">
                <label for="descripcion">Descripción Breve</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="3" placeholder="Describe brevemente en qué consiste el servicio..." required></textarea>
            </div>

            <div class="form-group">
                <label for="icono">Icono (Clases FontAwesome)</label>
                <input type="text" id="icono" name="icono" class="form-control" value="fas fa-tools" required>
                <small class="text-muted" style="font-size: 0.75rem;">Ej: fas fa-mobile-alt, fas fa-laptop</small>
            </div>

            <div class="form-group">
                <label for="precio_desde">Precio Desde ($)</label>
                <input type="number" step="0.01" id="precio_desde" name="precio_desde" class="form-control" value="0.00" required>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <a href="<?php echo URL_BASE; ?>admin/servicios" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Registrar Servicio</button>
        </div>
    </form>
</div>


