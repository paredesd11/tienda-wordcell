<div class="content-header">
    <h2>Métodos de Pago</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Configura las cuentas bancarias o billeteras para recibir pagos.</p>
</div>

<!-- ═══ FORM CARD ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title d-flex justify-content-between align-items-center">
        <div>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            Nuevo Método de Pago
        </div>
    </div>
    <form action="<?php echo URL_BASE; ?>admin/metodos_pagoCreate" method="POST" class="mt-3">
        <div class="form-grid">
            <div class="form-group">
                <label>Tipo (Ej: Transferencia, Nequi, Zelle)</label>
                <input type="text" name="tipo" class="form-control" placeholder="Ej: Transferencia" required>
            </div>
            <div class="form-group">
                <label>Banco / Plataforma</label>
                <input type="text" name="banco" class="form-control" placeholder="Ej: Banco Pichincha" required>
            </div>
            <div class="form-group">
                <label>Número de Cuenta / Teléfono</label>
                <input type="text" name="numero_cuenta" class="form-control" placeholder="Ej: 2201928374" required>
            </div>
            <div class="form-group">
                <label>Titular</label>
                <input type="text" name="titular" class="form-control" placeholder="Ej: Juan Pérez" required>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <button type="submit" class="btn btn-primary">Registrar Método</button>
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
                    <th width="15%">Tipo</th>
                    <th width="25%">Banco / Plataforma</th>
                    <th width="25%">N° Cuenta / Teléfono</th>
                    <th width="20%">Titular</th>
                    <th width="10%" style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                    <tr>
                        <td><span class="text-muted">#<?php echo $row['id']; ?></span></td>
                        <td><span class="badge badge-blue"><?php echo htmlspecialchars($row['tipo'] ?? ''); ?></span></td>
                        <td style="font-weight: 500;"><?php echo htmlspecialchars($row['banco'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['numero_cuenta'] ?? ''); ?></td>
                        <td class="text-muted"><?php echo htmlspecialchars($row['titular'] ?? '—'); ?></td>
                        <td style="text-align: right;">
                            <a href="<?php echo URL_BASE; ?>admin/metodos_pagoDelete/<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmCustom(event, '¿Eliminar registro?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center text-muted" style="padding: 2rem;">No hay métodos de pago registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>