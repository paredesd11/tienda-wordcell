    </main><!-- /.main-content -->

    <!-- ═══════════════════════════════════════════════════════════
         FLOATING MAP WIDGET — fixed bottom-right
    ═══════════════════════════════════════════════════════════ -->
    <?php if (!empty($ubicaciones)): ?>
    <?php
        $ub = $ubicaciones[0];
        $mapsShareUrl = '#';
        $gmapsEmbedUrl = '';
        
        // Use exact admin coordinates for BOTH the redirect and the visual embed preview
        if (!empty($ub['latitud']) && !empty($ub['longitud'])) {
            $lat = trim($ub['latitud']);
            $lng = trim($ub['longitud']);
            
            // Link to redirect when clicked
            $mapsShareUrl = "https://www.google.com/maps?q={$lat},{$lng}";
            
            // Link for the iframe preview
            $gmapsEmbedUrl = "https://maps.google.com/maps?q={$lat},{$lng}&hl=es&z=15&output=embed";
        } elseif (!empty($ub['iframe_mapa'])) {
            // Fallback just in case they only put an iframe
            if (preg_match('/src=["\']([^"\']+)["\']/', $ub['iframe_mapa'], $m)) {
                $gmapsEmbedUrl = $m[1];
            }
        }
    ?>
    <div class="floating-map" id="floatingMap">
        <a href="<?php echo htmlspecialchars($mapsShareUrl); ?>"
           target="_blank"
           rel="noopener"
           title="Ver en Google Maps — <?php echo htmlspecialchars($ub['nombre'] ?? 'Ubicación'); ?>"
           class="floating-map-circle">
            
            <?php if (!empty($gmapsEmbedUrl)): ?>
                <!-- Renderiza el mapa real con las coordenadas insertadas -->
                <div class="floating-map-iframe">
                    <iframe
                        src="<?php echo htmlspecialchars($gmapsEmbedUrl); ?>"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen>
                    </iframe>
                </div>
            <?php else: ?>
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <?php endif; ?>
            
            <div class="floating-map-label">
                <?php echo htmlspecialchars($ub['nombre'] ?? 'Local 1'); ?>
            </div>
        </a>
    </div>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════════════ -->
    <footer class="main-footer">
        <div class="footer-inner">
            <!-- Brand column -->
            <div class="footer-brand">
                <img src="<?php echo URL_BASE; ?>public/img/Logo.webp" alt="<?php echo APP_NAME; ?>" class="footer-logo">
                <p>Tu tienda de tecnología de confianza. Productos de calidad al mejor precio.</p>
            </div>

            <!-- Links column -->
            <div class="footer-col">
                <h4>Enlaces</h4>
                <ul>
                    <li><a href="<?php echo URL_BASE; ?>">Inicio</a></li>
                    <li><a href="<?php echo URL_BASE; ?>catalogo">Catálogo</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo URL_BASE; ?>auth/logout">Salir</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo URL_BASE; ?>auth/login">Iniciar Sesión</a></li>
                        <li><a href="<?php echo URL_BASE; ?>auth/register">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </div>

        <!-- Social column -->
            <div class="footer-col">
                <h4>Síguenos</h4>
                <p style="font-size:0.82rem;color:#6b88b5;margin-bottom:0.75rem;">Conoce las últimas novedades y ofertas.</p>
                <?php if(!empty($redesSociales)): ?>
                <div class="footer-socials">
                    <?php foreach($redesSociales as $red): ?>
                        <?php 
                            $href = $red['url_destino'] ?? $red['url'] ?? $red['valor'] ?? '#'; 
                            if ($href !== '#' && !preg_match("~^(?:f|ht)tps?://~i", $href)) {
                                $href = "https://" . ltrim($href, '/');
                            }
                        ?>
                        <a href="<?php echo htmlspecialchars($href); ?>"
                           target="_blank" rel="noopener"
                           title="<?php echo htmlspecialchars($red['nombre'] ?? $red['clave'] ?? ''); ?>"
                           class="social-link">
                            <?php if(!empty($red['icono'])): ?>
                                <svg width="22" height="22" fill="currentColor"><use href="#icon-<?php echo htmlspecialchars($red['icono']); ?>"></use></svg>
                            <?php else: ?>
                                <?php echo htmlspecialchars(mb_substr($red['nombre'] ?? $red['clave'] ?? 'Link', 0, 2)); ?>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Reusable Social Icons -->
    <svg style="display: none;">
        <defs>
            <g id="icon-facebook"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></g>
            <g id="icon-instagram"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" fill="none" stroke="currentColor" stroke-width="2"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke="currentColor" stroke-width="2"></line></g>
            <g id="icon-tiktok"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.12-3.44-3.17-3.64-5.41-.02-.38-.03-.76-.02-1.12.21-2.12 1.47-4 3.32-5.07 1.48-.84 3.22-1.08 4.88-.69v4.06c-1.18-.34-2.45-.11-3.44.62-1.01.76-1.54 2.01-1.36 3.24.16 1.07.82 2 1.74 2.52 1.1.61 2.45.65 3.58.11 1.08-.51 1.83-1.54 2-2.73.18-1.33.1-2.67.1-4-.01-5.14-.01-10.28-.02-15.42h-.01z"/></g>
            <g id="icon-x"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></g>
            <g id="icon-youtube"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></g>
            <g id="icon-whatsapp"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></g>
            <g id="icon-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></g>
            <g id="icon-pinterest"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.367 18.625 0 12.017 0z"/></g>
        </defs>
    </svg>

    <!-- ═══════════════════════════════════════════════════════════
         SCRIPTS
    ═══════════════════════════════════════════════════════════ -->
    <!-- Three.js starfield -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="<?php echo URL_BASE; ?>public/js/background3d.js"></script>

    <!-- Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- Main Logic -->
    <script src="<?php echo URL_BASE; ?>public/js/main.js"></script>

    <!-- Floating map toggle -->
    <script>
    (function(){
        const btn = document.getElementById('floatingMapToggle');
        const map = document.getElementById('floatingMap');
        if (btn && map) {
            btn.addEventListener('click', function(){
                map.classList.toggle('map-open');
            });
        }
    })();
    </script>

<!-- ═══ GLOBAL CUSTOM ALERT MODAL ═══ -->
<style>
.custom-alert-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    z-index: 99999; opacity: 0; visibility: hidden; transition: opacity 0.3s;
}
.custom-alert-overlay.show { opacity: 1; visibility: visible; }
.custom-alert-box {
    background: #1a1b26; border-radius: 12px; padding: 25px;
    width: 90%; max-width: 400px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    border: 1px solid #2f334d; text-align: center;
    transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.custom-alert-overlay.show .custom-alert-box { transform: translateY(0); }
.custom-alert-icon { font-size: 3rem; margin-bottom: 15px; }
.custom-alert-message { font-size: 1rem; color: #a9b1d6; margin-bottom: 20px; line-height: 1.5; white-space: pre-wrap; }
.custom-alert-btn {
    background: #3d59a1; color: white; border: none;
    padding: 10px 25px; border-radius: 6px; font-weight: 600; cursor: pointer;
    transition: background 0.2s;
}
.custom-alert-btn:hover { background: #2f4376; }
</style>

<div id="globalCustomAlert" class="custom-alert-overlay">
    <div class="custom-alert-box">
        <div class="custom-alert-icon" id="customAlertIcon">ℹ️</div>
        <div class="custom-alert-message" id="customAlertMessage"></div>
        <button class="custom-alert-btn" onclick="closeCustomAlert()">Entendido</button>
    </div>
</div>

<div id="globalCustomConfirm" class="custom-alert-overlay">
    <div class="custom-alert-box">
        <div class="custom-alert-icon">⚠️</div>
        <div class="custom-alert-message" id="customConfirmMessage"></div>
        <div style="display:flex; gap:10px; justify-content:center; margin-top: 20px;">
            <button class="custom-alert-btn" style="background: #f87171;" onclick="closeCustomConfirm()">Cancelar</button>
            <button class="custom-alert-btn" style="background: #3d59a1;" id="customConfirmOkBtn">Confirmar</button>
        </div>
    </div>
</div>

<script>
function showCustomAlert(message, type = 'info') {
    const overlay = document.getElementById('globalCustomAlert');
    const msgBox = document.getElementById('customAlertMessage');
    const iconBox = document.getElementById('customAlertIcon');
    
    if (message.includes('Error') || type === 'error') iconBox.innerHTML = '❌';
    else if (message.includes('éxito') || message.includes('xito') || type === 'success') iconBox.innerHTML = '✅';
    else if (message.includes('Atención') || message.includes('Importante') || type === 'warning') iconBox.innerHTML = '⚠️';
    else iconBox.innerHTML = 'ℹ️';

    msgBox.textContent = message;
    overlay.classList.add('show');
}
function closeCustomAlert() {
    document.getElementById('globalCustomAlert').classList.remove('show');
}
window.alert = function(msg) { showCustomAlert(msg); };

function showCustomConfirm(message, callback) {
    const overlay = document.getElementById('globalCustomConfirm');
    const msgBox = document.getElementById('customConfirmMessage');
    const okBtn = document.getElementById('customConfirmOkBtn');
    
    msgBox.textContent = message;
    overlay.classList.add('show');
    
    const newOkBtn = okBtn.cloneNode(true);
    okBtn.parentNode.replaceChild(newOkBtn, okBtn);
    
    newOkBtn.addEventListener('click', function() {
        closeCustomConfirm();
        if(callback) callback();
    });
}
function closeCustomConfirm() {
    document.getElementById('globalCustomConfirm').classList.remove('show');
}
function confirmCustom(event, message) {
    event.preventDefault();
    const targetUrl = event.currentTarget.href;
    const targetForm = event.currentTarget.closest('form');
    
    showCustomConfirm(message, function() {
        if (targetUrl && targetUrl !== "" && targetUrl !== "#" && !targetUrl.startsWith("javascript")) {
            window.location.href = targetUrl;
        } else if (targetForm) {
            targetForm.submit();
        }
    });
    return false;
}
</script>
</body>
</html>
