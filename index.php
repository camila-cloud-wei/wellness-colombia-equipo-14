<?php
// Guardar datos en la base
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "sql302.infinityfree.com"; // Replace with your InfinityFree host
    $username   = "if0_40020474"; 
    $password   = "aoGZOI4wU74i3"; 
    $dbname     = "if0_40020474_travel_db";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }

    // Preparar e insertar
    $stmt = $conn->prepare("INSERT INTO contactos (nombre, email, telefono, servicio, mensaje) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $email, $telefono, $servicio, $mensaje);

    $nombre   = $_POST["nombre"];
    $email    = $_POST["email"];
    $telefono = $_POST["telefono"];
    $servicio = $_POST["servicio"];
    $mensaje  = $_POST["mensaje"];

    $stmt->execute();

    $stmt->close();
    $conn->close();

    header("Location: gracias.html");
    exit();
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
    <style>
        /* Reset and Base Styles */
        :root {
            --primary: #0a2e2a;
            --secondary: #1a5d57;
            --accent: #d4af37;
            --light: #f8f9fa;
            --dark: #2c2c2c;
            --gray: #6c757d;
            --transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.12);
            --gold-gradient: linear-gradient(135deg, #d4af37 0%, #f9e076 100%);
            --emerald-gradient: linear-gradient(135deg, #0a2e2a 0%, #1a5d57 100%);
            --texture-overlay: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%230a2e2a" opacity="0.03"/><path d="M0,0 L100,100 M100,0 L0,100" stroke="%23d4af37" stroke-width="0.5" opacity="0.05"/></svg>');
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.7;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: var(--texture-overlay);
            pointer-events: none;
            z-index: -1;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            line-height: 1.3;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-padding {
            padding: 100px 0;
        }

        .section-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--accent), transparent);
            margin: 60px auto;
            width: 80%;
            max-width: 400px;
        }

        .btn {
            display: inline-block;
            background: var(--emerald-gradient);
            color: white;
            padding: 16px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            text-align: center;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gold-gradient);
            transition: var(--transition);
            z-index: -1;
        }

        .btn:hover::before {
            left: 0;
        }

        .btn:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            color: var(--primary);
        }

        .btn-accent {
            background: var(--gold-gradient);
            color: var(--primary);
        }

        .btn-accent::before {
            background: var(--emerald-gradient);
        }

        .btn-accent:hover {
            color: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 20px;
            color: var(--primary);
            font-size: 2.8rem;
            position: relative;
            padding-bottom: 20px;
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .section-title.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--accent);
        }

        .section-subtitle {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 60px;
            color: var(--gray);
            font-size: 1.2rem;
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease 0.2s, transform 0.8s ease 0.2s;
        }

        .section-subtitle.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, rgba(10, 46, 42, 0.85) 0%, rgba(26, 93, 87, 0.7) 100%), 
                        url('https://images.unsplash.com/photo-1552422535-c45813c61732?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="%23f8f9fa" opacity="0.03"/></svg>');
            background-size: cover;
        }

        .header-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 2;
            opacity: 0;
            transform: translateY(50px);
            animation: fadeInUp 1s ease 0.5s forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: 2px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            position: relative;
            display: inline-block;
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--accent);
        }

        .tagline {
            font-size: 1.4rem;
            font-weight: 300;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .header-btns {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }

        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 1.5rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(-50%) translateY(0);
            }
            40% {
                transform: translateX(-50%) translateY(-10px);
            }
            60% {
                transform: translateX(-50%) translateY(-5px);
            }
        }

        /* Navigation */
        nav {
            background: rgba(10, 46, 42, 0.95);
            padding: 20px 0;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        nav.scrolled {
            padding: 15px 0;
            background: rgba(10, 46, 42, 0.98);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .nav-logo span {
            color: var(--accent);
        }

        .nav-menu {
            display: flex;
            list-style: none;
        }

        .nav-menu li {
            margin-left: 30px;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            padding: 5px 0;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: var(--transition);
        }

        .nav-menu a:hover::after {
            width: 100%;
        }

        .nav-menu a:hover {
            color: var(--accent);
        }

        .hamburger {
            display: none;
            cursor: pointer;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
        }

        /* About Section */
        .about {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23f8f9fa"/><path d="M0,0 Q50,20 100,0 L100,100 Q50,80 0,100 Z" fill="%23ffffff"/></svg>');
            background-size: cover;
            position: relative;
        }

        .about-content {
            display: flex;
            align-items: center;
            gap: 60px;
        }

        .about-text {
            flex: 1;
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .about-text.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .about-text p {
            margin-bottom: 25px;
            font-size: 1.1rem;
            position: relative;
            padding-left: 20px;
        }

        .about-text p::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10px;
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
        }

        .about-image {
            flex: 1;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.8s ease, transform 0.8s ease;
            position: relative;
        }

        .about-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gold-gradient);
            opacity: 0;
            transition: var(--transition);
            z-index: 1;
        }

        .about-image.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .about-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.8s ease;
        }

        .about-image:hover img {
            transform: scale(1.05);
        }

        .about-image:hover::before {
            opacity: 0.1;
        }

        /* Services Section */
        .services {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            position: relative;
        }

        .services::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23f8f9fa"/><path d="M0,0 Q50,20 100,0 L100,100 Q50,80 0,100 Z" fill="%23ffffff"/></svg>');
            background-size: cover;
            opacity: 0.5;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            position: relative;
            z-index: 1;
        }

        .service-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            opacity: 0;
            transform: translateY(50px);
            position: relative;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gold-gradient);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.6s ease;
        }

        .service-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .service-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow-hover);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-image {
            height: 220px;
            overflow: hidden;
            position: relative;
        }

        .service-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 0%, rgba(10, 46, 42, 0.3) 100%);
        }

        .service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
        }

        .service-card:hover .service-image img {
            transform: scale(1.1);
        }

        .service-content {
            padding: 30px;
        }

        .service-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--primary);
            display: flex;
            align-items: center;
        }

        .service-title i {
            margin-right: 10px;
            color: var(--accent);
            font-size: 1.8rem;
        }

        /* Statistics Section */
        .statistics {
            background: var(--emerald-gradient);
            color: white;
            text-align: center;
            position: relative;
        }

        .statistics::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="%23ffffff" opacity="0.03"/></svg>');
            background-size: cover;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .stat-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--accent);
        }

        .stat-label {
            font-size: 1.2rem;
            font-weight: 500;
        }

        /* Testimonials Section */
        .testimonials {
            background: white;
            position: relative;
        }

        .testimonials::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%23ffffff"/><path d="M0,0 Q50,20 100,0 L100,100 Q50,80 0,100 Z" fill="%23f8f9fa"/></svg>');
            background-size: cover;
            opacity: 0.5;
        }

        .testimonial-carousel {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: var(--shadow);
            background: white;
            position: relative;
            z-index: 1;
        }

        .testimonial-slide {
            display: none;
            padding: 40px;
            text-align: center;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .testimonial-slide.active {
            display: block;
        }

        .testimonial-text {
            font-size: 1.2rem;
            font-style: italic;
            margin-bottom: 30px;
            position: relative;
            padding: 0 20px;
        }

        .testimonial-text::before, .testimonial-text::after {
            content: '"';
            font-size: 4rem;
            color: var(--accent);
            position: absolute;
            opacity: 0.3;
        }

        .testimonial-text::before {
            top: -30px;
            left: -20px;
        }

        .testimonial-text::after {
            bottom: -50px;
            right: -20px;
        }

        .testimonial-author {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .testimonial-role {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .carousel-controls {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ddd;
            margin: 0 5px;
            cursor: pointer;
            transition: var(--transition);
        }

        .carousel-dot.active {
            background: var(--accent);
            transform: scale(1.2);
        }

        /* Contact Form Section */
        .contact-form {
            background: linear-gradient(135deg, rgba(10, 46, 42, 0.95) 0%, rgba(26, 93, 87, 0.9) 100%), 
                        url('https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            position: relative;
        }

        .contact-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="%23d4af37" opacity="0.03"/></svg>');
            background-size: cover;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 50px;
            box-shadow: var(--shadow-hover);
            backdrop-filter: blur(10px);
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease, transform 0.8s ease;
            position: relative;
            z-index: 1;
        }

        .form-container.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .form-title {
            color: var(--primary);
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.2rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary);
            font-weight: 500;
            transition: var(--transition);
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            transition: var(--transition);
            background: #f8f9fa;
        }

        .form-control:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            background: white;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        /* Footer */
        footer {
            background: var(--primary);
            color: white;
            padding: 80px 0 30px;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="%23d4af37" opacity="0.03"/></svg>');
            background-size: cover;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 50px;
            position: relative;
            z-index: 1;
        }

        .footer-column h3 {
            font-size: 1.5rem;
            margin-bottom: 25px;
            font-weight: 600;
            position: relative;
            padding-bottom: 15px;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--accent);
        }

        .footer-column p, .footer-column a {
            color: #ccc;
            margin-bottom: 15px;
            display: block;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-column a:hover {
            color: var(--accent);
            transform: translateX(5px);
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: var(--transition);
            font-size: 1.2rem;
        }

        .social-icons a:hover {
            background: var(--accent);
            transform: translateY(-5px);
        }

        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #ccc;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--accent);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 999;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: #e6c158;
            transform: translateY(-5px);
        }

        /* Loading Animation */
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-spinner {
            width: 70px;
            height: 70px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--accent);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Certificate Badges */
        .certificates {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .certificate-badge {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .certificate-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gold-gradient);
            opacity: 0.1;
            transition: var(--transition);
        }

        .certificate-badge:hover::before {
            left: 0;
        }

        .certificate-badge:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .certificate-badge i {
            font-size: 2rem;
            color: var(--accent);
            position: relative;
            z-index: 1;
        }

        .certificate-text {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--primary);
            position: relative;
            z-index: 1;
        }

        /* Floating Labels for Form */
        .floating-label-group {
            position: relative;
            margin-bottom: 25px;
        }

        .floating-label {
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 1rem;
            color: var(--gray);
            pointer-events: none;
            transition: var(--transition);
            background: #f8f9fa;
            padding: 0 5px;
        }

        .form-control:focus ~ .floating-label,
        .form-control:not(:placeholder-shown) ~ .floating-label {
            top: -10px;
            left: 15px;
            font-size: 0.8rem;
            color: var(--accent);
            background: white;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .about-content {
                flex-direction: column;
            }
            
            .about-text, .about-image {
                transform: translateY(30px);
            }
            
            .about-text.visible, .about-image.visible {
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .nav-menu {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background: var(--primary);
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                padding-top: 50px;
                transition: var(--transition);
            }
            
            .nav-menu.active {
                left: 0;
            }
            
            .nav-menu li {
                margin: 20px 0;
            }
            
            .hamburger {
                display: block;
            }
            
            .header-btns {
                flex-direction: column;
                align-items: center;
            }
            
            .logo {
                font-size: 3rem;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Parallax Effect */
        .parallax {
            background-attachment: fixed;
        }

        /* Treatment Timeline */
        .timeline {
            display: flex;
            justify-content: space-between;
            margin: 60px 0;
            position: relative;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 30px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--accent);
        }

        .timeline-step {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .timeline-step::before {
            content: '';
            position: absolute;
            top: 24px;
            left: 50%;
            transform: translateX(-50%);
            width: 16px;
            height: 16px;
            background: var(--accent);
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 0 0 2px var(--accent);
        }

        .timeline-icon {
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 10px;
        }

        .timeline-text {
            font-size: 0.9rem;
            margin-top: 40px;
        }
    </style>
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
            </ul>
        </div>
    </nav>

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
                    <img src="https://images.unsplash.com/photo-1551601651-2a8555f1a136?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Wellness in Colombia">
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
                        <img src="https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Medicina Estética">
                    </div>
                    <div class="service-content">
                        <h3 class="service-title"><i class="fas fa-spa"></i> Medicina Estética Avanzada</h3>
                        <p>Tratamientos no invasivos y mínimamente invasivos para realzar tu belleza natural. Desde rejuvenecimiento facial hasta moldeamiento corporal con tecnología de vanguardia.</p>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-image">
                        <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Rehabilitación">
                    </div>
                    <div class="service-content">
                        <h3 class="service-title"><i class="fas fa-heartbeat"></i> Programas de Rehabilitación</h3>
                        <p>Recuperación integral después de procedimientos médicos o cirugías, combinando fisioterapia, terapias alternativas y entornos naturales que aceleran la sanación.</p>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-image">
                        <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Bienestar Mental">
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
                <form action="" method="POST">
                    <div class="form-group floating-label-group">
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder=" " required>
                        <label for="nombre" class="floating-label">Nombre Completo</label>
                    </div>
                    
                    <div class="form-group floating-label-group">
                        <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                        <label for="email" class="floating-label">Correo Electrónico</label>
                    </div>
                    
                    <div class="form-group floating-label-group">
                        <input type="tel" class="form-control" id="telefono" name="telefono" placeholder=" ">
                        <label for="telefono" class="floating-label">Teléfono (Opcional)</label>
                    </div>
                    
                    <div class="form-group">
                        <label for="servicio">Servicio de Interés</label>
                        <select class="form-control" id="servicio" name="servicio" required>
                            <option value="" disabled selected>Selecciona un servicio</option>
                            <option value="medicina-estetica">Medicina Estética Avanzada</option>
                            <option value="rehabilitacion">Programas de Rehabilitación</option>
                            <option value="bienestar-mental">Bienestar Mental y Emocional</option>
                            <option value="otros">Otros Servicios</option>
                        </select>
                    </div>
                    
                    <div class="form-group floating-label-group">
                        <textarea class="form-control" id="mensaje" name="mensaje" placeholder=" " rows="5"></textarea>
                        <label for="mensaje" class="floating-label">Mensaje (Opcional)</label>
                    </div>
                    
                    <button type="submit" class="btn btn-accent" style="width: 100%;">Enviar Solicitud</button>
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

    <script>
        // Wait for the page to load
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loader after page load
            setTimeout(function() {
                document.getElementById('loader').classList.add('hidden');
            }, 1500);
            
            // Mobile menu toggle
            const hamburger = document.getElementById('hamburger');
            const navMenu = document.getElementById('navMenu');
            
            hamburger.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                hamburger.innerHTML = navMenu.classList.contains('active') ? 
                    '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
            });
            
            // Close mobile menu when clicking on a link
            const navLinks = document.querySelectorAll('.nav-menu a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navMenu.classList.remove('active');
                    hamburger.innerHTML = '<i class="fas fa-bars"></i>';
                });
            });
            
            // Sticky navigation
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 100) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
            
            // Back to top button
            const backToTop = document.getElementById('backToTop');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTop.classList.add('visible');
                } else {
                    backToTop.classList.remove('visible');
                }
            });
            
            backToTop.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Testimonial carousel
            const slides = document.querySelectorAll('.testimonial-slide');
            const dots = document.querySelectorAll('.carousel-dot');
            let currentSlide = 0;
            
            function showSlide(n) {
                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));
                
                currentSlide = (n + slides.length) % slides.length;
                slides[currentSlide].classList.add('active');
                dots[currentSlide].classList.add('active');
            }
            
            dots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    showSlide(index);
                });
            });
            
            // Auto-advance carousel
            setInterval(function() {
                showSlide(currentSlide + 1);
            }, 5000);
            
            // Animated statistics
            const statItems = document.querySelectorAll('.stat-item');
            let statsAnimated = false;
            
            function animateStats() {
                if (statsAnimated) return;
                
                const statsSection = document.querySelector('.statistics');
                const sectionTop = statsSection.offsetTop;
                const sectionHeight = statsSection.offsetHeight;
                const scrollPosition = window.scrollY + window.innerHeight;
                
                if (scrollPosition > sectionTop + sectionHeight / 2) {
                    statItems.forEach(item => {
                        const numberElement = item.querySelector('.stat-number');
                        const target = parseInt(numberElement.getAttribute('data-count'));
                        let current = 0;
                        const increment = target / 100;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            numberElement.textContent = Math.floor(current);
                        }, 20);
                    });
                    
                    statsAnimated = true;
                }
            }
            
            // Scroll animations for sections
            function checkScroll() {
                const sections = document.querySelectorAll('.section-title, .section-subtitle, .about-text, .about-image, .service-card, .stat-item, .form-container');
                
                sections.forEach(section => {
                    const sectionTop = section.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;
                    
                    if (sectionTop < windowHeight - 100) {
                        section.classList.add('visible');
                    }
                });
                
                animateStats();
            }
            
            window.addEventListener('scroll', checkScroll);
            checkScroll(); // Check on initial load
            
            // Form validation enhancement
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input, textarea, select');
            
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() !== '') {
                        this.classList.add('filled');
                    } else {
                        this.classList.remove('filled');
                    }
                });
            });
            
            // Parallax effect for header
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const parallaxElements = document.querySelectorAll('.parallax');
                
                parallaxElements.forEach(element => {
                    const speed = 0.5;
                    element.style.transform = `translateY(${scrolled * speed}px)`;
                });
            });
        });
    </script>
</body>
</html>