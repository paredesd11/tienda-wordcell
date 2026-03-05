<div class="content-header d-flex justify-content-between align-items-center">
    <div>
        <h2>Gestión de Productos</h2>
        <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Administra el catálogo de productos de tu tienda.</p>
    </div>
    <a href="<?php echo URL_BASE; ?>admin/productosCreate" class="btn btn-primary" style="height: 42px; display: flex; align-items: center; gap: 8px;">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Nuevo Producto
    </a>
</div>

<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="8%" class="text-center">Img</th>
                    <th width="32%">Nombre</th>
                    <th width="15%">Precio</th>
                    <th width="10%">Stock</th>
                    <th width="20%">Categoría</th>
                    <th width="15%" style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productos)): ?>
                    <?php foreach($productos as $p): ?>
                    <tr>
                        <td class="text-center">
                            <?php 
                                $cover_image = '';
                                if (!empty($p['imagen_url'])) {
                                    $images = json_decode($p['imagen_url'], true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($images) && count($images) > 0) {
                                        $cover_image = $images[0];
                                    } else {
                                        $cover_image = $p['imagen_url'];
                                    }
                                }
                                $final_img_src = '';
                                if (!empty($cover_image)) {
                                    $final_img_src = rtrim(URL_BASE, '/') . '/' . ltrim($cover_image, '/');
                                }
                            ?>
                            <?php if (!empty($final_img_src)): ?>
                                <img src="<?php echo htmlspecialchars($final_img_src); ?>" alt="img"
                                     style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:1px solid #1e293b;">
                            <?php else: ?>
                                <div style="width:40px;height:40px;background:rgba(255,255,255,0.05);border-radius:6px;display:flex;align-items:center;justify-content:center;margin:0 auto;border:1px solid #1e293b;">
                                    <svg width="20" height="20" fill="none" stroke="#64748b" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight: 500;"><?php echo htmlspecialchars($p['nombre']); ?></td>
                        <td style="color: #10b981; font-weight: 600;">$<?php echo number_format($p['precio'], 2); ?></td>
                        <td>
                            <?php if($p['stock'] > 10): ?>
                                <span class="badge badge-blue"><?php echo $p['stock']; ?></span>
                            <?php elseif($p['stock'] > 0): ?>
                                <span class="badge badge-orange"><?php echo $p['stock']; ?></span>
                            <?php else: ?>
                                <span class="badge badge-red">Agotado</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted"><?php echo htmlspecialchars($p['categoria'] ?? '—'); ?></td>
                        <td style="text-align: right;">
                            <a href="<?php echo URL_BASE; ?>admin/productosDelete/<?php echo $p['id']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirmCustom(event, '¿Eliminar este producto?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 3rem;">
                            <svg width="32" height="32" fill="none" stroke="#6b88b5" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:12px;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21,15 16,10 5,21"/></svg><br>
                            No hay productos en el catálogo.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
