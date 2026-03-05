<div class="content-header d-flex justify-between align-center mb-3">
    <h2>Ubicaciones del Local</h2>
</div>

<div class="mb-4">
    <a href="<?php echo URL_BASE; ?>admin/ubicacionesCreate" class="btn btn-primary" style="background: #0ea5e9; padding: 0.6rem 1.2rem; border-radius: 6px; font-weight: 500;">
        + Nueva Ubicación
    </a>
</div>

<div class="ubicaciones-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
    <?php if (!empty($records)): ?>
        <?php foreach($records as $row): ?>
        <div class="admin-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; background: rgba(10, 26, 53, 0.4); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px;">
            
            <!-- Map Preview (Top half) -->
            <div style="height: 160px; background: #e2e8f0; position: relative; overflow: hidden;">
                <?php if (!empty($row['iframe_mapa'])): ?>
                    <div style="position: absolute; inset: -10px; opacity: 0.95;">
                        <?php echo $row['iframe_mapa']; ?>
                    </div>
                <?php else: ?>
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #1e293b; color: #64748b;">
                        Sin mapa
                    </div>
                <?php endif; ?>
            </div>

            <!-- Content (Bottom half) -->
            <div style="padding: 1.2rem; flex: 1; display: flex; flex-direction: column;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 0.3rem;">
                    <?php echo htmlspecialchars($row['nombre']); ?>
                </h3>
                
                <p style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 0.3rem; line-height: 1.4;">
                    <?php echo htmlspecialchars($row['direccion']); ?>
                </p>
                
                <?php if (!empty($row['horario'])): ?>
                <p style="font-size: 0.78rem; color: #64748b; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($row['horario']); ?>
                </p>
                <?php else: ?>
                <div style="margin-bottom: 1rem;"></div>
                <?php endif; ?>

                <!-- Actions -->
                <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="<?php echo URL_BASE; ?>admin/ubicacionesEdit/<?php echo $row['id']; ?>" class="btn-text" style="font-size: 0.85rem; color: #38bdf8; text-decoration: none;">Editar</a>
                    <a href="<?php echo URL_BASE; ?>admin/ubicacionesDelete/<?php echo $row['id']; ?>" class="btn-text" style="font-size: 0.85rem; color: #f87171; text-decoration: none;" onclick="return confirmCustom(event, '¿Seguro que deseas eliminar esta ubicación?');">Eliminar</a>
                </div>
            </div>
            
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info" style="grid-column: 1 / -1; background: rgba(255,255,255,0.05); border: none; color: #94a3b8;">
            No hay ubicaciones registradas. Haz clic en "Nueva Ubicación" para comenzar.
        </div>
    <?php endif; ?>
</div>

<style>
.ubicaciones-grid iframe {
    width: 100%;
    height: 100%;
    border: none;
}
</style>