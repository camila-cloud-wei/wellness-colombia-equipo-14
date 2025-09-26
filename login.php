<?php
session_start();

// Credenciales (puedes cambiarlas)
$admin_user = "admin";
$admin_pass = "Colombia2025!";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["usuario"];
    $pass = $_POST["clave"];

    if ($user === $admin_user && $pass === $admin_pass) {
        $_SESSION["loggedin"] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
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
