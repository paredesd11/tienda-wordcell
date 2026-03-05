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
