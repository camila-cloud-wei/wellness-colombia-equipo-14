
# 🌐 Proyecto Web en InfinityFree – Salud/Turismo

## 👥 Integrantes y Roles
- Camila Martínez López – Líder / Coordinador
- Luis Alejandro Espinal Arango / Jose David Arrieta Torres – Desarrollador Backend
- Luis Alejandro Espinal Arango – Desarrollador Frontend / UI
- Jose David Arrieta Torres – Administrador de Base de Datos (DBA)
- Camila Martínez López – DevOps / Deployment
- Diana Paola Mopan – QA / Tester
- Camila Martínez López / Luis Alejandro Espinal Arango – Documentador / Presentador

## 📖 Descripción del Proyecto
*Este proyecto es un sitio web para el sector turístico/salud o medical/tourism , que permite registrar datos de clientes existentes o potenciales a través de un formulario de contacto. La página permite que el usuario genera una contraseña con la que después se puede loguear a través del botón de Iniciar Sesión para ver sus contactos registrados; también permite el ingreso de un usuario con rol administrador que le permite ver todos los registros creados a través del formulario. 
Está desarrollado en PHP con base de datos MySQL y desplegado en InfinityFree.*

## 🚀 Instrucciones de Uso
1. Subir los archivos a la carpeta `htdocs` o `public_html` en InfinityFree.  
2. Configurar la conexión en `db_connect.php` con:
   - Host: `sql302.infinityfree.com`
   - Usuario: `if0_*********`
   - Contraseña: `*********`
   - Nombre de la base de datos: `if0_40020474_travel_db`
3. Ingresar al sitio desde la URL pública:  
   👉 https://wellnesscolombia.infinityfree.me/

## 🖼️ Evidencias de Despliegue
- URL del sitio: https://wellnesscolombia.infinityfree.me/
- Captura de phpMyAdmin mostrando ≥3 registros (`capturas/phpmyadmin.png`)  
- Captura del File Manager con archivos subidos (`capturas/filemanager.png`)  
- Captura del sitio funcionando (`capturas/sitio.png`)

## 📂 Archivos Entregados
- `codigo.zip` – Código completo del proyecto  
- `dump.sql` – Base de datos exportada  
- `qa-report.md` – Reporte de pruebas realizadas  
- Carpeta `capturas/` – Evidencias gráficas  

## 📝 Changelog (registro de cambios)
- Jose David Arrieta Torres / Luis Alejandro Espinal Arango – Implementó validaciones y seguridad con prepared statements.  
- Luis Alejandro Espinal Arango – Mejoró la interfaz y organizó assets en carpeta `static/`.  
- Camila Martínez López – Configuró la base de datos y generó `dump.sql`.  
- Camila Martínez López – Subió el proyecto al hosting InfinityFree.  
- Diana Paola Mopan – Realizó pruebas QA y documentó resultados.  
- Camila Martínez López – Redactó README.md y preparó presentación.  

## ❓ Preguntas de Reflexión (Cloud)
1. ¿Qué es despliegue y cómo lo hicieron en este proyecto?  
   > - Despliegue se puede definir como la acción de disponibilizar recursos ya compilados y con conexiones entre sí para el uso de usuarios finales en un servidor.
    > - Para este proyecto utilizamos un repositorio de GitHub como fuente de la verdad y trabajo colaborativo, todo el código del proyecto se almacenó en el repositorio.
    > - Desde las herramientas de InfinityFree se subieron los archivos a través de su gestor gráfico de FTP y se generaron los esquemas de base de datos MySQL a través de scripts         ejecutados desde del cliente gráfico PHPMyAdmin.

2. ¿Qué limitaciones encontraron en InfinityFree?  
   > - **Limitación sobre la seguridad del sitio y disponibilidad**:  se hicieron 5000 peticiones con 500 peticiones concurrentes y la IP pública desde la que se hicieron las peticiones quedó                  bloqueada totalmente de alguna forma en InfinityFree. No se tiene control sobre logs o alguna administración para levantar este bloqueo en caso de que sea un falso positivo.
     > - **Limitación de almacenamiento**: El tier free ofrece solo 5gb de almacenamiento. 
    >  - **Limitación de ancho de banda del sitio y escalamiento**: Si el sitio recibe cierta cantidad de peticiones que exceda el límite de ancho de banda, puede representar lentitud o caída del             sitio. No se tiene ningún control sobre la gestión de recursos para escalar la aplicación web.
     > - **Limitación de proveedor de base de datos y lenguaje backend**: Solo se soporta PHP como lenguage de backend y MySQL como proveedor de base de datos, esto puede restringir el         uso de otros lenguages y bases de datos que representen más funcionalidades para el sitio. 
     > - **Limitación de opciones de despliegue**: Solo se soporta FTP (No FTPS) para la subida de archivos al sitio y no se permite crear una conexión privada para hacer conexión a la base de datos y generar despliegos automáticos.      
    > - **Limitación de soporte**: InfinityFree no ofrece soporte directo como email o teléfono. 

3. ¿Qué servicio equivalente usarían en AWS, Azure o GCP para:  
   - Archivos estáticos  
   - Base de datos  
   - Hosting del sitio  
   > 
    |                    | AWS                                                     | Azure                                           | CGP                  |
    |--------------------|---------------------------------------------------------|-------------------------------------------------|----------------------|
    | Archivos estáticos | AWS Simple Storage Service (S3)                         | Storage Blob Storage                            | Cloud Storage        |
    | Base de datos      | Amazon Aurora; Amazon Relational Database Service (RDS) | SQL Database; Cosmos DB                         | Cloud SQL            |
    | Hosting del sitio  | AWS Amplify Hosting                                     | Storage Account Static Website; Static Web Apps | Firebase App Hosting |

4. ¿Cómo resolverían escalabilidad y alta disponibilidad en la nube?  
   > - Estas soluciones pueden variar dependiendo del proveedor, aunque conceptualmente entre proveedores de cloud la finalidad es similar. Una de las ventajas más significativas de cloud es la distrubución de múltiples data centers en varias regiones y países, esto permite la alta disponibilidad con conceptos como Avalability Zones y asignación automática de recursos con modelo de pago pay-as-you-go para escalabilidad. 

    > - Para efectos prácticos, resolveríamos se usaría AWS como ejemplo. AWS ofrece escalamiento horizontal o vertical con servicios como Amazon EC2 Auto Scaling y AWS Auto Scaling, además ofrece servicios de seguridad como  WAF, y ACL’s para prevenir ataques de DDoS y servicios de red como CDN’s, Load Balancers  y ACL’s para distribuir el tráfico y brindar un servicio de baja latencia.   Estos servicios se basan en detección de patrones o umbrales para la ejecución de acciones que permitan el escalamiento y la alta disponibilidad.

     > - Para la escalabilidad y disponibilidad del sitio se asegurará que: 
        > - La creación del servicio de hosting en una región que geográficamente esté más cerca de donde se encuentran los clientes o potenciales clientes que accederán el sitio. 
        > - Se creará el servicio con la opción de General purpose para tomar ventaja de las Availability Zones controladas por AWS, ya que automáticamente se crearán los objetos del sitio a lo largo de múltiples AZ como medida redundante. AWS provee 99.99% de disponibilidad para S3.


5. Plan de migración en 4–5 pasos desde InfinityFree hacia un servicio en la nube.  
   > 1. Descargaría los archivos de la página utilizando el FTP y generaríamos un backup completo de la base de datos con schemes y datos desde PHPMyAdmin
   >  2. Crearíamos un diagrama de arquitectura y basado en ello creamos los servicios en AWS.
    > 3. Importaríamos los archivos generados de la página web al servicio AWS Amplify Hosting o Amazon Lightsail
    > 4. Nos conectaríamos a la base de datos y ejecutaríamos el backup para crear los esquemas importar la data. 
     > 5. Probamos el acceso a través del DNS gratuito ofrecido por AWS y rezamos para que funcione. 

