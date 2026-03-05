<section class="section" style="padding-top: 2rem; min-height: 80vh;">
    <div class="container">
        <!-- Banner de Bienvenida -->
        <div class="user-welcome-banner" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(30, 58, 138, 0.4) 100%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 2.5rem; margin-bottom: 2rem; position: relative; overflow: hidden; backdrop-filter: blur(10px);">
            <div style="position: relative; z-index: 2;">
                <h2 style="font-size: 2rem; margin-bottom: 0.5rem; font-weight: 700;">¡Hola, <?php echo htmlspecialchars($user['nombre']); ?>! 👋</h2>
                <p style="color: #94a3b8; font-size: 1.1rem; margin-bottom: 0;">Gestiona tus pedidos, servicios técnicos y datos personales desde un solo lugar.</p>
            </div>
            <!-- Decoraciones abstractas -->
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(59, 130, 246, 0.2); border-radius: 50%; filter: blur(60px); z-index: 1;"></div>
        </div>

        <?php if(isset($_SESSION['success_password'])): ?>
            <div class="alert alert-success" style="margin-bottom: 1.5rem;"><?php echo $_SESSION['success_password']; unset($_SESSION['success_password']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error_password'])): ?>
            <div class="alert alert-error" style="margin-bottom: 1.5rem;"><?php echo $_SESSION['error_password']; unset($_SESSION['error_password']); ?></div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; align-items: start;">
            
            <!-- TARJETA: PERFIL -->
            <div class="card" style="background: var(--glass-bg); border: 1px solid var(--glass-border); padding: 1.5rem; border-radius: 20px; backdrop-filter: blur(15px);">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
                    <div style="width: 45px; height: 45px; background: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user" style="color: white; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.2rem;">Mi Perfil</h3>
                </div>
                <form action="<?php echo URL_BASE; ?>user/actualizar" method="POST">
                    <div class="row" style="display:flex; gap:1rem; margin-bottom: 1rem;">
                        <div style="flex:1;">
                            <label class="form-label" style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.3rem; display: block;">Nombres</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($user['nombre']); ?>" required 
                                   style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: white;">
                        </div>
                        <div style="flex:1;">
                            <label class="form-label" style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.3rem; display: block;">Apellidos</label>
                            <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($user['apellido']); ?>" required
                                   style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: white;">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.3rem; display: block;">Teléfono (WhatsApp)</label>
                        <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>" placeholder="+593..." 
                               style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: white;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label class="form-label" style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.3rem; display: block;">Dirección de Envío / Domicilio</label>
                        <input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($user['direccion'] ?? ''); ?>" placeholder="Calle, Nro, Referencia" 
                               style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: white;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.3rem; display: block;">Correo Electrónico (No modificable)</label>
                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['correo']); ?>" readonly 
                               style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05); color: #64748b; cursor: not-allowed;">
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="padding: 1rem; border-radius: 12px; font-weight: 600;">Guardar Cambios</button>
                </form>
            </div>

            <!-- TARJETA: REPARACIONES -->
            <div class="card" style="background: var(--glass-bg); border: 1px solid var(--glass-border); padding: 1.5rem; border-radius: 20px; backdrop-filter: blur(15px);">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
                    <div style="width: 45px; height: 45px; background: #fbbf24; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-tools" style="color: #451a03; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.2rem;">Seguimiento de Reparaciones</h3>
                </div>
                
                <?php if(!empty($reparaciones)): ?>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <?php foreach($reparaciones as $rep): ?>
                            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px; padding: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                    <div>
                                        <h4 style="margin: 0; font-size: 1rem; font-weight: 600;"><?php echo htmlspecialchars($rep['dispositivo']); ?></h4>
                                        <small style="color: #94a3b8;"><?php echo date('d M, Y', strtotime($rep['fecha_solicitud'])); ?></small>
                                    </div>
                                    <span style="padding: 0.3rem 0.7rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; 
                                        <?php 
                                            echo match($rep['estado']) {
                                                'Entregado'   => 'background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.3);',
                                                'Terminado'   => 'background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3);',
                                                'En Progreso' => 'background: rgba(251, 191, 36, 0.15); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3);',
                                                default       => 'background: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3);'
                                            };
                                        ?>">
                                        <?php echo htmlspecialchars($rep['estado']); ?>
                                    </span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 0.5rem; margin-top: 0.5rem;">
                                    <span style="color: #94a3b8; font-size: 0.85rem;"><i class="fas fa-tag"></i> Est.: $<?php echo number_format($rep['precio_estimado'], 2); ?></span>
                                    <button class="btn-sm" style="background:none; border:none; color: #3b82f6; cursor: pointer; font-size: 0.85rem;" 
                                            onclick="showCustomAlert('Problema: <?php echo addslashes($rep['descripcion_problema']); ?>')">Ver detalles</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 2rem 0;">
                        <img src="https://cdn-icons-png.flaticon.com/512/10044/10044810.png" style="width: 60px; filter: grayscale(1); opacity: 0.3; margin-bottom: 1rem;">
                        <p style="color: #64748b; font-size: 0.9rem;">No tienes servicios técnicos activos.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TARJETA: PEDIDOS -->
            <div class="card" style="background: var(--glass-bg); border: 1px solid var(--glass-border); padding: 1.5rem; border-radius: 20px; backdrop-filter: blur(15px);">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
                    <div style="width: 45px; height: 45px; background: #10b981; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-shopping-bag" style="color: white; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.2rem;">Mis Compras</h3>
                </div>

                <?php if(!empty($pedidos)): ?>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <?php foreach($pedidos as $p): ?>
                            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 15px; padding: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <strong>Pedido #<?php echo $p['id']; ?></strong><br>
                                        <small style="color: #94a3b8;"><?php echo date('d/m/Y', strtotime($p['fecha_pedido'])); ?></small>
                                    </div>
                                    <div style="text-align: right;">
                                        <span style="font-weight: 700; color: #10b981;">$<?php echo number_format($p['total'], 2); ?></span><br>
                                        <span style="font-size: 0.75rem; color: #94a3b8;"><?php echo $p['estado']; ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 2rem 0;">
                        <img src="https://cdn-icons-png.flaticon.com/512/3500/3500833.png" style="width: 60px; filter: grayscale(1); opacity: 0.3; margin-bottom: 1rem;">
                        <p style="color: #64748b; font-size: 0.9rem;">Aún no has realizado compras.</p>
                        <a href="<?php echo URL_BASE; ?>catalogo" style="color: #3b82f6; font-size: 0.85rem; text-decoration: none; font-weight: 600;">Explorar Catálogo →</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TARJETA: SEGURIDAD -->
            <div class="card" style="background: var(--glass-bg); border: 1px solid var(--glass-border); padding: 1.5rem; border-radius: 20px; backdrop-filter: blur(15px);">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
                    <div style="width: 45px; height: 45px; background: #6366f1; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-shield-alt" style="color: white; font-size: 1.2rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1.2rem;">Seguridad</h3>
                </div>
                <form action="<?php echo URL_BASE; ?>user/cambiarPassword" method="POST">
                    <div class="form-group" style="margin-bottom: 1.1rem;">
                        <label class="form-label" style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.45rem; display: block;">Contraseña Actual</label>
                        <div class="pw-field-wrap-dark">
                            <input type="password" id="upw-current" name="current_password" class="form-control" required
                                   placeholder="••••••••"
                                   style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); border-radius: 10px; color: white; padding: 0.75rem 3rem 0.75rem 1rem; font-size: 0.9rem; transition: border-color 0.2s, background 0.2s;">
                            <button type="button" class="pw-toggle-dark" onclick="togglePw('upw-current', this)" aria-label="Ver contraseña"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 1.1rem;">
                        <label class="form-label" style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.45rem; display: block;">Nueva Contraseña</label>
                        <div class="pw-field-wrap-dark">
                            <input type="password" id="upw-new" name="new_password" class="form-control" required
                                   placeholder="••••••••"
                                   style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); border-radius: 10px; color: white; padding: 0.75rem 3rem 0.75rem 1rem; font-size: 0.9rem; transition: border-color 0.2s, background 0.2s;">
                            <button type="button" class="pw-toggle-dark" onclick="togglePw('upw-new', this)" aria-label="Ver contraseña"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.45rem; display: block;">Confirmar Nueva Contraseña</label>
                        <div class="pw-field-wrap-dark">
                            <input type="password" id="upw-confirm" name="confirm_password" class="form-control" required
                                   placeholder="••••••••"
                                   style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); border-radius: 10px; color: white; padding: 0.75rem 3rem 0.75rem 1rem; font-size: 0.9rem; transition: border-color 0.2s, background 0.2s;">
                            <button type="button" class="pw-toggle-dark" onclick="togglePw('upw-confirm', this)" aria-label="Ver contraseña"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="padding: 0.85rem; border-radius: 12px; font-weight: 600; letter-spacing: 0.04em;">Cambiar Contraseña</button>
                    <div style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; color: #86efac; font-size: 0.85rem;">
                        <i class="fas fa-check-circle"></i>
                        <span>Autenticación en 2 Pasos Activa</span>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
