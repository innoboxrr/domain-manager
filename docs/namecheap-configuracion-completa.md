# Guía completa de configuración de Namecheap para Domain Manager

Esta guía describe paso a paso cómo preparar una cuenta de Namecheap, habilitar el acceso de desarrollador y conectar el proveedor con el paquete **Domain Manager**. Sigue cada sección en orden.

## 1. Crear y verificar tu cuenta Namecheap
1. Ingresa en [https://www.namecheap.com](https://www.namecheap.com) y pulsa **Sign In**.
2. Haz clic en **Create an account** y completa el formulario con tus datos.
3. Verifica la dirección de correo electrónico desde el mensaje que recibirás.
4. Inicia sesión nuevamente para confirmar que tu cuenta está activa.

## 2. Habilitar el perfil de desarrollador (API Access)
1. Desde el panel superior, abre **Profile** → **Security**.
2. En la sección **Tools**, pulsa **Manage** en **Namecheap API Access**.
3. Cambia el interruptor de **API Access** a **Enabled**.
4. Lee y acepta los términos de uso de la API.
5. Añade la IP pública desde la que se conectará tu backend (`APP_URL` o servidor de producción). Namecheap requiere que registres cada IP que consumirá la API.

> 💡 Puedes validar tu IP ejecutando `curl ifconfig.me` desde el servidor que ejecutará el paquete.

## 3. Generar credenciales API
1. Dirígete a **Profile** → **API Access**.
2. Anota tu **Username** (coincide con el usuario de Namecheap) y tu **API Key**.
3. Si todavía no posees una **API Key**, usa el botón **Generate new key**. Guarda la clave en un gestor seguro; no se mostrará nuevamente.
4. Opcionalmente, crea una **Sandbox API Key** si harás pruebas con el entorno de staging (`https://api.sandbox.namecheap.com`).

## 4. Configurar usuarios de contacto y TLDs permitidos
1. Ve a **Profile** → **Default Contact** y configura los datos de contacto (registrante, administrativo, técnico y de facturación).
2. En **Domain List** → **All Products**, verifica que los TLDs que planeas vender estén disponibles para la API. Namecheap publica una tabla de compatibilidad en [https://www.namecheap.com/support/api/intro/](https://www.namecheap.com/support/api/intro/).
3. Si un TLD requiere documentación adicional, consérvala lista porque la API puede rechazar compras sin esos datos.

## 5. Ajustar la whitelist de IPs
1. En **API Access**, revisa la tabla **Approved IP Addresses**.
2. Añade las IPs del entorno local, staging y producción según aplique.
3. Cada IP nueva debe confirmarse mediante el correo que Namecheap enviará. Asegúrate de completar este paso antes de hacer pruebas.

## 6. Configurar variables de entorno en Domain Manager
Edita tu archivo `.env` (o usa variables de entorno del orquestador) con las siguientes claves:

```env
# Credenciales Namecheap
DOMAIN_REGISTRAR=namecheap
NAMECHEAP_API_USER="<tu_apiuser>"
NAMECHEAP_API_KEY="<tu_apikey>"
NAMECHEAP_USERNAME="<tu_username>"
NAMECHEAP_CLIENT_IP="<ip_autorizada>"
NAMECHEAP_SANDBOX=false          # true si usarás el sandbox global
NAMECHEAP_BASE_URL="https://api.namecheap.com/xml.response"
NAMECHEAP_SANDBOX_BASE_URL="https://api.sandbox.namecheap.com/xml.response"
NAMECHEAP_CURRENCY="USD"
```

* `DOMAIN_REGISTRAR` permite seleccionar Namecheap como proveedor activo.
* `NAMECHEAP_CLIENT_IP` debe coincidir con la IP aprobada en el panel (puedes omitirlo si lo cargarás por workspace).

## 7. Actualizar la configuración de servicios
En `config/services.php` del proyecto principal, confirma que exista un bloque como el siguiente (ajusta según tu configuración actual):

```php
'namecheap' => [
    'base_url' => env('NAMECHEAP_BASE_URL', 'https://api.namecheap.com/xml.response'),
    'sandbox_base_url' => env('NAMECHEAP_SANDBOX_BASE_URL', 'https://api.sandbox.namecheap.com/xml.response'),
    'sandbox' => env('NAMECHEAP_SANDBOX', false),
    'api_user' => env('NAMECHEAP_API_USER'),
    'api_key' => env('NAMECHEAP_API_KEY'),
    'username' => env('NAMECHEAP_USERNAME'),
    'client_ip' => env('NAMECHEAP_CLIENT_IP'),
    'default_currency' => env('NAMECHEAP_CURRENCY', 'USD'),
],
```

## 8. Probar la conexión desde Domain Manager
1. Ejecuta `php artisan domain:registrar:test namecheap` (si el paquete incluye el comando de verificación) o crea un test manual contra el endpoint `/domains/registrar/test`.
2. Verifica que la respuesta sea satisfactoria y que el proveedor devuelva código 200.
3. Si recibes un error **"IP address is not allowed"**, vuelve al paso 5 para autorizarla.

## 9. Flujo para comprar un dominio desde la aplicación
1. Accede al módulo **Dominios → Comprar** en el frontend Vue.
2. Busca el dominio usando el cuadro de búsqueda; el sistema consultará la disponibilidad en Namecheap.
3. Revisa los precios y selecciona el periodo de registro deseado.
4. Completa los datos de contacto si se solicitan; se mapearán con los contactos configurados en Namecheap.
5. Confirma el método de pago asociado a la suscripción del workspace.
6. Finaliza la compra; Domain Manager ejecutará la orden vía API y almacenará el identificador de Namecheap.
7. Revisa el historial en **Dominios → Renovaciones** para confirmar la suscripción activa.

## 10. Renovar, transferir y liberar dominios
- **Renovación automática:** asegúrate de que la suscripción tenga un método de pago válido. El cron interno disparará la orden en Namecheap antes del vencimiento.
- **Renovación manual:** desde el panel, selecciona el dominio y elige **Renovar**. Se enviará `namecheap.domains.renew` con los años seleccionados.
- **Transferencia entrante:** utiliza el flujo **Transferir dominio** ingresando el AuthCode. Verifica la confirmación por email.
- **Liberar dominio:** Namecheap no ofrece un endpoint público para eliminar dominios activos; marca la suscripción como no renovable y espera el vencimiento.

## 11. Administración de DNS
1. Desde **Dominios → DNS**, selecciona el dominio.
2. Asegúrate de que el dominio esté con DNS personalizado (`Custom DNS`) en Namecheap.
3. Crea, edita o elimina registros A, AAAA, CNAME, MX y TXT.
4. Guarda los cambios; la API ejecuta `namecheap.domains.dns.setHosts` y retornará el estado de sincronización.

## 12. Buenas prácticas de seguridad
- Rota la **API Key** periódicamente y actualiza el `.env`.
- Limita las IPs de acceso sólo a los servidores de confianza.
- Usa el entorno de sandbox para pruebas y evita operar dominios reales en desarrollo.

Con estos pasos tu integración con Namecheap estará lista para que los workspaces puedan comprar, renovar y administrar sus dominios desde Domain Manager.
