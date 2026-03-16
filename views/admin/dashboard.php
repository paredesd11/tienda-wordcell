<?php
// Dashboard cards — adapt counts based on what's available
$usersCount   = $usersCount   ?? 0;
$productsCount = $productsCount ?? 0;
$ordersCount  = $ordersCount  ?? 0;
$categoriasCount = $categoriasCount ?? 0;
$marcasCount  = $marcasCount  ?? 0;
?>

<div class="page-header">
    <h1>Dashboard</h1>
    <p>Bienvenido al Panel Administrativo de <?php echo APP_NAME; ?></p>
</div>

<!-- Stat Cards -->
<div class="stats-grid">
    <div class="stat-card stat-blue">
        <div class="stat-card-label">Productos</div>
        <div class="stat-card-value"><?php echo $productsCount; ?></div>
    </div>
    <div class="stat-card stat-purple">
        <div class="stat-card-label">Categorías</div>
        <div class="stat-card-value"><?php echo $categoriasCount; ?></div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-card-label">Marcas</div>
        <div class="stat-card-value"><?php echo $marcasCount; ?></div>
    </div>
    <div class="stat-card stat-orange">
        <div class="stat-card-label">Pedidos</div>
        <div class="stat-card-value"><?php echo $ordersCount; ?></div>
    </div>
    <div class="stat-card stat-pink">
        <div class="stat-card-label">Usuarios</div>
        <div class="stat-card-value"><?php echo $usersCount; ?></div>
    </div>
</div>

<!-- Quick Actions -->
<div class="admin-card">
    <div class="admin-card-title">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
        Accesos Rápidos
    </div>
    <div class="d-flex gap-1" style="flex-wrap:wrap;">
        <a href="<?php echo URL_BASE; ?>admin/productosCreate" class="btn btn-primary">+ Nuevo Producto</a>
        <a href="<?php echo URL_BASE; ?>admin/categorias"      class="btn btn-outline">Gestionar Categorías</a>
        <a href="<?php echo URL_BASE; ?>admin/marcas"          class="btn btn-outline">Gestionar Marcas</a>
        <a href="<?php echo URL_BASE; ?>admin/pedidos"         class="btn btn-outline">Ver Pedidos</a>
    </div>
</div>

<!-- Pending Orders List -->
<div class="admin-card mt-4">
    <div class="admin-card-title text-danger">
        <i class="fas fa-exclamation-circle"></i> Pedidos Pendientes
    </div>
    <?php if(!empty($pendingOrders)): ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pendingOrders as $po): ?>
                <tr>
                    <td>#<?php echo $po['id']; ?></td>
                    <td><?php echo htmlspecialchars($po['nombre'] . ' ' . $po['apellido']); ?></td>
                    <td>$<?php echo number_format($po['total'], 2); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($po['fecha_pedido'])); ?></td>
                    <td>
                        <a href="<?php echo URL_BASE; ?>admin/pedidos" class="btn btn-sm btn-outline" title="Ir a Pedidos"><i class="fas fa-eye"></i> Ver</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p class="text-muted">No tienes pedidos pendientes en este momento.</p>
    <?php endif; ?>
</div>

<!-- Pending Services List -->
<div class="admin-card mt-4">
    <div class="admin-card-title text-warning">
        <i class="fas fa-tools"></i> Servicios Técnicos Pendientes
    </div>
    <?php if(!empty($pendingServices)): ?>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Dispositivo</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pendingServices as $ps): ?>
                <tr>
                    <td>#<?php echo $ps['id']; ?></td>
                    <td><?php echo htmlspecialchars($ps['nombre'] . ' ' . $ps['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($ps['dispositivo']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($ps['fecha_solicitud'])); ?></td>
                    <td>
                        <a href="<?php echo URL_BASE; ?>admin/servicio" class="btn btn-sm btn-outline" title="Ir a Servicios"><i class="fas fa-eye"></i> Ver</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p class="text-muted">No tienes servicios técnicos pendientes en este momento.</p>
    <?php endif; ?>
</div>

