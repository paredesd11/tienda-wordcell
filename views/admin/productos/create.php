<div class="content-header">
    <h2>Nuevo Producto</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Llena los datos para agregar un nuevo artículo al catálogo.</p>
</div>

<div class="admin-card mb-3">
    <div class="admin-card-title">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0020 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
        Información del Producto
    </div>
    <form action="<?php echo URL_BASE; ?>admin/productosCreate" method="POST" enctype="multipart/form-data" class="mt-3">
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Nombre del Producto</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: Laptop Asus ROG Strix" required>
            </div>
            <div class="form-group">
                <label>Precio ($)</label>
                <input type="number" step="0.01" name="precio" class="form-control" placeholder="0.00" required>
            </div>
            <div class="form-group">
                <label>Stock Disponible</label>
                <input type="number" name="stock" class="form-control" value="0" required>
            </div>
            <div class="form-group">
                <label>Categoría</label>
                <select name="categoria_id" class="form-control form-select" required>
                    <option value="">Selecciona...</option>
                    <?php if(!empty($categorias)): foreach($categorias as $c): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nombre']); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Marca</label>
                <select name="marca_id" class="form-control form-select" required>
                    <option value="">Selecciona...</option>
                    <?php if(!empty($marcas)): foreach($marcas as $m): ?>
                        <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['nombre']); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="form-group full-width">
                <label>Imágenes del Producto</label>
                <label class="file-input-wrapper mb-0" style="height: 48px; display: flex; align-items: center; border: 1px dashed #334155; background: #0f172a; border-radius: 8px; padding-left: 15px; width: 100%;">
                    <input type="file" name="file_upload[]" multiple accept="image/*,video/*" style="opacity: 0; position: absolute; width: 1px;">
                    <span class="btn btn-outline btn-sm" onclick="this.previousElementSibling.click()" style="cursor:pointer; white-space: nowrap;">Cargar Imágenes</span>
                    <span class="text-muted ml-3" style="font-size: 0.85rem; padding-left: 10px;">Puedes seleccionar varios archivos a la vez.</span>
                </label>
            </div>
            <div class="form-group full-width">
                <label>Descripción detallada</label>
                <textarea name="descripcion" class="form-control" rows="5" placeholder="Escribe las especificaciones técnicas y características del producto..."></textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <a href="<?php echo URL_BASE; ?>admin/productos" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Registrar Producto</button>
        </div>
    </form>
</div>
