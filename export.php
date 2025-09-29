<?php
session_start();
require_once __DIR__ . '/db.php';

// Control de acceso: sólo usuarios autenticados pueden exportar
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403);
    echo "Acceso denegado.";
    exit();
}

$username = $_SESSION['username'] ?? null;
$role = $_SESSION['role'] ?? 'user';

// Preparar consulta según rol
if ($role === 'admin') {
    $stmt = $mysqli->prepare("SELECT id, nombre, email, telefono, servicio, mensaje, fecha FROM contactos ORDER BY fecha DESC");
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        http_response_code(500);
        echo "Error preparando la consulta: " . $mysqli->error;
        exit();
    }
} else {
    $stmt = $mysqli->prepare("SELECT id, nombre, email, telefono, servicio, mensaje, fecha FROM contactos WHERE email = ? ORDER BY fecha DESC");
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        http_response_code(500);
        echo "Error preparando la consulta: " . $mysqli->error;
        exit();
    }
}

// Cabezeras para descarga
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="solicitudes_' . ($role === 'admin' ? 'all' : $username) . '.csv"');

// Abrir salida en buffer
$out = fopen('php://output', 'w');
if (!$out) {
    http_response_code(500);
    echo "No se pudo abrir la salida para escribir CSV.";
    exit();
}

// Encabezados CSV
fputcsv($out, ['ID', 'Nombre', 'Email', 'Teléfono', 'Servicio', 'Mensaje', 'Fecha']);

while ($row = $result->fetch_assoc()) {
    // Asegurar formato simple y no incluir etiquetas HTML
    $line = [
        $row['id'],
        $row['nombre'],
        $row['email'],
        $row['telefono'],
        $row['servicio'],
        $row['mensaje'],
        $row['fecha']
    ];
    fputcsv($out, $line);
}

fclose($out);
$mysqli->close();
exit();
