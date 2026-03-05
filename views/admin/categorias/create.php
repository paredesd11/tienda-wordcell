<div class="content-header">
    <h2>Nueva Categoría</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Registra los detalles de una nueva categoría.</p>
</div>

<div class="admin-card">
    <div class="admin-card-title">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
        Información de la Categoría
    </div>
    
    <form action="<?php echo URL_BASE; ?>admin/categoriasCreate" method="POST" class="mt-3">
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: Laptops, Accesorios" required>
            </div>
            <div class="form-group full-width">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3" placeholder="Descripción breve" required></textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <a href="<?php echo URL_BASE; ?>admin/categorias" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Crear Categoría</button>
        </div>
    </form>
</div>