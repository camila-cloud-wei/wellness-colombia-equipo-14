# wellness-colombia-equipo-14

Esta es una pequeña web con un formulario de contacto y un panel de administración.

Instrucciones para configurar la autenticación por base de datos (MySQL):

1) Crear la tabla `users` para los administradores (ejecuta en tu base de datos):

```sql
CREATE TABLE IF NOT EXISTS users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(100) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

2) Insertar un usuario administrador (reemplaza `tu_password` por el que quieras). Genera el hash con PHP:

```php
<?php
echo password_hash('tu_password', PASSWORD_DEFAULT);
```

Luego inserta el usuario con el hash resultante:

```sql
INSERT INTO users (username, password) VALUES ('admin', 'HASH_AQUI');
```

3) Archivos relevantes:
- `db.php` - Conexión centralizada a la base de datos (usa las credenciales que ya están en `index.php`/`admin.php`).
- `login.php` - Ahora verifica credenciales contra la tabla `users` usando `password_verify`.
- `admin.php` - Lista contactos; requiere sesión iniciada.

4) Probar localmente:
- Sube los cambios al servidor o configura un entorno PHP + MySQL local.
- Asegúrate de que `db.php` contiene las credenciales correctas.
- Crea el usuario admin como se indicó y abre `login.php` en el navegador.

Notas de seguridad:
- No almacenes contraseñas en texto plano. Usa `password_hash` y `password_verify`.
- Limita los intentos de login en producción y considera usar HTTPS.

## Assets movidos a static/

Se movieron el CSS (extraído de `index.php`) y el JavaScript a la carpeta `static/` para simular un almacenamiento de objetos en la nube (S3/Blob). Actualiza cualquier referencia local a imágenes o logos si los mueves también a `static/`.
