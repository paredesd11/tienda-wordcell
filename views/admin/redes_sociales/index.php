<div class="content-header d-flex justify-between align-center mb-3">
    <h2>Redes Sociales</h2>
</div>

<!-- ═══ FORM CARD ═══ -->
<div class="admin-card mb-4" style="padding: 0; overflow: hidden; max-width: 1000px;">
    <div class="admin-card-title px-4 py-3" style="background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; gap: 0.8rem;">
        <div style="width: 28px; height: 28px; background: rgba(59, 130, 246, 0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #60a5fa;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        </div>
        <span style="font-weight: 700; font-size: 1.1rem; color: #fff;">Agregar Red Social</span>
    </div>
    
    <form action="<?php echo URL_BASE; ?>admin/redes_socialesCreate" method="POST" class="p-4">
        <div class="form-grid mb-4" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group mb-0">
                <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; margin-bottom: 0.5rem; display: block;">Nombre</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: Facebook" required style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08);">
            </div>
            <div class="form-group mb-0">
                <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; margin-bottom: 0.5rem; display: block;">URL del Perfil</label>
                <input type="url" name="url_destino" class="form-control" placeholder="https://facebook.com/milocal" required style="background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08);">
            </div>
        </div>

        <div class="form-group mb-4">
            <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; margin-bottom: 0.8rem; display: block;">Seleccionar Icono Predefinido</label>
            <div class="icon-selector-grid">
                <!-- Facebook -->
                <label class="icon-option">
                    <input type="radio" name="icono" value="facebook" required>
                    <div class="icon-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </div>
                </label>
                <!-- Instagram -->
                <label class="icon-option">
                    <input type="radio" name="icono" value="instagram">
                    <div class="icon-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="url(#ig-grad)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><defs><linearGradient id="ig-grad" x1="2" y1="22" x2="22" y2="2"><stop offset="0%" stop-color="#f58529"/><stop offset="50%" stop-color="#dd2a7b"/><stop offset="100%" stop-color="#8134af"/></linearGradient></defs><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </div>
                </label>
                <!-- TikTok -->
                <label class="icon-option">
                    <input type="radio" name="icono" value="tiktok">
                    <div class="icon-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.12-3.44-3.17-3.64-5.41-.02-.38-.03-.76-.02-1.12.21-2.12 1.47-4 3.32-5.07 1.48-.84 3.22-1.08 4.88-.69v4.06c-1.18-.34-2.45-.11-3.44.62-1.01.76-1.54 2.01-1.36 3.24.16 1.07.82 2 1.74 2.52 1.1.61 2.45.65 3.58.11 1.08-.51 1.83-1.54 2-2.73.18-1.33.1-2.67.1-4-.01-5.14-.01-10.28-.02-15.42h-.01z"/></svg>
                    </div>
                </label>
                <!-- X (Twitter) -->
                <label class="icon-option">
                    <input type="radio" name="icono" value="x">
                    <div class="icon-box">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="#fff"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </div>
                </label>
                <!-- YouTube -->
                <label class="icon-option">
                    <input type="radio" name="icono" value="youtube">
                    <div class="icon-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#FF0000"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </div>
                </label>
                <!-- WhatsApp -->
                <label class="icon-option">
                    <input type="radio" name="icono" value="whatsapp">
                    <div class="icon-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    </div>
                </label>
                <!-- Link Option -->
                <label class="icon-option">
                    <input type="radio" name="icono" value="link">
                    <div class="icon-box">
                        <svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    </div>
                </label>
                <!-- Pinterest -->
                <label class="icon-option">
                    <input type="radio" name="icono" value="pinterest">
                    <div class="icon-box">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#E60023"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.367 18.625 0 12.017 0z"/></svg>
                    </div>
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="background:#3b82f6; border-radius:6px; padding:0.6rem 1.6rem; font-weight: 600;">Guardar</button>
    </form>
</div>

<!-- ═══ TABLE ═══ -->
<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 80px; text-align: center;">Icono</th>
                    <th>Nombre</th>
                    <th>URL Destino</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach($records as $row): ?>
                    <tr>
                        <td style="text-align: center;">
                            <div class="table-icon-preview">
                                <svg class="table-icon" width="24" height="24" fill="currentColor"><use href="#icon-<?php echo htmlspecialchars($row['icono']); ?>"></use></svg>
                            </div>
                        </td>
                        <td><strong style="color: #fff;"><?php echo htmlspecialchars($row['nombre']); ?></strong></td>
                        <td style="color: #94a3b8;"><?php echo htmlspecialchars($row['url_destino']); ?></td>
                        <td style="text-align: right;">
                            <a href="#" class="btn-text" style="color: #60a5fa; margin-right: 1rem;">Editar</a>
                            <a href="<?php echo URL_BASE; ?>admin/redes_socialesDelete/<?php echo $row['id']; ?>" class="btn-text" style="color: #f87171;" onclick="return confirmCustom(event, '¿Eliminar esta red social?');">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 2rem; color: #64748b;">No hay redes sociales registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* Icon Grid styles */
.icon-selector-grid {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 0.8rem;
    max-width: 600px;
}
.icon-option {
    cursor: pointer;
    position: relative;
    display: inline-block;
}
.icon-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.icon-box {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 50px;
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 8px;
    transition: all 0.2s;
}
.icon-option:hover .icon-box {
    background: rgba(255, 255, 255, 0.05);
}
.icon-option input[type="radio"]:checked + .icon-box {
    border-color: #3b82f6;
    background: rgba(59, 130, 246, 0.1);
    box-shadow: 0 0 0 1px #3b82f6;
}

.table-icon-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: rgba(0,0,0,0.3);
    border-radius: 8px;
    margin: 0 auto;
}
.table-icon {
    color: #e2e8f0;
}
</style>

<!-- Reusable table icons SVG defs -->
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
