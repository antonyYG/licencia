# Licencia (Sistema de Gestión de Licencias de Funcionamiento)

Breve descripción
------------------
Este proyecto es una aplicación web en PHP diseñada para gestionar licencias de funcionamiento a nivel municipal: registro de trámites, gestión de tiendas, generación de PDFs de licencia e integración con correo electrónico para notificaciones. Usa una estructura simple tipo MVC con FPDF para generación de PDFs y PHPMailer para envíos SMTP.

Primeros pasos (base de datos)
------------------------------
1. Importar la base de datos que se encuentra en la carpeta `bd/` (`licencia3.sql`).
2. Al importar la base de datos se crea un usuario de prueba con las credenciales siguientes:
	- DNI: `12345678`
	- Contraseña: `admin123`
3. Con esas credenciales podrás iniciar sesión y acceder al entorno demo.

Configuración de correo (envíos SMTP)
-------------------------------------
Para enviar correos desde la aplicación se usa PHPMailer con conexión SMTP. Debes configurar los parámetros SMTP en los ficheros indicados (buscar `$mail->isSMTP()`):

Ejemplo de configuración (usar tus datos):

```
$mail->isSMTP();
$mail->SMTPDebug = 0; // cambiar a 2 para debug
$mail->Host       = 'smtp.gmail.com'; // host SMTP
$mail->SMTPAuth   = true;
$mail->Username   = 'tucorreo@gmail.com'; // usuario SMTP (tu correo)
$mail->Password   = 'CONTRASEÑA_DE_APLICACIÓN'; // contraseña SMTP (ver pasos abajo)
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587; // puerto SMTP
$mail->CharSet    = 'UTF-8';
```

Cómo obtener la contraseña SMTP para Gmail (recomendado)
-------------------------------------------------------
1. Accede a la cuenta de Gmail que usarás como remitente.
2. Ve a `Gestionar tu cuenta` → `Seguridad` y habilita la verificación en 2 pasos.
3. En la sección `Contraseñas de aplicaciones` crea una nueva contraseña para la aplicación (elige "Otro" y pon un nombre identificador).
4. Copia la contraseña de aplicación generada y pégala en el campo `$mail->Password` en los archivos:
	- `model/Usuario.php` (donde se construye/envia correo de usuario)
	- `public/pdf/tramitelicencia.php` (si allí también se envía el PDF por correo)
	- Además, actualiza `$mail->Username` con tu correo real.

Notas y recomendaciones de seguridad
----------------------------------
- Nunca subas credenciales reales al repositorio. Usa variables de entorno o un archivo de configuración fuera del control de versiones (`config.php` o `.env`).
- Mantén `SMTPDebug = 0` en producción. Usa `2` solo para depuración local.
- Restringe el acceso a los archivos de configuración y, si es posible, almacena contraseñas cifradas.

Validaciones y UX
------------------
- El formulario de Tienda valida que el campo `celular` acepte solo números y máximo 9 dígitos (validación en cliente y servidor).
- Los números de documento y otros campos numéricos incluyen validación básica con `onkeypress` y comprobaciones en el controlador.

Mapa y geocodificación
----------------------
La aplicación usa OpenStreetMap (Nominatim) para la geocodificación inversa al hacer clic en el mapa al agregar o editar una tienda. En ocasiones Nominatim no devuelve una dirección completa y sólo rellena las coordenadas; en esos casos escribe la dirección manualmente en el campo correspondiente. Si necesitas una mejor cobertura o autocompletado más fiable, considera usar una API comercial (por ejemplo Google Places / Geocoding API) u otros proveedores (Mapbox, Here, etc.). Ten en cuenta:

- Algunas APIs requieren clave/credenciales y pueden tener costes o límites de cuota.
- Google/otros requieren activar facturación y crear credenciales (usa una clave restringida para mayor seguridad).
- Implementa un fallback que muestre las coordenadas cuando no haya dirección legible (la aplicación ya incluye este comportamiento como respaldo).

Recomendación: si dependes fuertemente del autocompletado de direcciones, evalúa una integración con Google Places (mejor cobertura y autocompletado) o un servicio comercial que ofrezca SLA, y almacena la clave en variables de entorno o en un archivo de configuración fuera del repositorio.

Estructura del proyecto (resumen)
---------------------------------
- `controller/` — controladores que reciben acciones (insertar, editar, listar).
- `model/` — clases para acceso a datos (Tienda, Usuario, RegistroTramite, etc.).
- `view/` — vistas y modales (formularios, tablas y scripts JS del frontend).
- `public/` — activos públicos: CSS, JS, PDFs generados, librerías, imágenes.
- `bd/` — dump de la base de datos (`licencia3.sql`).

Soporte y mejoras futuras
-------------------------
- Mover configuración sensible (SMTP, credenciales DB) a variables de entorno.
- Añadir pruebas automatizadas para controladores clave.
- Mejorar manejo de errores y logging en producción.


-- Fin del resumen
