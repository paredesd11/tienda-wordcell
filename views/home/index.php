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
                    <label>Precio Estimado ($)</label>
                    <input type="text" id="estimatedPrice" readonly value="20.00">
                    <small>El precio varía según la urgencia de entrega.</small>
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

<script>
    // Configuración de precios desde la base de datos
    const pricingRules = <?php echo json_encode($precios_config ?? []); ?>;
    
    function getRule(concepto) {
        return pricingRules.find(r => r.concepto === concepto) || { valor: 0, tipo: 'Monto' };
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
                    showCustomAlert("¡Solicitud registrada con éxito! Un técnico te contactará pronto.");
                    closeServiceModal();
                    this.reset();
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
                <div class="noticia-card draggable-card">
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
