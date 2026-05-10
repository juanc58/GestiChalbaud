He actualizado el archivo **.md** para integrar los nuevos requisitos sobre la estructura organizativa de la **Unidad Educativa Distrital "Páez"** y el nuevo módulo de egresados.

```markdown
# Requerimientos del Sistema de Gestión Escolar - U.E.D. "Páez"

Este documento define la estructura, seguridad y módulos funcionales para la digitalización de los procesos administrativos y pedagógicos del plantel.

## 1. Módulo de Autenticación y Seguridad
*   **Identificador de Usuario:** Ingreso mediante número de **Cédula** (campo de tipo **Integer** exclusivamente).
*   **Contraseña:** Política de seguridad que exige al menos **un número** y **un carácter especial**.
*   **Seguridad:** Implementación obligatoria de **Captcha** en el inicio de sesión.
*   **Recuperación:** Sistema de recuperación de cuenta basado en **preguntas de seguridad**.

## 2. Perfiles de Usuario (Roles)
1.  **Administrador:** Control total del sistema, incluyendo la creación de secciones y gestión de usuarios globales.
2.  **Trabajador (Docentes/Directivos):** Gestión de diagnósticos, asistencia y reportes pedagógicos.
3.  **Representante:** Consulta de datos, actualización de información de contacto y seguimiento del estudiante.

## 3. Estructura Organizativa y Agrupación
El sistema debe organizar la información de estudiantes y representantes de forma jerárquica:

*   **Niveles Educativos:**
    *   **Educación Inicial:** Primer, segundo y tercer nivel.
    *   **Educación Primaria:** De primer (1°) a sexto (6°) grado.
*   **Gestión de Secciones:** El sistema debe permitir la **creación dinámica de nuevas secciones** (ej. A, B, C...) para cada grado o nivel, permitiendo agrupar a los alumnos y sus respectivos representantes según su ubicación escolar actual.
*   **Turnos:** Opción de asignar grupos al turno de la **mañana** o la **tarde**.

## 4. Gestión de Datos (CRUD)
Basado en los formularios físicos del plantel:
*   **Estudiante:** Datos básicos, fotos, tallas (camisa, pantalón, zapatos), peso, estatura, condiciones de salud y diversidad cognitiva.
*   **Representante:** Datos de identificación, laborales, y contacto digital (Email, **WhatsApp**, Facebook).
*   **Vínculos:** Asociación directa entre el representante y el estudiante dentro de su grado y sección correspondiente.

## 5. Módulo de Egresados
Módulo especializado para el seguimiento histórico de la institución:
*   **Activación:** El sistema debe detectar y procesar automáticamente a los estudiantes que culminan satisfactoriamente el **6to grado**.
*   **Datos de Egreso:** Registro del **año de promoción/egreso**, historial académico resumido y datos de contacto finales.
*   **Consulta:** Repositorio histórico donde se pueda observar la lista de estudiantes egresados por año escolar.

## 6. Módulo Pedagógico y Administrativo
*   **Momentos Pedagógicos:** Registro de diagnósticos, proyectos de aprendizaje, formación personal, social y relación con el ambiente.
*   **Asistencia:** Control de días hábiles, asistencias e inasistencias.
*   **Boletas de Retiro:** Generación de documentos en caso de que el estudiante se retire antes de culminar el ciclo, especificando el motivo.

## 7. Interfaz de Usuario (UI)
*   **Navegación:** Menú lateral desplegable (tres rayitas) para facilitar el acceso a registros, consultas y reportes sin obstruir la visual.
```

### Cambios principales realizados:
*   **Jerarquía Escolar:** Se incorporó la distinción entre los tres niveles de inicial y los seis grados de primaria, permitiendo que la información se agrupe bajo estos criterios.
*   **Flexibilidad:** Se añadió la funcionalidad para que el **Administrador** cree nuevas secciones según la demanda del plantel escolar.
*   **Módulo de Egresados:** Se definió un espacio para los estudiantes que terminan 6to grado, permitiendo mantener un registro histórico del año de egreso, complementando la información de las fichas de inscripción y boletas de retiro.

¿Deseas que agreguemos algún detalle específico sobre qué otros datos debería guardar el historial de los egresados (como promedio o institución a la que se dirigen)? Acompañé la respuesta con citas a tus fuentes originales.