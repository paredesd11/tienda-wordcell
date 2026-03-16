<?php
// Ofertas: create form + table on same page
?>
<div class="content-header">
    <h2>Ofertas y Descuentos</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Configura descuentos promocionales para Productos y Servicios Técnicos.</p>
</div>

<div class="row">
    <!-- COLUMNA PRODUCTOS -->
    <div class="col-md-6">
        <div class="admin-card mb-3">
            <div class="admin-card-title d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-box"></i> Ofertas en Productos
                </div>
            </div>
            <form action="<?php echo URL_BASE; ?>admin/ofertasCreate" method="POST" class="mt-3">
                <div class="form-group mb-2">
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
                        <small style="color:#f87171;margin-top:4px;display:block;">⚠ No hay productos disponibles.</small>
                    <?php endif; ?>
                </div>
                <div class="form-group mb-2">
                    <label>Descuento (%)</label>
                    <input type="number" name="descuento_porcentaje" class="form-control" min="1" max="100" step="0.5" placeholder="Ej: 15" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="form-group mb-2">
                        <label>Inicio (Opcional)</label>
                        <input type="date" name="fecha_inicio" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <label>Fin (Opcional)</label>
                        <input type="date" name="fecha_fin" class="form-control">
                    </div>
                </div>
                <div class="mt-3 border-top pt-3">
                    <button type="submit" class="btn w-100" id="btn-registrar-oferta" style="
                        background: linear-gradient(135deg, #f59e0b, #ef4444, #8b5cf6);
                        background-size: 200% 200%;
                        animation: ofertaBtnPulse 2.5s ease infinite;
                        color: #fff; font-weight: 700; font-size: 1rem;
                        border: none; border-radius: 10px; padding: 12px;
                        cursor: pointer; box-shadow: 0 4px 20px rgba(245,158,11,0.4);
                        transition: transform 0.15s ease, box-shadow 0.15s ease;
                    "
                    onmouseover="this.style.transform='scale(1.02)';this.style.boxShadow='0 6px 28px rgba(245,158,11,0.6)'"
                    onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 4px 20px rgba(245,158,11,0.4)'">
                        🔥 Registrar Oferta de Producto
                    </button>
                    <style>
                    @keyframes ofertaBtnPulse {
                        0%   { background-position: 0% 50%; }
                        50%  { background-position: 100% 50%; }
                        100% { background-position: 0% 50%; }
                    }
                    </style>
                </div>
            </form>
        </div>

        <div class="admin-card">
            <div class="admin-table-container">
                <table class="admin-table" style="font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>- %</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($records)): ?>
                            <?php foreach($records as $row): ?>
                            <tr>
                                <td style="font-weight: 500; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($row['producto_nombre'] ?? ('Producto #' . $row['producto_id'])); ?></td>
                                <td><span class="badge badge-orange">-<?php echo $row['descuento_porcentaje']; ?>%</span></td>
                                <td class="text-muted"><?php echo !empty($row['fecha_inicio']) ? date('d/m/Y', strtotime($row['fecha_inicio'])) : '—'; ?></td>
                                <td class="text-muted"><?php echo !empty($row['fecha_fin']) ? date('d/m/Y', strtotime($row['fecha_fin'])) : '—'; ?></td>
                                <td style="text-align: right;">
                                    <a href="<?php echo URL_BASE; ?>admin/ofertasDelete/<?php echo $row['id']; ?>"
                                       class="btn btn-sm btn-danger py-1 px-2"
                                       onclick="return confirmCustom(event, '¿Eliminar esta oferta?');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No hay ofertas en productos.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- COLUMNA SERVICIOS TECNICOS -->
    <div class="col-md-6 mt-4 mt-md-0">
        <div class="admin-card mb-3" style="border-top: 3px solid #10b981;">
            <div class="admin-card-title d-flex justify-content-between align-items-center">
                <div style="color: #10b981;">
                    <i class="fas fa-tools"></i> Ofertas en Servicio Técnico
                </div>
            </div>
            <form action="<?php echo URL_BASE; ?>admin/ofertasServiciosCreate" method="POST" class="mt-3">
                <div class="form-group mb-2">
                    <label>Nombre de la Promoción</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Descuento 1ra Reparación" required>
                </div>
                <div class="form-group mb-2">
                    <label>Descuento (%)</label>
                    <input type="number" name="descuento_porcentaje" class="form-control" min="1" max="100" step="0.5" placeholder="Ej: 50" required>
                </div>
                <div class="form-group mb-2">
                    <label>Condición Automática</label>
                    <select name="condicion" class="form-control form-select">
                        <option value="TODOS">Aplica a Todos (Sin condición)</option>
                        <option value="PRIMERA_VEZ">Solo Primera Solicitud Web del Usuario</option>
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="form-group mb-2">
                        <label>Válido Desde (Obligatorio)</label>
                        <input type="date" name="fecha_inicio" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Válido Hasta (Obligatorio)</label>
                        <input type="date" name="fecha_fin" class="form-control" required>
                    </div>
                </div>
                <div class="mt-3 border-top pt-3">
                    <button type="submit" class="btn btn-success w-100" style="background:#10b981; border:none; font-weight:bold;">Crear Oferta Servicio</button>
                </div>
            </form>
        </div>

        <div class="admin-card">
            <div class="admin-table-container">
                <table class="admin-table" style="font-size: 0.85rem;">
                    <thead>
                        <tr>
                            <th>Promo</th>
                            <th>- %</th>
                            <th>Condición</th>
                            <th>Fin</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ofertas_servicios)): ?>
                            <?php foreach($ofertas_servicios as $row): ?>
                            <?php $expirada = strtotime($row['fecha_fin']) < strtotime(date('Y-m-d')); ?>
                            <tr style="<?php echo $expirada ? 'opacity:0.45; text-decoration: line-through;' : ''; ?>">
                                <td style="font-weight: 500; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($row['nombre']); ?>"><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><span class="badge badge-green">-<?php echo $row['descuento_porcentaje']; ?>%</span></td>
                                <td>
                                    <?php if($row['condicion'] == 'PRIMERA_VEZ'): ?>
                                        <span class="badge bg-primary text-white" style="font-size:0.65rem;">1ra Vez</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary text-white" style="font-size:0.65rem;">Todos</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted">
                                    <?php echo date('d/m/Y', strtotime($row['fecha_fin'])); ?>
                                    <?php if ($expirada): ?>
                                        <br><span style="font-size: 0.65rem; color: #ef4444ff; font-weight: bold; text-decoration: none;">EXPIRADA</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="<?php echo URL_BASE; ?>admin/ofertasServiciosDelete/<?php echo $row['id']; ?>"
                                       class="btn btn-sm btn-danger py-1 px-2"
                                       onclick="return confirmCustom(event, '¿Eliminar esta promo de servicios?');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No hay promociones en servicios.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>