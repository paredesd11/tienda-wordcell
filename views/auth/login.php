<?php
$show_register = isset($show_register_form) && $show_register_form;
?>

<!-- Botón regresar al inicio -->
<a href="<?php echo URL_BASE; ?>" class="btn-back-home" title="Regresar al inicio">
    <i class="fas fa-arrow-left"></i>
    <span>Inicio</span>
</a>


<div class="auth-card<?php echo $show_register ? ' show-register' : ''; ?>" id="auth-card">

    <!-- ════════════════════════════════════════
         PANEL IZQUIERDO: INICIAR SESIÓN
         ════════════════════════════════════════ -->
    <div class="auth-panel panel-login">
        <h2>Iniciar Sesión</h2>

        <?php if(isset($error) && !$show_register): ?>
            <div class="auth-alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if(isset($success)): ?>
            <div class="auth-alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="<?php echo URL_BASE; ?>auth/loginPost" method="POST" style="width:100%;">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

            <div class="auth-input-group">
                <input type="email" name="correo" placeholder="Correo Electrónico" required autocomplete="email">
            </div>

            <div class="auth-input-group">
                <div class="pw-field-wrap">
                    <input type="password" id="login-password" name="password" placeholder="Contraseña" required autocomplete="current-password">
                    <button type="button" class="pw-toggle" onclick="togglePw('login-password', this)" aria-label="Ver contraseña">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <a href="#" class="auth-link">¿Olvidaste tu contraseña?</a>

            <div style="text-align:center;">
                <button type="submit" class="btn-auth">Ingresar</button>
            </div>
        </form>
    </div>

    <!-- ════════════════════════════════════════
         PANEL DERECHO: CREAR CUENTA
         ════════════════════════════════════════ -->
    <div class="auth-panel panel-register">
        <h2>Crear Cuenta</h2>
        <div class="auth-divider">usa tu correo para registrarte</div>

        <?php if(isset($error) && $show_register): ?>
            <div class="auth-alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form id="register-form" action="<?php echo URL_BASE; ?>auth/registerPost" method="POST" style="width:100%;">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

            <div class="auth-input-group">
                <input type="text" name="nombre" placeholder="Nombres" required autocomplete="given-name">
            </div>
            <div class="auth-input-group">
                <input type="text" name="apellido" placeholder="Apellidos" required autocomplete="family-name">
            </div>
            <div class="auth-input-group">
                <input type="email" name="correo" placeholder="Correo Electrónico" required autocomplete="email">
            </div>
            <div class="auth-input-group">
                <input type="text" name="telefono" placeholder="Teléfono / WhatsApp (+593...)" required autocomplete="tel">
            </div>
            <div class="auth-input-group">
                <input type="text" name="direccion" placeholder="Dirección (Calle, Nro, Referencia)" required autocomplete="street-address">
            </div>

            <div class="auth-input-group">
                <div class="pw-field-wrap">
                    <input type="password" id="reg-password" name="password" placeholder="Contraseña" required autocomplete="new-password">
                    <button type="button" class="pw-toggle" onclick="togglePw('reg-password', this)" aria-label="Ver contraseña">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="auth-input-group">
                <div class="pw-field-wrap">
                    <input type="password" id="reg-password-repeat" name="password_repeat" placeholder="Repetir Contraseña" required autocomplete="new-password">
                    <button type="button" class="pw-toggle" onclick="togglePw('reg-password-repeat', this)" aria-label="Ver contraseña">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Requisitos de contraseña -->
            <div class="pw-requirements">
                <span class="pw-req" id="req-length">8+ Caracteres</span>
                <span class="pw-req" id="req-upper">Mayúscula (A-Z)</span>
                <span class="pw-req" id="req-lower">Minúscula (a-z)</span>
                <span class="pw-req" id="req-number">Número (0-9)</span>
                <span class="pw-req" id="req-symbol">Símbolo (!@#$)</span>
            </div>

            <div style="text-align:center;">
                <button type="submit" id="btn-crear-cuenta" class="btn-auth" disabled>Crear Cuenta</button>
            </div>
        </form>
    </div>

    <!-- ════════════════════════════════════════
         PANEL OVERLAY AZUL (deslizante)
         ════════════════════════════════════════ -->
    <div class="auth-overlay">
        <!-- Contenido LOGIN → invita a registrarse -->
        <div class="overlay-content" id="overlay-login-msg">
            <h2>¡Hola, Amigo!</h2>
            <p>Ingresa tus datos personales<br>y empieza tu experiencia con nosotros.</p>
            <button class="btn-overlay" id="go-register">Registrarse</button>
        </div>

        <!-- Contenido REGISTRO → invita a iniciar sesión -->
        <div class="overlay-content" id="overlay-register-msg" style="display:none;">
            <h2>¡Bienvenido de vuelta!</h2>
            <p>Para seguir conectado, inicia sesión<br>con tu información personal.</p>
            <button class="btn-overlay" id="go-login">Iniciar Sesión</button>
        </div>
    </div>

</div>

<script>
/* Show / hide password helper (global para auth, panel y admin) */
function togglePw(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    const icon = btn.querySelector('i');
    if (icon) {
        icon.classList.toggle('fa-eye',      !isHidden);
        icon.classList.toggle('fa-eye-slash', isHidden);
    }
}
</script>
