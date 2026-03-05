<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - <?php echo APP_NAME; ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo URL_BASE; ?>public/img/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Bootstrap CSS (added before admin.css to maintain custom overrides but allow grid/utilities to work) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>public/css/admin.css">
</head>
<body>

<!-- ★ Animated starfield background -->
<div id="stars-bg"></div>

<div class="admin-shell">

    <!-- ══════════════════════════════════════
         TOP NAVBAR
    ══════════════════════════════════════ -->
    <nav class="admin-topnav">
        <a href="<?php echo URL_BASE; ?>" class="topnav-logo">
            <img src="<?php echo URL_BASE; ?>public/img/Logo.webp" alt="<?php echo APP_NAME; ?>">
        </a>
        <div class="topnav-links">
            <a href="<?php echo URL_BASE; ?>">Inicio</a>
            <a href="<?php echo URL_BASE; ?>catalogo">Catálogo</a>
            <a href="<?php echo URL_BASE; ?>admin/dashboard">Admin</a>
            <a href="<?php echo URL_BASE; ?>carrito" class="topnav-cart" title="Carrito">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
            </a>
            <a href="<?php echo URL_BASE; ?>auth/logout" class="danger">Salir</a>
        </div>
    </nav>

    <div class="admin-body">

        <!-- ══════════════════════════════════════
             SIDEBAR
        ══════════════════════════════════════ -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-title">
                    <?php echo (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] == 1) ? 'Panel<br>Admin' : 'Panel<br>Cliente'; ?>
                </div>
                <div class="sidebar-brand-sub">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre'] ?? 'Admin'); ?></div>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li data-page="dashboard">
                        <a href="<?php echo URL_BASE; ?>admin/dashboard">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            </span>
                            Dashboard
                        </a>
                    </li>
                    <li data-page="productos">
                        <a href="<?php echo URL_BASE; ?>admin/productos">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                            </span>
                            Productos
                        </a>
                    </li>
                    <li data-page="categorias">
                        <a href="<?php echo URL_BASE; ?>admin/categorias">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14"/><path d="M4.93 4.93a10 10 0 000 14.14"/></svg>
                            </span>
                            Categorías
                        </a>
                    </li>
                    <li data-page="marcas">
                        <a href="<?php echo URL_BASE; ?>admin/marcas">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polygon points="10,8 16,12 10,16"/></svg>
                            </span>
                            Marcas
                        </a>
                    </li>
                    <li data-page="referencias">
                        <a href="<?php echo URL_BASE; ?>admin/referencias">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                            </span>
                            Referencias
                        </a>
                    </li>
                    <li data-page="ofertas">
                        <a href="<?php echo URL_BASE; ?>admin/ofertas">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            </span>
                            Ofertas
                        </a>
                    </li>
                    <li data-page="noticias">
                        <a href="<?php echo URL_BASE; ?>admin/noticias">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 22h14a2 2 0 002-2V7.5L14.5 2H6a2 2 0 00-2 2v4"/><polyline points="14 2 14 8 20 8"/><path d="M2 15s.5-1 2-1 2 2 4 2 2-1 2-1"/></svg>
                            </span>
                            Noticias
                        </a>
                    </li>
                    <li data-page="servicio">
                        <a href="<?php echo URL_BASE; ?>admin/servicio">
                            <span class="nav-icon">
                                <i class="fas fa-tools"></i>
                            </span>
                            Solicitar Servicio
                        </a>
                    </li>
                    <li data-page="servicios">
                        <a href="<?php echo URL_BASE; ?>admin/servicios">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 17l-5-5m0 0l5-5m-5 5h12" /></svg>
                            </span>
                            Servicios (Home)
                        </a>
                    </li>
                    <li data-page="pedidos">
                        <a href="<?php echo URL_BASE; ?>admin/pedidos">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                            </span>
                            Pedidos
                        </a>
                    </li>
                    <li data-page="metodos">
                        <a href="<?php echo URL_BASE; ?>admin/metodos">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </span>
                            Métodos de Envío
                        </a>
                    </li>
                    <li data-page="reglas_envio">
                        <a href="<?php echo URL_BASE; ?>admin/reglas_envio">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </span>
                            Envío: Ofertas/Reglas
                        </a>
                    </li>
                    <li data-page="usuarios">
                        <a href="<?php echo URL_BASE; ?>admin/usuarios">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                            </span>
                            Usuarios
                        </a>
                    </li>
                    <li data-page="metodos_pago">
                        <a href="<?php echo URL_BASE; ?>admin/metodos_pago">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </span>
                            Métodos de Pago
                        </a>
                    </li>
                    <li data-page="configuracionPayphone">
                        <a href="<?php echo URL_BASE; ?>admin/configuracionPayphone">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                            </span>
                            Config. PayPhone
                        </a>
                    </li>
                    <li data-page="ubicaciones">
                        <a href="<?php echo URL_BASE; ?>admin/ubicaciones">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </span>
                            Ubicación
                        </a>
                    </li>

                    <li data-page="redes_sociales">
                        <a href="<?php echo URL_BASE; ?>admin/redes_sociales">
                            <span class="nav-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            </span>
                            Redes Sociales
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <a href="<?php echo URL_BASE; ?>auth/logout">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Cerrar Sesión
                </a>
            </div>
        </aside>

        <!-- ══════════════════════════════════════
             MAIN CONTENT
        ══════════════════════════════════════ -->
        <main class="admin-main">
            <div class="admin-content">
