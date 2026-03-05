<div class="content-header">
    <h2>Gestión de Categorías</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Administra las categorías a las cuales pertenecen los productos.</p>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] == 'in_use'): ?>
    <div class="alert alert-danger" style="background: rgba(220, 38, 38, 0.2); border: 1px solid rgba(220, 38, 38, 0.5); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #fca5a5;">
        <strong>Error:</strong> No se puede eliminar esta categoría porque hay productos asociados a ella. Por favor, reasigna o elimina esos productos primero.
    </div>
<?php endif; ?>

<!-- Create Form -->
<div class="admin-card mb-3">
    <div class="admin-card-title d-flex justify-content-between align-items-center">
        <div>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Nueva Categoría
        </div>
    </div>
    <form action="<?php echo URL_BASE; ?>admin/categoriasCreate" method="POST" class="mt-3">
        <div class="form-grid" style="grid-template-columns: 1fr auto;">
            <div class="form-group full-width" style="margin-bottom: 0;">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre de la categoría" required>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 100%;"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg> Crear</button>
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
                    <th width="35%">Nombre</th>
                    <th width="45%">Descripción</th>
                    <th width="15%" style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                    <tr>
                        <td><span class="text-muted">#<?php echo $row['id']; ?></span></td>
                        <td style="font-weight: 500;"><?php echo htmlspecialchars($row['nombre'] ?? ''); ?></td>
                        <td class="text-muted" style="font-size: 0.85rem;"><?php echo htmlspecialchars($row['descripcion'] ?? '—'); ?></td>
                        <td style="text-align: right;">
                            <a href="<?php echo URL_BASE; ?>admin/categoriasDelete/<?php echo $row['id']; ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirmCustom(event, '¿Eliminar esta categoría?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted" style="padding: 2rem;">No hay categorías registradas</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>