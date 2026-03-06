<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-success" style="margin-bottom:1.25rem;">
        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['flash_message']; ?>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

<div class="content-header" style="margin-bottom:1.75rem;">
    <h2 style="display:flex;align-items:center;gap:.6rem;">
        <i class="fas fa-credit-card" style="color:var(--blue);"></i>
        Configuración PayPhone
    </h2>
</div>

<div class="payphone-grid">

    <!-- Panel principal -->
    <div class="admin-card" style="padding:2rem;">

        <!-- Aviso info -->
        <div style="display:flex;align-items:flex-start;gap:.75rem;
                    background:rgba(37,99,235,.10);border:1px solid rgba(37,99,235,.22);
                    border-radius:var(--radius-sm);padding:1rem 1.1rem;margin-bottom:1.75rem;">
            <i class="fas fa-info-circle" style="color:#60a5fa;margin-top:.15rem;flex-shrink:0;"></i>
            <span style="font-size:.875rem;color:var(--text-main);line-height:1.55;">
                Para obtener estas credenciales, inicia sesión en tu panel de
                <a href="https://payphone.app/" target="_blank"
                   style="color:#60a5fa;font-weight:600;text-decoration:underline;">PayPhone Developer</a>
                y ve a la sección de <strong>"Comercios"</strong> y <strong>"Tokens"</strong>.
            </span>
        </div>

        <form action="<?php echo URL_BASE; ?>admin/configuracionPayphoneUpdate" method="POST">

            <!-- Ambiente -->
            <div class="form-group" style="margin-bottom:1.4rem;">
                <label style="font-size:.82rem;font-weight:700;color:#94a3b8;
                              text-transform:uppercase;letter-spacing:.05em;margin-bottom:.45rem;display:block;">
                    Ambiente de Operación
                </label>
                <select name="ambiente" class="form-select" required>
                    <option value="Pruebas"    <?php echo (isset($config['ambiente']) && $config['ambiente'] == 'Pruebas')    ? 'selected' : ''; ?>>📍 Sandbox (Modo de Pruebas)</option>
                    <option value="Produccion" <?php echo (isset($config['ambiente']) && $config['ambiente'] == 'Produccion') ? 'selected' : ''; ?>>🟢 Producción (Pagos Reales)</option>
                </select>
                <p style="font-size:.81rem;color:var(--text-muted);margin-top:.45rem;">
                    Utiliza el modo de Pruebas hasta que confirmes que la validación del pago funciona correctamente.
                </p>
            </div>

            <!-- Token -->
            <div class="form-group" style="margin-bottom:1.4rem;">
                <label style="font-size:.82rem;font-weight:700;color:#94a3b8;
                              text-transform:uppercase;letter-spacing:.05em;margin-bottom:.45rem;display:block;">
                    Token de Autorización &nbsp;<span style="opacity:.55;font-weight:400;font-size:.78rem;">(Authorization Token)</span>
                </label>
                <div style="display:flex;align-items:stretch;gap:0;border-radius:var(--radius-sm);overflow:hidden;
                            border:1px solid var(--input-border);">
                    <span style="display:flex;align-items:flex-start;padding:.75rem .85rem;
                                 background:rgba(255,255,255,.04);border-right:1px solid var(--input-border);">
                        <i class="fas fa-key" style="color:var(--text-muted);margin-top:.15rem;"></i>
                    </span>
                    <textarea name="token_autorizacion" rows="4" required
                              placeholder="Bearer eyJhbGci..."
                              style="flex:1;padding:.7rem .9rem;background:var(--input-bg);border:none;
                                     color:var(--text-main);font-family:'Courier New',monospace;font-size:.88rem;
                                     outline:none;resize:vertical;min-height:100px;"
                    ><?php echo htmlspecialchars($config['token_autorizacion'] ?? ''); ?></textarea>
                </div>
                <p style="font-size:.81rem;color:var(--text-muted);margin-top:.45rem;">
                    El token largo que sirve para autenticarte contra la API de PayPhone.
                </p>
            </div>

            <!-- Store ID -->
            <div class="form-group" style="margin-bottom:1.4rem;">
                <label style="font-size:.82rem;font-weight:700;color:#94a3b8;
                              text-transform:uppercase;letter-spacing:.05em;margin-bottom:.45rem;display:block;">
                    Store ID &nbsp;<span style="opacity:.55;font-weight:400;font-size:.78rem;">(ID del Comercio / App)</span>
                </label>
                <div style="display:flex;align-items:center;gap:0;border-radius:var(--radius-sm);overflow:hidden;
                            border:1px solid var(--input-border);">
                    <span style="display:flex;align-items:center;padding:0 .85rem;height:100%;
                                 background:rgba(255,255,255,.04);border-right:1px solid var(--input-border);
                                 align-self:stretch;justify-content:center;">
                        <i class="fas fa-store" style="color:var(--text-muted);"></i>
                    </span>
                    <input type="text" name="store_id" required
                           placeholder="Ej: 12345678-abcd-1234-..."
                           value="<?php echo htmlspecialchars($config['store_id'] ?? ''); ?>"
                           style="flex:1;padding:.7rem .9rem;background:var(--input-bg);border:none;
                                  color:var(--text-main);font-family:'Courier New',monospace;font-size:.88rem;
                                  outline:none;">
                </div>
                <p style="font-size:.81rem;color:var(--text-muted);margin-top:.45rem;">
                    El ID único que identifica a tu tienda dentro de PayPhone.
                </p>
            </div>

            <div style="border-top:1px solid var(--border-color);padding-top:1.25rem;
                        display:flex;justify-content:flex-end;">
                <button type="submit" class="btn btn-primary" style="padding:.65rem 2rem;font-size:.95rem;">
                    <i class="fas fa-save"></i> Guardar Configuración
                </button>
            </div>

        </form>
    </div>

    <!-- Panel lateral seguridad -->
    <div>
        <div class="admin-card" style="background:linear-gradient(135deg,var(--blue),#1d4ed8);
                                       border-color:rgba(255,255,255,.12);padding:1.5rem;">
            <h5 style="font-weight:700;font-size:.95rem;color:#fff;
                       display:flex;align-items:center;gap:.5rem;margin-bottom:.85rem;">
                <i class="fas fa-shield-alt"></i> Seguridad PayPhone
            </h5>
            <p style="font-size:.82rem;color:rgba(255,255,255,.8);line-height:1.6;margin:0;">
                Al utilizar PayPhone, la tienda nunca almacenará números de tarjetas de crédito o CVV localmente.
                El usuario introducirá sus datos directamente en el entorno seguro (PCI-DSS) provisto por PayPhone
                mediante su Widget en la página de Checkout.
            </p>
        </div>

        <!-- Tips card -->
        <div class="admin-card" style="padding:1.25rem;margin-top:0;">
            <h6 style="font-size:.82rem;font-weight:700;color:#94a3b8;
                       text-transform:uppercase;letter-spacing:.05em;margin-bottom:.85rem;">
                <i class="fas fa-lightbulb" style="color:#fbbf24;"></i> &nbsp;Tips
            </h6>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.6rem;">
                <li style="font-size:.82rem;color:var(--text-muted);display:flex;gap:.5rem;">
                    <i class="fas fa-check-circle" style="color:var(--green);margin-top:.15rem;flex-shrink:0;"></i>
                    Prueba primero en modo Sandbox antes de pasar a Producción.
                </li>
                <li style="font-size:.82rem;color:var(--text-muted);display:flex;gap:.5rem;">
                    <i class="fas fa-check-circle" style="color:var(--green);margin-top:.15rem;flex-shrink:0;"></i>
                    El token debe incluir el prefijo <code style="font-size:.78rem;color:#93c5fd;">Bearer</code>.
                </li>
                <li style="font-size:.82rem;color:var(--text-muted);display:flex;gap:.5rem;">
                    <i class="fas fa-check-circle" style="color:var(--green);margin-top:.15rem;flex-shrink:0;"></i>
                    Nunca compartas tu token de producción.
                </li>
            </ul>
        </div>
    </div>

</div>
