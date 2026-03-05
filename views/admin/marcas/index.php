<div class="content-header">
    <h2>Gestión de Marcas</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Administra las marcas de los fabricantes de hardware y repuestos.</p>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] == 'in_use'): ?>
    <div class="alert alert-danger" style="background: rgba(220, 38, 38, 0.2); border: 1px solid rgba(220, 38, 38, 0.5); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #fca5a5;">
        <strong>Error:</strong> No se puede eliminar esta marca porque hay productos asociados a ella. Por favor, reasigna o elimina esos productos primero.
    </div>
<?php endif; ?>

<!-- Create Form -->
<div class="admin-card mb-3">
    <div class="admin-card-title d-flex justify-content-between align-items-center">
        <div>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg> 
            Nueva Marca
        </div>
    </div>
    <form action="<?php echo URL_BASE; ?>admin/marcasCreate" method="POST" enctype="multipart/form-data" class="mt-3">
        <div class="form-grid" style="grid-template-columns: 1fr auto auto; align-items: start;">
            <div class="form-group full-width mb-0">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre de la marca" required>
            </div>
            
            <div class="form-group mb-0">
                <label class="file-input-wrapper mb-0" style="height: 100%; display: flex; align-items: center; border: 1px solid #1e293b; background: #0f172a; border-radius: 6px; padding-left: 10px;">
                    <span class="text-muted d-block mr-2" style="font-size: 0.85rem;">Logo:</span>
                    <input type="file" name="logo" accept="image/*" style="opacity: 0; position: absolute; width: 1px;">
                    <span class="btn btn-outline btn-sm" onclick="this.previousElementSibling.click()" style="cursor:pointer; white-space: nowrap;">Elegir archivo</span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary" style="height: 42px;"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg> Crear</button>
        </div>
    </form>
</div>

<!-- Table -->
<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="15%" class="text-center">Logo</th>
                    <th width="60%">Nombre</th>
                    <th width="20%" style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                    <tr>
                        <td><span class="text-muted">#<?php echo $row['id']; ?></span></td>
                        <td class="text-center">
                            <?php if (!empty($row['logo_url'])): ?>
                                <img src="<?php echo URL_BASE . htmlspecialchars($row['logo_url']); ?>" alt="logo" style="height:35px;width:35px;object-fit:contain;background:#fff;padding:4px;border-radius:6px;">
                            <?php else: ?>
                                <span class="text-muted" style="font-size:0.8rem;">Sin logo</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight: 500;"><?php echo htmlspecialchars($row['nombre'] ?? ''); ?></td>
                        <td style="text-align: right;">
                            <a href="<?php echo URL_BASE; ?>admin/marcasDelete/<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirmCustom(event, '¿Eliminar esta marca?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted" style="padding: 2rem;">No hay marcas registradas</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>