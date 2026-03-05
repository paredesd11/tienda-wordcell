<div class="content-header">
    <h2>Configuración General / Ajustes</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Ajustes de la página, logos y variables de entorno.</p>
</div>

<!-- ═══ FORM CARD ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title d-flex justify-content-between align-items-center">
        <div>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
            Nuevo Ajuste
        </div>
    </div>
    <form action="<?php echo URL_BASE; ?>admin/configuracionCreate" method="POST" enctype="multipart/form-data" class="mt-3">
        <div class="form-grid">
            <div class="form-group">
                <label>Clave o Llave (Identificador)</label>
                <input type="text" name="clave" class="form-control" placeholder="Ej: telefono_contacto o logo_principal" required>
            </div>
            <div class="form-group">
                <label>Valor (Texto, Número o URL)</label>
                <input type="text" name="valor" class="form-control" placeholder="Ej: 0991234567 o https://... (Si es texto)">
            </div>
            <div class="form-group full-width">
                <label>Subir Archivo (En caso de ser un logo o imagen)</label>
                <label class="file-input-wrapper mb-0" style="height: 42px; display: flex; align-items: center; border: 1px solid #1e293b; background: #0f172a; border-radius: 6px; padding-left: 10px; width: 100%;">
                    <input type="file" name="file_upload" accept="image/*,video/*" style="opacity: 0; position: absolute; width: 1px;">
                    <span class="btn btn-outline btn-sm" onclick="this.previousElementSibling.click()" style="cursor:pointer; white-space: nowrap;">Examinar...</span>
                    <span class="text-muted ml-2" style="font-size: 0.8rem; padding-left: 5px;">Se ignorará el campo 'Valor' anterior si subes un archivo.</span>
                </label>
            </div>
        </div>
        <div class="d-flex gap-2 flex-end mt-4 border-top pt-3">
            <button type="submit" class="btn btn-primary">Registrar Configuración</button>
        </div>
    </form>
</div>

<!-- ═══ TABLE ═══ -->
<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="10%">ID</th>
                    <th width="35%">Clave (Key)</th>
                    <th width="40%">Valor o Archivo</th>
                    <th width="15%" style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                    <tr>
                        <td><span class="text-muted">#<?php echo $row['id']; ?></span></td>
                        <td>
                            <span class="badge badge-purple" style="letter-spacing: 0.5px;text-transform:none;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:4px;"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0020 16z"/></svg>    
                                <?php echo htmlspecialchars($row['clave'] ?? ''); ?>
                            </span>
                        </td>
                        <td style="word-break: break-all; color: #cbd5e1;">
                            <?php if(preg_match('/\.(jpg|jpeg|png|webp|svg|gif|mp4|webm)$/i', $row['valor'])): ?>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <?php if(preg_match('/\.(mp4|webm)$/i', $row['valor'])): ?>
                                        <span class="badge badge-orange">Video</span>
                                    <?php else: ?>
                                        <img src="<?php echo URL_BASE . ltrim($row['valor'], '/'); ?>" alt="img" style="height:30px; border-radius:4px; border:1px solid rgba(255,255,255,0.1);">
                                    <?php endif; ?>
                                    <span class="text-muted" style="font-size:0.8rem;"><?php echo htmlspecialchars($row['valor']); ?></span>
                                </div>
                            <?php else: ?>
                                <?php echo htmlspecialchars($row['valor'] ?? '—'); ?>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?php echo URL_BASE; ?>admin/configuracionDelete/<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmCustom(event, '¿Eliminar este registro de configuración?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted" style="padding: 3rem;">
                            <svg width="24" height="24" fill="none" stroke="#6b88b5" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:10px;"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg><br>
                            No hay configuraciones base registradas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>