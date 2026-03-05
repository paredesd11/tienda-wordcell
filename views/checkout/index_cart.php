
<section class="section" style="padding-top: 3rem; min-height: calc(100vh - 60px);">
    <div class="container">
        
        <div class="card" style="max-width: 600px; margin: 0 auto; background: var(--glass-bg); border: 1px solid var(--glass-border); padding: 2rem; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.4);">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 class="section-title" style="margin-bottom: 0.5rem; color: #fff;">Finalizar Compra</h2>
                <p style="color: var(--text-muted);">Verifica tu carrito y elige cómo pagar</p>
            </div>

            <!-- Order Summary -->
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem; color: #fff; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;">Resumen de tu Pedido</h3>
                
                    <?php 
                    $subtotal = 0;
                    foreach($carrito as $item): 
                        $itemTotal = $item['precio'] * $item['cantidad'];
                        $subtotal += $itemTotal;
                        
                        $cover_image = $item['imagen'];
                        $images = json_decode($item['imagen'], true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($images) && count($images) > 0) {
                            $cover_image = $images[0];
                        }
                        
                        if (strpos($cover_image, URL_BASE) === 0) {
                            $imgSrc = $cover_image;
                        } else {
                            $imgSrc = URL_BASE . ltrim($cover_image, '/');
                        }
                    ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" style="width: 40px; height: 40px; object-fit: contain; background: rgba(0,0,0,0.3); border-radius: 6px;">
                            <div>
                                <h4 style="color: #e2eaf8; font-size: 0.95rem; margin: 0; font-weight: 500;"><?php echo htmlspecialchars($item['nombre']); ?></h4>
                                <span style="color: var(--text-muted); font-size: 0.8rem;">Cant: <?php echo $item['cantidad']; ?></span>
                            </div>
                        </div>
                        <div style="font-weight: 600; color: #fff;">
                            $<?php echo number_format($itemTotal, 2); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1); margin-bottom: 0.5rem;">
                    <span style="color: var(--text-muted); font-size: 0.95rem;">Subtotal</span>
                    <span style="color: #fff; font-weight: 600;">$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span style="color: var(--text-muted); font-size: 0.95rem;">Costo de Envío <span id="shipping-label-indicator" style="font-size: 0.8rem; background: rgba(16, 185, 129, 0.2); color: #34d399; padding: 2px 6px; border-radius: 4px; display: none; margin-left: 5px; border: 1px solid rgba(16,185,129,0.3);">Oferta Aplicada</span></span>
                    <span id="shipping-cost-display" style="color: #f59e0b; font-weight: 600;">+$0.00</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                    <span style="color: var(--text-muted); font-size: 1.1rem; font-weight: 600;">Total a Pagar</span>
                    <span id="grand-total-display" style="font-size: 1.6rem; color: #60a5fa; font-weight: 700;">$<?php echo number_format($subtotal, 2); ?></span>
                </div>
            </div>
            
            <form action="<?php echo URL_BASE; ?>checkout/confirmarCart" method="POST" enctype="multipart/form-data">

                <!-- Métodos de Envío -->
                <h3 style="margin-bottom: 1rem; font-size: 1.05rem; font-weight: 600; color: #fff; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;"><i class="fas fa-truck me-2 text-info"></i> Método de Envío</h3>
                <div style="margin-bottom: 2rem;">
                    <?php if(!empty($metodos_envio)): foreach($metodos_envio as $index => $env): ?>
                        <label style="display: block; margin-bottom: 0.8rem; padding: 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="method-label">
                            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                                <input type="radio" name="metodo_envio_id" value="<?php echo $env['id']; ?>" data-cost="<?php echo $env['costo_base']; ?>" required <?php echo $index === 0 ? 'checked' : ''; ?> onchange="calculateTotal()" style="margin-top: 0.25rem;">
                                <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                                    <div>
                                        <strong style="display: block; color: #e2eaf8;"><?php echo htmlspecialchars($env['nombre']); ?></strong>
                                        <span style="font-size: 0.85rem; color: var(--text-muted);"><i class="far fa-clock"></i> <?php echo htmlspecialchars($env['tiempo_estimado']); ?></span>
                                    </div>
                                    <div style="font-weight: 600; color: #10b981;">
                                        <?php echo $env['costo_base'] == 0 ? 'Gratis' : '$' . number_format($env['costo_base'], 2); ?>
                                    </div>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; else: ?>
                        <p style="padding: 1rem; background: rgba(245, 158, 11, 0.1); border-left: 3px solid #f59e0b; color: #fcd34d; font-size: 0.9rem;">No hay métodos de envío configurados. Retiro en tienda por defecto.</p>
                    <?php endif; ?>
                </div>

                <h3 style="margin-bottom: 1rem; font-size: 1.05rem; font-weight: 600; color: #fff; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem;"><i class="fas fa-credit-card me-2 text-info"></i> Método de Pago (Ecuador)</h3>
                
                <?php if(!empty($metodos)): foreach($metodos as $m): ?>
                    <label style="display: block; margin-bottom: 0.8rem; padding: 1rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="payment-method-label">
                        <div style="display: flex; align-items: flex-start; gap: 1rem;">
                            <input type="radio" name="metodo_pago_id" value="<?php echo $m['id']; ?>" required style="margin-top: 0.25rem;" onchange="toggleReceiptUpload(this, '<?php echo htmlspecialchars($m['tipo']); ?>')">
                            <div style="width: 100%;">
                                <strong style="display: block; margin-bottom: 0.2rem; color: #e2eaf8;"><?php echo htmlspecialchars($m['tipo']); ?></strong>
                                <?php if($m['tipo'] == 'Transferencia'): ?>
                                    <div style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 0.8rem;">
                                        Banco: <span style="color:#fff;"><?php echo htmlspecialchars($m['banco']); ?></span><br>
                                        Cuenta: <span style="color:#fff;"><?php echo htmlspecialchars($m['numero_cuenta']); ?></span><br>
                                        Titular: <?php echo htmlspecialchars($m['titular']); ?>
                                    </div>
                                    <div id="receipt-upload-<?php echo $m['id']; ?>" class="receipt-upload-container" style="display: none; background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 6px; border: 1px dashed rgba(255,255,255,0.2); margin-top: 0.5rem;">
                                        <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; color: #94a3b8;">Por favor, adjunta la foto o captura de pantalla de la transferencia:</p>
                                        <input type="file" name="comprobante_<?php echo $m['id']; ?>" accept="image/*" class="form-control" style="font-size: 0.85rem; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); color: #fff;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </label>
                <?php endforeach; else: ?>
                    <p style="padding: 1rem; background: rgba(239, 68, 68, 0.1); border-left: 3px solid #ef4444; border-radius: 4px; color: #fca5a5; font-size: 0.9rem;">No hay métodos de pago configurados en este momento.</p>
                <?php endif; ?>

                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1.5rem; font-size: 1.05rem; padding: 1rem; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border: none; font-weight: 700; color: white; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" <?php echo empty($metodos) ? 'disabled' : ''; ?>>
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

<script>
const subtotalCart = <?php echo $subtotal; ?>;
const shippingRules = <?php echo json_encode($reglas_envio ?? []); ?>;

function calculateTotal() {
    let selectedShipping = document.querySelector('input[name="metodo_envio_id"]:checked');
    let baseShippingCost = selectedShipping ? parseFloat(selectedShipping.getAttribute('data-cost')) : 0;
    
    let finalShippingCost = baseShippingCost;
    let ruleApplied = false;
    
    if (shippingRules && shippingRules.length > 0) {
        let sortedRules = [...shippingRules].sort((a, b) => parseFloat(b.monto_minimo_carrito) - parseFloat(a.monto_minimo_carrito));
        for(let rule of sortedRules) {
            if (subtotalCart >= parseFloat(rule.monto_minimo_carrito)) {
                finalShippingCost = parseFloat(rule.costo_fijo);
                
                if (finalShippingCost === 0 || finalShippingCost < baseShippingCost) {
                    ruleApplied = true;
                }
                break;
            }
        }
    }
    
    let grandTotal = subtotalCart + finalShippingCost;
    
    document.getElementById('shipping-cost-display').innerText = finalShippingCost === 0 ? 'GRATIS' : '+$' + finalShippingCost.toFixed(2);
    if(finalShippingCost === 0) {
        document.getElementById('shipping-cost-display').style.color = '#34d399';
    } else {
        document.getElementById('shipping-cost-display').style.color = '#f59e0b';
    }
    document.getElementById('grand-total-display').innerText = '$' + grandTotal.toFixed(2);
    document.getElementById('shipping-label-indicator').style.display = ruleApplied ? 'inline-block' : 'none';
}

document.addEventListener("DOMContentLoaded", function() {
    calculateTotal();
});

function toggleReceiptUpload(radioElement, methodName) {
    // Esconder y quitar required de todos los inputs de comprobante
    document.querySelectorAll('.receipt-upload-container').forEach(container => {
        container.style.display = 'none';
        let input = container.querySelector('input[type="file"]');
        if(input) input.removeAttribute('required');
    });

    if (methodName === 'Transferencia') {
        const metId = radioElement.value;
        const container = document.getElementById('receipt-upload-' + metId);
        if (container) {
            container.style.display = 'block';
            let input = container.querySelector('input[type="file"]');
            if(input) input.setAttribute('required', 'required');
        }
    }
}
</script>

