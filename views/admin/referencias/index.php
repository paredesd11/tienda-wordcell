<div class="content-header">
    <h2>Referencias (Testimonios)</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Gestiona los testimonios de clientes que aparecen en la página de inicio.</p>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] == 'in_use'): ?>
    <div class="alert alert-danger" style="background: rgba(220, 38, 38, 0.2); border: 1px solid rgba(220, 38, 38, 0.5); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #fca5a5;">
        <strong>Error:</strong> No se puede eliminar esta referencia porque está en uso o asociada a otros registros.
    </div>
<?php endif; ?>

<!-- ═══ FORM CARD ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title d-flex justify-content-between align-items-center">
        <div>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Nueva Referencia / Testimonio
        </div>
    </div>
    <form action="<?php echo URL_BASE; ?>admin/referenciasCreate" method="POST" enctype="multipart/form-data" class="mt-3">
        <div class="form-grid">
            <div class="form-group">
                <label>Nombre del Cliente</label>
                <input type="text" name="nombre_autor" class="form-control" placeholder="Ej: Juan Pérez" required>
            </div>
            <div class="form-group">
                <label>Calificación (Estrellas)</label>
                <select name="estrellas" class="form-control form-select">
                    <option value="5">⭐⭐⭐⭐⭐ (5 Estrellas)</option>
                    <option value="4">⭐⭐⭐⭐ (4 Estrellas)</option>
                    <option value="3">⭐⭐⭐ (3 Estrellas)</option>
                    <option value="2">⭐⭐ (2 Estrellas)</option>
                    <option value="1">⭐ (1 Estrella)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tipo de Media</label>
                <select name="tipo_media" class="form-control form-select" required>
                    <option value="texto">Solo Texto</option>
                    <option value="imagen">Imagen</option>
                    <option value="video">Video</option>
                    <option value="mixto">Mixto (Texto + Media)</option>
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
            <div class="form-group full-width">
                <label>URL de Referencia / Enlace Externo (Opcional)</label>
                <input type="url" name="url_referencia" class="form-control" placeholder="https://ejemplo.com/testimonio">
            </div>
            <div class="form-group full-width">
                <label>Comentario (Se verá en la web)</label>
                <textarea name="comentario" class="form-control" rows="3" placeholder="Escribe el testimonio o comentario detallado del cliente..."></textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <button type="submit" class="btn btn-primary">Registrar Testimonio</button>
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
                    <th width="35%">Cliente / Comentario</th>
                    <th width="20%">Calificación</th>
                    <th width="10%" class="text-center">Tipo</th>
                    <th width="15%" class="text-center">Media</th>
                    <th width="15%" style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                    <tr>
                        <td><span class="text-muted">#<?php echo $row['id']; ?></span></td>
                        <td>
                            <strong style="color: #fff;"><?php echo htmlspecialchars($row['nombre_autor'] ?? ''); ?></strong><br>
                            <small class="text-muted" style="font-size: 0.8rem; display: block; margin-top: 4px;"><?php echo htmlspecialchars(mb_substr($row['comentario'] ?? '', 0, 50)); ?><?php echo strlen($row['comentario'] ?? '') > 50 ? '...' : ''; ?></small>
                        </td>
                        <td style="color: #fbbf24; font-size: 0.9rem; letter-spacing: 2px;">
                            <?php echo str_repeat('★', $row['estrellas']); ?><span style="color: #475569;"><?php echo str_repeat('★', 5 - $row['estrellas']); ?></span>
                        </td>
                        <td class="text-center">
                            <?php if($row['tipo_media'] == 'texto'): ?>
                                <span class="badge badge-outline">Texto</span>
                            <?php elseif($row['tipo_media'] == 'imagen'): ?>
                                <span class="badge badge-blue">Imagen</span>
                            <?php elseif($row['tipo_media'] == 'video'): ?>
                                <span class="badge badge-orange">Video</span>
                            <?php else: ?>
                                <span class="badge badge-green">Mixto</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if (!empty($row['media_url'])): ?>
                                <?php if ($row['tipo_media'] == 'video'): ?>
                                    <div style="width:50px;height:36px;background:#1a1b26;border-radius:5px;display:inline-flex;align-items:center;justify-content:center;border: 1px solid #2f334d;">
                                        <svg width="20" height="20" fill="white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                <?php else: ?>
                                    <img src="<?php echo URL_BASE . htmlspecialchars($row['media_url']); ?>" alt="media" style="height:36px;width:50px;object-fit:cover;border-radius:5px;border: 1px solid #2f334d;">
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:0.8rem;">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?php echo URL_BASE; ?>admin/referenciasDelete/<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirmCustom(event, '¿Eliminar esta referencia?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 3rem;">
                            <svg width="24" height="24" fill="none" stroke="#6b88b5" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:10px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><br>
                            No hay referencias registradas. Agrega la primera arriba.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>