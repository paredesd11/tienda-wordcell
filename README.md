# Guía de Configuración y Despliegue de la Tienda WordCell

Este documento contiene las instrucciones necesarias para configurar el sistema cuando se despliegue para tu cliente o se migre a otro servidor.

## 1. Configuración de Base de Datos y Entorno

El archivo principal donde se define la conexión a la base de datos y correos es:
`config/config.php`

Abre este archivo y localiza la sección de Producción (o la sección de pruebas si estás en localhost). Deberás cambiar las siguientes constantes:

```php
// ── ENTORNO PRODUCCIÓN ─────────
define('DB_HOST', 'HOST_DE_TU_CLIENTE');       // Ej. localhost, sql211.infinityfree.com
define('DB_USER', 'USUARIO_BD_CLIENTE');       // Ej. root, u123456_user
define('DB_PASS', 'CONTRASEÑA_BD_CLIENTE');    // Ej. '', mySecretPass!
define('DB_NAME', 'NOMBRE_BD_CLIENTE');        // Ej. tienda_mvc, u123456_tienda
define('URL_BASE', 'https://midominio.com/');  // IMPORTANTE: Incluir https:// y la barra (/) final
```

También define el nombre oficial del sistema de tu cliente:
```php
define('APP_NAME', 'Nombre de la Tienda del Cliente');
```

---

## 2. Configuración de Correos (SMTP con Gmail)

El sistema utiliza la función `mail()` (o PHPMailer si está disponible) para enviar códigos de seguridad (autenticación en 2 pasos) y correos de servicios pendientes.
A las cuentas de Gmail recientes **NO** se les permite usar su contraseña normal de cuenta para conectarse a enviar correos automatizados, se requiere utilizar una **Contraseña de Aplicación**.

En `config/config.php`:
```php
define('SMTP_USER', 'correo_del_cliente@gmail.com');
define('SMTP_PASS', 'contrasena_de_aplicacion_generada');
```

### ¿Cómo crear una Contraseña de Aplicación en Gmail?

1. Ve a la cuenta de Google del cliente: [https://myaccount.google.com/](https://myaccount.google.com/)
2. En el menú lateral izquierdo, haz clic en **"Seguridad"**.
3. Busca la sección **"Cómo accedes a Google"**.
4. Es **obligatorio** que la cuenta tenga la **"Verificación en dos pasos"** activada. Si dice "Desactivada", haz clic para activarla usando un número de teléfono.
5. Una vez activada la Verificación en 2 pasos, vuelve al apartado "Seguridad".
6. En el buscador superior de la página (la lupa de los ajustes de Google), escribe: **"Contraseñas de aplicación"** y selecciona el primer resultado. 
*(Si no lo encuentras buscando, ingresa mediante este enlace directo una vez tengas la verificación en 2 pasos activa: [https://myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords) )*
7. Te pedirá un nombre de aplicación. Puedes escribir por ejemplo: "Pagina Web Tienda".
8. Haz clic en **"Crear"**.
9. Google te mostrará un recuadro amarillo con **16 letras (una contraseña larga sin espacios)**.
10. ¡Copia esa contraseña exactamente como aparece (puedes quitarle los espacios)!
11. Pega esa contraseña en el archivo `config/config.php` dentro de `SMTP_PASS`.

> **Nota importante:** Esa contraseña solo se mostrará una vez. Si la pierdes, deberás borrarla de la lista en Google y generar una nueva.

---

## 3. Configuración del Panel de Administrador (WhatsApp y PayPhone)

Cualquier otro parámetro del sistema no se toca por código. Se modifica directamente desde el panel del Administrador, por lo cual tu cliente podrá modificarlo a su antojo cuando inicie sesión:

1. **WhatsApp de Administradores:**
   - Ir a la opción **"Solicitar Servicio"** (Servicios) del menú lateral como Admin.
   - En la tarjeta **"Números de Notificación"**, pueden Agregar/Editar/Eliminar a qué números les llegará las notificaciones de nuevos servicios o pedidos. *(Recordatorio: los números deben ir con el código de país (ej: `5939...`), sin espacios ni el signo `+`).*

2. **API de Ultramsg (Para que salgan mensajes a los clientes):**
   - En la misma pantalla de servicios ("Solicitar Servicio"), buscar la pestaña inferior **Configurar API de WhatsApp (Ultramsg)**.
   - Ahí pueden colocar el `instance_id` y su `token` oficial creado en [ultramsg.com](ultramsg.com).

3. **Configuración de PayPhone (Cobros por Tarjeta):**
   - En el menú izquierdo como administrador, ir a **"Config. PayPhone"**.
   - Presionar "Editar".
   - Allí colocarán el identificador oficial proporcionado por PayPhone (`store_id`, Token Client, etc.) y la URL por donde procesarán el pago. Esto conectará automáticamente con el script de validación.

4. **Tarifas de Servicios (Precios Modificables Estándar):**
   - También en la parte inferior de **Servicios Técnicos**, el cliente puede editar variables como el *"Adicional por Formateo Plus"*, *"Revisión Standard"*, etc. Si guardan esto, el Frontend actualizará automáticamente las calculadoras de cotización para los visitantes.
