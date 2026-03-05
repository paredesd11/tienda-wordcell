<style>
/* Cart Specific Styles */
.cart-section {
    padding: 4rem 0;
    min-height: calc(100vh - 60px);
}
.cart-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 2rem;
    color: #fff;
}
.cart-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

@media (max-width: 900px) {
    .cart-layout {
        grid-template-columns: 1fr;
    }
}

.cart-items {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 1.5rem;
}

.cart-item {
    display: grid;
    grid-template-columns: 80px 1fr auto auto;
    gap: 1.5rem;
    padding: 1.5rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    align-items: center;
}
.cart-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.cart-item-img {
    width: 80px;
    height: 80px;
    background: rgba(0,0,0,0.3);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.cart-item-img img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.cart-item-details h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: 0.3rem;
}
.cart-item-details p {
    color: #60a5fa;
    font-weight: 700;
    font-size: 1.05rem;
}

.cart-item-qty {
    display: flex;
    align-items: center;
    background: rgba(0,0,0,0.3);
    border-radius: 6px;
    border: 1px solid rgba(255,255,255,0.1);
    overflow: hidden;
}
.cart-item-qty button {
    background: transparent;
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    cursor: pointer;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.cart-item-qty button:hover {
    background: rgba(255,255,255,0.1);
}
.cart-item-qty span {
    padding: 0 10px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #fff;
}

.cart-item-remove button {
    background: rgba(220, 38, 38, 0.15);
    color: #fca5a5;
    border: 1px solid rgba(220, 38, 38, 0.3);
    width: 36px;
    height: 36px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.cart-item-remove button:hover {
    background: #dc2626;
    color: white;
}

.cart-summary {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 1.5rem;
    height: fit-content;
    position: sticky;
    top: 80px;
}
.cart-summary h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    color: var(--text-light);
}
.summary-total {
    display: flex;
    justify-content: space-between;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255,255,255,0.1);
    font-size: 1.25rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 2rem;
}
.summary-total .total-price {
    color: #60a5fa;
}

.btn-checkout {
    display: block;
    width: 100%;
    text-align: center;
    padding: 0.8rem;
    background: var(--primary);
    color: white;
    font-weight: 700;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-checkout:hover {
    background: var(--primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
}
.cart-empty {
    text-align: center;
    padding: 4rem 1rem;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
}
.cart-empty svg {
    margin-bottom: 1rem;
    color: #6b88b5;
}
.cart-empty h3 {
    font-size: 1.5rem;
    color: #fff;
    margin-bottom: 0.5rem;
}
.cart-empty p {
    color: var(--text-muted);
    margin-bottom: 2rem;
}
</style>

<div class="container">
    <section class="cart-section">
        <h1 class="cart-title">Tu Carrito de Compras</h1>
        
        <?php if (!empty($carrito)): ?>
        <div class="cart-layout">
            <div class="cart-items">
                <?php 
                $subtotal = 0;
                foreach($carrito as $item): 
                    $itemTotal = $item['precio'] * $item['cantidad'];
                    $subtotal += $itemTotal;
                    
                    // Decode image if it's a JSON array
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
                <div class="cart-item" data-id="<?php echo $item['id']; ?>">
                    <a href="<?php echo URL_BASE; ?>producto/<?php echo $item['id']; ?>?from=cart" class="cart-item-img" style="text-decoration: none;">
                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                    </a>
                    <div class="cart-item-details">
                        <a href="<?php echo URL_BASE; ?>producto/<?php echo $item['id']; ?>?from=cart" style="text-decoration: none; color: inherit;">
                            <h4><?php echo htmlspecialchars($item['nombre']); ?></h4>
                        </a>
                        <p>$<?php echo number_format($item['precio'], 2); ?></p>
                    </div>
                    <div class="cart-item-qty">
                        <button onclick="updateQty(<?php echo $item['id']; ?>, 'decrease')">−</button>
                        <span><?php echo $item['cantidad']; ?></span>
                        <button onclick="updateQty(<?php echo $item['id']; ?>, 'increase')">+</button>
                    </div>
                    <div class="cart-item-remove">
                        <button onclick="removeItem(<?php echo $item['id']; ?>)" title="Eliminar del carrito">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="cart-summary">
                <h3>Resumen de Orden</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="summary-row">
                    <span>Impuestos (0%)</span>
                    <span>$0.00</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span class="total-price">$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                
                <a href="<?php echo URL_BASE; ?>checkout/procesarCart" class="btn-checkout">Proceder al Pago</a>
                
                <a href="<?php echo URL_BASE; ?>catalogo" class="btn-hero-outline" style="width: 100%; text-align: center; margin-top: 1rem; border-color: rgba(255,255,255,0.1); font-size: 0.9rem; padding: 0.7rem;">Seguir Comprando</a>
                
                <button onclick="clearCart()" class="btn-text" style="width: 100%; color: #f87171; background: transparent; border: none; font-size: 0.85rem; margin-top: 1.5rem; cursor: pointer; text-decoration: underline;">Vaciar Carrito</button>
            </div>
        </div>
        <?php else: ?>
        <div class="cart-empty">
            <svg width="80" height="80" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 01-8 0"/>
            </svg>
            <h3>Tu carrito está vacío</h3>
            <p>¡Explora nuestro catálogo y descubre los mejores productos!</p>
            <a href="<?php echo URL_BASE; ?>catalogo" class="btn-hero-primary">Ver Productos</a>
        </div>
        <?php endif; ?>
    </section>
</div>

<script>
let modalAction = null;
let modalPayload = null;

function showConfirmModal(title, desc, btnText, action, payload) {
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalDesc').innerText = desc;
    document.getElementById('modalConfirmBtn').innerText = btnText;
    modalAction = action;
    modalPayload = payload;
    document.getElementById('confirmModal').style.display = 'flex';
}

function closeConfirmModal() {
    document.getElementById('confirmModal').style.display = 'none';
    modalAction = null;
    modalPayload = null;
}

function executeConfirm() {
    if (modalAction === 'clearCart') {
        window.location.href = '<?php echo URL_BASE; ?>carrito/clear';
    } else if (modalAction === 'removeItem') {
        fetch('<?php echo URL_BASE; ?>carrito/remove', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: modalPayload })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                window.location.reload();
            }
        });
    }
}

function updateQty(id, action) {
    fetch('<?php echo URL_BASE; ?>carrito/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, action: action })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        }
    });
}

function removeItem(id) {
    showConfirmModal(
        '¿Lanzar del carrito?', 
        'Estás a punto de eliminar este producto de tu orden de compra.', 
        'Sí, eliminar', 
        'removeItem', 
        id
    );
}

function clearCart() {
    showConfirmModal(
        '¿Vaciar Carrito?', 
        'Estás a punto de eliminar todos los productos de tu carrito. Esta acción no se puede deshacer.', 
        'Sí, Vaciar Carrito', 
        'clearCart', 
        null
    );
}
</script>

<!-- Custom Modal Confirmacion -->
<div id="confirmModal" class="custom-modal-overlay" style="display: none;">
    <div class="custom-modal-box">
        <div class="modal-icon warning">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 id="modalTitle">¿Vaciar Carrito?</h3>
        <p id="modalDesc">Estás a punto de eliminar todos los productos de tu carrito. Esta acción no se puede deshacer.</p>
        <div class="modal-actions">
            <button class="btn-cancel-modal" onclick="closeConfirmModal()">Cancelar</button>
            <button class="btn-confirm-modal" id="modalConfirmBtn" onclick="executeConfirm()">Sí, Vaciar Carrito</button>
        </div>
    </div>
</div>

<style>
.custom-modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    display: flex; align-items: center; justify-content: center;
    z-index: 9999;
}
.custom-modal-box {
    background: #1e293b; border: 1px solid rgba(255,255,255,0.1);
    border-radius: 16px; padding: 2rem; width: 90%; max-width: 400px;
    text-align: center; color: #f8fafc; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    animation: modalPop 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes modalPop { 0% { opacity: 0; transform: scale(0.9); } 100% { opacity: 1; transform: scale(1); } }
.modal-icon {
    width: 64px; height: 64px; border-radius: 50%; margin: 0 auto 1.2rem;
    display: flex; align-items: center; justify-content: center;
}
.modal-icon.warning { background: rgba(244, 63, 94, 0.15); color: #f43f5e; border: 2px solid rgba(244, 63, 94, 0.3); }
.custom-modal-box h3 { margin: 0 0 0.8rem; font-size: 1.4rem; font-weight: 600; font-family: 'Inter', sans-serif;}
.custom-modal-box p { color: #94a3b8; font-size: 0.95rem; line-height: 1.5; margin: 0 0 1.5rem; font-family: 'Inter', sans-serif;}
.modal-actions { display: flex; gap: 1rem; }
.modal-actions button { flex: 1; padding: 0.8rem; border-radius: 8px; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; font-family: 'Inter', sans-serif; font-size: 0.95rem; }
.btn-cancel-modal { background: rgba(255,255,255,0.05); color: #cbd5e1; border: 1px solid rgba(255,255,255,0.1) !important; }
.btn-cancel-modal:hover { background: rgba(255,255,255,0.1); color: #fff; }
.btn-confirm-modal { background: #e11d48; color: #fff; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.3); }
.btn-confirm-modal:hover { background: #be123c; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(225, 29, 72, 0.4); }
</style>
