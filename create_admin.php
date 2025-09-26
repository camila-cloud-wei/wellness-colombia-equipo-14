<?php
// create_admin.php
// Script sencillo para crear un usuario administrador en la tabla `users`.
// Puede ejecutarse desde la CLI: php create_admin.php username password
// O desde el navegador con un formulario POST.

require_once __DIR__ . '/db.php';

function create_admin_user($username, $password, $mysqli) {
    $username = trim($username);
    if ($username === '' || $password === '') {
        return ['ok' => false, 'msg' => 'Usuario y contraseña son requeridos.'];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    if (!$stmt) {
        return ['ok' => false, 'msg' => 'Error en la preparación de la consulta: ' . $mysqli->error];
    }
    $stmt->bind_param('ss', $username, $hash);
    if ($stmt->execute()) {
        $stmt->close();
        return ['ok' => true, 'msg' => 'Usuario creado correctamente.'];
    }
    $err = $stmt->error;
    $stmt->close();
    if (stripos($err, 'duplicate') !== false) {
        return ['ok' => false, 'msg' => 'El usuario ya existe.'];
    }
    return ['ok' => false, 'msg' => 'Error al crear usuario: ' . $err];
}

// CLI mode
if (php_sapi_name() === 'cli') {
    global $argv;
    if (!isset($argv[1]) || !isset($argv[2])) {
        fwrite(STDOUT, "Uso: php create_admin.php usuario contraseña\n");
        exit(1);
    }
    $user = $argv[1];
    $pass = $argv[2];
    $res = create_admin_user($user, $pass, $mysqli);
    fwrite(STDOUT, $res['msg'] . "\n");
    exit($res['ok'] ? 0 : 1);
}

// Web form mode
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $res = create_admin_user($user, $pass, $mysqli);
    $message = $res['msg'];
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Crear administrador</title>
  <style> body{font-family:Segoe UI, Arial; padding:20px;background:#f8f9fa} form{background:#fff;padding:20px;border-radius:8px;max-width:420px} input{width:100%;padding:10px;margin:8px 0;border-radius:6px;border:1px solid #ccc} button{padding:10px 14px;background:#2e8b57;color:#fff;border:none;border-radius:6px;cursor:pointer} .msg{margin-top:12px;padding:10px;border-radius:6px;background:#fff}</style>
</head>
<body>
  <h2>Crear usuario administrador</h2>
  <p>Usa este formulario para crear un admin. Alternativamente ejecuta <code>php create_admin.php usuario contraseña</code> en CLI.</p>
  <form method="post">
    <label>Usuario</label>
    <input type="text" name="username" required>
    <label>Contraseña</label>
    <input type="password" name="password" required>
    <button type="submit">Crear</button>
  </form>
  <?php if ($message !== ''): ?>
  <div class="msg"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>
</body>
</html>
