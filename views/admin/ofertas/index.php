<?php
// Ofertas: create form + table on same page
?>
<div class="content-header">
    <h2>Ofertas y Descuentos</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Configura descuentos por porcentaje sobre productos del catálogo.</p>
</div>

<!-- ═══ FORM CARD ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title d-flex justify-content-between align-items-center">
        <div>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Nueva Oferta
        </div>
    </div>
    <form action="<?php echo URL_BASE; ?>admin/ofertasCreate" method="POST" class="mt-3">
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Producto a aplicar descuento</label>
                <select name="producto_id" class="form-control form-select" required>
                    <option value="">Selecciona un producto...</option>
                    <?php if (!empty($productos)): ?>
                        <?php foreach($productos as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nombre']); ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <?php if (empty($productos)): ?>
                    <small style="color:#f87171;margin-top:4px;display:block;">⚠ No hay productos. Primero agrega productos al catálogo.</small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Descuento (%)</label>
                <input type="number" name="descuento_porcentaje" class="form-control" min="1" max="100" step="0.5" placeholder="Ej: 15" required>
            </div>
            <div class="form-group">
                <label>Fecha de Inicio (Opcional)</label>
                <input type="datetime-local" name="fecha_inicio" class="form-control">
            </div>
            <div class="form-group">
                <label>Fecha de Fin (Opcional)</label>
                <input type="datetime-local" name="fecha_fin" class="form-control">
            </div>
        </div>
        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <button type="submit" class="btn btn-primary">Registrar Oferta</button>
        </div>
    </form>
</div>

<!-- ═══ TABLE ═══ -->
<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="40%">Producto</th>
                    <th width="15%">Descuento</th>
                    <th width="15%">Fecha Inicio</th>
                    <th width="15%">Fecha Fin</th>
                    <th width="10%" style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                    <tr>
                        <td><span class="text-muted">#<?php echo $row['id']; ?></span></td>
                        <td style="font-weight: 500;"><?php echo htmlspecialchars($row['producto_nombre'] ?? ('Producto #' . $row['producto_id'])); ?></td>
                        <td><span class="badge badge-orange">-<?php echo $row['descuento_porcentaje']; ?>%</span></td>
                        <td class="text-muted"><?php echo !empty($row['fecha_inicio']) ? date('d/m/Y', strtotime($row['fecha_inicio'])) : '—'; ?></td>
                        <td class="text-muted"><?php echo !empty($row['fecha_fin']) ? date('d/m/Y', strtotime($row['fecha_fin'])) : '—'; ?></td>
                        <td style="text-align: right;">
                            <a href="<?php echo URL_BASE; ?>admin/ofertasDelete/<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirmCustom(event, '¿Eliminar esta oferta?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 3rem;">
                            <svg width="24" height="24" fill="none" stroke="#6b88b5" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:10px;"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg><br>
                            No hay ofertas configuradas. Crea la primera arriba.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>