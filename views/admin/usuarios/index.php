<div class="content-header">
    <h2>Gestión de Usuarios</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Crea o administra cuentas de staff y clientes del sistema.</p>
</div>

<!-- ═══ FORM CARD ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title d-flex justify-content-between align-items-center">
        <div>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            Nuevo Usuario
        </div>
    </div>
    <form action="<?php echo URL_BASE; ?>admin/usuariosCreate" method="POST" class="mt-3">
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Rol del Usuario</label>
                <select name="rol_id" class="form-control form-select" required>
                    <option value="2">Cliente / Usuario Normal (2)</option>
                    <option value="1">Administrador (1)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: Carlos" required>
            </div>
            <div class="form-group">
                <label>Apellido</label>
                <input type="text" name="apellido" class="form-control" placeholder="Ej: Mendoza" required>
            </div>
            <div class="form-group full-width">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" placeholder="usuario@dominio.com" required>
            </div>
        </div>
        <div class="d-flex gap-2 flex-end mt-4 border-top pt-3">
            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
        </div>
    </form>
</div>

<!-- ═══ TABLE ═══ -->
<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="30%">Email</th>
                    <th width="25%">Nombre Completo</th>
                    <th width="15%">Rol</th>
                    <th width="10%">Estado</th>
                    <th width="20%" style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                    <tr>
                        <td style="color: #94a3b8;"><?php echo htmlspecialchars($row['correo'] ?? ''); ?></td>
                        <td><strong style="color: #fff; font-weight: 500;"><?php echo htmlspecialchars(($row['nombre'] ?? '') . ' ' . ($row['apellido'] ?? '')); ?></strong></td>
                        <td>
                            <?php if ($row['rol_id'] == 1): ?>
                                <span class="badge badge-pink" style="letter-spacing: 0.5px;">ADMIN</span>
                            <?php else: ?>
                                <span class="badge badge-blue">CLIENTE</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge badge-green">Activo</span></td>
                        <td style="text-align: right;">
                            <a href="<?php echo URL_BASE; ?>admin/usuariosEdit/<?php echo $row['id']; ?>" class="btn btn-sm btn-outline" style="margin-right:0.5rem;">Editar</a>
                            <a href="<?php echo URL_BASE; ?>admin/usuariosDelete/<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmCustom(event, '¿Eliminar este usuario?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding: 3rem;">
                            <svg width="24" height="24" fill="none" stroke="#6b88b5" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:10px;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg><br>
                            No hay usuarios registrados en el sistema.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>