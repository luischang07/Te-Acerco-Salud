# Te Acerco Salud (TAS)

## Planteamiento del Problema

En el sector salud, uno de los principales retos que enfrentan los pacientes es el surtido completo de sus recetas médicas. La fragmentación del inventario entre distintas cadenas de farmacias obliga a los pacientes a recorrer múltiples sucursales, generando pérdida de tiempo, costos adicionales y riesgos para su salud al retrasar tratamientos.

**Te Acerco Salud (TAS)** es una solución tecnológica diseñada para conectar pacientes y farmacias, permitiendo:
- Seleccionar la sucursal más conveniente.
- Cargar recetas médicas digitales.
- Garantizar la disponibilidad de medicamentos mediante un sistema de colaboración entre cadenas.
- Surtir productos faltantes desde farmacias cercanas automáticamente.

## Objetivos
- Optimizar la experiencia del paciente.
- Mejorar la coordinación entre farmacias.
- Reducir barreras para el acceso oportuno a tratamientos médicos.

## Características Principales
- **Registro de Pacientes**: Gestión de usuarios y perfiles médicos.
- **Red de Farmacias**: Registro de cadenas y sucursales colaborativas.
- **Gestión de Inventarios**: Actualización en tiempo real para garantizar disponibilidad.
- **Surtido Inteligente**: Algoritmo para completar recetas buscando en la red de farmacias cercanas.
- **Dashboard Administrativo**: Panel para empleados y administradores (cotizaciones, rastreos, reportes).

## Instalación y Configuración

Sigue estos pasos para levantar el proyecto en tu entorno local:

### Prerrequisitos
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / MariaDB

### Pasos
1.  **Clonar el repositorio**
    ```bash
    git clone <url-del-repositorio>
    cd security-access
    ```

2.  **Instalar dependencias de PHP**
    ```bash
    composer install
    ```

3.  **Instalar dependencias de Frontend**
    ```bash
    npm install
    ```

4.  **Configurar entorno**
    - Copia el archivo de ejemplo:
      ```bash
      cp .env.example .env
      ```
    - Configura tus credenciales de base de datos en el archivo `.env`:
      ```env
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=nombre_de_tu_bd
      DB_USERNAME=tu_usuario
      DB_PASSWORD=tu_password
      ```

5.  **Generar clave de aplicación**
    ```bash
    php artisan key:generate
    ```

6.  **Ejecutar migraciones**
    ```bash
    php artisan migrate
    ```
    *(Opcional: `php artisan migrate --seed` para cargar datos de prueba)*

7.  **Compilar activos**
    ```bash
    npm run dev
    ```

8.  **Iniciar servidor local**
    ```bash
    php artisan serve
    ```

¡Listo! Accede a `http://localhost:8000` en tu navegador.

## Tecnologías
- **Backend**: Laravel 12
- **Frontend**: Blade, Alpine.js, Tailwind CSS
- **Base de Datos**: MySQL
