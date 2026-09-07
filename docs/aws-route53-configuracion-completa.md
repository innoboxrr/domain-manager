# Guía completa de configuración de AWS Route 53 para Domain Manager

Este tutorial explica cómo preparar AWS para operar dominios desde el paquete **Domain Manager**, incluyendo la creación del usuario IAM, la habilitación del servicio Route 53 y la carga de credenciales en el proyecto.

## 1. Crear una cuenta de AWS o utilizar una existente
1. Visita [https://aws.amazon.com](https://aws.amazon.com) y selecciona **Create an AWS Account**.
2. Completa la información de contacto, método de pago y verifica tu identidad.
3. Inicia sesión en la **AWS Management Console** con el usuario root o un usuario IAM con permisos administrativos.

## 2. Activar Route 53 en la región requerida
1. Desde la barra de búsqueda de la consola, escribe **Route 53** y abre el servicio.
2. Si es la primera vez, acepta las condiciones y confirma que el servicio está disponible en la cuenta.
3. Route 53 es global, por lo que no necesitas seleccionar una región específica.

## 3. Crear un usuario IAM dedicado para Domain Manager
1. Ingresa a **IAM** (Identity and Access Management).
2. En el menú lateral, haz clic en **Users** → **Add users**.
3. Define un nombre como `domain-manager-registrar`.
4. Marca **Provide access to the AWS Management Console** sólo si deseas acceso manual. Para API basta con **Access key - Programmatic access**.
5. Pulsa **Next** para asignar permisos.

## 4. Asignar permisos mínimos necesarios
1. En la sección **Permissions**, elige **Attach policies directly**.
2. Añade las políticas administradas:
   - `AmazonRoute53FullAccess`
   - `AmazonRoute53DomainsFullAccess`
   - `AWSMarketplaceMeteringRegisterUsage` (si gestionas facturación desde AWS Marketplace).
3. Opcionalmente, crea una política personalizada que limite la gestión a zonas específicas.
4. Continúa hasta **Review** y confirma la creación del usuario.

## 5. Generar claves de acceso (Access Key ID y Secret Access Key)
1. En la pantalla final del asistente, pulsa **Create access key**.
2. Selecciona **Application running outside AWS** como caso de uso.
3. Descarga el archivo `.csv` con el **Access Key ID** y el **Secret Access Key**. Guárdalo de forma segura; no podrás verlo de nuevo.

## 6. Configurar dominios y zonas hospedadas en Route 53
1. En **Route 53 → Registered domains**, valida que tu cuenta pueda registrar dominios. Si nunca registraste uno, añade un método de pago.
2. Para dominios existentes, crea una **Hosted zone** en **Route 53 → Hosted zones** con el mismo nombre del dominio.
3. Copia los **Name Servers** si necesitas delegar la zona desde otro registrador.
4. Asegúrate de que las zonas hospedadas tengan registros SOA y NS generados automáticamente.

## 7. Preparar la facturación en AWS
1. Ingresa a **Billing → Payment methods** y registra una tarjeta válida para cobros de dominios.
2. Habilita **Billing alerts** si quieres recibir notificaciones por email.
3. Verifica que el límite de registro de dominios se ajusta a tus necesidades (por defecto suele ser 20 dominios, puedes solicitar aumento mediante soporte).

## 8. Registrar las credenciales en el workspace
1. Desde la aplicación Vue ingresa al workspace que operará dominios con Route 53.
2. Ve a **Integraciones** → **Dominios (Namecheap / AWS Route 53)**.
3. En el bloque **AWS Route 53** captura:
   - **Access Key ID** y **Secret Access Key** del usuario IAM creado en los pasos anteriores.
   - Opcionalmente la **Hosted Zone ID** que utilizarás como predeterminada.
   - Si usas STS/AssumeRole, especifica el **Role ARN** a asumir.
4. Guarda los cambios para que el workspace quede habilitado como registrador.

## 9. Configuración global mínima
En el `.env` principal sólo debes mantener `ROUTE53_REGION` para indicar la región por defecto (usualmente `us-east-1`). Las claves de acceso viven exclusivamente en el workspace, lo que permite que cada espacio de trabajo gestione sus propias credenciales.

## 10. Probar la autenticación
1. Instala y configura la CLI de AWS en tu entorno (`aws configure`).
2. Ejecuta `aws route53 list-hosted-zones --profile <perfil>` para comprobar que las credenciales tienen permisos.
3. Desde la aplicación, ejecuta el comando de prueba `php artisan domain:registrar:test aws_route53` o el endpoint correspondiente.
4. Confirma que la respuesta incluya las zonas o dominios disponibles.

## 11. Registrar un dominio nuevo desde Domain Manager
1. Accede al módulo **Dominios → Comprar** en el frontend.
2. Busca el dominio y revisa la disponibilidad reportada por Route 53 Domains.
3. Selecciona el periodo de registro y rellena los datos de contacto requeridos.
4. Confirma la compra; Domain Manager ejecutará la operación `route53domains:RegisterDomain`.
5. Verifica en **Route 53 → Registered domains** que el dominio aparece con estado **Registered**.

## 12. Administrar renovaciones y transferencias
- **Renovación automática:** habilita `Auto renew` en el registro del dominio o gestiona la suscripción desde Domain Manager para enviar `route53domains:RenewDomain`.
- **Transferencias entrantes:** inicia el flujo en Domain Manager con el AuthCode. AWS enviará correos de confirmación al contacto registrante.
- **Transferencias salientes:** desbloquea el dominio y comparte el AuthCode desde Route 53.
- **Liberación:** cancela la renovación automática y espera a que el dominio expire. AWS Route 53 no ofrece delete inmediato, sólo cancelación de auto-renovación.

## 13. Gestionar DNS con Hosted Zones
1. En **Dominios → DNS**, selecciona el dominio y revisa la lista de registros.
2. Crea entradas A, AAAA, CNAME, MX, TXT, SRV o Alias según lo requerido.
3. Al guardar, el backend invoca `route53:ChangeResourceRecordSets` con un `ChangeBatch` que se reflejará en la zona hospedada.
4. Monitoriza el **Change ID** desde la consola de AWS para confirmar que el status llegue a **INSYNC**.

## 14. Seguridad y buenas prácticas
- Asigna una política IAM personalizada si quieres limitar el acceso sólo a determinados dominios o zonas.
- Habilita **MFA** para el usuario root y los administradores.
- Considera rotar las **Access Keys** periódicamente y actualizar el `.env`.
- Habilita CloudTrail para auditar las operaciones de Route 53.

Con estas instrucciones tu integración con AWS Route 53 quedará preparada para operar dominios, renovar registros y administrar DNS directamente desde Domain Manager.
