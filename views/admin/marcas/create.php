<div class="content-header">
    <h2>Crear Nueva Marca</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Registra los detalles de una nueva marca.</p>
</div>

<div class="admin-card">
    <div class="admin-card-title">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg> 
        Información de la Marca
    </div>

    <form action="<?php echo URL_BASE; ?>admin/marcasCreate" method="POST" class="mt-3" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: Samsung, Apple" required>
            </div>
            
            <div class="form-group full-width">
                <label>Logo / Imagen</label>
                <div style="border: 1px solid #1e293b; background: #0f172a; padding: 15px; border-radius: 8px;">
                    <input type="file" name="logo" accept="image/*,video/*">
                </div>
            </div>
        </div>
        
        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <a href="<?php echo URL_BASE; ?>admin/marcas" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar Marca</button>
        </div>
    </form>
</div>