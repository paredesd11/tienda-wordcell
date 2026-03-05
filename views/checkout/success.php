<section class="section" style="padding-top: 4rem; text-align: center;">
    <div class="container">
        <div class="card" style="max-width: 500px; margin: 0 auto; background: var(--glass-bg); border: 1px solid var(--glass-border); padding: 2.5rem 2rem; border-radius: 16px; box-shadow: 0 16px 50px rgba(0,0,0,0.4);">
            
            <!-- Success Icon -->
            <div style="width: 72px; height: 72px; border-radius: 50%; background: rgba(34, 197, 94, 0.15); border: 2px solid #22c55e; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <svg width="34" height="34" fill="none" stroke="#22c55e" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>

            <h2 style="color: #4ade80; margin-bottom: 0.5rem; font-size: 1.6rem;">¡Pedido Registrado con Éxito!</h2>
            <p style="color: var(--text-light); font-size: 1.05rem;">Tu orden ha sido generada bajo el número: <strong style="color: #fff; font-size: 1.2rem;">#<?php echo htmlspecialchars($pedido_id); ?></strong></p>
            
            <div style="margin: 2rem 0; padding: 1.2rem; background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; text-align: left;">
                <p style="margin-bottom: 0.5rem; color: #cbd5e1; font-size: 0.95rem;">Método seleccionado: <strong style="color: #fff; float: right;"><?php echo htmlspecialchars($metodo); ?></strong></p>
                <p style="margin: 0; color: #cbd5e1; font-size: 0.95rem;">Estado del pedido: 
                    <strong style="float: right; padding: 0.1rem 0.6rem; border-radius: 12px; font-size: 0.85rem; 
                        background: <?php echo $estado == 'Confirmado' ? 'rgba(34, 197, 94, 0.2)' : 'rgba(245, 158, 11, 0.2)'; ?>; 
                        color: <?php echo $estado == 'Confirmado' ? '#4ade80' : '#fbbf24'; ?>;
                        border: 1px solid <?php echo $estado == 'Confirmado' ? 'rgba(34, 197, 94, 0.4)' : 'rgba(245, 158, 11, 0.4)'; ?>;">
                        <?php echo htmlspecialchars($estado); ?>
                    </strong>
                </p>
            </div>

            <?php if ($estado == 'Pendiente'): ?>
                <div style="background: rgba(59, 130, 246, 0.1); border-left: 3px solid #3b82f6; padding: 1rem; border-radius: 0 4px 4px 0; margin-bottom: 2rem; text-align: left;">
                    <p style="font-size: 0.9rem; color: #93c5fd; line-height: 1.5; margin: 0;">
                        Hemos recibido tu comprobante de pago exitosamente. Un administrador validará la transferencia en breve y te notificaremos el resultado vía WhatsApp. El stock se reservará definitivamente una vez confirmada la validación.
                    </p>
                </div>
            <?php else: ?>
                <div style="background: rgba(34, 197, 94, 0.1); border-left: 3px solid #22c55e; padding: 1rem; border-radius: 0 4px 4px 0; margin-bottom: 2rem; text-align: left;">
                    <p style="font-size: 0.9rem; color: #86efac; line-height: 1.5; margin: 0;">
                        Pago confirmado automáticamente. El stock ha sido descontado.
                    </p>
                </div>
            <?php endif; ?>

            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo URL_BASE; ?>user/panel" class="btn btn-primary">Ir a Mi Panel</a>
                <a href="<?php echo URL_BASE; ?>home" class="btn btn-outline" style="border: 1px solid var(--glass-border);">Volver a la Tienda</a>
            </div>
        </div>
    </div>
</section>
