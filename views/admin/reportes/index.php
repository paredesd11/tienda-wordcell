<!-- Encabezado exclusivo de Impresión -->
<div class="only-print">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
        <div>
            <!-- Dejamos el logo con sus colores originales para un reporte más vivo -->
            <img src="<?php echo URL_BASE; ?>public/img/Logo.webp" alt="Logo" style="height: 55px; margin-left: 10px;">
        </div>
        <div style="text-align: right;">
            <h2 style="margin: 0; font-size: 1.5rem; color: #000;">Reporte Operativo (Ventas y Servicios)</h2>
            <p style="margin: 0; font-size: 1rem; color: #555;">Fecha de Emisión: <?php echo date('d/m/Y h:i A'); ?></p>
            <p style="margin: 0; font-size: 0.95rem; color: #777; margin-top: 5px;">
                <?php if ($inicio && $fin): ?>
                    <strong>Período:</strong> <?php echo date('d/m/Y', strtotime($inicio)); ?> al <?php echo date('d/m/Y', strtotime($fin)); ?>
                <?php else: ?>
                    <strong>Período:</strong> Histórico Completo
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>

<div class="page-header d-flex align-center flex-between" style="justify-content: space-between;">
    <div>
        <h1>Reportes y Ventas</h1>
        <p>Resumen administrativo de ventas en tienda y servicios técnicos.</p>
    </div>
    <button onclick="window.print()" class="btn btn-primary d-print-none">
        <i class="fas fa-file-pdf"></i> Imprimir a PDF
    </button>
</div>

<!-- Filtro de Fechas -->
<div class="card mb-4 d-print-none" style="padding: 15px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--card-border);">
    <form method="GET" action="<?php echo URL_BASE; ?>admin/reportes" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
        <div style="flex: 1; min-width: 150px;">
            <label style="display: block; margin-bottom: 5px; font-size: 0.9em; color: var(--text-muted);">Desde:</label>
            <input type="date" name="inicio" class="form-control" value="<?php echo htmlspecialchars($inicio ?? ''); ?>">
        </div>
        <div style="flex: 1; min-width: 150px;">
            <label style="display: block; margin-bottom: 5px; font-size: 0.9em; color: var(--text-muted);">Hasta:</label>
            <input type="date" name="fin" class="form-control" value="<?php echo htmlspecialchars($fin ?? ''); ?>">
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="submit" class="btn btn-primary" style="padding: 8px 15px;">
                <i class="fas fa-filter"></i> Filtrar
            </button>
            <?php if ($inicio || $fin): ?>
                <a href="<?php echo URL_BASE; ?>admin/reportes" class="btn btn-secondary" style="padding: 8px 15px;">
                    Limpiar
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php
// Procesar Totales Pedidos
$totalIngresosPedidos = 0;
$totalPendientesPedidos = 0;

foreach ($reportePedidos as $rp) {
    // Manejo flexible del estado para sumar ventas concretadas
    $estado_lower = strtolower(trim($rp['estado']));
    if (in_array($estado_lower, ['pagado', 'completado', 'entregado', 'confirmado', 'aprobado'])) {
        $totalIngresosPedidos += (float)$rp['monto'];
    } elseif (in_array($estado_lower, ['pendiente', 'en espera', 'procesando'])) {
        $totalPendientesPedidos += (float)$rp['monto'];
    }
}

// Procesar Totales Servicios
$totalIngresosServicios = 0;
$totalPendientesServicios = 0;

foreach ($reporteServicios as $rs) {
    $estado_lower = strtolower(trim($rs['estado']));
    if (in_array($estado_lower, ['entregado', 'pagado', 'completado', 'finalizado', 'confirmado', 'aprobado'])) {
        $totalIngresosServicios += (float)$rs['monto'];
    } else { // Pendiente, En Proceso, etc.
        $totalPendientesServicios += (float)$rs['monto'];
    }
}

$ingresosNetos = $totalIngresosPedidos + $totalIngresosServicios;
?>

<!-- Resumen General -->
<div class="stats-grid mb-3">
    <div class="stat-card stat-blue">
        <div class="stat-card-label">Ingresos Totales (Pagados)</div>
        <div class="stat-card-value">$<?php echo number_format($ingresosNetos, 2); ?></div>
        <div style="font-size: 0.8rem; margin-top: 5px; opacity: 0.8;">Ventas + SS.TT</div>
    </div>
    
    <div class="stat-card stat-green">
        <div class="stat-card-label">Solo Ventas (Tienda)</div>
        <div class="stat-card-value">$<?php echo number_format($totalIngresosPedidos, 2); ?></div>
    </div>
    
    <div class="stat-card stat-orange">
        <div class="stat-card-label">Solo Servicios Técnicos</div>
        <div class="stat-card-value">$<?php echo number_format($totalIngresosServicios, 2); ?></div>
    </div>

    <div class="stat-card stat-pink">
        <div class="stat-card-label">Dinero Pendiente / Por Cobrar</div>
        <div class="stat-card-value">$<?php echo number_format($totalPendientesPedidos + $totalPendientesServicios, 2); ?></div>
    </div>
</div>

<div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    
    <!-- Detalle Pedidos -->
    <div class="admin-card print-card">
        <div class="admin-card-title">
            <i class="fas fa-shopping-cart text-muted"></i> Reporte de Pedidos (Ventas)
        </div>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>Cantidad</th>
                    <th style="text-align: right;">Total ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($reportePedidos)): ?>
                    <tr><td colspan="3" class="empty-row">No hay registros de pedidos aún.</td></tr>
                <?php else: ?>
                    <?php 
                    $sumaPedidos = 0;
                    foreach($reportePedidos as $row): 
                        $sumaPedidos += $row['monto'];
                    ?>
                    <tr>
                        <td>
                            <?php 
                            $badge = 'badge-gray';
                            if(in_array($row['estado'], ['Pagado', 'Completado', 'Entregado'])) $badge = 'badge-green';
                            if($row['estado'] == 'Pendiente') $badge = 'badge-orange';
                            if($row['estado'] == 'Rechazado' || $row['estado'] == 'Cancelado') $badge = 'badge-red';
                            ?>
                            <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($row['estado']); ?></span>
                        </td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td style="text-align: right; font-weight: 500;">
                            $<?php echo number_format($row['monto'] ?: 0, 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="background: rgba(255,255,255,0.03);">
                    <td colspan="2" style="font-weight: 700; text-align: right; border-bottom: none;">TOTAL ACUMULADO:</td>
                    <td style="font-weight: 700; text-align: right; color: #60a5fa; font-size: 1.1em; border-bottom: none;">
                        $<?php echo number_format($sumaPedidos ?? 0, 2); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Detalle Servicios Técnicos -->
    <div class="admin-card print-card">
        <div class="admin-card-title">
            <i class="fas fa-tools text-muted"></i> Reporte de Servicios Técnicos
        </div>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>Cantidad</th>
                    <th style="text-align: right;">Ingresos ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($reporteServicios)): ?>
                    <tr><td colspan="3" class="empty-row">No hay registros de servicios aún.</td></tr>
                <?php else: ?>
                    <?php 
                    $sumaServicios = 0;
                    foreach($reporteServicios as $row): 
                        $sumaServicios += $row['monto'];
                    ?>
                    <tr>
                        <td>
                            <?php 
                            $badge = 'badge-gray';
                            if(in_array($row['estado'], ['Pagado', 'Entregado', 'Finalizado'])) $badge = 'badge-green';
                            if($row['estado'] == 'Pendiente' || strpos(strtolower($row['estado']), 'proceso') !== false) $badge = 'badge-orange';
                        if($row['estado'] == 'Cancelado') $badge = 'badge-red';
                            ?>
                            <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($row['estado'] ?: 'No Definido'); ?></span>
                        </td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td style="text-align: right; font-weight: 500;">
                            $<?php echo number_format($row['monto'] ?: 0, 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="background: rgba(255,255,255,0.03);">
                    <td colspan="2" style="font-weight: 700; text-align: right; border-bottom: none;">TOTAL ACUMULADO:</td>
                    <td style="font-weight: 700; text-align: right; color: #4ade80; font-size: 1.1em; border-bottom: none;">
                        $<?php echo number_format($sumaServicios ?? 0, 2); ?>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Tablas Detalladas (Nuevas) -->
<div class="detailed-reports">
    <h3 class="mt-5 mb-3" style="color: var(--text-main); border-bottom: 2px solid var(--border-color); padding-bottom: 10px; font-size: 1.25rem;">
        <i class="fas fa-list text-primary"></i> Desglose Detallado de Ventas
    </h3>
    <div class="admin-card print-card mb-5">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>C.I</th>
                    <th>Correo</th>
                    <th>Detalle de Productos</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Total ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($listaPedidos)): ?>
                    <tr><td colspan="8" class="empty-row text-center">No hay registros detallados de pedidos para este período.</td></tr>
                <?php else: ?>
                    <?php foreach($listaPedidos as $lp): ?>
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 500;">#<?php echo $lp['pedido_id']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($lp['fecha_pedido'])); ?></td>
                        <td style="font-weight: 600;"><?php echo htmlspecialchars($lp['nombre'] . ' ' . $lp['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($lp['cedula'] ?: 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($lp['correo']); ?></td>
                        <td style="max-width: 300px;"><small><?php echo htmlspecialchars($lp['detalle'] ?: 'Sin detalle'); ?></small></td>
                        <td>
                            <?php 
                            $badge = 'badge-gray';
                            $est = strtolower(trim($lp['estado']));
                            if(in_array($est, ['pagado','completado','entregado','confirmado','aprobado'])) $badge = 'badge-green';
                            if(in_array($est, ['pendiente','en espera','procesando'])) $badge = 'badge-orange';
                            if($est == 'rechazado' || $est == 'cancelado') $badge = 'badge-red';
                            ?>
                            <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($lp['estado']); ?></span>
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #4ade80;">$<?php echo number_format($lp['total'] ?: 0, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <h3 class="mt-5 mb-3" style="color: var(--text-main); border-bottom: 2px solid var(--border-color); padding-bottom: 10px; font-size: 1.25rem;">
        <i class="fas fa-tools text-orange"></i> Desglose Detallado de Servicios Técnicos
    </h3>
    <div class="admin-card print-card mb-5">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>C.I</th>
                    <th>Correo</th>
                    <th>Equipo y Problema</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Costo ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($listaServicios)): ?>
                    <tr><td colspan="8" class="empty-row text-center">No hay registros detallados de servicios para este período.</td></tr>
                <?php else: ?>
                    <?php foreach($listaServicios as $ls): ?>
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 500;">#<?php echo $ls['servicio_id']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($ls['fecha_solicitud'])); ?></td>
                        <td style="font-weight: 600;"><?php echo htmlspecialchars($ls['nombre'] . ' ' . $ls['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($ls['cedula'] ?: 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($ls['correo']); ?></td>
                        <td style="max-width: 300px;"><small><?php echo htmlspecialchars($ls['detalle'] ?: 'Sin detalle'); ?></small></td>
                        <td>
                            <?php 
                            $badge = 'badge-gray';
                            $est = strtolower(trim($ls['estado']));
                            if(in_array($est, ['entregado','pagado','completado','finalizado','confirmado','aprobado'])) $badge = 'badge-green';
                            if($est == 'pendiente' || strpos($est, 'proceso') !== false) $badge = 'badge-orange';
                            if($est == 'cancelado') $badge = 'badge-red';
                            ?>
                            <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($ls['estado'] ?: 'N/A'); ?></span>
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #3b82f6;">$<?php echo number_format($ls['total'] ?: 0, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pie de firma exclusivo de Impresión -->
<div class="only-print">
    <div style="margin-top: 60px; text-align: center;">
        <div style="width: 250px; border-top: 1px solid #000; margin: 0 auto; padding-top: 10px;">
            <p style="margin: 0; font-weight: bold; color: #000; text-transform: uppercase;">
                <?php echo htmlspecialchars($currentUser['nombre'] . ' ' . $currentUser['apellido']); ?>
            </p>
            <p style="margin: 0; color: #555;">C.I: <?php echo htmlspecialchars($currentUser['cedula'] ?? 'N/A'); ?></p>
            <p style="margin: 0; color: #555; font-size: 0.9em;">Firma de Responsable / Generador</p>
        </div>
    </div>
</div>

<!-- CSS Específico para impresión en PDF -->
<style>
/* Ocultar elementos exclusivos de impresion en la vista normal del navegador */
.only-print {
    display: none;
}

/* Permitir scroll en móviles en pantallas pequeñas, pero lo apagamos en impresión */
.print-card {
    overflow-x: auto;
}

@media print {
    /* Mostrar cabecera y pie de impresión */
    .only-print {
        display: block !important;
    }
    
    /* Forzamos que los colores de fondo e imágenes se impriman (Chrome/Edge/Safari) */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* Ocultamos elementos de navegación y extras */
    .admin-topnav, .admin-sidebar, .d-print-none, #stars-bg, .page-header {
        display: none !important;
    }
    
    /* Configuración de la página */
    @page {
        margin: 1.5cm;
        size: A4 portrait;
    }
    
    /* Configuración crítica para evitar que el navegador corte hojas (truncamiento PDF) */
    html, body, .admin-shell, .admin-body, .admin-main, .admin-content {
        height: auto !important;
        min-height: auto !important;
        max-height: none !important;
        overflow: visible !important;
        position: static !important;
        display: block !important;
        background: #ffffff !important;
        color: #1e293b !important;
        margin: 0 !important;
        padding: 0 !important;
        font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
    }
    
    .admin-main { margin-left: 0 !important; display: block !important; }
    .admin-content { padding: 0 !important; display: block !important; }
    
    /* Estilo del Header de Impresión */
    .only-print > div:first-child {
        border-bottom: 3px solid #3b82f6 !important;
        padding-bottom: 15px !important;
        margin-bottom: 25px !important;
        background: #f8fafc !important;
        padding: 15px !important;
        border-radius: 8px 8px 0 0 !important;
    }
    
    /* Etiquetas de texto generales: Mas pequeñas (responsive on paper) */
    html, body {
        font-size: 11px !important; 
    }
    
    h1, h2, h3, p, td, th, div {
        color: #0f172a !important;
    }
    
    .admin-table th, .admin-table td {
        padding: 6px !important;
        font-size: 10px !important;
    }
    
    /* Ajuste de las tarjetas principales (stats) */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 15px !important;
        margin-bottom: 35px !important;
    }
    
    .stat-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px !important;
        padding: 15px 10px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
        text-align: center !important;
        position: relative !important;
        overflow: hidden !important;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
    }
    
    /* Colores superiores de cada tarjeta */
    .stat-blue::before { background-color: #3b82f6 !important; }
    .stat-green::before { background-color: #10b981 !important; }
    .stat-orange::before { background-color: #f59e0b !important; }
    .stat-pink::before { background-color: #ef4444 !important; }
    
    .stat-card::after { display: none !important; } 
    
    .stat-card-label {
        color: #64748b !important;
        font-weight: 600 !important;
        font-size: 0.8rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 8px !important;
    }
    
    .stat-card-value {
        color: #0f172a !important;
        font-size: 1.8rem !important;
        font-weight: 800 !important;
    }
    
    /* Paneles de detalles (Ventas y Servicios) - NO USAR GRID EN PRINT */
    .form-grid {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 25px !important;
    }
    .form-grid > div {
        flex: 1 1 45% !important;
    }
    
    /* Nuclear Reset: Previene todos los bugs conocidos del renderizador PDF de Chromium en elementos grandes */
    .print-card, .admin-card, .detailed-reports {
        display: block !important;
        position: static !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
        transform: none !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        max-height: none !important;
        height: auto !important;
        overflow: visible !important;
    }
    
    .admin-table {
        display: table !important;
        page-break-inside: auto !important;
        width: 100% !important;
    }
    
    .print-card {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
    
    .admin-card-title {
        background: #f1f5f9 !important;
        color: #1e293b !important;
        border-bottom: 1px solid #cbd5e1 !important;
        padding: 12px 15px !important;
        font-weight: 700 !important;
        font-size: 1.1rem !important;
    }
    
    .admin-card-title i { color: #3b82f6 !important; margin-right: 8px !important; }
    
    /* Configuración de Tablas */
    .admin-table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 0 !important;
    }
    
    .admin-table th {
        background: #ffffff !important;
        color: #64748b !important;
        border-bottom: 2px solid #e2e8f0 !important;
        padding: 10px 15px !important;
        text-transform: uppercase !important;
        font-size: 0.75rem !important;
        letter-spacing: 0.5px !important;
    }
    
    .admin-table td {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 12px 15px !important;
        color: #334155 !important;
        font-size: 0.9rem !important;
    }
    
    /* Filas alternas para mejor lectura */
    .admin-table tbody tr:nth-child(even) {
        background-color: #f8fafc !important;
    }
    
    /* El footer de los totales */
    tfoot tr td {
        background: #eff6ff !important;
        color: #1e293b !important;
        border-top: 2px solid #bfdbfe !important;
        padding: 15px !important;
    }
    
    /* Forzar Badges al imprimir con colores suaves */
    .badge {
        padding: 4px 8px !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
        display: inline-block !important;
        text-align: center !important;
    }
    .badge-green { background: #dcfce7 !important; color: #166534 !important; border: 1px solid #bbf7d0 !important; }
    .badge-orange { background: #fef3c7 !important; color: #92400e !important; border: 1px solid #fde68a !important; }
    .badge-red { background: #fee2e2 !important; color: #b91c1c !important; border: 1px solid #fecaca !important; }
    .badge-gray { background: #f1f5f9 !important; color: #475569 !important; border: 1px solid #e2e8f0 !important; }

    /* Forzar salto de página limpio en PDF: Desactivado a petición del usuario para intentar 1 sola página */
    .detailed-reports h3 { page-break-after: avoid; }
    .admin-table { page-break-inside: auto; }
    .admin-table tr { page-break-inside: avoid; page-break-after: auto; }
}
</style>
