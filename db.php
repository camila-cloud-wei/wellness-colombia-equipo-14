<?php
// db.php - conexión centralizada a la base de datos
// Ajusta las credenciales si las cambias en tu hosting
$db_host = 'sql302.infinityfree.com';
$db_user = 'if0_40020474';
$db_pass = 'aoGZOI4wU74i3';
$db_name = 'if0_40020474_travel_db';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
    error_log('DB connect error: ' . $mysqli->connect_error);
    die('Error en la conexión a la base de datos.');
}

// Establecer charset
$mysqli->set_charset('utf8mb4');

?>
