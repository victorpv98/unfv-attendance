# Sistema de Asistencia QR - UNFV

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-14.0-336791?style=for-the-badge&logo=postgresql)

Sistema de gestión de asistencia mediante códigos QR para la Universidad Nacional Federico Villarreal. Permite a los profesores registrar la asistencia de alumnos a sus clases utilizando escaneo de códigos QR, facilitando el proceso de control de asistencia y generando reportes automáticos.

## Características

- **Gestión de Facultades, Cursos y Horarios**: Administración completa de la estructura académica
- **Registro de Profesores y Estudiantes**: Control de usuarios con diferentes roles y permisos
- **Generación de Códigos QR**: Cada estudiante recibe un código QR único para su identificación
- **Escaneo de Asistencia**: Interfaz móvil para que profesores escaneen los códigos QR de los estudiantes
- **Reportes de Asistencia**: Visualización y exportación de reportes de asistencia por curso y fecha
- **Panel de Administración**: Gestión centralizada de todos los aspectos del sistema

## Tecnologías Utilizadas

- **Laravel 12**: Framework backend del proyecto
- **PHP 8.4**: Lenguaje de programación base
- **PostgreSQL**: Sistema de base de datos relacional
- **Bootstrap**: Framework CSS para el diseño responsive
- **HTML5-QRCode**: Librería para el escaneo de códigos QR
- **SimpleSoftwareIO/simple-qrcode**: Librería para la generación de códigos QR

## Requisitos del Sistema

- PHP 8.4 o superior
- Composer
- PostgreSQL 14.0 o superior
- Node.js y NPM (para compilar assets)

## Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/victorpv98/unfv-attendance.git
   cd unfv-attendance
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   npm install
   ```

3. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar base de datos**
   
   Edita el archivo `.env` con los detalles de tu conexión PostgreSQL:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=unfv_attendance
   DB_USERNAME=unfv_user
   DB_PASSWORD=1234
   ```

5. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Compilar assets**
   ```bash
   npm run build
   ```

7. **Iniciar servidor de desarrollo**
   ```bash
   php artisan serve
   ```

8. **Acceder al sistema**
   
   Abre tu navegador y visita `http://localhost:8000`

## Despliegue del Sistema en un dispositivo movil con red local

1. **Verificar la IP local(WiFi)**
   ```bash
   ifconfig | grep "inet " | grep -v 127.0.0.1
   ```
   ```bash
   ifconfig en0 | grep "inet "
   ```

2. **Permisos del navegador movil**
   
   Para Chrome en Android:
   1. Abre Chrome y navega a chrome://flags
   2. Busca "Insecure origins treated as secure"
   3. Activa esta opción y agrega tu URL local (por ejemplo, http://[TU_IP]:8000)
   4. Reinicia Chrome

3. **Movil Login**

   Accede a http://[TU_IP]:8000 desde tu móvil 


## Estructura del Sistema

### Roles de Usuario

- **Administrador**: Gestión completa del sistema (facultades, cursos, profesores, estudiantes)
- **Profesor**: Gestión de asistencias en sus cursos asignados
- **Estudiante**: Visualización de su código QR y asistencias registradas

### Módulos Principales

- **Gestión de Facultades**: Administración de las facultades de la universidad
- **Gestión de Cursos**: Configuración de cursos, asignación a facultades
- **Gestión de Horarios**: Definición de horarios para cursos y profesores
- **Gestión de Estudiantes**: Administración de perfiles de estudiantes
- **Gestión de Matricula**: Administración de los estudiantes matriculados
- **Generación de QR**: Creación y regeneración de códigos QR para estudiantes
- **Registro de Asistencia**: Escaneo de códigos QR en tiempo real
- **Reportes**: Visualización y exportación de datos de asistencia

## Credenciales por Defecto

Para acceder al sistema por primera vez, utiliza:

- **Administrador**
  - Usuario: admin@unfv.edu.pe
  - Contraseña: 1234



---

Desarrollado para la Universidad Nacional Federico Villarreal - 2025