<!-- Botón regresar al login -->
<a href="<?php echo URL_BASE; ?>auth/login" class="btn-back-home" title="Regresar al Login">
    <i class="fas fa-arrow-left"></i>
    <span>Regresar</span>
</a>

<div class="auth-card" id="auth-card" style="max-width: 500px; display: flex; flex-direction: column;">

    <div class="auth-panel" style="width: 100%; border-radius: 20px; text-align: center;">
        <h2>Verificación Requerida</h2>
        <div class="auth-divider">Hemos enviado un código a tu correo.</div>

        <?php if(isset($_SESSION['dev_mail_code'])): ?>
            <div class="auth-alert success" style="margin-bottom: 20px; font-size: 0.95rem;">
                <strong>Modo Desarrollador:</strong> Tu código simulado de correo es <b><?php echo $_SESSION['dev_mail_code']; ?></b>
                <?php unset($_SESSION['dev_mail_code']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
            <div class="auth-alert error" style="margin-bottom: 20px; font-size: 0.95rem;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="<?php echo URL_BASE; ?>auth/verifyPost" method="POST" style="width: 100%; max-width: 350px; margin: 0 auto;">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            
            <div class="floating-group">
                <input type="text" name="codigo" class="floating-input" maxlength="6" placeholder=" " required autocomplete="off" style="text-align: center; letter-spacing: 10px; font-size: 1.5rem; font-weight: bold;">
                <label class="floating-label" style="text-align: center; width: 100%;">Código de 6 dígitos</label>
            </div>
            
            <button type="submit" class="btn-auth" style="margin-top: 20px; width: 100%;">Verificar Código</button>
        </form>
    </div>

</div>
