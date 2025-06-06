  <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="form-container">
                    <div class="form-header">
                        <i class="fas fa-user-plus"></i>
                         <div class="text-center mb-4">
                        <h2> <i class="bi bi-person-plus-fill"></i>  Registro de Usuario</h2>
                        <p class="text-muted">Complete todos los campos para crear su cuenta</p>
                        </div>
                    </div>

                    <form id="registroForm" enctype="multipart/form-data">
                        <!-- Información Personal -->
                        <h5 class="section-title">
                            <i class="fas fa-user me-2"></i>Información Personal
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario_nom1" class="form-label">Primer Nombre </label>
                                <input type="text" class="form-control" id="usuario_nom1" name="usuario_nom1" required maxlength="50">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario_nom2" class="form-label">Segundo Nombre</label>
                                <input type="text" class="form-control" id="usuario_nom2" name="usuario_nom2" required maxlength="50">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario_ape1" class="form-label">Primer Apellido </label>
                                <input type="text" class="form-control" id="usuario_ape1" name="usuario_ape1" required maxlength="50">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario_ape2" class="form-label">Segundo Apellido </label>
                                <input type="text" class="form-control" id="usuario_ape2" name="usuario_ape2" required maxlength="50">
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <h5 class="section-title mt-4">
                            <i class="fas fa-address-book me-2"></i>Información de Contacto
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario_tel" class="form-label">Teléfono </label>
                                <input type="tel" class="form-control" id="usuario_tel" name="usuario_tel" required placeholder="12345678">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario_correo" class="form-label">Correo Electrónico </label>
                                <input type="email" class="form-control" id="usuario_correo" name="usuario_correo" required maxlength="50">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="usuario_direc" class="form-label">Dirección </label>
                            <textarea class="form-control" id="usuario_direc" name="usuario_direc" rows="3" required maxlength="150"></textarea>
                        </div>

                        <!-- Información de Identificación -->
                        <h5 class="section-title mt-4">
                            <i class="fas fa-id-card me-2"></i>Información de Identificación
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario_dpi" class="form-label">DPI </label>
                                <input type="text" class="form-control" id="usuario_dpi" name="usuario_dpi" required maxlength="13" placeholder="1234567890123">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="usuario_contra" class="form-label">Contraseña </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="usuario_contra" name="usuario_contra" required minlength="10">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Mínimo 10 caracteres, 1 mayuscula y un signo (,.)</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="confirmar_contra" class="form-label">Confirmar Contraseña </label>
                                <input type="password" class="form-control" id="confirmar_contra" name="confirmar_contra" required>
                            </div>
                        </div>

                        <!-- Fotografía -->
                        <h5 class="section-title mt-4">
                            <i class="fas fa-camera me-2"></i>Fotografía (Opcional)
                        </h5>
                        <div class="mb-3">
                            <div class="photo-upload" onclick="document.getElementById('usuario_fotografia').click()">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                <p class="mb-0">Haga clic para subir una fotografía</p>
                                <small class="text-muted">JPG, PNG o GIF (máx. 2MB)</small>
                            </div>
                            <input type="file" class="form-control d-none" id="usuario_fotografia" name="usuario_fotografia" accept="image/*">
                            <div id="photoPreview"></div>
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="button" class="btn btn-secondary me-md-2" onclick="limpiarFormulario()">
                                <i class="fas fa-broom me-2"></i>Limpiar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Registrar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= asset('build/js/usuarios/index.js') ?>"></script>