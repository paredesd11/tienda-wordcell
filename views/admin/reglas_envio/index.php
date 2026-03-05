<div class="content-header d-flex justify-between align-center mb-3">
    <h2>Reglas de Envío y Ofertas</h2>
</div>

<!-- ═══ FORM CARD ═══ -->
<div class="admin-card mb-4" style="padding: 0; overflow: hidden; max-width: 1000px;">
    <div class="admin-card-title px-4 py-3" style="background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; gap: 0.8rem;">
        <div style="width: 28px; height: 28px; background: rgba(245, 158, 11, 0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #f59e0b;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        </div>
        <span style="font-weight: 700; font-size: 1.1rem; color: #fff;">Crear Regla Promocional</span>
    </div>
    
    <form action="<?php echo URL_BASE; ?>admin/reglas_envioCreate" method="POST" class="p-4">
        <div class="form-grid mb-4" style="grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
            <div class="form-group mb-0">
                <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; margin-bottom: 0.5rem; display: block;">Nombre de la Oferta</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: Envío Gratis > $50" required style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08);">
            </div>
            <div class="form-group mb-0">
                <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; margin-bottom: 0.5rem; display: block;">Monto Mínimo Carrito ($)</label>
                <input type="number" step="0.01" name="monto_minimo_carrito" class="form-control" placeholder="Ej: 50.00" required style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08);">
            </div>
            <div class="form-group mb-0">
                <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; margin-bottom: 0.5rem; display: block;">Costo del Envío de la Oferta ($)</label>
                <input type="number" step="0.01" name="costo_fijo" class="form-control" placeholder="Ej: 0.00 (Envío Gratis)" required style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08);">
            </div>
        </div>

        <div class="form-group mb-4 d-flex align-items-center">
            <input type="checkbox" id="estado" name="estado" value="1" checked style="width:18px; height:18px; margin-right: 8px;">
            <label for="estado" style="font-size: 0.95rem; font-weight: 600; color: #cbd5e1; cursor: pointer;">Regla Activa</label>
        </div>

        <p class="text-muted small mb-3">Si una regla se cumple, esta <strong>sobrescribirá</strong> el costo de todos los demás métodos de envío.</p>

        <button type="submit" class="btn btn-warning" style="background:#f59e0b; border:none; border-radius:6px; padding:0.6rem 1.6rem; font-weight: 600;">Guardar Oferta</button>
    </form>
</div>

<!-- ═══ TABLE ═══ -->
<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nombre de Oferta</th>
                    <th>Aplica desde ($)</th>
                    <th>Costo Fijo de Envío</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($records)): ?>
                <tr><td colspan="5" class="text-center py-4 text-muted">No hay reglas ni ofertas registradas.</td></tr>
                <?php else: ?>
                    <?php foreach ($records as $row): ?>
                    <tr>
                        <td style="font-weight: 600; color: #f59e0b;"><i class="fas fa-gift me-2"></i> <?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td>$<?php echo number_format($row['monto_minimo_carrito'], 2); ?></td>
                        <td>
                            <?php if ($row['costo_fijo'] == 0): ?>
                                <span class="badge bg-success" style="background: rgba(16, 185, 129, 0.1) !important; color: #34d399 !important; border: 1px solid rgba(16, 185, 129, 0.2);">¡GRATIS! ($0.00)</span>
                            <?php else: ?>
                                <span style="color: #10b981; font-weight: 600;">$<?php echo number_format($row['costo_fijo'], 2); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['estado']): ?>
                                <span class="badge bg-success" style="background: rgba(16, 185, 129, 0.1) !important; color: #34d399 !important; border: 1px solid rgba(16, 185, 129, 0.2);">Activa</span>
                            <?php else: ?>
                                <span class="badge bg-secondary" style="background: rgba(148, 163, 184, 0.1) !important; color: #94a3b8 !important; border: 1px solid rgba(148, 163, 184, 0.2);">Inactiva</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="table-actions">
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)" class="btn-icon text-info" title="Editar"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                <a href="<?php echo URL_BASE; ?>admin/reglas_envioDelete/<?php echo $row['id']; ?>" class="btn-icon text-danger" onclick="return confirmCustom(event, '¿Estás seguro de que deseas eliminar esta regla u oferta de envío?');" title="Eliminar"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/></svg></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Editar -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow" style="background: #1e293b; color: #f8fafc;">
      <div class="modal-header border-0" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
        <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Editar Oferta</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editForm" action="" method="POST">
        <div class="modal-body p-4">
            <div class="mb-4">
                <label class="form-label mb-2" style="color:#cbd5e1; font-size:0.9rem;">Nombre de la Oferta</label>
                <input type="text" name="nombre" id="edit_nombre" class="form-control p-2" required style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08); color: white;">
            </div>
            <div class="mb-4">
                <label class="form-label mb-2" style="color:#cbd5e1; font-size:0.9rem;">Monto Mínimo Carrito ($)</label>
                <input type="number" step="0.01" name="monto_minimo_carrito" id="edit_monto_minimo_carrito" class="form-control p-2" required style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08); color: white;">
            </div>
            <div class="mb-4">
                <label class="form-label mb-2" style="color:#cbd5e1; font-size:0.9rem;">Costo Fijo de Envío ($)</label>
                <input type="number" step="0.01" name="costo_fijo" id="edit_costo_fijo" class="form-control p-2" required style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08); color: white;">
            </div>
            <div class="form-check mt-3 mb-2">
                <input type="checkbox" class="form-check-input" id="edit_estado" name="estado" value="1">
                <label class="form-check-label" for="edit_estado" style="color:#cbd5e1; font-size:0.9rem;">Regla Activa</label>
            </div>
        </div>
        <div class="modal-footer border-0" style="background: rgba(0,0,0,0.1);">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-warning">Actualizar Oferta</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openEditModal(data) {
    document.getElementById('editForm').action = '<?php echo URL_BASE; ?>admin/reglas_envioEdit/' + data.id;
    document.getElementById('edit_nombre').value = data.nombre;
    document.getElementById('edit_monto_minimo_carrito').value = data.monto_minimo_carrito;
    document.getElementById('edit_costo_fijo').value = data.costo_fijo;
    document.getElementById('edit_estado').checked = data.estado == 1;
    var modal = new bootstrap.Modal(document.getElementById('modalEdit'));
    modal.show();
}
</script>
