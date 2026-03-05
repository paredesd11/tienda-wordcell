<div class="form-wrapper">
    <div class="auth-brand">
        <h1>Verificación Requerida</h1>
        <p>Hemos enviado un código a tu correo.</p>
    </div>

    <?php if(isset($_SESSION['dev_mail_code'])): ?>
        <div class="alert alert-success" style="background-color: #d1fae5; color: #065f46; border: 1px solid #34d399;">
            <strong>Modo Desarrollador:</strong> Tu código simulado de correo es <b><?php echo $_SESSION['dev_mail_code']; ?></b>
            <?php unset($_SESSION['dev_mail_code']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="<?php echo URL_BASE; ?>auth/verifyPost" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
        <div class="input-group">
            <label>Código de Verificación (6 dígitos)</label>
            <input type="text" name="codigo" maxlength="6" required autocomplete="off">
        </div>
        <button type="submit" class="btn btn-primary">Verificar Código</button>
    </form>
</div>
