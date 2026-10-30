# Portal de Documentación Médica — Hospital de Clínicas

## 📋 Descripción del proyecto

Este proyecto consiste en el desarrollo de un portal web para el Hospital de Clínicas de Montevideo, Uruguay, cuyo objetivo es facilitar el acceso a documentación médica para pacientes y brindar herramientas de gestión al personal autorizado del hospital.

La plataforma busca centralizar la consulta de documentos médicos, organizar la información por categorías y especialidades, y facilitar la administración de usuarios y encuestas desde una interfaz web.

## 🎯 Objetivos

- Facilitar el acceso de los pacientes a documentación e indicaciones médicas.
- Organizar los documentos por categorías y especialidades.
- Proporcionar herramientas para la gestión de documentos.
- Permitir la administración de usuarios y encuestas.
- Ofrecer interfaces diferenciadas según el rol del usuario.

## ⚙️ Funcionalidades

### 👤 Área de pacientes

- Acceso al apartado de documentación médica.
- Navegación por categorías y especialidades.
- Consulta y lectura de documentos disponibles.

### 🛠️ Área de administración

- Gestión de usuarios.
- Creación y gestión de encuestas.
- Acceso a herramientas administrativas.

### 🩺 Área de enfermería

- Acceso a una interfaz específica para el personal de enfermería.
- Consulta y edición de información documental según las funciones implementadas en el sistema.

> **Estado del proyecto:** la aplicación se encuentra en desarrollo. Algunas funcionalidades y documentos todavía pueden estar incompletos o utilizar datos de prueba.

## 🧰 Tecnologías utilizadas

- **HTML5:** estructura de las páginas web.
- **CSS3:** diseño y estilos de la interfaz.
- **JavaScript:** interactividad del sitio.
- **PHP:** lógica del servidor y procesamiento de las funcionalidades.
- **Bootstrap 5.3.8:** componentes y diseño adaptable.
- **MariaDB/MySQL:** gestión de la base de datos.
- **XAMPP:** entorno de desarrollo local.
- **Git y GitHub:** control de versiones y colaboración.

## 📁 Estructura del proyecto

```text
Pagina-hc-versiones/
├── css/       # Hojas de estilo
├── html/      # Páginas PHP, accesos y documentos médicos
├── img/       # Imágenes y recursos gráficos
├── js/        # Scripts JavaScript
├── video/     # Recursos audiovisuales
├── index.php  # Página principal
└── readme.md  # Documentación del proyecto
```

## 🚀 Instalación y ejecución

### Requisitos previos

- Tener instalado [XAMPP](https://www.apachefriends.org/).
- Contar con PHP y MariaDB/MySQL disponibles mediante el entorno local.
- Tener Git instalado o descargar el repositorio desde GitHub.

### 1. Clonar el repositorio

Abrí una terminal y ejecutá:

```bash
git clone https://github.com/companyfsrtechnologies-coder/Pagina-hc-versiones.git
```

### 2. Ubicar el proyecto

Copiá la carpeta del proyecto dentro del directorio `htdocs` de XAMPP. Por ejemplo, en Windows:

```text
C:\xampp\htdocs\Pagina-hc-versiones
```

Si clonaste el repositorio directamente dentro de `htdocs`, este paso no es necesario.

### 3. Iniciar los servicios

Abrí el panel de control de XAMPP e iniciá:

- **Apache**
- **MySQL** (servicio que proporciona MariaDB en muchas instalaciones de XAMPP).

### 4. Configurar la base de datos

1. Abrí [phpMyAdmin](http://localhost/phpmyadmin/).
2. Prepará la base de datos que requiere el proyecto.
3. Importá `sigsm_hospital_corregida.sql` desde phpMyAdmin. El script crea la estructura
   de la base    `sigsm_hospital_corregida`; el script también crea la base si no existe:

   ```sql
   CREATE DATABASE sigsm_hospital_corregida
     CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```

   El esquema incluye `paginas_doc`, la tabla que almacena el contenido paginado
   asociado a cada documento.
4. La conexión se configura en `html/conect-bd.php`. Por defecto usa `127.0.0.1`,
   el puerto `3306`, el usuario `root`, contraseña vacía y la base
   `sigsm_hospital_corregida`.
5. Para otra instalación, definí estas variables de entorno antes de iniciar Apache:
   `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` y `DB_PASSWORD`. No guardes
   contraseñas reales en el repositorio.

Si disponés de un archivo SQL con la estructura y los datos iniciales, importalo desde phpMyAdmin. La configuración exacta depende de los archivos de base de datos disponibles para esta versión.

### 5. Abrir el portal

Ingresá desde el navegador a:

http://localhost/Pagina-hc-versiones/

## 🧪 Pruebas de funcionamiento

Para comprobar las funcionalidades:

1. Accedé a la página principal.
2. Entrá en el área de pacientes y navegá por las categorías y especialidades disponibles.
3. Probá la lectura de los documentos de ejemplo.
4. Accedé a las interfaces administrativas y de enfermería utilizando cuentas de prueba configuradas en el entorno local.
5. Verificá las operaciones de gestión de usuarios, documentos y encuestas que estén implementadas.

Las credenciales de prueba deben utilizarse únicamente en un entorno local controlado. No deben emplearse contraseñas reales ni publicarse credenciales de producción en el repositorio.

## 🔒 Seguridad

Este proyecto está orientado al desarrollo y las pruebas de una aplicación de documentación médica. Antes de utilizarlo en un entorno hospitalario real, es necesario verificar, entre otros aspectos:

- La autenticación y autorización de los usuarios.
- La protección de los datos y las credenciales.
- La validación de los datos recibidos por el servidor.
- La protección de las operaciones sobre documentos y usuarios.
- La configuración segura del servidor y de la base de datos.

## 👥 Colaboración

El proyecto se desarrolla mediante Git y GitHub, permitiendo al equipo mantener un historial de cambios y colaborar en el desarrollo de las funcionalidades.

Para proponer cambios, se recomienda trabajar en una rama independiente y crear un Pull Request para su revisión.

## 📌 Estado y alcance

Este repositorio corresponde a un proyecto de desarrollo web para la consulta y gestión de documentación médica del Hospital de Clínicas. Las funcionalidades disponibles pueden variar según la versión y el avance de la implementación.
