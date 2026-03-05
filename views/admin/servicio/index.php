<?php
// Servicio Técnico: create form + table on same page
?>
<div class="content-header">
    <h2>Servicio Técnico</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Gestiona las solicitudes de soporte técnico de los clientes</p>
</div>

<!-- ═══ FORM CARD — Registrar Solicitud ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
        Nueva Solicitud de Servicio
    </div>
    <form action="<?php echo URL_BASE; ?>admin/servicioCreate" method="POST">
<div class="form-grid">
            <div class="form-group">
                <label>Cliente / Usuario</label>
                <select name="usuario_id" class="form-control form-select">
                    <option value="">Selecciona un usuario...</option>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach($usuarios as $u): ?>
                            <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars(($u['nombre'] ?? '') . ' ' . ($u['apellido'] ?? '') . ' — ' . ($u['correo'] ?? '')); ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Dispositivo</label>
                <input type="text" name="dispositivo" class="form-control" placeholder="Ej: iPhone 15 Pro">
            </div>
            <div class="form-group">
                <label>Prioridad</label>
                <select name="prioridad" class="form-control form-select">
                    <option value="Baja">Baja</option>
                    <option value="Media" selected>Media</option>
                    <option value="Alta">Alta</option>
                    <option value="Urgente">Urgente</option>
                </select>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado" class="form-control form-select">
                    <option value="Pendiente">⏳ Pendiente</option>
                    <option value="En Proceso">🔧 En Proceso</option>
                    <option value="Resuelto">✅ Resuelto</option>
                    <option value="Cancelado">❌ Cancelado</option>
                </select>
            </div>
            <div class="form-group">
                <label>Fecha Entrega</label>
                <input type="datetime-local" name="fecha_fin" class="form-control">
            </div>
            <div class="form-group">
                <label>Precio Estimado ($)</label>
                <input type="number" step="0.01" name="precio_estimado" class="form-control" value="0.00">
            </div>
            <div class="form-group full-width">
                <label>Descripción del Problema</label>
                <textarea name="descripcion_problema" class="form-control" rows="3"
                          placeholder="Describe el problema reportado por el cliente..." required></textarea>
            </div>
        </div>
        <div class="d-flex gap-1 flex-end mt-2">
            <button type="submit" class="btn btn-primary">Registrar Solicitud</button>
        </div>
    </form>
</div>

<!-- ═══ WHATSAPP CONFIG SECTION ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title">
        <i class="fab fa-whatsapp"></i> Configuración de Notificaciones WhatsApp (Admins)
    </div>
    <div class="p-2">
        <form action="<?php echo URL_BASE; ?>admin/whatsappConfigSave" method="POST" class="d-flex gap-1 align-items-end mb-2">
            <div class="form-group">
                <label>Nombre Admin</label>
                <input type="text" name="nombre_admin" class="form-control" placeholder="Ej: Juan Pérez" required>
            </div>
            <div class="form-group">
                <label>Número (con +)</label>
                <input type="text" name="numero" class="form-control" placeholder="+593..." required>
            </div>
            <button type="submit" class="btn btn-success">Agregar Número</button>
        </form>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Número</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($whatsapp_config)): ?>
                        <?php foreach($whatsapp_config as $wa): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($wa['nombre_admin']); ?></td>
                            <td><?php echo htmlspecialchars($wa['numero']); ?></td>
                            <td>
                                <a href="<?php echo URL_BASE; ?>admin/whatsappConfigDelete/<?php echo $wa['id']; ?>" 
                                   class="btn btn-sm btn-danger" onclick="return confirmCustom(event, '¿Eliminar?')">Borrar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">No hay números configurados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ═══ PRICING CONFIG SECTION ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title">
        <i class="fas fa-dollar-sign"></i> Configuración de Precios (Prioridad / Tiempo)
    </div>
    <div class="p-2">
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Descripción</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($precios_config)): ?>
                        <?php foreach($precios_config as $p): ?>
                        <tr>
                            <form action="<?php echo URL_BASE; ?>admin/pricingConfigSave" method="POST">
                                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                <td><strong><?php echo htmlspecialchars($p['concepto']); ?></strong></td>
                                <td><?php echo $p['tipo']; ?></td>
                                <td>
                                    <input type="number" step="0.01" name="valor" class="form-control" value="<?php echo $p['valor']; ?>" style="width:100px;">
                                </td>
                                <td><?php echo htmlspecialchars($p['descripcion']); ?></td>
                                <td>
                                    <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                                </td>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ═══ solicitudes TABLE ═══ -->
<div class="admin-table-container">
    <table class="admin-table" style="font-size: 0.9rem;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Equipo</th>
                <th>Problema</th>
                <th>Prioridad</th>
                <th>Entrega</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Nota Admin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($records)): ?>
                <?php foreach($records as $row): ?>
                <?php
                    $estado = $row['estado'] ?? 'Pendiente';
                    $badgeClass = match($estado) {
                        'Resuelto'   => 'badge-green',
                        'En Proceso' => 'badge-orange',
                        'Cancelado'  => 'badge-red',
                        default      => 'badge-gray'
                    };
                    $prioridadClass = match($row['prioridad']) {
                        'Urgente' => 'text-danger fw-bold',
                        'Alta'    => 'text-warning fw-bold',
                        default   => ''
                    };
                ?>
                <tr>
                    <form action="<?php echo URL_BASE; ?>admin/servicioUpdate" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td title="<?php echo htmlspecialchars($row['usuario_id']); ?>">
                            <?php echo htmlspecialchars($row['usuario_nombre'] ?? 'Usuario'); ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['dispositivo'] ?? '—'); ?></td>
                        <td style="max-width: 15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlspecialchars($row['descripcion_problema'] ?? ''); ?>">
                            <?php echo htmlspecialchars($row['descripcion_problema'] ?? '—'); ?>
                        </td>
                        <td><span class="<?php echo $prioridadClass; ?>"><?php echo $row['prioridad']; ?></span></td>
                        <td><?php echo !empty($row['fecha_fin']) ? date('d/m, H:i', strtotime($row['fecha_fin'])) : '—'; ?></td>
                        <td>
                            <input type="number" step="0.01" name="precio_estimado" class="form-control" 
                                   value="<?php echo $row['precio_estimado']; ?>" style="width:90px; padding: 2px 5px;">
                        </td>
                        <td>
                            <select name="estado" class="form-control form-select-sm" onchange="checkEstadoAlert(this)" style="padding: 2px 5px; font-size: 0.8rem;">
                                <option value="Pendiente" <?php echo $estado == 'Pendiente' ? 'selected' : ''; ?>>⏳ Pendiente</option>
                                <option value="En Progreso" <?php echo $estado == 'En Proceso' ? 'selected' : ''; ?>>🔧 En Progreso</option>
                                <option value="Terminado" <?php echo $estado == 'Terminado' ? 'selected' : ''; ?>>✅ Terminado</option>
                                <option value="Entregado" <?php echo $estado == 'Entregado' ? 'selected' : ''; ?>>📦 Entregado</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="nota_admin" class="form-control form-control-sm" placeholder="Razón del cambio..." style="width: 120px; font-size:0.8rem; padding: 2px 5px;">
                        </td>
                        <td class="d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
                            <a href="<?php echo URL_BASE; ?>admin/servicioDelete/<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirmCustom(event, '¿Eliminar esta solicitud?');">Eliminar</a>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="empty-row text-center">
                        No hay solicitudes de servicio técnico registradas.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<br><br>

<!-- ═══ WHATSAPP API CONFIG ═══ -->
<div class="admin-card mt-5 border-top border-secondary pt-4" style="border-top-width: 2px !important; margin-top: 3rem !important;">
    <div class="admin-card-title">
        <i class="fab fa-whatsapp"></i> Configuración API WhatsApp (Credenciales)
    </div>
    <div class="p-3">
        <form action="<?php echo URL_BASE; ?>admin/whatsappApiSave" method="POST">
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label text-white-50 small mb-2">Instance ID</label>
                    <input type="text" name="instance_id" class="form-control bg-dark text-white border-secondary p-2" 
                           value="<?php echo htmlspecialchars($whatsapp_api['instance_id'] ?? ''); ?>" placeholder="Ej: instance12345">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label text-white-50 small mb-2">Token</label>
                    <input type="text" name="mensaje_token" class="form-control bg-dark text-white border-secondary p-2" 
                           value="<?php echo htmlspecialchars($whatsapp_api['mensaje_token'] ?? ''); ?>" placeholder="Tu Token de UltraMsg">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label text-white-50 small mb-2">API URL</label>
                    <input type="text" name="api_url" class="form-control bg-dark text-white border-secondary p-2" 
                           value="<?php echo htmlspecialchars($whatsapp_api['api_url'] ?? 'https://api.ultramsg.com'); ?>">
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-primary w-100 py-2">Guardar API</button>
                </div>
            </div>
            <div class="mt-2 small text-info">
                <i class="fas fa-info-circle"></i> Estas credenciales permiten que el sistema envíe mensajes automáticos a través de UltraMsg.
            </div>
        </form>
    </div>
</div>

<script>
function checkEstadoAlert(selectObj) {
    if (selectObj.value === 'Entregado') {
        showCustomAlert('ℹ️ Importante: Las solicitudes marcadas como "Entregado" desaparecerán automáticamente de esta tabla después de 24 horas.', 'warning');
    }
}
</script>


