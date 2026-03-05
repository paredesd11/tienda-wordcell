<div class="content-header">
    <h2>Registrar Pedido Manual</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Crea un registro de pedido manualmente en el sistema.</p>
</div>

<div class="admin-card">
    <div class="admin-card-title">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg> 
        Información del Pedido
    </div>
    
    <form action="<?php echo URL_BASE; ?>admin/pedidosCreate" method="POST" class="mt-3">
        <div class="form-grid">
            <div class="form-group">
                <label>ID del Usuario (Cliente)</label>
                <input type="number" name="usuario_id" class="form-control" placeholder="Ej: 15" required>
            </div>
            <div class="form-group">
                <label>ID del Método de Pago</label>
                <input type="number" name="metodo_pago_id" class="form-control" placeholder="Ej: 2" required>
            </div>
            <div class="form-group">
                <label>Total del Pedido ($)</label>
                <input type="number" step="0.01" name="total" class="form-control" placeholder="Ej: 150.50" required>
            </div>
            <div class="form-group">
                <label>Estado del Pedido</label>
                <select name="estado" class="form-control form-select" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Pagado">Pagado</option>
                    <option value="Enviado">Enviado</option>
                    <option value="Entregado">Entregado</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
            </div>
        </div>
        
        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <a href="<?php echo URL_BASE; ?>admin/pedidos" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Registrar Pedido</button>
        </div>
    </form>
</div>