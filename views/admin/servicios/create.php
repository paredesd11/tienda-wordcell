<div class="content-header">
    <h2>Nuevo Servicio Técnico</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Registra un nuevo tipo de servicio o reparación.</p>
</div>

<div class="admin-card">
    <div class="admin-card-title">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg> 
        Información del Servicio
    </div>
    
    <form action="<?php echo URL_BASE; ?>admin/serviciosCreate" method="POST" class="mt-3">
        <div class="form-grid">
            <div class="form-group full-width">
                <label for="nombre">Nombre del Servicio</label>
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej: Cambio de Pantalla" required>
            </div>

            <div class="form-group full-width">
                <label for="descripcion">Descripción Breve</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="3" placeholder="Describe brevemente en qué consiste el servicio..." required></textarea>
            </div>

            <div class="form-group">
                <label for="icono-search">Ícono (FontAwesome)</label>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <div style="flex: 1; position: relative;">
                        <input type="text" id="icono-search" class="form-control" placeholder="Buscar ícono..." autocomplete="off"
                               oninput="filterIcons(this.value)" onfocus="document.getElementById('icon-dropdown').style.display='block'">
                        <div id="icon-dropdown" style="display:none; position:absolute; top:100%; left:0; right:0; max-height:220px; overflow-y:auto; background:#1e293b; border:1px solid #334155; border-radius:6px; z-index:999; padding:4px;"></div>
                    </div>
                    <div id="icon-preview" style="width:48px;height:48px;background:#0f172a;border:1px solid #334155;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#60a5fa;flex-shrink:0;">
                        <i class="fas fa-tools" id="icon-preview-el"></i>
                    </div>
                </div>
                <input type="hidden" id="icono" name="icono" value="fas fa-tools" required>
                <small class="text-muted" style="font-size: 0.72rem;">Escribe para filtrar. Haz clic para seleccionar.</small>
            </div>

            <script>
            const FA_ICONS = [
                'fas fa-tools','fas fa-mobile-alt','fas fa-laptop','fas fa-desktop','fas fa-tablet-alt',
                'fas fa-tv','fas fa-headphones','fas fa-camera','fas fa-print','fas fa-keyboard',
                'fas fa-mouse','fas fa-hdd','fas fa-memory','fas fa-microchip','fas fa-server',
                'fas fa-wifi','fas fa-bluetooth','fas fa-battery-full','fas fa-plug','fas fa-charging-station',
                'fas fa-wrench','fas fa-cog','fas fa-cogs','fas fa-hammer','fas fa-screwdriver',
                'fas fa-shield-alt','fas fa-lock','fas fa-unlock','fas fa-key','fas fa-bug',
                'fas fa-virus','fas fa-game-console','fas fa-gamepad','fas fa-headset','fas fa-speaker',
                'fas fa-volume-up','fas fa-signal','fas fa-network-wired','fas fa-ethernet','fas fa-sim-card',
                'fas fa-sd-card','fas fa-usb','fas fa-cloud','fas fa-database','fas fa-code',
                'fas fa-phone','fas fa-star','fas fa-check-circle','fas fa-exclamation-triangle',
                'fas fa-info-circle','fas fa-question-circle','fas fa-sync-alt','fas fa-redo',
                'fas fa-arrow-up','fas fa-arrow-down','fas fa-clipboard-list','fas fa-file-alt',
                'fas fa-receipt','fas fa-tag','fas fa-tags','fas fa-box','fas fa-boxes',
                'fas fa-truck','fas fa-shuttle-van','fas fa-motorcycle','fas fa-bicycle',
                'fas fa-home','fas fa-store','fas fa-building','fas fa-briefcase',
                'far fa-clock','fas fa-calendar-alt','fas fa-map-marker-alt','fas fa-user-cog',
                'fas fa-user-shield','fas fa-user-check','fas fa-users','fas fa-hands-helping',
                'fas fa-fire','fas fa-bolt','fas fa-thermometer-half','fas fa-water',
                'fas fa-fan','fas fa-wind','fas fa-snowflake','fas fa-sun',
            ];

            const dropdown = document.getElementById('icon-dropdown');
            const hiddenInput = document.getElementById('icono');
            const searchInput = document.getElementById('icono-search');
            const previewEl = document.getElementById('icon-preview-el');

            function renderIcons(list) {
                dropdown.innerHTML = '';
                list.forEach(cls => {
                    const item = document.createElement('div');
                    item.style.cssText = 'display:flex;align-items:center;gap:10px;padding:7px 10px;border-radius:5px;cursor:pointer;transition:background 0.15s;';
                    item.onmouseover = () => item.style.background = '#334155';
                    item.onmouseout  = () => item.style.background = 'transparent';
                    item.innerHTML = `<i class="${cls}" style="width:20px;text-align:center;color:#60a5fa;font-size:1rem;"></i><span style="font-size:0.82rem;color:#e2e8f0;">${cls}</span>`;
                    item.onclick = () => {
                        hiddenInput.value = cls;
                        searchInput.value = cls;
                        previewEl.className = cls;
                        dropdown.style.display = 'none';
                    };
                    dropdown.appendChild(item);
                });
                if(list.length === 0) dropdown.innerHTML = '<div style="padding:8px 12px;color:#94a3b8;font-size:0.8rem;">Sin resultados</div>';
            }

            function filterIcons(q) {
                const matches = FA_ICONS.filter(c => c.toLowerCase().includes(q.toLowerCase()));
                renderIcons(matches);
                dropdown.style.display = 'block';
            }

            // Initial load
            renderIcons(FA_ICONS);
            // Pre-fill from hidden value
            searchInput.value = hiddenInput.value || 'fas fa-tools';

            // Close on outside click
            document.addEventListener('click', e => {
                if (!e.target.closest('#icono-search') && !e.target.closest('#icon-dropdown')) {
                    dropdown.style.display = 'none';
                }
            });
            </script>

            <div class="form-group">
                <label for="precio_desde">Precio Desde ($)</label>
                <input type="number" step="0.01" id="precio_desde" name="precio_desde" class="form-control" value="0.00" required>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <a href="<?php echo URL_BASE; ?>admin/servicios" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Registrar Servicio</button>
        </div>
    </form>
</div>


