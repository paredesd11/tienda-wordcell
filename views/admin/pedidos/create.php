<div class="content-header">
    <h2>Registrar Pedido Manual</h2>
    <p class="text-muted" style="font-size:0.85rem;margin-top:0.25rem;">Crea un registro de pedido manualmente en el sistema.</p>
</div>

<div class="admin-card">
    <div class="admin-card-title">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg> 
        Información del Pedido
    </div>
    
    <form action="<?php echo URL_BASE; ?>admin/pedidosCreate" method="POST" class="mt-3">
        <div class="form-grid" style="overflow: visible;">
            <div class="form-group" style="position: relative; z-index: 9999;">
                <label>Usuario (Cliente) *</label>
                <!-- Visible search input -->
                <input type="text" id="searchUsuario" class="form-control" autocomplete="off" placeholder="Buscar por nombre, correo o cédula...">
                <!-- Hidden input that actually submits the ID -->
                <input type="hidden" name="usuario_id" id="hiddenUsuarioId" required>
                <!-- Dropdown results -->
                <ul id="userResults" class="autocomplete-dropdown" style="display:none;"></ul>
            </div>
            <div class="form-group">
                <label>Método de Pago *</label>
                <select name="metodo_pago_id" class="form-control form-select" required>
                    <option value="">— Selecciona un método —</option>
                    <?php foreach ($metodos_pago as $mp): ?>
                        <option value="<?php echo $mp['id']; ?>">
                            #<?php echo $mp['id']; ?> — <?php echo htmlspecialchars($mp['tipo']); ?>
                            <?php if (!empty($mp['banco'])): ?>(<?php echo htmlspecialchars($mp['banco']); ?>)<?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Total del Pedido ($)</label>
                <input type="number" step="0.01" name="total" class="form-control" placeholder="Ej: 150.50" required>
            </div>
            <div class="form-group">
                <label>Estado del Pedido</label>
                <select name="estado" class="form-control form-select" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Pagado">Pagado</option>
                    <option value="Enviado">Enviado</option>
                    <option value="Entregado">Entregado</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
            </div>
        </div>
        
        <div class="d-flex gap-2 mt-4 flex-end border-top pt-3">
            <a href="<?php echo URL_BASE; ?>admin/pedidos" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Registrar Pedido</button>
        </div>
    </form>
</div>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchUsuario');
    const hiddenInput = document.getElementById('hiddenUsuarioId');
    const resultsList = document.getElementById('userResults');
    let timeoutId;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value.trim();
        
        // Reset state if empty
        if (query.length < 1) {
            resultsList.style.display = 'none';
            hiddenInput.value = '';
            resultsList.innerHTML = '';
            return;
        }

        // Debounce to prevent spamming the database, reduced for instant feel
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
                                // On select
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
        }, 100); // 100ms delay for real-time feel
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target !== searchInput && e.target !== resultsList) {
            resultsList.style.display = 'none';
        }
    });
});
</script>