<?php
// ─── NOTICIAS: index + create en una sola vista ───
?>
<div class="content-header">
    <h2>Noticias</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Gestiona las publicaciones y noticias que aparecen en el sitio.</p>
</div>

<!-- ═══ FORM CARD ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title d-flex justify-content-between align-items-center">
        <div>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 22h14a2 2 0 002-2V7.5L14.5 2H6a2 2 0 00-2 2v4"/><polyline points="14 2 14 8 20 8"/><path d="M2 15s.5-1 2-1 2 2 4 2 2-1 2-1"/></svg>
            Nueva Noticia / Publicación
        </div>
    </div>
    <form action="<?php echo URL_BASE; ?>admin/noticiasCreate" method="POST" enctype="multipart/form-data" class="mt-3">
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Título</label>
                <input type="text" name="titulo" class="form-control" placeholder="Título impactante para tu publicación" required>
            </div>
            <div class="form-group">
                <label>Tipo de Contenido</label>
                <select name="tipo_contenido" class="form-control form-select">
                    <option value="noticia_imagen">Noticia (Imagen)</option>
                    <option value="noticia_video">Noticia (Video)</option>
                    <option value="articulo">Artículo</option>
                    <option value="anuncio">Anuncio</option>
                </select>
            </div>
            <div class="form-group">
                <label>Archivo (Imagen o Video)</label>
                <label class="file-input-wrapper mb-0" style="height: 42px; display: flex; align-items: center; border: 1px solid #1e293b; background: #0f172a; border-radius: 6px; padding-left: 10px; width: 100%;">
                    <input type="file" name="file_upload" accept="image/*,video/*" style="opacity: 0; position: absolute; width: 1px;">
                    <span class="btn btn-outline btn-sm" onclick="this.previousElementSibling.click()" style="cursor:pointer; white-space: nowrap;">Cargar Archivo</span>
                    <span class="text-muted ml-2" style="font-size: 0.8rem; padding-left: 5px;">Ningún archivo seleccionado</span>
                </label>
            </div>
            <div class="form-group">
                <label>Fecha de Inicio (Opcional)</label>
                <input type="datetime-local" name="fecha_inicio" class="form-control">
            </div>
            <div class="form-group">
                <label>Fecha de Fin (Opcional)</label>
                <input type="datetime-local" name="fecha_fin" class="form-control">
            </div>
            
            <div class="form-group full-width">
                <label>Contenido</label>
                <textarea name="contenido" class="form-control" rows="4" placeholder="Escribe el contenido detallado aquí..." required></textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <button type="submit" class="btn btn-primary">Publicar Noticia</button>
        </div>
    </form>
</div>

<!-- ═══ TABLE ═══ -->
<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="15%" class="text-center">Media</th>
                    <th width="25%">Título</th>
                    <th width="45%">Contenido Breve</th>
                    <th width="10%" style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                    <tr>
                        <td><span class="text-muted">#<?php echo $row['id']; ?></span></td>
                        <td class="text-center">
                            <?php if (!empty($row['imagen_url'])): ?>
                                <?php if ($row['tipo_contenido'] === 'noticia_video' || strpos($row['imagen_url'], '.mp4') !== false): ?>
                                    <div style="width:50px;height:36px;background:#1a1b26;border-radius:5px;display:flex;align-items:center;justify-content:center;margin: 0 auto;border: 1px solid #2f334d;">
                                        <svg width="20" height="20" fill="white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                <?php else: ?>
                                    <img src="<?php echo URL_BASE . htmlspecialchars($row['imagen_url']); ?>" alt="img"
                                         style="width:50px;height:36px;object-fit:cover;border-radius:5px; border: 1px solid #2f334d;">
                                <?php endif; ?>
                            <?php else: ?>
                                <div style="width:50px;height:36px;background:rgba(255,255,255,0.05);border-radius:5px;margin: 0 auto;border: 1px solid #1e293b;"></div>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight: 500;"><?php echo htmlspecialchars($row['titulo'] ?? ''); ?></td>
                        <td class="text-muted" style="font-size: 0.85rem;">
                            <?php echo htmlspecialchars(mb_substr($row['contenido'] ?? '', 0, 80)); ?><?php echo strlen($row['contenido'] ?? '') > 80 ? '...' : ''; ?>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?php echo URL_BASE; ?>admin/noticiasDelete/<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirmCustom(event, '¿Eliminar esta noticia?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding: 3rem;">
                            <svg width="24" height="24" fill="none" stroke="#6b88b5" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:10px;"><path d="M4 22h14a2 2 0 002-2V7.5L14.5 2H6a2 2 0 00-2 2v4"/><polyline points="14 2 14 8 20 8"/></svg><br>
                            No hay noticias publicadas aún. Crea la primera arriba.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>