<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

$servername = "sql302.infinityfree.com"; // Replace with your InfinityFree host
$username   = "if0_40020474"; 
$password   = "aoGZOI4wU74i3"; 
$dbname     = "if0_40020474_travel_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM contactos ORDER BY fecha DESC");
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
<?php $conn->close(); ?>
