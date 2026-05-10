# Especificación Técnica y Requerimientos: Proyecto SEPAEZ
**Unidad Educativa Distrital "Páez"**

---

## 1. Visión General del Arquitecto
Como responsable técnico de este proyecto, el objetivo primordial es la transición de una gestión basada en papel a una arquitectura digital robusta, escalable y segura. El sistema **SEPAEZ** no es solo un CRUD; es un ecosistema institucional que integra la jerarquía académica con la realidad socio-pedagógica del plantel.

### Pilares Fundamentales:
*   **Integridad de Datos:** Garantizar que la relación Representante-Estudiante sea inquebrantable y auditada.
*   **Seguridad por Diseño:** Implementación de capas de protección desde el nivel de red hasta la aplicación.
*   **Usabilidad Profesional:** Interfaz limpia que reduce la carga cognitiva del personal docente y administrativo.

---

## 2. Stack Tecnológico (Core Stack)

El sistema se fundamenta en un stack moderno, priorizando la estabilidad a largo plazo:

*   **Lenguaje:** PHP 8.2+ (Tipado estricto habilitado).
*   **Framework:** Laravel 12.x (Aprovechando las últimas mejoras en rendimiento y seguridad).
*   **Motor de Base de Datos:** MySQL 8.0 (Uso intensivo de llaves foráneas e índices para optimizar consultas de reportes).
*   **Frontend Engine:** Vite 6.0+ para el bundling de assets.
*   **Estilizado:** Tailwind CSS (Diseño atómico y responsivo).
*   **Runtime Local:** Laragon / XAMPP (Entornos WAMP/LAMP).

---

## 3. Arquitectura del Sistema

### 3.1. Patrón de Diseño
Se utiliza el patrón **MVC (Modelo-Vista-Controlador)**. Sin embargo, para mantener la lógica de negocio aislada, se recomienda la implementación de:
*   **Services Layer:** Para procesos complejos como el cálculo automático de egresos y promociones.
*   **Form Requests:** Centralización de la validación de datos para mantener los controladores delgados (Thin Controllers).

### 3.2. Esquema de Datos (Estrategia)
La base de datos está diseñada de forma normalizada para evitar redundancias:
*   **Entidades Core:** `users`, `students`, `representatives`, `sections`, `grades`, `academic_histories`.
*   **Relaciones N:M:** Gestión de actividades extracurriculares y condiciones de salud/alergias mediante tablas pivote.
*   **Geolocalización:** Soporte para la división política-territorial de Venezuela (Estados, Municipios, Parroquias).

---

## 4. Módulos Funcionales (Especificaciones Técnicas)

### 4.1. Seguridad y Acceso
*   **Auth ID:** El sistema utiliza la **Cédula de Identidad** como identificador único (Primary Key lógica).
*   **RBAC (Role-Based Access Control):**
    *   `Admin`: Acceso a configuración global y logs.
    *   `Trabajador`: Permisos de escritura en asistencia y diagnósticos.
    *   `Representante`: Acceso de solo lectura a sus representados.
*   **Medidas Anti-Abuso:** Captcha obligatorio en el `Login` y limitación de intentos fallidos (Throttling).

### 4.2. Jerarquía Académica Dinámica
*   **Estructura de Niveles:** Soporte para Educación Inicial (3 niveles) y Primaria (6 grados).
*   **Secciones Dinámicas:** Capacidad de instanciar secciones (A, B, C...) bajo demanda, con asignación de turnos (Matutino/Vespertino).

### 4.3. Módulo de Egresados (Lógica de Negocio)
*   **Trigger de Egreso:** Proceso automatizado que detecta la culminación satisfactoria del 6to grado.
*   **Persistencia Histórica:** Almacenamiento de fotos de perfil, tallas, y contacto digital (WhatsApp/Facebook) para trazabilidad post-escolar.

---

## 5. Estándares de UI/UX

El diseño debe ser **Premium** y **Accesible**:
*   **Paleta:** Azul Profundo (`#032e5e`), Beige Hueso (`#f8f3f0`) y Ámbar Tierra (`#c56c39`).
*   **Tipografía:** `Circular Std` (Legibilidad óptima en reportes densos).
*   **Interactividad:** Implementación de menús colapsables (Hamburger Menu) y transiciones suaves mediante CSS puro y Vanilla JS.

---

## 6. Plan de Despliegue y Mantenimiento

### 6.1. Entorno de Producción Recomendado
*   **Servidor:** Linux (Ubuntu Server 22.04 LTS).
*   **Web Server:** Nginx con PHP-FPM.
*   **Certificación:** SSL/TLS vía Let's Encrypt.

### 6.2. Estrategia de Backup
*   Backups automáticos diarios de la base de datos MySQL.
*   Versionamiento de código vía Git (GitHub/GitLab).

---

## 7. Próximos Pasos Técnicos
1.  Finalización de la Landing Page responsiva.
2.  Implementación del módulo de autenticación con lógica de recuperación por preguntas de seguridad.
3.  Desarrollo de los Wizards de inscripción (Multistep Forms) para facilitar la carga de datos masivos.

---
**Elaborado por:** Senior Software Architect
**Fecha de Revisión:** Mayo, 2026
