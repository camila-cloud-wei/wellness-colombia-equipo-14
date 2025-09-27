<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("Location: login.php");
  exit();
}

require_once __DIR__ . '/db.php';

// Mostrar todos los registros si el usuario es 'admin@gmail.com', si no, mostrar solo sus propios registros (username == email)
$username = $_SESSION['username'] ?? null;
// Control por rol: leer `role` desde la sesión (se estableció en el login)
$role = $_SESSION['role'] ?? 'user';
if ($role === 'admin') {
  // Admin ve todos los registros
  $stmt = $mysqli->prepare("SELECT * FROM contactos ORDER BY fecha DESC");
  if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  } else {
    // Fallback: empty result set
    $result = $mysqli->prepare("SELECT * FROM contactos WHERE 0");
  }
} else {
  // Usuario normal: ver sólo sus propios contactos
  $stmt = $mysqli->prepare("SELECT * FROM contactos WHERE email = ? ORDER BY fecha DESC");
  if ($stmt) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  } else {
    // Fallback: empty result set
    $result = $mysqli->prepare("SELECT * FROM contactos WHERE 0");
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitudes recibidas</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 20px; }
    h1 { color: #2e8b57; }
    .logout { float: right; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; background: white; }
    th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
    th { background: #2e8b57; color: white; }
  </style>
</head>
<body>
  <a class="logout" href="logout.php">Cerrar sesión</a>
  <h1>Solicitudes de Contacto</h1>
  <table>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Email</th>
      <th>Teléfono</th>
      <th>Servicio</th>
      <th>Mensaje</th>
      <th>Fecha</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row["id"] ?></td>
      <td><?= htmlspecialchars($row["nombre"]) ?></td>
      <td><?= htmlspecialchars($row["email"]) ?></td>
      <td><?= htmlspecialchars($row["telefono"]) ?></td>
      <td><?= htmlspecialchars($row["servicio"]) ?></td>
      <td><?= htmlspecialchars($row["mensaje"]) ?></td>
      <td><?= $row["fecha"] ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
<?php $mysqli->close(); ?>
