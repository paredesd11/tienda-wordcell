<div class="container" style="margin-top: 4rem; margin-bottom: 4rem;">
    <div class="product-detail-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start;">
        
        <!-- Left Column: Image Gallery -->
        <div class="product-gallery">
            <div class="main-image-container" style="background: var(--glass-bg); padding: 2rem; border-radius: 16px; border: 1px solid var(--glass-border); text-align: center; margin-bottom: 1rem;">
                <?php 
                    $main_img_path = rtrim(URL_BASE, '/') . '/' . ltrim($imagenes[0], '/');
                ?>
                <img id="main-product-image" src="<?php echo htmlspecialchars($main_img_path); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" style="width: 100%; max-height: 500px; object-fit: contain; border-radius: 8px;">
            </div>
            
            <?php if (count($imagenes) > 1): ?>
            <div class="thumbnail-gallery" style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem;">
                <?php foreach($imagenes as $index => $img): ?>
                    <?php 
                        $thumb_path = rtrim(URL_BASE, '/') . '/' . ltrim($img, '/');
                    ?>
                    <img src="<?php echo htmlspecialchars($thumb_path); ?>" 
                         class="gallery-thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" 
                         onclick="changeMainImage(this.src)"
                         style="width: 80px; height: 80px; object-fit: contain; background: var(--glass-bg); border: 2px solid <?php echo $index === 0 ? '#3b82f6' : 'transparent'; ?>; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; padding: 0.5rem;">
                <?php endforeach; ?>
            </div>
            <style>
                .gallery-thumbnail:hover { border-color: rgba(59, 130, 246, 0.5) !important; transform: translateY(-2px); }
            </style>
            <script>
                function changeMainImage(src) {
                    document.getElementById('main-product-image').src = src;
                    document.querySelectorAll('.gallery-thumbnail').forEach(t => t.style.borderColor = 'transparent');
                    event.target.style.borderColor = '#3b82f6';
                }
            </script>
            <?php endif; ?>
        </div>

        <!-- Right Column: Product Info -->
        <div class="product-details-info">
            <?php if(!empty($producto['categoria_nombre'])): ?>
                <span style="color: #64748b; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></span>
            <?php endif; ?>
            
            <h1 style="font-size: 3rem; margin: 0.5rem 0 1rem; color: #fff; line-height: 1.2;"><?php echo htmlspecialchars($producto['nombre']); ?></h1>
            
            <?php if(!empty($producto['marca_nombre'])): ?>
                <p style="color: #94a3b8; font-size: 1.1rem; margin-bottom: 2rem;">Marca: <strong style="color: #fff;"><?php echo htmlspecialchars($producto['marca_nombre']); ?></strong></p>
            <?php endif; ?>

            <div style="font-size: 2.5rem; font-weight: 800; color: #3b82f6; margin-bottom: 2rem;">
                $<?php echo number_format($producto['precio'], 2); ?>
            </div>

            <p style="color: #cbd5e1; font-size: 1.1rem; line-height: 1.7; margin-bottom: 2.5rem;">
                <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?>
            </p>

            <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--glass-border); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <span style="color: #94a3b8; font-size: 1.1rem;">Disponibilidad:</span>
                    <?php if ($producto['stock'] > 0): ?>
                        <span style="color: #10b981; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981; box-shadow: 0 0 10px #10b981;"></span>
                            En Stock (<?php echo $producto['stock']; ?> disponibles)
                        </span>
                    <?php else: ?>
                        <span style="color: #ef4444; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #ef4444; box-shadow: 0 0 10px #ef4444;"></span>
                            Agotado
                        </span>
                    <?php endif; ?>
                </div>
            </div>

             <div style="display: flex; gap: 1rem;">
                <?php if (isset($_GET['from']) && $_GET['from'] === 'cart'): ?>
                    <a 
                        href="<?php echo URL_BASE; ?>carrito" 
                        class="btn-hero-outline" 
                        style="flex: 1; padding: 1rem 2rem; font-size: 1.1rem; border-radius: 12px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; text-decoration: none; border: 1px solid rgba(255,255,255,0.2); color: white;"
                    >
                        <i class="fas fa-arrow-left"></i> Volver al Carrito
                    </a>
                <?php else: ?>
                    <button 
                        class="btn-hero-primary" 
                        style="flex: 1; padding: 1rem 2rem; font-size: 1.1rem; border-radius: 12px; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" 
                        <?php echo $producto['stock'] <= 0 ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''; ?>
                        <?php if ($producto['stock'] > 0): ?>
                            onclick="event.preventDefault(); addToCart(<?php echo $producto['id']; ?>, '<?php echo htmlspecialchars(addslashes($producto['nombre'])); ?>', <?php echo $producto['precio']; ?>, '<?php echo htmlspecialchars(addslashes(rtrim(URL_BASE, '/') . '/' . ltrim($imagenes[0] ?? $producto['imagen_url'], '/'))); ?>')"
                        <?php endif; ?>
                    >
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 20a1 1 0 100-2 1 1 0 000 2zM20 20a1 1 0 100-2 1 1 0 000 2z"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                        Añadir al Carrito
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 900px) {
    .product-detail-grid {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
}
</style>
