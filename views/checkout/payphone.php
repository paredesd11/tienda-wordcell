<section class="section" style="padding-top: 3rem; min-height: calc(100vh - 60px);">
    <div class="container text-center">
        
        <div class="card" style="max-width: 500px; margin: 0 auto; background: var(--glass-bg); border: 1px solid var(--glass-border); padding: 2.5rem; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.4);">
            
            <img src="https://payphone.app/wp-content/uploads/2021/08/logo-payphone.svg" alt="PayPhone" style="height: 40px; margin-bottom: 2rem; filter: brightness(0) invert(1);">

            <h2 style="color: #fff; margin-bottom: 0.5rem; font-weight: 700;">Pago Seguro</h2>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Ingresa los datos de tu tarjeta de crédito o débito a continuación.</p>

            <div style="background: rgba(96, 165, 250, 0.1); border: 1px solid rgba(96, 165, 250, 0.2); border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem;">
                <span style="color: var(--text-muted); font-size: 1rem; display: block; margin-bottom: 0.5rem;">Total a Pagar</span>
                <span style="font-size: 2rem; color: #60a5fa; font-weight: 700;">$<?php echo number_format($total, 2); ?></span>
            </div>

            <!-- PayPhone Widget Container -->
            <div id="pp-button" style="min-height: 400px; background: white; border-radius: 8px; padding: 10px;"></div>

            <!-- Loader while widget is loading -->
            <div id="pp-loader" style="margin-top: 1rem; color: #94a3b8;">
                <i class="fas fa-circle-notch fa-spin me-2"></i> Cargando entorno seguro de pagos...
            </div>

            <a href="<?php echo URL_BASE; ?>checkout/index_cart" class="btn-text mt-4" style="color: var(--text-muted); text-decoration: underline;">
                Cancelar y regresar
            </a>
        </div>
    </div>
</section>

<!-- PayPhone Official Scripts -->
<script type="module" src="https://pay.payphonetodoesposible.com/api/button/js?appId=<?php echo htmlspecialchars($config['store_id']); ?>"></script>

<script>
window.onload = function() {
    
    // Convert total to cents for PayPhone API
    const amountInCents = parseInt(<?php echo $total; ?> * 100);

    payphone.Button({
        // Id del Contenedor de renderizado
        id: "pp-button",
        
        // El total (Monto) a cobrar
        amount: amountInCents,
        amountWithoutTax: amountInCents,
        amountWithTax: 0,
        tax: 0,
        service: 0,
        tip: 0,
        
        // Identificador del cliente (Usamos el user_id de la sesión local)
        clientTransactionId: "TX-" + Date.now() + "-<?php echo $_SESSION['user_id']; ?>",
        reference: "Compra Online - <?php echo APP_NAME; ?>",

        // Callbacks
        onReady: function() {
            // Widget Loaded successfully
            document.getElementById('pp-loader').style.display = 'none';
        },
        // Callback para cobros exitosos. (Aprobado)
        onTransaction: function(response) {
            document.getElementById('pp-loader').innerHTML = '<i class="fas fa-check-circle text-success me-2"></i> Procesando tu orden...';
            document.getElementById('pp-loader').style.display = 'block';
            document.getElementById('pp-button').style.display = 'none';

            // Send proof of transaction to our backend to finalize DB insertion
            fetch("<?php echo URL_BASE; ?>checkout/procesarPayphone", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "status=" + encodeURIComponent(response.transactionStatus) + 
                      "&transactionId=" + encodeURIComponent(response.transactionId) +
                      "&receipt=" + encodeURIComponent(response.authorizationCode)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert("PayPhone Aprobado, pero hubo un error en la tienda: " + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Error de red confirmando el pago. Por favor, contacta soporte inmediatamente.");
            });
        },
        onError: function(error) {
            console.error("PayPhone Error: ", error);
            alert("Apareció un problema al cargar el pago: " + JSON.stringify(error));
        }
    }).render();
};
</script>
