<div class="catalogo-header">
    <div class="container">
        <h1 class="catalogo-title">Catálogo</h1>
        
        <form action="<?php echo URL_BASE; ?>catalogo" method="GET" class="catalogo-filters">
            <div class="search-bar">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" name="search" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($search ?? ''); ?>" class="search-input">
            </div>
            
            <div class="filter-selects">
                <select name="categoria" class="filter-dropdown" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    <?php if(!empty($categorias)): foreach($categorias as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo (isset($selected_categoria) && $selected_categoria == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nombre']); ?>
                        </option>
                    <?php endforeach; endif; ?>
                </select>

                <select name="marca" class="filter-dropdown" onchange="this.form.submit()">
                    <option value="">Todas las marcas</option>
                    <?php if(!empty($marcas)): foreach($marcas as $mar): ?>
                        <option value="<?php echo htmlspecialchars($mar['nombre']); ?>" <?php echo (isset($selected_marca) && ($selected_marca == $mar['id'] || $selected_marca == $mar['nombre'])) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($mar['nombre']); ?>
                        </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<section class="section catalogo-section">
    <div class="container">
        <?php if(!empty($productos)): ?>
            <div class="productos-grid">
                <?php foreach($productos as $producto): ?>
                <div class="product-card">
                    <a href="<?php echo URL_BASE; ?>producto/<?php echo $producto['id']; ?>" style="text-decoration: none; display: flex; flex-direction: column; flex: 1; color: inherit;">
                        <div class="product-image">
                            <?php 
                                $cover_image = 'public/img/Logo.webp';
                                if (!empty($producto['imagen_url'])) {
                                    $images = json_decode($producto['imagen_url'], true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($images) && count($images) > 0) {
                                        $cover_image = $images[0];
                                    } else {
                                        $cover_image = $producto['imagen_url'];
                                    }
                                }
                                $final_img_src = rtrim(URL_BASE, '/') . '/' . ltrim($cover_image, '/');
                            ?>
                            <img src="<?php echo htmlspecialchars($final_img_src); ?>"
                                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        </div>
                        <div class="product-info">
                            <?php if(!empty($producto['categoria_nombre'])): ?>
                                <span class="product-category"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></span>
                            <?php endif; ?>
                            
                            <h3 class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p class="product-price">$<?php echo number_format($producto['precio'], 2); ?></p>
                        </div>
                    </a>
                    <button 
                        class="btn-product-cart" 
                        title="Añadir al carrito"
                        onclick="event.preventDefault(); addToCart(<?php echo $producto['id']; ?>, '<?php echo htmlspecialchars(addslashes($producto['nombre'])); ?>', <?php echo $producto['precio']; ?>, '<?php echo htmlspecialchars(addslashes($final_img_src)); ?>')"
                    >   
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 20a1 1 0 100-2 1 1 0 000 2zM20 20a1 1 0 100-2 1 1 0 000 2z"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                        Añadir al Carrito
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="catalogo-empty">
                <div class="empty-icon">
                    <svg width="60" height="60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                </div>
                <h3 class="empty-title">No se encontraron productos</h3>
                <p class="empty-desc">Intenta ajustar tu búsqueda o filtros para encontrar lo que buscas.</p>
                <a href="<?php echo URL_BASE; ?>catalogo" class="btn-hero-primary mt-3">Limpiar Filtros</a>
            </div>
        <?php endif; ?>
    </div>
</section>
