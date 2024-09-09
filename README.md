# Feed Instagram Login

**Plugin Name:** Feed Instagram Login  
**Version:** 1.0  
**Author:** Jesús Jiménez  
**Description:** Plugin personalizado para iniciar sesión en WordPress y verificar datos en Airtable. Inspirado en el inicio de sesión de Instagram.

## Descripción

El plugin **Feed Instagram Login** permite a los usuarios iniciar sesión en WordPress usando un formulario que verifica las credenciales contra una base de datos en Airtable. Si las credenciales son correctas, el usuario es autenticado en WordPress y redirigido a su perfil. Si el usuario no existe en WordPress, se crea una nueva cuenta automáticamente.

El plugin está diseñado para imitar la experiencia de inicio de sesión de Instagram.

## Características

- **Inicio de sesión**: Verifica el inicio de sesión contra Airtable.
- **Creación de usuarios**: Crea nuevos usuarios en WordPress si no existen.
- **Redirección automática**: Redirige al usuario a su perfil después de iniciar sesión exitosamente.
- **Mensajes de error**: Muestra mensajes de error si las credenciales son incorrectas o faltantes.

## Instalación

1. Sube los archivos del plugin a la carpeta `/wp-content/plugins/feed-instagram-login` o instala el plugin directamente desde el panel de WordPress.
2. Activa el plugin desde el menú "Plugins" en WordPress.
3. Crea una página o un post y agrega el shortcode `[feed_instagram_login]` para mostrar el formulario de inicio de sesión.
4. Asegúrate de tener una tabla en Airtable llamada `Usuarios` con las columnas correspondientes: `Nombre`, `Password`, `Telefono`, `email`.

## Uso

Una vez activado, agrega el siguiente shortcode en la página donde desees mostrar el formulario de inicio de sesión:

Esto generará un formulario de inicio de sesión donde los usuarios podrán ingresar su número de teléfono, nombre o email, junto con su contraseña. El plugin verificará las credenciales contra Airtable y, si son correctas, permitirá el acceso a WordPress.

### Campos del formulario

- **Número de teléfono, nombre o email:** Campo requerido para ingresar la credencial de inicio de sesión.
- **Contraseña:** Campo requerido para ingresar la contraseña del usuario.

## Funcionalidades

### 1. Verificación de Credenciales

El plugin verifica las credenciales del usuario en Airtable. Si se encuentran coincidencias y la contraseña es correcta, el usuario es autenticado en WordPress.

### 2. Creación de Usuario en WordPress

Si el usuario no existe en WordPress, el plugin crea una cuenta nueva usando el nombre de usuario proporcionado y la dirección de correo electrónico. 

### 3. Inicio de Sesión en WordPress

El usuario es autenticado en WordPress mediante `wp_set_auth_cookie` y `wp_set_current_user`, lo que permite acceso a las áreas protegidas del sitio.

### 4. Redirección a Perfil

Después de un inicio de sesión exitoso, el usuario es redirigido a la página de perfil (`/perfil`). 

## Integración con Airtable

- **Endpoint de Airtable:** `https://api.airtable.com/v0/appzmB3zBmwWkhnkn/Usuarios`
- **Campos esperados:**
  - `Nombre`: Nombre de usuario ingresado en Airtable.
  - `Password`: Contraseña encriptada guardada en Airtable.
  - `Telefono`: Número de teléfono (opcional).
  - `email`: Email del usuario.

**Nota:** Asegúrate de que el API Key de Airtable esté correctamente configurado en el código del plugin.

## Requisitos

- Versión de WordPress: 5.0 o superior.
- Cuenta en Airtable con una tabla de usuarios.

## Personalización

Puedes modificar el estilo del formulario editando el archivo `style_login.css` y personalizar el comportamiento del script en el archivo `script_login.js`. Ajusta la API Key de Airtable en el código del plugin según sea necesario.

## Errores Comunes

- **Credenciales incorrectas:** Si el número de teléfono, nombre o email no coinciden con los datos en Airtable o la contraseña es incorrecta.
- **Error de conexión con Airtable:** Si hay problemas al conectarse con Airtable o si la API Key es incorrecta.

## Contribuciones

Si deseas contribuir al desarrollo o mejoras del plugin, por favor, abre un issue o envía un pull request en el repositorio.

## Licencia

Este plugin está licenciado bajo la [Licencia GPL v2 o posterior](https://www.gnu.org/licenses/gpl-2.0.html).