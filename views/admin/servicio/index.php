<?php
// Servicio Técnico: create form + table on same page
?>
<div class="content-header">
    <h2>Servicio Técnico</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Gestiona las solicitudes de soporte técnico de los clientes</p>
</div>

<!-- ═══ FORM CARD — Registrar Solicitud ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
        Nueva Solicitud de Servicio
    </div>
    <form action="<?php echo URL_BASE; ?>admin/servicioCreate" method="POST">
<div class="form-grid">
            <div class="form-group" style="position: relative; z-index: 9999;">
                <label>Cliente / Usuario *</label>
                <!-- Visible search input -->
                <input type="text" id="searchUsuario" class="form-control" autocomplete="off" placeholder="Buscar por nombre, correo o cédula..." required>
                <!-- Hidden input that actually submits the ID -->
                <input type="hidden" name="usuario_id" id="hiddenUsuarioId" required>
                <!-- Dropdown results -->
                <ul id="userResults" class="autocomplete-dropdown" style="display:none;"></ul>
            </div>
            <div class="form-group">
                <label>Dispositivo</label>
                <input type="text" name="dispositivo" class="form-control" placeholder="Ej: iPhone 15 Pro">
            </div>
            <div class="form-group">
                <label>Prioridad</label>
                <select name="prioridad" class="form-control form-select">
                    <option value="Baja">Baja</option>
                    <option value="Media" selected>Media</option>
                    <option value="Alta">Alta</option>
                    <option value="Urgente">Urgente</option>
                </select>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado" class="form-control form-select">
                    <option value="Pendiente">⏳ Pendiente</option>
                    <option value="En Proceso">🔧 En Proceso</option>
                    <option value="Resuelto">✅ Resuelto</option>
                    <option value="Cancelado">❌ Cancelado</option>
                </select>
            </div>
            <div class="form-group">
                <label>Fecha Entrega</label>
                <input type="datetime-local" name="fecha_fin" class="form-control">
            </div>
            <div class="form-group">
                <label>Precio Estimado ($)</label>
                <input type="number" step="0.01" name="precio_estimado" class="form-control" value="0.00">
            </div>
            <div class="form-group full-width">
                <label>Descripción del Problema</label>
                <textarea name="descripcion_problema" class="form-control" rows="3"
                          placeholder="Describe el problema reportado por el cliente..." required></textarea>
            </div>
            
            <!-- Delivery Options -->
            <div class="form-group full-width" style="margin-top: 10px;">
                <label>Tipo de Entrega *</label>
                <select name="tipo_entrega" id="tipoEntrega" class="form-control form-select" onchange="toggleEntregaFields()" required>
                    <option value="Entrega fisica">Entrega física (En mostrador)</option>
                    <option value="Recepcion a domicilio">Recepción a domicilio</option>
                    <option value="Envio al local">Envío al local (Por encomienda)</option>
                </select>
            </div>
            
            <!-- Domilicio Fields -->
            <div id="fieldsDomicilio" class="form-group full-width" style="display:none; background: #232c45; padding: 15px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div style="grid-column: span 2;">
                        <label>Ubicación / Dirección exacta (Domicilio)</label>
                        <input type="text" name="ubicacion_domicilio" class="form-control" placeholder="Ej: Calle Principal 123 y Secundaria">
                    </div>
                    <div>
                        <label>Fecha de Recepción</label>
                        <input type="date" name="fecha_domicilio" class="form-control">
                    </div>
                    <div>
                        <label>Hora de Recepción Estimada</label>
                        <input type="time" name="hora_domicilio" class="form-control">
                    </div>
                </div>
            </div>
            
            <!-- Envío al Local Fields -->
            <div id="fieldsLocal" class="form-group full-width" style="display:none; background: #232c45; padding: 15px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div style="grid-column: span 2;">
                        <label>Sucursal del Local (Destino)</label>
                        <input type="text" name="sucursal_local" class="form-control" placeholder="Ej: Matriz Norte">
                    </div>
                    <div style="grid-column: span 2;">
                        <label>Método / Agencia de Envío</label>
                        <input type="text" name="metodo_envio" class="form-control" placeholder="Ej: Servientrega, Tramaco, etc.">
                    </div>
                    <div>
                        <label>Fecha Estimada de Llegada</label>
                        <input type="date" name="fecha_local" class="form-control">
                    </div>
                    <div>
                        <label>Hora Estimada de Llegada</label>
                        <input type="time" name="hora_local" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-1 flex-end mt-2">
            <button type="submit" class="btn btn-primary">Registrar Solicitud</button>
        </div>
    </form>
</div>

<!-- ═══ WHATSAPP CONFIG SECTION ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title">
        <i class="fab fa-whatsapp"></i> Configuración de Notificaciones WhatsApp (Admins)
    </div>
    <div class="p-2">
        <form action="<?php echo URL_BASE; ?>admin/whatsappConfigSave" method="POST" class="d-flex gap-1 align-items-end mb-2">
            <div class="form-group">
                <label>Nombre Admin</label>
                <input type="text" name="nombre_admin" class="form-control" placeholder="Ej: Juan Pérez" required>
            </div>
            <div class="form-group">
                <label>Número (con +)</label>
                <input type="text" name="numero" class="form-control" placeholder="+593..." required>
            </div>
            <button type="submit" class="btn btn-success">Agregar Número</button>
        </form>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Número</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($whatsapp_config)): ?>
                        <?php foreach($whatsapp_config as $wa): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($wa['nombre_admin']); ?></td>
                            <td><?php echo htmlspecialchars($wa['numero']); ?></td>
                            <td>
                                <a href="<?php echo URL_BASE; ?>admin/whatsappConfigDelete/<?php echo $wa['id']; ?>" 
                                   class="btn btn-sm btn-danger" onclick="return confirmCustom(event, '¿Eliminar?')">Borrar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">No hay números configurados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ═══ PRICING CONFIG SECTION ═══ -->
<div class="admin-card mb-3">
    <div class="admin-card-title">
        <i class="fas fa-dollar-sign"></i> Configuración de Precios (Prioridad / Tiempo)
    </div>
    <div class="p-2">
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Descripción</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($precios_config)): ?>
                        <?php foreach($precios_config as $p): ?>
                        <tr>
                            <form action="<?php echo URL_BASE; ?>admin/pricingConfigSave" method="POST">
                                <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                <td><strong><?php echo htmlspecialchars($p['concepto']); ?></strong></td>
                                <td><?php echo $p['tipo']; ?></td>
                                <td>
                                    <input type="number" step="0.01" name="valor" class="form-control" value="<?php echo $p['valor']; ?>" style="width:100px;">
                                </td>
                                <td>
                                    <input type="text" name="descripcion" class="form-control" value="<?php echo htmlspecialchars($p['descripcion']); ?>" style="width: 100%; min-width: 200px;">
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                                </td>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ═══ solicitudes TABLE ═══ -->
<div class="admin-table-container">
    <table class="admin-table" style="font-size: 0.9rem;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Equipo</th>
                <th>Problema</th>
                <th>Prioridad</th>
                <th>Entrega</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Nota Admin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($records)): ?>
                <?php foreach($records as $row): ?>
                <?php
                    $estado = $row['estado'] ?? 'Pendiente';
                    $badgeClass = match($estado) {
                        'Resuelto'   => 'badge-green',
                        'En Proceso' => 'badge-orange',
                        'Cancelado'  => 'badge-red',
                        default      => 'badge-gray'
                    };
                    $prioridadClass = match($row['prioridad']) {
                        'Urgente' => 'text-danger fw-bold',
                        'Alta'    => 'text-warning fw-bold',
                        default   => ''
                    };
                ?>
                <tr>
                    <form action="<?php echo URL_BASE; ?>admin/servicioUpdate" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td title="<?php echo htmlspecialchars($row['usuario_id']); ?>">
                            <?php echo htmlspecialchars($row['usuario_nombre'] ?? 'Usuario'); ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['dispositivo'] ?? '—'); ?></td>
                        <td style="max-width: 15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo htmlspecialchars($row['descripcion_problema'] ?? ''); ?>">
                            <?php echo htmlspecialchars($row['descripcion_problema'] ?? '—'); ?>
                        </td>
                        <td><span class="<?php echo $prioridadClass; ?>"><?php echo $row['prioridad']; ?></span></td>
                        <td>
                            <div style="font-size: 0.8rem;">
                                <strong><?php echo htmlspecialchars($row['tipo_entrega'] ?? '—'); ?></strong><br>
                                <?php if($row['tipo_entrega'] === 'Recepcion a domicilio'): ?>
                                    <span class="text-muted"><?php echo htmlspecialchars($row['ubicacion_domicilio']); ?></span><br>
                                    <span class="text-muted"><?php echo htmlspecialchars($row['fecha_domicilio'].' '.$row['hora_domicilio']); ?></span><br>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($row['ubicacion_domicilio']); ?>" target="_blank" class="btn btn-sm btn-primary mt-1" style="font-size:0.7rem; padding: 2px 5px;"><i class="fas fa-map-marker-alt"></i> Ver Mapa</a>
                                <?php elseif($row['tipo_entrega'] === 'Envio al local'): ?>
                                    <span class="text-muted"><?php echo htmlspecialchars($row['sucursal_local']); ?> (<?php echo htmlspecialchars($row['metodo_envio']); ?>)</span><br>
                                    <span class="text-muted"><?php echo htmlspecialchars($row['fecha_local'].' '.$row['hora_local']); ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($row['descuento_porcentaje']) && (float)$row['descuento_porcentaje'] > 0): ?>
                                <div style="line-height: 1.4; font-size: 0.8rem;">
                                    <small class="text-muted" style="text-decoration: line-through;">Base: $<?php echo number_format((float)$row['precio_base'], 2); ?></small><br>
                                    <span style="color: #10b981; font-weight: bold;">$<?php echo number_format((float)$row['precio_estimado'], 2); ?></span>
                                    <span class="badge" style="background:#ef4444;color:white;font-size:0.6rem;padding:1px 4px;border-radius:4px;">-<?php echo round($row['descuento_porcentaje']); ?>%</span>
                                </div>
                            <?php else: ?>
                                <span style="font-weight:bold;">$<?php echo number_format((float)$row['precio_estimado'], 2); ?></span>
                            <?php endif; ?>
                            <input type="number" step="0.01" name="precio_estimado" class="form-control mt-1"
                                   value="<?php echo number_format((float)($row['descuento_porcentaje'] > 0 ? ($row['precio_base'] ?? $row['precio_estimado']) : $row['precio_estimado']), 2); ?>"
                                   style="width:90px; padding: 2px 5px;"
                                   title="<?php echo !empty($row['descuento_porcentaje']) ? 'Ingresa el precio BASE, el descuento de '.$row['descuento_porcentaje'].'% se aplicará automáticamente' : 'Precio estimado'; ?>">
                            <?php if (!empty($row['descuento_porcentaje']) && (float)$row['descuento_porcentaje'] > 0): ?>
                                <small style="color:#f59e0b;font-size:0.65rem;">⚠ -<?php echo round($row['descuento_porcentaje']); ?>% se aplicará</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <select name="estado" class="form-control form-select-sm" onchange="checkEstadoAlert(this)" style="padding: 2px 5px; font-size: 0.8rem;">
                                <option value="Pendiente" <?php echo $estado == 'Pendiente' ? 'selected' : ''; ?>>⏳ Pendiente</option>
                                <option value="En Progreso" <?php echo $estado == 'En Proceso' ? 'selected' : ''; ?>>🔧 En Progreso</option>
                                <option value="Terminado" <?php echo $estado == 'Terminado' ? 'selected' : ''; ?>>✅ Terminado</option>
                                <option value="Entregado" <?php echo $estado == 'Entregado' ? 'selected' : ''; ?>>📦 Entregado</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="nota_admin" class="form-control form-control-sm" placeholder="Razón del cambio..." style="width: 120px; font-size:0.8rem; padding: 2px 5px;">
                        </td>
                        <td style="vertical-align: middle;">
                            <div style="display: flex; flex-direction: column; gap: 4px; align-items: stretch; min-width: 90px;">
                                <button type="submit" class="btn btn-sm btn-primary" style="width: 100%;">Actualizar</button>
                                <a href="<?php echo URL_BASE; ?>admin/servicioPdf/<?php echo $row['id']; ?>" class="btn btn-sm" style="background-color: #f59e0b; color: white; border: none; display: flex; align-items: center; justify-content: center; gap: 5px; width: 100%;" target="_blank" title="Descargar PDF"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a href="<?php echo URL_BASE; ?>admin/servicioDelete/<?php echo $row['id']; ?>"
                                   class="btn btn-sm btn-danger" style="width: 100%; text-align: center;"
                                   onclick="return confirmCustom(event, '¿Eliminar esta solicitud?');">Eliminar</a>
                            </div>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="empty-row text-center">
                        No hay solicitudes de servicio técnico registradas.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<br><br>

<!-- ═══ WHATSAPP API CONFIG ═══ -->
<div class="admin-card mt-5 border-top border-secondary pt-4" style="border-top-width: 2px !important; margin-top: 3rem !important;">
    <div class="admin-card-title">
        <i class="fab fa-whatsapp"></i> Configuración API WhatsApp (Credenciales)
    </div>
    <div class="p-3">
        <form action="<?php echo URL_BASE; ?>admin/whatsappApiSave" method="POST">
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label text-white-50 small mb-2">Instance ID</label>
                    <input type="text" name="instance_id" class="form-control bg-dark text-white border-secondary p-2" 
                           value="<?php echo htmlspecialchars($whatsapp_api['instance_id'] ?? ''); ?>" placeholder="Ej: instance12345">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label text-white-50 small mb-2">Token</label>
                    <input type="text" name="mensaje_token" class="form-control bg-dark text-white border-secondary p-2" 
                           value="<?php echo htmlspecialchars($whatsapp_api['mensaje_token'] ?? ''); ?>" placeholder="Tu Token de UltraMsg">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label text-white-50 small mb-2">API URL</label>
                    <input type="text" name="api_url" class="form-control bg-dark text-white border-secondary p-2" 
                           value="<?php echo htmlspecialchars($whatsapp_api['api_url'] ?? 'https://api.ultramsg.com'); ?>">
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-primary w-100 py-2">Guardar API</button>
                </div>
            </div>
            <div class="mt-2 small text-info">
                <i class="fas fa-info-circle"></i> Estas credenciales permiten que el sistema envíe mensajes automáticos a través de UltraMsg.
            </div>
        </form>
    </div>
</div>

<script>
function checkEstadoAlert(selectObj) {
    if (selectObj.value === 'Entregado') {
        showCustomAlert('ℹ️ Importante: Las solicitudes marcadas como "Entregado" desaparecerán automáticamente de esta tabla después de 24 horas.', 'warning');
    }
}

    let mapAdmin = null;
    let markerAdmin = null;

    function openMapPickerAdmin() {
        const container = document.getElementById('mapPickerContainerAdmin');
        container.style.display = 'block';
        
        setTimeout(() => {
            if (!mapAdmin) {
                mapAdmin = L.map('mapPickerContainerAdmin').setView([-2.196, -79.886], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(mapAdmin);

                markerAdmin = L.marker([-2.196, -79.886], {draggable: true}).addTo(mapAdmin);

                markerAdmin.on('dragend', function (e) {
                    const position = markerAdmin.getLatLng();
                    reverseGeocodeAdmin(position.lat, position.lng);
                });

                mapAdmin.on('click', function(e) {
                    markerAdmin.setLatLng(e.latlng);
                    reverseGeocodeAdmin(e.latlng.lat, e.latlng.lng);
                });
                
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(pos) {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        mapAdmin.setView([lat, lng], 15);
                        markerAdmin.setLatLng([lat, lng]);
                        reverseGeocodeAdmin(lat, lng);
                    });
                }
            } else {
                mapAdmin.invalidateSize();
            }
        }, 300);
    }

    function reverseGeocodeAdmin(lat, lng) {
        document.getElementById('locationResultTextAdmin').innerText = "Buscando dirección...";
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if(data && data.display_name) {
                    const parts = data.display_name.split(',');
                    const cleanAddress = parts.slice(0, 3).join(', ');
                    
                    document.getElementById('locationResultTextAdmin').innerText = "📍 " + cleanAddress;
                    document.getElementById('ubicacionHiddenInputAdmin').value = cleanAddress;
                }
            })
            .catch(err => {
                document.getElementById('locationResultTextAdmin').innerText = "📍 Coordenadas: " + lat.toFixed(5) + ", " + lng.toFixed(5);
                document.getElementById('ubicacionHiddenInputAdmin').value = lat + ", " + lng;
            });
    }

function toggleEntregaFields() {
    const val = document.getElementById('tipoEntrega').value;
    const fDomicilio = document.getElementById('fieldsDomicilio');
    const fLocal = document.getElementById('fieldsLocal');
    
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

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar toggle
    toggleEntregaFields();

    // Autocomplete Usuario
    const searchInput = document.getElementById('searchUsuario');
    const hiddenInput = document.getElementById('hiddenUsuarioId');
    const resultsList = document.getElementById('userResults');
    let timeoutId;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const query = this.value.trim();
            
            if (query.length < 1) {
                resultsList.style.display = 'none';
                hiddenInput.value = '';
                resultsList.innerHTML = '';
                return;
            }

            timeoutId = setTimeout(() => {
                fetch('<?php echo URL_BASE; ?>admin/ajaxSearchUsers?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        resultsList.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(user => {
                                const li = document.createElement('li');
                                const cedulaHtml = user.cedula ? `<span class="cedula">CI: ${user.cedula}</span>` : '';
                                li.innerHTML = `
                                    <strong>${user.nombre} ${user.apellido}</strong>
                                    <span>${cedulaHtml} ${user.correo}</span>
                                `;
                                li.addEventListener('click', function() {
                                    searchInput.value = `${user.nombre} ${user.apellido} (${user.correo})`;
                                    hiddenInput.value = user.id;
                                    resultsList.style.display = 'none';
                                });
                                resultsList.appendChild(li);
                            });
                            resultsList.style.display = 'block';
                        } else {
                            const li = document.createElement('li');
                            li.innerHTML = '<span class="no-results">No se encontraron usuarios...</span>';
                            resultsList.appendChild(li);
                            resultsList.style.display = 'block';
                        }
                    })
                    .catch(err => {
                        console.error('Error buscando usuarios:', err);
                    });
            }, 100);
        });

        document.addEventListener('click', function(e) {
            if (e.target !== searchInput && e.target !== resultsList) {
                resultsList.style.display = 'none';
            }
        });
    }
});
</script>

<style>
.autocomplete-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    z-index: 99999;
    background: #1a2035;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    max-height: 280px;
    overflow-y: auto;
    list-style: none;
    padding: 6px 0;
    margin: 0;
}
.autocomplete-dropdown::-webkit-scrollbar {
    width: 4px;
}
.autocomplete-dropdown::-webkit-scrollbar-track {
    background: transparent;
}
.autocomplete-dropdown::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.15);
    border-radius: 4px;
}
.autocomplete-dropdown li {
    padding: 10px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    cursor: pointer;
    transition: background 0.15s ease;
}
.autocomplete-dropdown li:last-child {
    border-bottom: none;
}
.autocomplete-dropdown li:hover {
    background: rgba(255,255,255,0.07);
}
.autocomplete-dropdown li strong {
    display: block;
    color: #e2e8f0;
    font-size: 0.92rem;
    font-weight: 600;
}
.autocomplete-dropdown li span {
    display: block;
    color: #94a3b8;
    font-size: 0.78rem;
    margin-top: 3px;
}
.autocomplete-dropdown li .cedula {
    display: inline-block;
    background: rgba(99,102,241,0.25);
    color: #a5b4fc;
    padding: 1px 7px;
    border-radius: 4px;
    font-size: 0.72rem;
    margin-right: 6px;
    font-weight: 700;
    letter-spacing: 0.3px;
}
.autocomplete-dropdown li .no-results {
    color: #f87171;
    font-size: 0.85rem;
    text-align: center;
    display: block;
    padding: 4px 0;
}
</style>

<?php if (isset($_SESSION['download_pdf_id'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-download the PDF generated from the CREATE form
        window.open('<?php echo URL_BASE; ?>admin/servicioPdf/<?php echo $_SESSION['download_pdf_id']; ?>', '_blank');
    });
</script>
<?php unset($_SESSION['download_pdf_id']); endif; ?>
