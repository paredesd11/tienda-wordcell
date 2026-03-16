<!-- ═══════════════════════════════════════════════════════════
     HERO SECTION — full screen with brands strip at bottom
═══════════════════════════════════════════════════════════ -->
<section class="hero-section">
    <div class="hero-logo">
        <img src="<?php echo URL_BASE; ?>public/img/Logo.webp" alt="<?php echo APP_NAME; ?>">
    </div>
    <!-- ── BRANDS CENTERED ── -->
    <?php if (!empty($marcas)): ?>
    <div class="hero-brands">
        <?php foreach($marcas as $m): ?>
            <a href="<?php echo URL_BASE; ?>catalogo?marca=<?php echo urlencode($m['nombre']); ?>" class="hero-brand-link">
                <?php if (!empty($m['logo_url'])): ?>
                    <img src="<?php echo URL_BASE . htmlspecialchars($m['logo_url']); ?>"
                         alt="<?php echo htmlspecialchars($m['nombre']); ?>">
                <?php else: ?>
                    <span><?php echo htmlspecialchars($m['nombre']); ?></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <p class="hero-tagline">Tu tienda de tecnología de confianza</p>
    <div class="hero-actions">
        <a href="<?php echo URL_BASE; ?>catalogo" class="btn-hero-primary">Ver Catálogo</a>
        <?php if (!empty($ofertas)): ?>
            <a href="#ofertas" class="btn-hero-offers">¡Ofertas!</a>
        <?php else: ?>
            <a href="<?php echo URL_BASE; ?>auth/register" class="btn-hero-outline">Registrarse</a>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     SERVICIO TÉCNICO (Infinite Carousel)
═══════════════════════════════════════════════════════════ -->
<section class="section servicios-section overflow-hidden">
    <div class="container text-center mb-4">
        <h2 class="section-title">Servicio Técnico Especializado</h2>
        <p class="section-subtitle">Reparamos tus equipos con garantía y repuestos originales</p>
    </div>

    <?php if(!empty($servicios)): ?>
        <div class="marquee-container interactive-marquee" id="marquee-servicios">
            <div class="marquee-content">
                <?php foreach($servicios as $s): ?>
                <div class="servicio-card-enhanced draggable-card">
                    <div class="ser-icon">
                        <i class="<?php echo htmlspecialchars($s['icono']); ?>"></i>
                    </div>
                    <h3 class="ser-title"><?php echo htmlspecialchars($s['nombre']); ?></h3>
                    <p class="ser-description"><?php echo htmlspecialchars($s['descripcion']); ?></p>
                    <div class="ser-footer">
                        <span class="ser-price">Desde $<?php echo number_format($s['precio_desde'], 2); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="container text-center mt-5">
            <button class="btn-hero-primary btn-lg shadow-pulse" onclick="openServiceModal()">
                <i class="fas fa-file-signature"></i> Solicitar Servicio Técnico
            </button>
        </div>
    <?php else: ?>
        <div class="container text-center">
            <p class="text-muted-home">No hay servicios técnicos listados aún.</p>
        </div>
    <?php endif; ?>
</section>

<!-- LEAFLET CSS & JS FOR MAP PICKER -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        #mapPickerContainer {
            height: 300px;
            width: 100%;
            border-radius: 8px;
            margin-top: 10px;
            display: none;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-map {
            width: 100%;
            padding: 10px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.3s;
        }
        .btn-map:hover { background: #1d4ed8; }
        .location-result {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #10b981;
            font-weight: bold;
        }
    </style>

<!-- ═══════════════════════════════════════════════════════════
     MODAL: SOLICITAR SERVICIO TÉCNICO
═══════════════════════════════════════════════════════════ -->
<div id="serviceRequestModal" class="service-modal">
    <div class="service-modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-tools"></i> Nueva Solicitud de Servicio</h3>
            <button class="close-modal" onclick="closeServiceModal()">&times;</button>
        </div>
        <form id="serviceRequestForm" class="service-form">
            <div class="form-grid">
                <div class="form-group">
                    <label>Dispositivo / Equipo</label>
                    <input type="text" name="dispositivo" required placeholder="Ej: iPhone 15 Pro, Laptop Dell...">
                </div>
                <div class="form-group">
                    <label>Prioridad / Urgencia</label>
                    <select name="prioridad" id="prioritySelect" onchange="calculatePrice()">
                        <option value="Baja">Baja (Mantenimiento normal)</option>
                        <option value="Media" selected>Media (Estándar)</option>
                        <option value="Alta">Alta (Prioritario)</option>
                        <option value="Urgente">Urgente (Mismo día)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha Entrega del Equipo</label>
                    <input type="datetime-local" name="fecha_inicio" id="dateInicio" required>
                </div>
                <div class="form-group">
                    <label>Fecha Estimada de Retiro</label>
                    <input type="datetime-local" name="fecha_fin" id="dateFin" required onchange="calculatePrice()">
                </div>
                <div class="form-group">
                    <label>
                        Precio Estimado ($)
                        <span id="discountBadge" style="display:none; font-size: 0.75rem; background: #ef4444; color: white; padding: 2px 6px; border-radius: 4px; margin-left: 5px;">
                            Oferta Aplicada!
                        </span>
                    </label>
                    <input type="text" id="estimatedPrice" readonly value="20.00">
                    <small>El precio varía según la urgencia y prioridades.</small>
                    <input type="hidden" id="discountAppliedId" name="oferta_servicio_id" value="">
                </div>
            </div>
            
            <div class="form-group full-width" style="margin-top: 15px;">
                <label>Tipo de Entrega</label>
                <select name="tipo_entrega" id="tipoEntregaClient" onchange="toggleEntregaClient()">
                    <option value="Entrega fisica">Entrega física (En mostrador)</option>
                    <option value="Recepcion a domicilio">Recepción a domicilio</option>
                    <option value="Envio al local">Envío al local (Por encomienda)</option>
                </select>
            </div>
            <div id="fieldsDomicilioClient" class="form-group full-width" style="display:none; background: rgba(255,255,255,0.03); padding: 15px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); margin-top: 10px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div style="grid-column: span 2;">
                        <label>Ubicación / Dirección exacta (Domicilio)</label>
                        <button type="button" class="btn-map" onclick="openMapPicker()">
                            <i class="fas fa-map-marker-alt"></i> Abrir Mapa para Seleccionar Ubicación
                        </button>
                        <div id="mapPickerContainer"></div>
                        <div id="locationResultText" class="location-result"></div>
                        <input type="hidden" name="ubicacion_domicilio" id="ubicacionHiddenInput">
                    </div>
                    <div>
                        <label>Fecha de Recepción</label>
                        <input type="date" name="fecha_domicilio">
                    </div>
                    <div>
                        <label>Hora de Recepción Estimada</label>
                        <input type="time" name="hora_domicilio">
                    </div>
                </div>
            </div>
            
            <div id="fieldsLocalClient" class="form-group full-width" style="display:none; background: rgba(255,255,255,0.03); padding: 15px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); margin-top: 10px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div style="grid-column: span 2;">
                        <label>Sucursal del Local (Destino)</label>
                        <input type="text" name="sucursal_local" placeholder="Ej: Matriz Norte">
                    </div>
                    <div style="grid-column: span 2;">
                        <label>Método / Agencia de Envío</label>
                        <input type="text" name="metodo_envio" placeholder="Ej: Servientrega, Tramaco, etc.">
                    </div>
                    <div>
                        <label>Fecha Estimada de Llegada</label>
                        <input type="date" name="fecha_local">
                    </div>
                    <div>
                        <label>Hora Estimada de Llegada</label>
                        <input type="time" name="hora_local">
                    </div>
                </div>
            </div>
            
            <div class="form-group full-width">
                <label>Descripción del Problema</label>
                <textarea name="descripcion_problema" rows="4" required placeholder="Describe detalladamente qué le sucede a tu equipo..."></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeServiceModal()">Cancelar</button>
                <button type="submit" class="btn-submit">Registrar Solicitud</button>
            </div>
        </form>
    </div>
</div>
<style>
#serviceRequestModal .service-modal-content {
    max-height: 90vh;
    overflow-y: auto;
}
</style>

<script>
    // Configuración de precios desde la base de datos
    const pricingRules = <?php echo json_encode($precios_config ?? []); ?>;
    
    function getRule(concepto) {
        return pricingRules.find(r => r.concepto === concepto) || { valor: 0, tipo: 'Monto' };
    }

    function toggleEntregaClient() {
        const val = document.getElementById('tipoEntregaClient').value;
        const fDomicilio = document.getElementById('fieldsDomicilioClient');
        const fLocal = document.getElementById('fieldsLocalClient');
        
        if (val === 'Recepcion a domicilio') {
            fDomicilio.style.display = 'block';
            fLocal.style.display = 'none';
        } else if (val === 'Envio al local') {
            fDomicilio.style.display = 'none';
            fLocal.style.display = 'block';
        } else {
            fDomicilio.style.display = 'none';
            fLocal.style.display = 'none';
        }
    }

    function openServiceModal() {
        <?php if(!isset($_SESSION['user_id'])): ?>
            window.location.href = "<?php echo URL_BASE; ?>auth/login";
            return;
        <?php endif; ?>
        document.getElementById('serviceRequestModal').style.display = 'flex';
        // Set default dates
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('dateInicio').value = now.toISOString().slice(0, 16);
        
        const delivery = new Date();
        delivery.setHours(delivery.getHours() + 48);
        delivery.setMinutes(delivery.getMinutes() - delivery.getTimezoneOffset());
        document.getElementById('dateFin').value = delivery.toISOString().slice(0, 16);
        calculatePrice();
    }

    let map = null;
    let marker = null;

    function openMapPicker() {
        const container = document.getElementById('mapPickerContainer');
        container.style.display = 'block';
        
        // Timeout to allow the div to render properly before Leaflet calculates size
        setTimeout(() => {
            if (!map) {
                // Initialize map centered in Guayaquil (default)
                map = L.map('mapPickerContainer').setView([-2.196, -79.886], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                marker = L.marker([-2.196, -79.886], {draggable: true}).addTo(map);

                // Event on drag end
                marker.on('dragend', function (e) {
                    const position = marker.getLatLng();
                    reverseGeocode(position.lat, position.lng);
                });

                // Event on map click
                map.on('click', function(e) {
                    marker.setLatLng(e.latlng);
                    reverseGeocode(e.latlng.lat, e.latlng.lng);
                });
                
                // Try to get user current location
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(pos) {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        map.setView([lat, lng], 15);
                        marker.setLatLng([lat, lng]);
                        reverseGeocode(lat, lng);
                    });
                }
            } else {
                map.invalidateSize();
            }
        }, 300);
    }

    function reverseGeocode(lat, lng) {
        document.getElementById('locationResultText').innerText = "Buscando dirección...";
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if(data && data.display_name) {
                    // Extract a shorter string from display_name
                    const parts = data.display_name.split(',');
                    const cleanAddress = parts.slice(0, 3).join(', ');
                    
                    document.getElementById('locationResultText').innerText = "📍 " + cleanAddress;
                    document.getElementById('ubicacionHiddenInput').value = cleanAddress;
                }
            })
            .catch(err => {
                document.getElementById('locationResultText').innerText = "📍 Coordenadas: " + lat.toFixed(5) + ", " + lng.toFixed(5);
                document.getElementById('ubicacionHiddenInput').value = lat + ", " + lng;
            });
    }

    function closeServiceModal() {
        document.getElementById('serviceRequestModal').style.display = 'none';
    }

    function calculatePrice() {
        const priority = document.getElementById('prioritySelect').value;
        const dateFin = new Date(document.getElementById('dateFin').value);
        const now = new Date();
        
        // Regla Base
        const baseRule = getRule('Base');
        let total = parseFloat(baseRule.valor);

        // Multiplicadores por tiempo
        const diffHours = (dateFin - now) / (1000 * 60 * 60);
        let timeMultiplier = 1;

        if (diffHours < 24) {
            const rule24 = getRule('24h');
            timeMultiplier = parseFloat(rule24.valor);
        } else if (diffHours < 48) {
            const rule48 = getRule('48h');
            timeMultiplier = parseFloat(rule48.valor);
        }
        
        total *= timeMultiplier;

        // Adicional por prioridad
        if (priority !== 'Baja' && priority !== 'Media') {
            const priorityRule = getRule(priority);
            if (priorityRule.tipo === 'Monto') {
                total += parseFloat(priorityRule.valor);
            } else {
                total *= parseFloat(priorityRule.valor);
            }
        }

        // --- SISTEMA DE OFERTAS SERVICIO TECNICO ---
        const ofertasServicios = <?php echo json_encode($ofertasServicios ?? []); ?>;
        const isFirstRequest = <?php echo ($isFirstRequest ?? true) ? 'true' : 'false'; ?>;
        
        // Hide badge by default
        document.getElementById('discountBadge').style.display = 'none';
        document.getElementById('discountAppliedId').value = '';
        document.getElementById('estimatedPrice').style.textDecoration = 'none';
        document.getElementById('estimatedPrice').style.color = '#fff';

        // Check for applicable offers
        let bestDiscount = 0;
        let bestOfferId = null;
        let offerName = '';

        if(ofertasServicios && ofertasServicios.length > 0) {
            ofertasServicios.forEach(oferta => {
                let applicable = false;
                if(oferta.condicion === 'TODOS') {
                    applicable = true;
                } else if(oferta.condicion === 'PRIMERA_VEZ' && isFirstRequest) {
                    applicable = true;
                }

                if(applicable) {
                    let descVal = parseFloat(oferta.descuento_porcentaje);
                    if(descVal > bestDiscount) {
                        bestDiscount = descVal;
                        bestOfferId = oferta.id;
                        offerName = oferta.nombre;
                    }
                }
            });
        }

        // Apply discount if found
        if(bestDiscount > 0) {
            const originalTotal = total;
            total = total - (total * (bestDiscount / 100));
            
            // UI changes
            document.getElementById('discountBadge').style.display = 'inline-block';
            document.getElementById('discountBadge').innerText = `🔥 -${bestDiscount}% ${offerName}`;
            document.getElementById('discountAppliedId').value = bestOfferId;
            document.getElementById('estimatedPrice').style.color = '#10b981'; // Green to denote discount
        }
        // --- FIN OFERTAS ---

        document.getElementById('estimatedPrice').value = total.toFixed(2);
    }

    document.getElementById('serviceRequestForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        showCustomConfirm("¿Estás seguro de registrar esta solicitud de servicio técnico con los datos proporcionados?", () => {
            const formData = new FormData(this);
            formData.append('precio_estimado', document.getElementById('estimatedPrice').value);

            fetch("<?php echo URL_BASE; ?>home/solicitarServicio", {
                method: "POST",
                body: formData,
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.id) {
                        window.location.href = "<?php echo URL_BASE; ?>user/servicioPdf/" + data.id;
                    } else {
                        showCustomAlert("¡Solicitud registrada con éxito! Un técnico te contactará pronto.");
                        closeServiceModal();
                        this.reset();
                    }
                } else {
                    if (data.error_type === 'auth') {
                        showCustomAlert(data.message);
                        window.location.href = "<?php echo URL_BASE; ?>auth/login";
                    } else {
                        showCustomAlert("Error: " + data.message);
                    }
                }
            })
            .catch(err => {
                showCustomAlert("Error de conexión. Inténtalo de nuevo.");
                console.error(err);
            });
        });
    });
</script>

<!-- ═══════════════════════════════════════════════════════════
     NOTICIAS Y EVENTOS
═══════════════════════════════════════════════════════════ -->
<section class="section noticias-section overflow-hidden">
    <div class="container text-center mb-4">
        <h2 class="section-title">Noticias y Eventos</h2>
    </div>
    <?php if(!empty($noticias)): ?>
        <div class="marquee-container interactive-marquee" id="marquee-noticias">
            <div class="marquee-content">
                <?php foreach($noticias as $n): ?>
                <div class="noticia-card draggable-card" style="cursor:pointer; user-select:none;"
                     data-noticia-json="<?php echo htmlspecialchars(json_encode([
                         'titulo'   => $n['titulo'] ?? '',
                         'contenido'=> $n['contenido'] ?? '',
                         'autor'    => $n['autor'] ?? 'WorldCell',
                         'fecha'    => !empty($n['fecha_publicacion']) ? date('d/m/Y', strtotime($n['fecha_publicacion'])) : '',
                         'imagen'   => !empty($n['imagen_url']) ? (URL_BASE . ltrim($n['imagen_url'], '/')) : '',
                         'url'      => $n['url_externa'] ?? '',
                         'is_oferta'=> !empty($n['oferta_servicio_id']),
                     ]), ENT_QUOTES); ?>">
                    <?php if (!empty($n['imagen_url'])): ?>
                        <div class="noticia-img">
                            <img src="<?php echo URL_BASE . ltrim(htmlspecialchars($n['imagen_url']), '/'); ?>"
                                 alt="<?php echo htmlspecialchars($n['titulo'] ?? ''); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="noticia-body">
                        <h3><?php echo htmlspecialchars($n['titulo'] ?? ''); ?></h3>
                        <p><?php echo htmlspecialchars(mb_substr($n['contenido'] ?? '', 0, 120)); ?><?php echo mb_strlen($n['contenido'] ?? '', 'UTF-8') > 120 ? '…' : ''; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="container text-center">
            <p class="text-muted-home">No hay noticias disponibles en este momento.</p>
        </div>
    <?php endif; ?>
</section>

<!-- ═══ MODAL NOTICIA ═══ -->
<div id="noticia-modal-overlay" style="
    position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,0.75); backdrop-filter:blur(6px);
    align-items:center; justify-content:center; padding:16px;
    display:none;">
    <div id="noticia-modal-box" onclick="event.stopPropagation()" style="
        background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);
        border:1px solid #334155; border-radius:20px;
        width:100%; max-width:560px; max-height:88vh; overflow-y:auto;
        padding:0; position:relative; animation:nmSlideIn .3s ease;">
        <!-- Cabecera -->
        <div id="nm-header" style="padding:28px 28px 0; display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
            <div>
                <span id="nm-fecha" style="font-size:0.72rem;color:#64748b;letter-spacing:.05em;"></span>
                <h2 id="nm-titulo" style="margin:6px 0 0;font-size:1.25rem;font-weight:700;color:#f1f5f9;line-height:1.3;"></h2>
                <span id="nm-autor" style="font-size:0.78rem;color:#94a3b8;"></span>
            </div>
            <button onclick="closeNoticiaModal()" style="flex-shrink:0;background:rgba(255,255,255,.07);border:none;color:#94a3b8;border-radius:50%;width:36px;height:36px;font-size:1.1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s;"
                    onmouseover="this.style.background='rgba(255,255,255,.15)'" onmouseout="this.style.background='rgba(255,255,255,.07)'">✕</button>
        </div>
        <!-- Imagen (si existe) -->
        <div id="nm-img-wrap" style="padding:16px 28px 0;display:none;">
            <img id="nm-img" src="" alt="" style="width:100%;border-radius:12px;object-fit:cover;max-height:220px;">
        </div>
        <!-- Cuerpo -->
        <div id="nm-contenido" style="padding:20px 28px;color:#cbd5e1;font-size:0.93rem;line-height:1.75;white-space:pre-line;"></div>
        <!-- Footer oferta -->
        <div id="nm-oferta-footer" style="display:none;padding:0 28px 24px;">
            <div style="background:linear-gradient(135deg,#1d4ed8,#7c3aed);border-radius:14px;padding:18px 20px;text-align:center;">
                <p style="margin:0 0 10px;color:#e0e7ff;font-size:0.9rem;">¿Listo para aprovechar esta oferta?</p>
                <a href="#servicios" onclick="closeNoticiaModal()" style="display:inline-block;background:#fff;color:#1d4ed8;font-weight:700;padding:10px 24px;border-radius:50px;text-decoration:none;font-size:0.9rem;transition:opacity .2s;" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">🚀 Solicitar Servicio Ahora</a>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes nmSlideIn {
    from { opacity:0; transform:translateY(30px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
#noticia-modal-overlay { display:none; }
#noticia-modal-overlay.open { display:flex; }
</style>

<script>
// ── NOTICIA MODAL ──
function openNoticiaModal(data) {
    var overlay = document.getElementById('noticia-modal-overlay');
    document.getElementById('nm-titulo').textContent    = data.titulo || '';
    document.getElementById('nm-contenido').textContent = data.contenido || '';
    document.getElementById('nm-autor').textContent     = data.autor ? ('Por ' + data.autor) : '';
    document.getElementById('nm-fecha').textContent     = data.fecha || '';

    var imgWrap = document.getElementById('nm-img-wrap');
    if (data.imagen) {
        document.getElementById('nm-img').src = data.imagen;
        imgWrap.style.display = 'block';
    } else {
        imgWrap.style.display = 'none';
    }
    document.getElementById('nm-oferta-footer').style.display = data.is_oferta ? 'block' : 'none';

    // Directly set display:flex (overrides any inline display:none)
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeNoticiaModal() {
    document.getElementById('noticia-modal-overlay').style.display = 'none';
    document.body.style.overflow = '';
}

// Click on backdrop closes
document.getElementById('noticia-modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeNoticiaModal();
});

// Escape key closes
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeNoticiaModal();
});

// ── Event delegation: detect real click (not drag) on noticia cards ──
(function() {
    var startX = 0, startY = 0;
    document.addEventListener('mousedown', function(e) {
        startX = e.clientX; startY = e.clientY;
    }, true);
    document.addEventListener('mouseup', function(e) {
        var dx = Math.abs(e.clientX - startX);
        var dy = Math.abs(e.clientY - startY);
        if (dx > 6 || dy > 6) return; // was a drag, ignore
        var card = e.target.closest('[data-noticia-json]');
        if (!card) return;
        try { openNoticiaModal(JSON.parse(card.dataset.noticiaJson)); } catch(err) { console.error(err); }
    }, true);
})();
</script>

<!-- ═══════════════════════════════════════════════════════════
     NUESTROS CLIENTES / REFERENCIAS (Infinite Carousel)
═══════════════════════════════════════════════════════════ -->
<section class="section referencias-section overflow-hidden">
    <div class="container text-center mb-4">
        <h2 class="section-title">Nuestros Clientes</h2>
    </div>
    
    <?php if(!empty($referencias)): ?>
        <div class="marquee-container interactive-marquee" id="marquee-referencias">
            <div class="marquee-content <?php echo (count($referencias) < 4) ? 'marquee-static-center' : ''; ?>">
                <?php foreach($referencias as $ref): ?>
                <div class="referencia-card-enhanced draggable-card" onclick="window.location.href='<?php echo URL_BASE; ?>referencia/<?php echo $ref['id']; ?>'">
                    <div class="ref-stars">
                        <?php echo str_repeat('★', $ref['estrellas']); ?><?php echo str_repeat('☆', 5 - $ref['estrellas']); ?>
                    </div>
                    
                    <div class="ref-comment" style="flex: 1;">
                        <span class="quote-mark">“</span>
                        <p class="comment-text italicized"><?php echo htmlspecialchars($ref['comentario'] ?? ''); ?></p>
                        <span class="quote-mark">”</span>
                    </div>

                    <?php if (!empty($ref['media_url'])): ?>
                        <div class="ref-media mt-2">
                            <?php if ($ref['tipo_media'] == 'video'): ?>
                                <div class="video-overlay">
                                    <svg viewBox="0 0 24 24" width="40" height="40" fill="white"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                                <video muted loop playsinline class="ref-video-thumb">
                                    <source src="<?php echo URL_BASE . htmlspecialchars($ref['media_url']); ?>">
                                </video>
                            <?php elseif ($ref['tipo_media'] == 'imagen' || $ref['tipo_media'] == 'mixto'): ?>
                                <img src="<?php echo URL_BASE . htmlspecialchars($ref['media_url']); ?>" alt="referencia" class="ref-img-thumb">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="ref-author mt-3">
                        <strong><?php echo htmlspecialchars($ref['nombre_autor'] ?? ''); ?></strong>
                        <?php if (!empty($ref['url_referencia'])): ?>
                            <a href="<?php echo htmlspecialchars($ref['url_referencia']); ?>" target="_blank" onclick="event.stopPropagation();" class="ref-link">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="container text-center">
            <p class="text-muted-home">No hay referencias agregadas aún.</p>
        </div>
    <?php endif; ?>
</section>

<!-- ═══════════════════════════════════════════════════════════
     OFERTAS (Antes del Footer)
═══════════════════════════════════════════════════════════ -->
<?php if(!empty($ofertas)): ?>
<section id="ofertas" class="section ofertas-section">
    <div class="container">
        <h2 class="section-title">Nuestras Ofertas</h2>
        <div class="ofertas-grid">
            <?php foreach($ofertas as $o): ?>
                <div class="oferta-card">
                    <div class="oferta-badge">-<?php echo round($o['descuento_porcentaje']); ?>%</div>
                    <a href="<?php echo URL_BASE . 'producto/' . $o['producto_id']; ?>">
                        <div class="oferta-img">
                            <?php 
                                $img_url = 'public/img/Logo.webp';
                                if (!empty($o['imagen_url'])) {
                                    $imgs = json_decode($o['imagen_url'], true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($imgs) && count($imgs) > 0) {
                                        $img_url = $imgs[0];
                                    } else {
                                        $img_url = $o['imagen_url'];
                                    }
                                }
                                $final_img_src = rtrim(URL_BASE, '/') . '/' . ltrim($img_url, '/');
                            ?>
                            <img src="<?php echo htmlspecialchars($final_img_src); ?>" alt="<?php echo htmlspecialchars($o['nombre']); ?>">
                        </div>
                        <div class="oferta-info">
                            <span class="product-category"><?php echo htmlspecialchars($o['categoria_nombre']); ?></span>
                            <h3><?php echo htmlspecialchars($o['nombre']); ?></h3>
                            <div class="precio-oferta">
                                <span class="original-price">$<?php echo number_format($o['precio'], 2); ?></span>
                                <span class="discount-price">$<?php echo number_format($o['precio'] * (1 - $o['descuento_porcentaje']/100), 2); ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
