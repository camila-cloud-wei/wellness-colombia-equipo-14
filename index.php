<?php
session_start();
// Guardar datos en la base
// Para depuración temporal activa $debug = true. Desactiva en producción.
$debug = true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once __DIR__ . '/db.php'; // $mysqli disponible

    // Recoger y sanitizar entradas básicas
    $nombre   = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : '';
    $email    = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $telefono = isset($_POST["telefono"]) ? trim($_POST["telefono"]) : '';
    $servicio = isset($_POST["servicio"]) ? trim($_POST["servicio"]) : '';
    $mensaje  = isset($_POST["mensaje"]) ? trim($_POST["mensaje"]) : '';
    $contrasena_plain = isset($_POST["contrasena"]) ? $_POST["contrasena"] : '';
    $contrasena_hash = $contrasena_plain !== '' ? password_hash($contrasena_plain, PASSWORD_DEFAULT) : null;

    // CSRF token check
    if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'Token de seguridad inválido. Intenta recargar la página y enviar de nuevo.';
    } else {
        $errors = [];
        $field_errors = [];

        // Validaciones obligatorias (con errores por campo)
        if ($nombre === '') {
            $field_errors['nombre'] = 'El nombre es requerido.';
            $errors[] = 'El nombre es requerido.';
        }
        if ($email === '') {
            $field_errors['email'] = 'El correo es requerido.';
            $errors[] = 'El correo es requerido.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $field_errors['email'] = 'El correo no tiene un formato válido.';
            $errors[] = 'El correo no tiene un formato válido.';
        }
        if ($servicio === '' || $servicio === null) {
            $field_errors['servicio'] = 'Debes seleccionar un servicio.';
            $errors[] = 'Debes seleccionar un servicio.';
        }

        // Teléfono opcional pero si se envía validar caracteres (mínimo 6 dígitos)
        if ($telefono !== '') {
            // Permite dígitos, espacios, +, -, paréntesis
            if (!preg_match('/^[0-9\+\-\s\(\)]{6,20}$/', $telefono)) {
                $field_errors['telefono'] = 'El teléfono no tiene un formato válido.';
                $errors[] = 'El teléfono no tiene un formato válido.';
            }
        }

        // Contraseña opcional pero si viene, exigir longitud mínima
        if ($contrasena_plain !== '' && strlen($contrasena_plain) < 6) {
            $field_errors['contrasena'] = 'La contraseña debe tener al menos 6 caracteres.';
            $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
        }

        if (!empty($errors)) {
            // No continuar con la inserción si hay errores
            $error = implode("<br>", $errors);
        } else {
            // Defensive guard: double-check required fields before attempting DB insert
            if ($nombre === '' || $email === '' || $servicio === '') {
                // Populate field_errors if somehow missing and prevent insert
                if ($nombre === '') $field_errors['nombre'] = 'El nombre es requerido.';
                if ($email === '') $field_errors['email'] = 'El correo es requerido.';
                if ($servicio === '' || $servicio === null) $field_errors['servicio'] = 'Debes seleccionar un servicio.';
                $error = 'Faltan campos obligatorios. Corrige los errores y vuelve a intentar.';
                error_log('Form not inserted: required fields missing.');
            } else {
            // Preparar e insertar con manejo de errores (prepared statements para evitar SQL injection)
            $stmt = $mysqli->prepare("INSERT INTO contactos (nombre, email, contrasena, telefono, servicio, mensaje) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                $err = $mysqli->error;
                error_log('DB prepare error: ' . $err);
                if ($debug) {
                    // Mostrar error en pantalla sólo en debugging
                    $error = 'Error en la preparación de la consulta: ' . $err;
                } else {
                    $error = 'Error interno. Intenta de nuevo más tarde.';
                }
            } else {
                // Start transaction so both inserts/updates (contactos + users) succeed together
                $mysqli->begin_transaction();
                $ok = true;

                if (!$stmt->bind_param("ssssss", $nombre, $email, $contrasena_hash, $telefono, $servicio, $mensaje)) {
                    $err = $stmt->error;
                    error_log('DB bind_param error: ' . $err);
                    $ok = false;
                }

                if ($ok && !$stmt->execute()) {
                    $err = $stmt->error;
                    error_log('DB execute error (contactos): ' . $err);
                    $ok = false;
                }

                if ($ok) {
                    // If contact insert succeeded, proceed to create/update users table
                    $contact_id = $mysqli->insert_id;

                    // Decide password for users: if none provided, generate a random one
                    if ($contrasena_plain === '') {
                        try {
                            $contrasena_plain = bin2hex(random_bytes(6));
                        } catch (Exception $e) {
                            // fallback
                            $contrasena_plain = substr(md5(uniqid((string)mt_rand(), true)), 0, 12);
                        }
                        $contrasena_hash = password_hash($contrasena_plain, PASSWORD_DEFAULT);
                    }

                    // Check if user exists by username=email
                    $ucheck = $mysqli->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
                    if ($ucheck) {
                        $ucheck->bind_param('s', $email);
                        $ucheck->execute();
                        $ures = $ucheck->get_result();
                        if ($urow = $ures->fetch_assoc()) {
                            // Update password only if the form provided one
                            if (isset($_POST['contrasena']) && $_POST['contrasena'] !== '') {
                                $uid = $urow['id'];
                                $uupdate = $mysqli->prepare('UPDATE users SET password = ? WHERE id = ?');
                                if ($uupdate) {
                                    $uupdate->bind_param('si', $contrasena_hash, $uid);
                                    if (!$uupdate->execute()) {
                                        error_log('DB execute error (users update): ' . $uupdate->error);
                                        $ok = false;
                                    }
                                    $uupdate->close();
                                } else {
                                    error_log('DB prepare error (users update): ' . $mysqli->error);
                                    $ok = false;
                                }
                            }
                        } else {
                            // Insert new user (username = email)
                            $uinsert = $mysqli->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
                            if ($uinsert) {
                                $uinsert->bind_param('ss', $email, $contrasena_hash);
                                if (!$uinsert->execute()) {
                                    error_log('DB execute error (users insert): ' . $uinsert->error);
                                    $ok = false;
                                }
                                $uinsert->close();
                            } else {
                                error_log('DB prepare error (users insert): ' . $mysqli->error);
                                $ok = false;
                            }
                        }
                        $ucheck->close();
                    } else {
                        error_log('DB prepare error (users check): ' . $mysqli->error);
                        $ok = false;
                    }
                }

                if ($ok) {
                    $stmt->close();
                    $mysqli->commit();
                    $mysqli->close();
                    header("Location: gracias.html");
                    exit();
                } else {
                    $mysqli->rollback();
                    if (isset($stmt) && $stmt) $stmt->close();
                    $mysqli->close();
                    if ($debug) {
                        $error = 'Ocurrió un error al guardar los datos. Revisa los logs del servidor.';
                    } else {
                        $error = 'No se pudo guardar la información. Intenta de nuevo.';
                    }
                }
            }
            $mysqli->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness in Colombia - Turismo de Salud de Lujo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="static/style.css">
</head>
<body>
    <!-- Loading Animation -->
    <div class="loader" id="loader">
        <div class="loader-spinner"></div>
    </div>

    <!-- Back to Top Button -->
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </div>

    <!-- Header -->
    <header class="parallax">
        <div class="container">
            <div class="header-content">
                <h1 class="logo">WELLNESS IN COLOMBIA</h1>
                <p class="tagline">Descubre el equilibrio perfecto entre salud, bienestar y la belleza exótica de Colombia. Experiencias de lujo diseñadas para renovar tu cuerpo y alma.</p>
                <div class="header-btns">
                    <a href="#contacto" class="btn btn-accent">Solicitar Consulta</a>
                    <a href="#servicios" class="btn">Nuestros Servicios</a>
                </div>
            </div>
        </div>
        <div class="scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </div>
    </header>
    <!-- Navigation -->
    <nav id="navbar">
        <div class="container nav-container">
            <a href="#" class="nav-logo">Wellness<span>Colombia</span></a>
            <button class="hamburger" id="hamburger">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#nosotros">Nosotros</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#testimonios">Testimonios</a></li>
                <li><a href="#contacto">Contacto</a></li>
                <li><a href="login.php">Iniciar sesión</a></li>
            </ul>
        </div>
    </nav>

    <main style="flex:1">

    <!-- About Section -->
    <section id="nosotros" class="about section-padding">
        <div class="container">
            <h2 class="section-title">Bienvenido a Wellness in Colombia</h2>
            <p class="section-subtitle">Donde la excelencia médica se encuentra con la belleza natural para ofrecerte una experiencia de bienestar transformadora.</p>
            
            <div class="about-content">
                <div class="about-text">
                    <p>En Wellness in Colombia, fusionamos los últimos avances médicos con técnicas de bienestar ancestrales para crear programas personalizados que restauran el equilibrio entre cuerpo, mente y espíritu.</p>
                    <p>Nuestro equipo de especialistas altamente calificados trabaja en armonía con el entorno natural único de Colombia para ofrecer tratamientos que no solo mejoran tu salud, sino que también renuevan tu energía vital.</p>
                    <p>Cada experiencia está cuidadosamente diseñada para integrar terapias modernas con la sabiduría tradicional, creando un viaje de sanación que trasciende lo convencional y se convierte en una transformación de vida.</p>
                    <a href="#contacto" class="btn">Descubre Tu Programa Ideal</a>
                </div>
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3&s=PLACEHOLDER" alt="Wellness in Colombia">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="servicios" class="services section-padding">
        <div class="container">
            <h2 class="section-title">Nuestros Servicios de Excelencia</h2>
            <p class="section-subtitle">Programas integrales diseñados para abordar tus necesidades específicas de salud y bienestar con los más altos estándares de calidad.</p>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-image">
                        <img src="https://images.unsplash.com/photo-1549576490-b0b4831ef60a?q=80&w=1200&auto=format&fit=crop&ixlib=rb-4.0.3&s=PLACEHOLDER" alt="Medicina Estética">
                    </div>
                    <div class="service-content">
                        <h3 class="service-title"><i class="fas fa-spa"></i> Medicina Estética Avanzada</h3>
                        <p>Tratamientos no invasivos y mínimamente invasivos para realzar tu belleza natural. Desde rejuvenecimiento facial hasta moldeamiento corporal con tecnología de vanguardia.</p>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-image">
                        <img src="https://images.unsplash.com/photo-1526256262350-7da7584cf5eb?q=80&w=1200&auto=format&fit=crop&ixlib=rb-4.0.3&s=PLACEHOLDER" alt="Rehabilitación">
                    </div>
                    <div class="service-content">
                        <h3 class="service-title"><i class="fas fa-heartbeat"></i> Programas de Rehabilitación</h3>
                        <p>Recuperación integral después de procedimientos médicos o cirugías, combinando fisioterapia, terapias alternativas y entornos naturales que aceleran la sanación.</p>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-image">
                        <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?q=80&w=1200&auto=format&fit=crop&ixlib=rb-4.0.3&s=PLACEHOLDER" alt="Bienestar Mental">
                    </div>
                    <div class="service-content">
                        <h3 class="service-title"><i class="fas fa-brain"></i> Bienestar Mental y Emocional</h3>
                        <p>Terapias holísticas para reducir el estrés, mejorar el equilibrio emocional y fomentar un estado de paz interior duradero en entornos diseñados para la introspección.</p>
                    </div>
                </div>
            </div>
            
            <!-- Treatment Timeline -->
            <div class="timeline">
                <div class="timeline-step">
                    <div class="timeline-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="timeline-text">Consulta Inicial y Evaluación</div>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon"><i class="fas fa-user-md"></i></div>
                    <div class="timeline-text">Programa Personalizado</div>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon"><i class="fas fa-procedures"></i></div>
                    <div class="timeline-text">Tratamiento y Cuidados</div>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon"><i class="fas fa-heart"></i></div>
                    <div class="timeline-text">Seguimiento Post-Tratamiento</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="statistics section-padding">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" data-count="1500">0</div>
                    <div class="stat-label">Pacientes Satisfechos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="50">0</div>
                    <div class="stat-label">Especialistas Médicos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="12">0</div>
                    <div class="stat-label">Años de Experiencia</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-count="98">0</div>
                    <div class="stat-label">Tasa de Satisfacción</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonios" class="testimonials section-padding">
        <div class="container">
            <h2 class="section-title">Historias de Transformación</h2>
            <p class="section-subtitle">Descubre las experiencias de quienes han confiado en nosotros para su bienestar.</p>
            
            <div class="testimonial-carousel">
                <div class="testimonial-slide active">
                    <p class="testimonial-text">Mi experiencia con Wellness in Colombia superó todas mis expectativas. No solo recibí un tratamiento médico excepcional, sino que también encontré un espacio de paz y renovación que transformó mi perspectiva sobre la salud.</p>
                    <div class="testimonial-author">María González</div>
                    <div class="testimonial-role">Paciente de Medicina Regenerativa</div>
                </div>
                
                <div class="testimonial-slide">
                    <p class="testimonial-text">La combinación de tecnología médica avanzada con terapias naturales en entornos espectaculares hizo que mi proceso de rehabilitación fuera una experiencia enriquecedora. El equipo es altamente profesional y compasivo.</p>
                    <div class="testimonial-author">Carlos Rodríguez</div>
                    <div class="testimonial-role">Programa de Recuperación Post-Quirúrgica</div>
                </div>
                
                <div class="testimonial-slide">
                    <p class="testimonial-text">Wellness in Colombia no solo me ayudó a mejorar mi salud física, sino que también me enseñó herramientas valiosas para manejar el estrés y encontrar equilibrio en mi vida diaria. Una inversión que vale cada centavo.</p>
                    <div class="testimonial-author">Ana Martínez</div>
                    <div class="testimonial-role">Programa de Bienestar Integral</div>
                </div>
            </div>
            
            <div class="carousel-controls">
                <span class="carousel-dot active" data-slide="0"></span>
                <span class="carousel-dot" data-slide="1"></span>
                <span class="carousel-dot" data-slide="2"></span>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section id="contacto" class="contact-form section-padding parallax">
        <div class="container">
            <h2 class="section-title">Comienza Tu Viaje de Bienestar</h2>
            <p class="section-subtitle">Completa el formulario y nuestro equipo se pondrá en contacto contigo para diseñar un programa personalizado.</p>
            
            <div class="form-container">
                <h3 class="form-title">Solicitar Consulta</h3>
                        <?php
                        // Ensure CSRF token exists for the session
                        if (empty($_SESSION['csrf_token'])) {
                            try {
                                $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
                            } catch (Exception $e) {
                                $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(16));
                            }
                        }
                        // Helper to escape output
                        function e($v) { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
                        ?>
                        <form action="" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['csrf_token']); ?>">
                    <div class="form-group floating-label-group">
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder=" " required value="<?php echo e(isset($nombre) ? $nombre : ''); ?>" style="<?php echo isset($field_errors['nombre']) ? 'outline: 2px solid #e53935;' : ''; ?>">
                        <label for="nombre" class="floating-label">Nombre Completo</label>
                        <?php if (!empty($field_errors['nombre'])): ?>
                            <div class="field-error" style="color:#b00020; font-size:0.9rem; margin-top:6px"><?php echo e($field_errors['nombre']); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group floating-label-group">
                        <input type="email" class="form-control" id="email" name="email" placeholder=" " required value="<?php echo e(isset($email) ? $email : ''); ?>" style="<?php echo isset($field_errors['email']) ? 'outline: 2px solid #e53935;' : ''; ?>">
                        <label for="email" class="floating-label">Correo Electrónico</label>
                        <?php if (!empty($field_errors['email'])): ?>
                            <div class="field-error" style="color:#b00020; font-size:0.9rem; margin-top:6px"><?php echo e($field_errors['email']); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group floating-label-group">
                        <input type="tel" class="form-control" id="telefono" name="telefono" placeholder=" " value="<?php echo e(isset($telefono) ? $telefono : ''); ?>" style="<?php echo isset($field_errors['telefono']) ? 'outline: 2px solid #e53935;' : ''; ?>">
                        <label for="telefono" class="floating-label">Teléfono (Opcional)</label>
                        <?php if (!empty($field_errors['telefono'])): ?>
                            <div class="field-error" style="color:#b00020; font-size:0.9rem; margin-top:6px"><?php echo e($field_errors['telefono']); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="servicio">Servicio de Interés</label>
                        <select class="form-control" id="servicio" name="servicio" required>
                            <option value="" disabled <?php echo empty($servicio) ? 'selected' : ''; ?>>Selecciona un servicio</option>
                            <option value="medicina-estetica">Medicina Estética Avanzada</option>
                            <option value="rehabilitacion">Programas de Rehabilitación</option>
                            <option value="bienestar-mental">Bienestar Mental y Emocional</option>
                            <option value="otros">Otros Servicios</option>
                        </select>
                    </div>
                    
                    <div class="form-group floating-label-group">
                        <textarea class="form-control" id="mensaje" name="mensaje" placeholder=" " rows="5"><?php echo e(isset($mensaje) ? $mensaje : ''); ?></textarea>
                        <label for="mensaje" class="floating-label">Mensaje (Opcional)</label>
                        <?php if (!empty($field_errors['mensaje'])): ?>
                            <div class="field-error" style="color:#b00020; font-size:0.9rem; margin-top:6px"><?php echo e($field_errors['mensaje']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group floating-label-group">
                        <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder=" " style="<?php echo isset($field_errors['contrasena']) ? 'outline: 2px solid #e53935;' : ''; ?>">
                        <label for="contrasena" class="floating-label">Contraseña (Opcional)</label>
                        <?php if (!empty($field_errors['contrasena'])): ?>
                            <div class="field-error" style="color:#b00020; font-size:0.9rem; margin-top:6px"><?php echo e($field_errors['contrasena']); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-accent" style="width: 100%;">Enviar Solicitud</button>
                    <?php if (!empty($error)): ?>
                        <div class="form-error" style="color: #b00020; margin-top: 12px;">
                            <?php echo $error; // already escaped pieces via e() when injected above ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
            
            <!-- Certificate Badges -->
            <div class="certificates">
                <div class="certificate-badge">
                    <i class="fas fa-award"></i>
                    <div class="certificate-text">Certificación Internacional</div>
                </div>
                <div class="certificate-badge">
                    <i class="fas fa-stethoscope"></i>
                    <div class="certificate-text">Médicos Especializados</div>
                </div>
                <div class="certificate-badge">
                    <i class="fas fa-shield-alt"></i>
                    <div class="certificate-text">Estándares de Calidad</div>
                </div>
            </div>
        </div>
    </section>

    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Wellness in Colombia</h3>
                    <p>Líder en turismo de salud de lujo, fusionando excelencia médica con experiencias transformadoras en los entornos más bellos de Colombia.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Enlaces Rápidos</h3>
                    <a href="#inicio">Inicio</a>
                    <a href="#nosotros">Nosotros</a>
                    <a href="#servicios">Servicios</a>
                    <a href="#testimonios">Testimonios</a>
                    <a href="#contacto">Contacto</a>
                </div>
                
                <div class="footer-column">
                    <h3>Servicios</h3>
                    <a href="#">Medicina Estética</a>
                    <a href="#">Rehabilitación</a>
                    <a href="#">Bienestar Mental</a>
                    <a href="#">Programas Personalizados</a>
                    <a href="#">Consultas Virtuales</a>
                </div>
                
                <div class="footer-column">
                    <h3>Contacto</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Bogotá, Colombia</p>
                    <p><i class="fas fa-phone"></i> +57 1 234 5678</p>
                    <p><i class="fas fa-envelope"></i> info@wellnesscolombia.com</p>
                    <p><i class="fas fa-clock"></i> Lun - Vie: 9:00 - 18:00</p>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2023 Wellness in Colombia. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="static/script.js"></script>
</body>
</html>