# Sorteos Demo

Esta es una aplicación de ejemplo para gestionar sorteos con PHP, MySQL,
CSS y JavaScript. El sistema genera su base de datos automáticamente y
ahora se conecta a la API de Instagram Graph tras autenticar con Facebook.
Las publicaciones y sus comentarios se cargan dinámicamente para realizar
un sorteo. El ganador puede descargarse en PDF.

## Estructura
- `public/` archivos accesibles públicamente.
- `src/db.php` conexión a MySQL usando variables de entorno.
- `src/config.php` datos de la app de Facebook (APP_ID, APP_SECRET y URL de retorno).

## Uso
1. Configure su servidor web apuntando al directorio `public` o ejecute
   `php -S localhost:8000 -t public`.
2. Defina las variables de entorno `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASS`
   con las credenciales de MySQL (opcionalmente deje sus valores por defecto).
3. Complete en `src/config.php` los datos de su aplicación de Facebook.
4. Al iniciar la aplicación se creará automáticamente la base de datos y unas
   tablas de ejemplo con datos.
5. Abra `index.php` en su navegador y pulse "Entrar con Facebook".
6. Tras autorizar, cargue sus publicaciones y ejecute el sorteo.

La conexión con pasarelas de pago no está implementada.

