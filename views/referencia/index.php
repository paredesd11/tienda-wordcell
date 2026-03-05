<div class="container section">
    <div class="admin-card" style="max-width: 800px; margin: 4rem auto; padding: 3rem;">
        <div class="text-center mb-4">
            <h2 class="section-title" style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($ref['nombre_autor']); ?></h2>
            <div style="color: #fbbf24; font-size: 1.5rem; margin-bottom: 1.5rem;">
                <?php echo str_repeat('★', $ref['estrellas']); ?><?php echo str_repeat('☆', 5 - $ref['estrellas']); ?>
            </div>
        </div>

        <div class="ref-detail-comment mb-4" style="position: relative; padding: 0 2rem; width: 100%; box-sizing: border-box;">
            <span style="font-family: serif; font-size: 4rem; color: rgba(59, 130, 246, 0.2); position: absolute; top: -20px; left: -10px; pointer-events: none;">“</span>
            <p style="font-style: italic; font-size: 1.4rem; color: #cbd5e1; line-height: 1.8; text-align: center; margin: 2rem 0; word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; max-width: 100%;">
                <?php echo htmlspecialchars($ref['comentario']); ?>
            </p>
            <span style="font-family: serif; font-size: 4rem; color: rgba(59, 130, 246, 0.2); position: absolute; bottom: -40px; right: -10px; pointer-events: none;">”</span>
        </div>

        <?php if (!empty($ref['media_url'])): ?>
            <div class="ref-detail-media mt-4" style="border-radius: 24px; overflow: hidden; background: #000; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
                <?php if ($ref['tipo_media'] == 'video'): ?>
                    <video controls autoplay class="w-100" style="display: block; max-height: 500px; width: 100%; object-fit: contain;">
                        <source src="<?php echo URL_BASE . htmlspecialchars($ref['media_url']); ?>">
                        Tu navegador no soporta el elemento de video.
                    </video>
                <?php else: ?>
                    <img src="<?php echo URL_BASE . htmlspecialchars($ref['media_url']); ?>" alt="referencia full" style="width: 100%; height: auto; max-height: 600px; object-fit: contain; display: block;">
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mt-5">
            <?php if (!empty($ref['url_referencia'])): ?>
                <a href="<?php echo htmlspecialchars($ref['url_referencia']); ?>" target="_blank" class="btn btn-primary" style="padding: 1rem 2rem;">Visitar Referencia Original</a>
            <?php endif; ?>
            <a href="<?php echo URL_BASE; ?>" class="btn btn-outline" style="margin-left: 1rem; padding: 1rem 2rem;">Volver al Inicio</a>
        </div>
    </div>
</div>
