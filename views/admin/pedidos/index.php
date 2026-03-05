<div class="content-header">
    <h2>Gestión de Pedidos</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Revisa y administra las compras y pedidos de los clientes.</p>
</div>

<!-- Create Button -->
<div class="mb-3 d-flex justify-content-end">
    <a href="<?php echo URL_BASE; ?>admin/pedidosCreate" class="btn btn-primary">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg> 
        Registrar Pedido Manual
    </a>
</div>

<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="15%">Cliente</th>
                    <th width="15%">Pago / Envío</th>
                    <th width="10%">Total</th>
                    <th width="15%">ESTADO</th>
                    <th width="25%">NOTA ADMIN</th>
                    <th width="15%" style="text-align: right;">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                    <tr>
                        <form action="<?php echo URL_BASE; ?>admin/pedidosValidarPago" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            
                            <td><span class="text-muted">#<?php echo $row['id']; ?></span></td>
                            <td style="font-weight: 500;">Usuario <?php echo htmlspecialchars($row['usuario_id'] ?? ''); ?></td>
                            <td class="text-muted" style="max-width: 180px; font-size: 0.85rem;">
                                <strong>Pago:</strong> <span class="text-light"><?php echo htmlspecialchars($row['tipo'] ?? '—'); ?></span><br>
                                <strong>Envío:</strong> <span class="text-light"><?php echo htmlspecialchars($row['metodo_envio'] ?? 'Retiro'); ?></span> <small class="text-success">($<?php echo number_format($row['costo_envio'] ?? 0, 2); ?>)</small>
                                <?php if (!empty($row['comprobante_url'])): ?>
                                    <br><a href="<?php echo URL_BASE . htmlspecialchars($row['comprobante_url']); ?>" target="_blank" class="badge bg-info text-decoration-none mt-1" title="Ver Comprobante" style="font-size: 0.7rem;">
                                        <i class="fas fa-file-invoice-dollar"></i> Recibo
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td style="color: #10b981; font-weight: 600;">$<?php echo number_format($row['total'] ?? 0, 2); ?></td>
                            
                            <!-- ESTADO (Dropdown) -->
                            <td>
                                <select name="estado" class="form-select form-select-sm" style="font-size: 0.85rem; background-color: var(--glass-bg); color: var(--text-light); border-color: rgba(255,255,255,0.1);" onchange="toggleNota(this, <?php echo $row['id']; ?>)">
                                    <option value="Pendiente" <?php echo $row['estado'] == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                    <option value="Confirmado" <?php echo $row['estado'] == 'Confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                                    <option value="Rechazado" <?php echo $row['estado'] == 'Rechazado' ? 'selected' : ''; ?>>Rechazado</option>
                                    <option value="Entregado" <?php echo $row['estado'] == 'Entregado' ? 'selected' : ''; ?>>Entregado</option>
                                </select>
                            </td>

                            <!-- NOTA ADMIN -->
                            <td>
                                <textarea name="motivo_rechazo" id="nota_<?php echo $row['id']; ?>" class="form-control form-control-sm" rows="2" style="font-size: 0.8rem; background-color: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1); color: #fff;" placeholder="Motivo o detalle (opcional)..." <?php echo $row['estado'] == 'Pendiente' ? 'disabled' : ''; ?>><?php echo htmlspecialchars($row['motivo_rechazo'] ?? ''); ?></textarea>
                            </td>

                            <!-- ACCIONES -->
                            <td style="text-align: right;">
                                <div class="btn-group-vertical w-100 gap-1">
                                    <button type="submit" class="btn btn-sm btn-primary w-100" title="Guardar cambios y notificar al cliente">
                                        <i class="fas fa-paper-plane"></i> Enviar
                                    </button>
                                    <a href="<?php echo URL_BASE; ?>admin/pedidosDelete/<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger w-100" onclick="return confirmCustom(event, '¿Eliminar pedido de forma permanente?');">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </div>
                            </td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted" style="padding: 2rem;">No hay pedidos registrados</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleNota(selectElement, id) {
    const notaInput = document.getElementById('nota_' + id);
    if (!notaInput) return;
    
    // Enable the note if the state is anything other than Pendiente
    if (selectElement.value !== 'Pendiente') {
        notaInput.disabled = false;
        if(selectElement.value === 'Rechazado') {
            notaInput.setAttribute('required', 'required');
            notaInput.placeholder = 'Escribe obligatoriamente el motivo del rechazo...';
        } else {
            notaInput.removeAttribute('required');
            notaInput.placeholder = 'Nota o mensaje adicional (opcional)...';
        }
    } else {
        notaInput.disabled = true;
        notaInput.value = '';
    }
}
</script>