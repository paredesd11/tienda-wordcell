<section class="section" style="padding-top: 3rem;">
    <div class="container">
        
        <div class="card" style="max-width: 600px; margin: 0 auto; background: var(--glass-bg); border: 1px solid var(--glass-border); padding: 2rem; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.4);">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 class="section-title" style="margin-bottom: 0.5rem;">Finalizar Compra</h2>
                <p style="color: var(--text-muted);">Verifica tu producto y elige cómo pagar</p>
            </div>

            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.2rem;">Producto</p>
                    <p style="font-weight: 600; font-size: 1.1rem; margin: 0;"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                </div>
                <div style="text-align: right;">
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.2rem;">Total a Pagar</p>
                    <p style="font-size: 1.6rem; color: #60a5fa; font-weight: 700; margin: 0;">$<?php echo number_format($producto['precio'], 2); ?></p>
                </div>
            </div>
            
            <form action="<?php echo URL_BASE; ?>checkout/confirmar" method="POST">
                <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                <input type="hidden" name="total" value="<?php echo $producto['precio']; ?>">

                <h3 style="margin-bottom: 1rem; font-size: 1.05rem; font-weight: 600; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Método de Pago (Ecuador)</h3>
                
                <?php if(!empty($metodos)): foreach($metodos as $m): ?>
                    <label style="display: block; margin-bottom: 0.8rem; padding: 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="payment-method-label">
                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <input type="radio" name="metodo_pago_id" value="<?php echo $m['id']; ?>" required style="margin-top: 0.25rem;">
                            <div>
                                <strong style="display: block; margin-bottom: 0.2rem; color: #e2eaf8;"><?php echo htmlspecialchars($m['tipo']); ?></strong>
                                <?php if($m['tipo'] == 'Transferencia Banco'): ?>
                                    <div style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">
                                        Banco: <span style="color:#fff;"><?php echo htmlspecialchars($m['banco']); ?></span><br>
                                        Cuenta: <span style="color:#fff;"><?php echo htmlspecialchars($m['numero_cuenta']); ?></span><br>
                                        Titular: <?php echo htmlspecialchars($m['titular']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </label>
                <?php endforeach; else: ?>
                    <p style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border-left: 3px solid #ef4444; border-radius: 4px; color: #fca5a5; font-size: 0.9rem;">No hay métodos de pago configurados en este momento.</p>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; font-size: 1.05rem; padding: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" <?php echo empty($metodos) ? 'disabled' : ''; ?>>
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="7" y1="15" x2="7.01" y2="15"/><line x1="11" y1="15" x2="13" y2="15"/></svg>
                    Confirmar y Pagar Pedido
                </button>
            </form>
        </div>
    </div>
</section>

<style>
.payment-method-label:hover {
    background: rgba(255,255,255,0.05) !important;
    border-color: rgba(96,165,250,0.5) !important;
}
input[type="radio"]:checked + div strong {
    color: #60a5fa !important;
}
</style>
