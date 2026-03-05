<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> </title>
    <meta name="description" content="<?php echo APP_NAME; ?>. Tu tienda de tecnología de confianza. Productos de calidad al mejor precio.">
    <link rel="icon" type="image/x-icon" href="<?php echo URL_BASE; ?>public/img/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo URL_BASE; ?>public/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <!-- Starfield canvas background -->
    <canvas id="threejs-canvas"></canvas>

    <!-- ══════════════════════════════════════
         TOPNAV — always visible
    ══════════════════════════════════════ -->
    <header class="main-header" id="mainHeader">
        <div class="header-inner">
            <!-- Logo -->
            <a href="<?php echo URL_BASE; ?>" class="header-logo">
                <img src="<?php echo URL_BASE; ?>public/img/Logo.webp" alt="<?php echo APP_NAME; ?>">
            </a>

            <!-- Navigation -->
            <nav class="header-nav">
                <a href="<?php echo URL_BASE; ?>">Inicio</a>
                <a href="<?php echo URL_BASE; ?>catalogo">Catálogo</a>
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_rol'] == 1): ?>
                    <a href="<?php echo URL_BASE; ?>admin/dashboard">Admin</a>
                <?php endif; ?>
                
                <?php
                    // Compute cart count
                    $cart_count = 0;
                    if (isset($_SESSION['carrito'])) {
                        foreach ($_SESSION['carrito'] as $item) {
                            $cart_count += $item['cantidad'];
                        }
                    }
                ?>
                <a href="<?php echo URL_BASE; ?>carrito" class="header-cart" title="Carrito" style="position: relative;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                    <span id="cart-counter" style="position: absolute; top: 0px; right: -5px; background: #e11d48; color: white; font-size: 0.65rem; font-weight: bold; width: 16px; height: 16px; display: <?php echo $cart_count > 0 ? 'flex' : 'none'; ?>; align-items: center; justify-content: center; border-radius: 50%;">
                        <?php echo $cart_count; ?>
                    </span>
                </a>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo URL_BASE; ?>user/panel">Mi Panel</a>
                    <a href="<?php echo URL_BASE; ?>auth/logout" class="header-link-danger">Salir</a>
                <?php else: ?>
                    <a href="<?php echo URL_BASE; ?>auth/login" class="header-link-danger">Iniciar Sesión</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Toast Notification Container -->
    <div id="toast-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px;"></div>

    <script>
    // Global function to add to cart
    function addToCart(id, nombre, precio, imagen) {
        fetch('<?php echo URL_BASE; ?>carrito/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: id,
                nombre: nombre,
                precio: precio,
                imagen: imagen,
                cantidad: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update Badge
                const counter = document.getElementById('cart-counter');
                counter.textContent = data.count;
                counter.style.display = 'flex';
                
                // Show Toast Notification
                showToast(`Se añadió <strong>${nombre}</strong> al carrito`);
            }
        })
        .catch(err => console.error("Error adding to cart: ", err));
    }

    function showToast(message) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.style.background = 'rgba(16, 185, 129, 0.9)'; // emerald-500
        toast.style.color = 'white';
        toast.style.padding = '12px 20px';
        toast.style.borderRadius = '8px';
        toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.3)';
        toast.style.backdropFilter = 'blur(4px)';
        toast.style.fontSize = '0.9rem';
        toast.style.display = 'flex';
        toast.style.alignItems = 'center';
        toast.style.gap = '10px';
        toast.style.transform = 'translateX(100%)';
        toast.style.opacity = '0';
        toast.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        
        toast.innerHTML = `
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>${message}</span>
        `;
        
        container.appendChild(toast);
        
        // Trigger animation
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        });
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    </script>

    <main class="main-content">
