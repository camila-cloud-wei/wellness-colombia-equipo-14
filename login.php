<?php
session_start();
require_once __DIR__ . '/db.php';

// Espera una tabla `users` con campos id, username, password (hashed)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user = isset($_POST["usuario"]) ? trim($_POST["usuario"]) : '';
  $pass = isset($_POST["clave"]) ? $_POST["clave"] : '';

    if ($user === '' || $pass === '') {
    $error = "Usuario o contraseña incorrectos.";
  } else {
    // Traer también el campo `role` desde la tabla users
    $stmt = $mysqli->prepare('SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1');
    if ($stmt) {
      $stmt->bind_param('s', $user);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($row = $result->fetch_assoc()) {
        // password almacenada debe ser un hash (password_hash)
        if (password_verify($pass, $row['password'])) {
          // Autenticación exitosa
          $_SESSION["loggedin"] = true;
          $_SESSION["user_id"] = $row['id'];
          $_SESSION["username"] = $row['username'];
          // Guardar rol en sesión; por defecto 'user' si no existe
          $_SESSION["role"] = isset($row['role']) && $row['role'] !== '' ? $row['role'] : 'user';
          header("Location: admin.php");
          exit();
        } else {
          $error = "Usuario o contraseña incorrectos.";
        }
      } else {
        $error = "Usuario o contraseña incorrectos.";
      }
      $stmt->close();
    } else {
      error_log('DB prepare error: '. $mysqli->error);
      $error = 'Error interno, inténtalo de nuevo más tarde.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Acceso Administrador</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: #f0f9f7;
    }
    form {
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
      width: 320px;
    }
    h2 {
      margin-bottom: 20px;
      color: #2e8b57;
    }
    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #2e8b57;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
    }
    button:hover {
      background: #246b45;
    }
    .error {
      color: red;
      margin-top: 10px;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <form method="POST">
    <h2>Ingreso Seguro</h2>
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="clave" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
  </form>
</body>
</html>
