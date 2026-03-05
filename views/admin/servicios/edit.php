<div class="content-header">
    <h2>Editar Servicio Técnico</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Modifica los detalles del servicio seleccionado.</p>
</div>

<div class="admin-card">
    <div class="admin-card-title">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> 
        Edición del Servicio
    </div>
    
    <form action="<?php echo URL_BASE; ?>admin/serviciosEdit/<?php echo $record['id']; ?>" method="POST" class="mt-3">
        <div class="form-grid">
            <div class="form-group full-width">
                <label for="nombre">Nombre del Servicio</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($record['nombre']); ?>" required>
            </div>

            <div class="form-group full-width">
                <label for="descripcion">Descripción Breve</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="3" required><?php echo htmlspecialchars($record['descripcion']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="icono">Icono (Clases FontAwesome)</label>
                <input type="text" id="icono" name="icono" class="form-control" value="<?php echo htmlspecialchars($record['icono']); ?>" required>
                <small class="text-muted" style="font-size: 0.75rem;">Ej: fas fa-mobile-alt, fas fa-laptop</small>
            </div>

            <div class="form-group">
                <label for="precio_desde">Precio Desde ($)</label>
                <input type="number" step="0.01" id="precio_desde" name="precio_desde" class="form-control" value="<?php echo $record['precio_desde']; ?>" required>
            </div>

            <div class="form-group full-width">
                <label class="d-flex align-items-center gap-2" style="cursor: pointer; margin-top: 10px;">
                    <input type="checkbox" name="estado" value="1" <?php echo $record['estado'] ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: #3b82f6;">
                    <span style="font-weight: 500; font-size: 0.95rem; color: #e2e8f0;">Servicio Activo (Visible para clientes)</span>
                </label>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <a href="<?php echo URL_BASE; ?>admin/servicios" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>
