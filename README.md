# GestiChalbaud - Sistema de Gestión Académica

**GestiChalbaud** es una plataforma web integral, diseñada bajo una arquitectura moderna tipo SaaS, para administrar y automatizar de manera eficiente todos los procesos académicos y administrativos de la **Unidad Educativa Bolivariana Coronel Carlos Delgado Chalbaud**.

---

## 🚀 Arquitectura y Stack Tecnológico

El proyecto sigue el patrón de diseño **MVC (Modelo-Vista-Controlador)** y está construido para garantizar rendimiento, escalabilidad y una experiencia de usuario (UX) excepcional.

### Backend
*   **Framework Core:** Laravel 11/12 (PHP 8.x)
*   **Base de Datos:** MySQL 8+
*   **Seguridad:** Control de Acceso basado en Roles (RBAC), Encriptación Bcrypt, Protección nativa CSRF, y Generación de Captcha anti-bots en memoria.

### Frontend (UI/UX)
*   **Estructura y Estilos:** HTML5, **Tailwind CSS** (implementación utilitaria directa).
*   **Arquitectura Visual:** App Shell Layout (SaaS Dashboard). Sidebar estático, navbar responsivo y área de contenido centralizado con scroll independiente.
*   **Interactividad:** JavaScript Vanilla puro (para validaciones asíncronas, AJAX y componentes Wizard).
*   **Identidad Visual:** Adaptada al manual institucional (Azul Marino `#1A237E`, Amarillo Sol `#FBC02D`, bordes `rounded-xl`, y sombras `shadow-sm`).


## 🧩 Módulos y Funcionalidades Principales

### 1. 🔐 Seguridad y Autenticación
*   **Roles Centralizados:** 3 perfiles principales de sistema (`Admin`, `Docente/Worker`, `Representante`).
*   **Recuperación Segura:** Flujo dinámico de recuperación de contraseña basado en configuración de preguntas de seguridad personales.
*   **Captcha In-House:** Sistema de Captcha renderizado nativamente desde el servidor, persistente en sesión y arquitectónicamente compatible con despliegues a través de túneles proxy.

### 2. 👥 Gestión de Matrícula (Estudiantes y Representantes)
*   **Wizard de Inscripción (Multi-step):** Un formulario interactivo de 6 pasos (Identidad, Antropometría/Salud, Ubicación, Antecedentes, Entorno, Asignación de Aula). Permite navegación libre en edición y navegación estricta en creación.
*   **Geografía Dinámica (AJAX):** Listas desplegables en cascada (Estado -> Municipio -> Parroquia) alimentadas por una API interna conectada a la base de datos geográfica oficial del país.
*   **Normalización de Usuarios:** Toda la información de identificación básica (Nombres, Apellidos, Cédula) ha sido extraída hacia la tabla principal de `users`, evitando la redundancia de datos y facilitando la escalabilidad si un Representante pasa a ser Docente.

### 3. 🏫 Gestión Académica
*   **Años Escolares:** Control de ciclos lectivos (Configuración de años activos e históricos).
*   **Grados y Secciones:** Gestión de niveles de Educación Inicial y Primaria. Asignación de turnos (Mañana, Tarde) y control de capacidad máxima.
*   **Personal Docente:** Registro de profesores y asignación a aulas específicas. El sistema aplica permisos estrictos garantizando que cada docente solo acceda y manipule datos de su aula asignada en el año escolar vigente.

### 4. 📝 Evaluaciones y Cierre de Ciclo
*   **Carga de Calificaciones:** Formulario matricial ágil para la inserción de calificaciones cualitativas (A, B, C, D, E) y observaciones por Lapso y Materia.
*   **Lógica de Promoción Estudiantil:** Sistema automatizado para el fin de año escolar. Permite determinar dinámicamente si un estudiante es **Promovido**, **Repitiente** o **Egresado** (graduación automática y egreso del sistema al superar el 6to Grado).

---

## 🗄️ Estructura y Normalización de Base de Datos

La base de datos de GestiChalbaud ha sido exhaustivamente normalizada para garantizar la integridad referencial y evitar dependencias transitivas:
*   **Tabla Central (`users`):** Almacena credenciales, estados de cuenta y datos personales universales.
*   **Tablas de Dominio (`students`, `representatives`, `teachers`):** Almacenan exclusivamente datos biológicos o contractuales específicos de su función (Ej: *fecha de ingreso* para docentes, *tallas y alergias* para estudiantes).
*   **Tabla de Transacciones (`enrollments`):** Actúa como pivote histórico maestro. Un estudiante posee un `enrollment` por cada año escolar cursado, vinculando al Estudiante, la Sección inscrita, el Representante responsable en ese momento y el estatus final de promoción.

---

## 🛠️ Instalación y Entorno de Desarrollo

Para poner en marcha este proyecto en tu entorno local:

1. **Clonar el repositorio** y acceder a la carpeta del proyecto.
2. **Instalar dependencias** de PHP usando Composer:
   ```bash
   composer install
   ```
3. **Configurar Entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Base de Datos:** Configurar credenciales en el `.env` (crear una base de datos vacía, ej. `gestichalbaud`).
5. **Ejecutar Migraciones y Población de Datos (Seeders):**
   El sistema cuenta con fábricas de datos (`SystemTestDataSeeder`) que generarán todo un ecosistema de prueba (Roles funcionales, 100 estudiantes, 20 docentes, catálogo de geografía, materias, alergias y actividades).
   ```bash
   php artisan migrate:fresh
   php artisan db:seed --class=SystemTestDataSeeder
   ```
6. **Ejecución:** Desplegar usando Laragon o `php artisan serve`.
   *(Nota: Al acceder a través de túneles externos temporales, usar `php artisan optimize:clear` para limpiar caché de vistas).*
