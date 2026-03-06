document.addEventListener('DOMContentLoaded', () => {
    const card = document.getElementById('auth-card');
    const goRegister = document.getElementById('go-register');
    const goLogin = document.getElementById('go-login');
    const overlayLoginMsg = document.getElementById('overlay-login-msg');
    const overlayRegisterMsg = document.getElementById('overlay-register-msg');

    // ── Panel slide: Login → Register ────────────────────
    if (goRegister) {
        goRegister.addEventListener('click', () => {
            card.classList.add('show-register');
            overlayLoginMsg.style.display = 'none';
            overlayRegisterMsg.style.display = 'block';
        });
    }

    // ── Panel slide: Register → Login ────────────────────
    if (goLogin) {
        goLogin.addEventListener('click', () => {
            card.classList.remove('show-register');
            overlayLoginMsg.style.display = 'block';
            overlayRegisterMsg.style.display = 'none';
        });
    }

    // ── Card entrance animation ───────────────────────────
    if (typeof gsap !== 'undefined') {
        gsap.from('#auth-card', {
            opacity: 0, scale: 0.9, y: 30,
            duration: 0.9, ease: 'back.out(1.5)'
        });
    }

    // ── Live password validation ───────────────────────────
    const regPassword = document.getElementById('reg-password');
    const regPasswordRepeat = document.getElementById('reg-password-repeat');
    const btnCrear = document.getElementById('btn-crear-cuenta');

    const rules = {
        'req-length': v => v.length >= 8,
        'req-upper': v => /[A-Z]/.test(v),
        'req-lower': v => /[a-z]/.test(v),
        'req-number': v => /\d/.test(v),
        'req-symbol': v => /[\W_]/.test(v),
    };

    function validateForm() {
        if (!regPassword || !btnCrear) return;
        const val = regPassword.value;
        const repeat = regPasswordRepeat ? regPasswordRepeat.value : '';
        let passValid = true;

        Object.entries(rules).forEach(([id, test]) => {
            const el = document.getElementById(id);
            if (!el) return;
            const ok = test(val);
            el.classList.toggle('valid', ok);
            if (!ok) passValid = false;
        });

        const reqInputs = document.querySelectorAll('#register-form input[required]');
        let fieldsFilled = true;
        reqInputs.forEach(i => {
            if (i.value.trim() === '') fieldsFilled = false;
        });

        btnCrear.disabled = !(passValid && val === repeat && repeat.length > 0 && fieldsFilled);
    }

    const allRegInputs = document.querySelectorAll('#register-form input[required]');
    allRegInputs.forEach(inp => {
        inp.addEventListener('input', validateForm);
    });
});
