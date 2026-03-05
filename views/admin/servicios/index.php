<div class="content-header">
    <h2>Servicios Técnicos</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Administra los tipos de servicios de mantenimiento y reparaciones que ofreces.</p>
</div>

<div class="admin-card mb-3">
    <div class="admin-card-title d-flex justify-content-between align-items-center">
        <div>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
            Catálogo de Servicios
        </div>
        <a href="<?php echo URL_BASE; ?>admin/serviciosCreate" class="btn btn-primary btn-sm">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg> 
            Nuevo Servicio
        </a>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="8%">Icono</th>
                    <th width="20%">Nombre</th>
                    <th width="35%">Descripción</th>
                    <th width="12%">Desde ($)</th>
                    <th width="10%">Estado</th>
                    <th width="10%" style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($records)): ?>
                    <?php foreach($records as $r): ?>
                    <tr>
                        <td><span class="text-muted">#<?php echo $r['id']; ?></span></td>
                        <td class="text-center">
                            <i class="<?php echo htmlspecialchars($r['icono']); ?>" style="color: #60a5fa; font-size: 1.2rem;"></i>
                        </td>
                        <td style="font-weight: 500;"><?php echo htmlspecialchars($r['nombre']); ?></td>
                        <td class="text-muted" style="font-size: 0.85rem;">
                            <?php echo htmlspecialchars(mb_substr($r['descripcion'], 0, 60)) . (strlen($r['descripcion']) > 60 ? '...' : ''); ?>
                        </td>
                        <td style="color: #10b981; font-weight: 600;">$<?php echo number_format($r['precio_desde'], 2); ?></td>
                        <td>
                            <?php if ($r['estado']): ?>
                                <span class="badge badge-green">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-red">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?php echo URL_BASE; ?>admin/serviciosEdit/<?php echo $r['id']; ?>" class="btn btn-sm btn-outline" style="margin-right: 5px;">Editar</a>
                            <a href="<?php echo URL_BASE; ?>admin/serviciosDelete/<?php echo $r['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmCustom(event, '¿Seguro que deseas eliminar este servicio?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 2rem;">No hay servicios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
