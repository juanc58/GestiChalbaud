Plan Integral de Reestructuración: Desacoplamiento de Representantes, Flujo de Aprobación de Usuarios y Automatización de Docentes

Proyecto: GestiChalbaud
Fecha: 28 de Octubre, 2026
Estado: Propuesta Técnica de Arquitectura e Implementación

1. Diagnóstico del Sistema Actual

Tras el análisis detallado del código fuente y la base de datos de GestiChalbaud, se identificaron las siguientes dinámicas y puntos de dolor:

1.1. El rol "Representante" y su sobrecarga en el sistema
Representante modelado como Usuario del Sistema:
Actualmente existe el rol representative en la tabla roles (con 52 usuarios registrados actualmente).
Cuenta con un portal exclusivo (MyStudentsController, rutas /representative/*), con vistas de consulta y registro propio de alumnos.
En una normalización anterior, los campos de identidad (cedula, first_name, last_name, etc.) fueron extraídos de la tabla representatives y delegados a users mediante una relación 1 a 1 (user_id).
Fricción en la inscripción escolar:
Para asociar un alumno a un representante desde el panel administrativo (StudentController), el sistema obliga a que el representante exista previamente como un User en el sistema.
Se utiliza un componente buscador (rep_selector.blade.php) que busca en users y ejecuta un Representative::firstOrCreate(['user_id' => $user->id]). Esto es innecesariamente complejo cuando el representante solo debe ser una ficha de datos de contacto del estudiante y no un usuario con credenciales de acceso.
1.2. Desconexión y redundancia en la creación de Docentes
Separación rígida entre User y Teacher:
La tabla teachers almacena metadatos laborales (academic_degree, entry_date, status, etc.) vinculados por user_id.
Las aulas (sections) asignan profesores usando la llave foránea teacher_id hacia la tabla teachers, no hacia users.
Si un usuario se registra o un administrador le otorga el rol docente desde la edición de usuarios (UserController@update), no se crea automáticamente el registro en teachers.
Esto obliga al personal administrativo a crear al docente por separado en /teachers/create (donde el controlador intenta crear un usuario duplicado) o genera docentes "huérfanos" que no pueden ser asignados a ninguna sección.
1.3. Ausencia de un flujo de aprobación de usuarios
El registro público actual solo permite registrar representantes y realiza un inicio de sesión automático (Auth::login).
El personal de la institución (docentes, secretarias, directivos, obreros) no dispone de un registro público.
No existe una etapa de auditoría donde un usuario registrado deba esperar a que un administrador lo verifique, le asigne su rol (admin, docente, worker) y active su cuenta antes de poder ingresar.
2. Arquitectura Objetivo
2.1. Principios del Nuevo Diseño
Representante como Ficha de Datos: Los datos del representante (cédula, nombres, apellidos, parentesco, teléfonos, correo, trabajo) residen exclusivamente como metadatos vinculados al estudiante (o en una entidad representatives independiente sin relación con users). Cero cuentas de usuario para representantes.
Registro Institucional Unificado: La ruta pública se convierte en /register, permitiendo a docentes y trabajadores registrar su cuenta, pregunta de seguridad y datos de acceso.
Control de Acceso por Aprobación: Toda cuenta nueva nace inactiva (is_active = false). El administrador recibe una alerta en el Dashboard, la aprueba y le asigna su rol institucional (docente, admin, worker).
Sincronización Automática Docente: La asignación del rol docente (sea por aprobación o edición) crea o actualiza automáticamente el registro correspondiente en la tabla teachers.
3. Plan de Implementación Fase por Fase
Fase 1: Migración de Base de Datos y Desacoplamiento de Representantes
1.1. Modificación de Esquema (representatives)

Se creará una migración que:

Agregue a la tabla representatives los campos de identidad propios:
cedula (integer, indexado, nullable para extranjeros o menores bajo tutela especial).
first_name (string).
second_name (string, nullable).
last_name (string).
second_last_name (string, nullable).
email (string, nullable).
Copie automáticamente los datos existentes desde la tabla users hacia la tabla representatives para los registros actuales (preservando el 100% de la data histórica).
Elimine la llave foránea user_id de representatives.
Elimine las cuentas de usuario de la tabla users que posean el rol representative.
Elimine el rol representative de la tabla roles.
1.2. Limpieza de Rutas y Controladores Obsoletos
Eliminar: Controlador app/Http/Controllers/Representative/MyStudentsController.php.
Eliminar: Carpeta de vistas resources/views/representative/.
Modificar: routes/web.php eliminando el grupo de rutas Route::prefix('representative').
Modificar: resources/views/dashboard.blade.php eliminando la sección "Representante / Mis Representados" del sidebar y las referencias a isRepresentative().
Modificar: app/Models/User.php eliminando la relación representative() y el método isRepresentative().
Fase 2: Rediseño de la Ficha del Estudiante (Datos del Representante Integrados)
2.1. Integración en el Wizard de Inscripción
En resources/views/students/partials/form_sections.blade.php:
Reemplazar el buscador rep_selector.blade.php por un paso o bloque limpio titulado "Datos del Representante":
Cédula de Identidad del Representante (con formateo automático y búsqueda predictiva si ya existe registrado por otro hermano en la escuela).
Primer y Segundo Nombre.
Primer y Segundo Apellido.
Parentesco (Padre, Madre, Abuelo/a, Tío/a, Representante Legal).
Teléfono WhatsApp / Principal.
Teléfono Local / Fijo.
Correo Electrónico.
Ocupación / Oficio.
Dirección de Trabajo.
En resources/views/students/create.blade.php y edit.blade.php:
El paso 6 se simplifica a "Asignación de Aula" o se reordena para que los datos del representante fluyan con naturalidad junto a los datos del entorno familiar.
2.2. Lógica de Negocio en StudentRegistrationService y StudentController
Al guardar o actualizar el estudiante:
Si se suministra la cédula del representante, se busca si ya existe en representatives (para reutilizar ficha de contacto entre hermanos) o se crea/actualiza el registro.
Se vincula directamente $student->representative_id = $representative->id.
Se actualiza Enrollment::create([... 'representative_id' => $representative->id]).
Las vistas de detalle (students/show.blade.php) y boletines (reports/boletin.blade.php) seguirán accediendo a $student->representative->full_name, $student->representative->cedula, etc., de forma transparente y sin cambios drásticos.
Fase 3: Transformación del Formulario de Registro Público
3.1. Reemplazo de /register-representative por /register
Renombrar y actualizar RegisterRepresentativeController a RegisterController (o RegisterUserController).
Ruta pública: GET /register y POST /register.
En resources/views/welcome.blade.php:
Cambiar los enlaces de "Registrarme como Representante" por "Solicitar Registro de Usuario" o "Registrarse".
En la vista del formulario (resources/views/auth/register.blade.php adaptada del actual register_representative.blade.php):
Título: "Registro de Nuevo Usuario — Personal Institucional".
Campos a solicitar:
Cédula de Identidad (Login).
Nombres y Apellidos completos.
Teléfono de contacto y Correo Electrónico.
Contraseña y Confirmación (con validador de fortaleza).
Pregunta y Respuesta de Seguridad.
Campo informativo: Perfil / Cargo institucional solicitado (ej. Docente de Aula, Docente Especialista, Personal Administrativo, Personal Obrero).
3.2. Proceso de Registro sin Auto-Login
El usuario se registra en la base de datos con:
is_active = false (Inactivo / En espera).
role_id = null (o un rol base temporal 'solicitante' si la integridad referencial lo requiere, o rol nullable en migración).
No se ejecuta Auth::login($user).
Redirección a la pantalla de Login (/) con notificación tipo Toast/Alert:

"¡Solicitud de registro enviada con éxito! Tu cuenta está en proceso de revisión. Un administrador validará tu identidad y activará tu acceso con el rol asignado."

3.3. Control en el Inicio de Sesión (LoginController)
En app/Http/Controllers/Auth/LoginController.php:
Si las credenciales son válidas pero !$user->is_active:
Desconectar sesión inmediatamente (Auth::logout()).
Mensaje diferenciado y amigable:

"Tu cuenta se encuentra registrada pero aún no ha sido aprobada por la administración. Por favor comunícate con la dirección del plantel."

Fase 4: Flujo de Aprobación Administrativa de Usuarios
4.1. Notificaciones en el Dashboard
En DashboardController.php:
Contabilizar usuarios pendientes: $pendingUsersCount = User::where('is_active', false)->count();.
En resources/views/dashboard.blade.php:
Mostrar una tarjeta o alerta destacada en el Dashboard para administradores: "Hay X usuarios esperando aprobación y asignación de rol", con enlace directo a la lista filtrada.
Mostrar badge numérico en el menú lateral junto a "Usuarios".
4.2. Módulo de Gestión de Usuarios (UserController y vistas)
En resources/views/users/index.blade.php:
Agregar filtro rápido / pestaña: "Pendientes de Aprobación" (?status=pending).
Cada fila de usuario inactivo tendrá un botón destacado: "Aprobar y Asignar Rol".
En la modal o formulario de aprobación/edición (users/edit.blade.php):
Selector de Rol obligatorio:
Docente
Administrador
Trabajador / Administrativo
Casilla de verificación: "Aprobar y activar acceso al sistema" (is_active = true).
Botón de guardado que procesa la aprobación.
Fase 5: Automatización y Sincronización del Perfil Docente (Cero Redundancia)
5.1. Sincronización en Eventos de Usuario (UserObserver o User::saved)
Implementar un UserObserver o lógica en UserController@update / UserController@approve:
php
if ($user->isDocente()) {
    \App\Models\Teacher::firstOrCreate(
        ['user_id' => $user->id],
        [
            'status'          => 'Activo',
            'gender'          => 'M', // o tomado del formulario si se suministra
            'birth_date'      => now()->subYears(25)->toDateString(),
            'entry_date'      => now()->toDateString(),
            'academic_degree' => 'Docente Institucional',
        ]
    );
}
Resultado Inmediato: En el instante exacto en que el administrador selecciona el rol "Docente" y aprueba al usuario:
El usuario queda activado.
Su ficha en la tabla teachers se crea automáticamente.
Al ir a gestionar cualquier aula (SectionController@edit o asignación de secciones), el nombre del docente aparece inmediatamente en el listado desplegable.
Se elimina por completo la necesidad de registrar dos veces al mismo usuario.
5.2. Armonización del Módulo de Docentes (TeacherController)
En teachers/index.blade.php: lista a los docentes sincronizados.
En teachers/edit.blade.php: permite completar o afinar los detalles pedagógicos del docente (fecha de ingreso real, título universitario específico, turno, etc.) sin tener que tocar los datos básicos de usuario.
En teachers/create.blade.php: se puede simplificar para seleccionar un usuario existente sin rol docente o registrar directamente usuario + docente en una sola acción unificada.
4. Matriz de Cambios por Archivo
Módulo / Archivo	Tipo de Cambio	Descripción
database/migrations/..._restructure_representatives.php	Nuevo	Agrega datos propios a representatives, migra data de users, elimina user_id.
database/migrations/..._make_role_id_nullable_in_users.php	Nuevo	Permite que usuarios recién registrados puedan nacer con rol pendiente.
app/Models/Representative.php	Modificar	Atributos $fillable propios, métodos fullName, elimina delegación a user.
app/Models/User.php	Modificar	Elimina relación representative() y método isRepresentative(). Agrega método isPending().
app/Models/Teacher.php	Mantener	Relación con user intacta.
app/Http/Controllers/Auth/RegisterRepresentativeController.php	Eliminar / Reemplazar	Convertir en RegisterUserController.php para registro general.
resources/views/auth/register_representative.blade.php	Reemplazar	Convertir en resources/views/auth/register.blade.php.
app/Http/Controllers/Auth/LoginController.php	Modificar	Manejo de mensaje específico para cuentas en espera de aprobación.
app/Http/Controllers/Representative/MyStudentsController.php	Eliminar	El portal del representante ya no existe.
resources/views/representative/**	Eliminar	Vistas del portal de representantes obsoletas.
app/Http/Controllers/StudentController.php	Modificar	Guardar datos del representante directamente con el alumno, sin buscar en users.
app/Services/StudentRegistrationService.php	Modificar	Crear/actualizar Representative con los datos ingresados en el formulario del estudiante.
resources/views/students/partials/rep_selector.blade.php	Eliminar / Reemplazar	Reemplazar por inputs directos de datos del representante.
resources/views/students/create.blade.php y edit.blade.php	Modificar	Integrar campos directos del representante en el wizard.
app/Http/Controllers/UserController.php	Modificar	Agregar acción de aprobación rápida y creación automática de Teacher al asignar rol docente.
resources/views/users/index.blade.php	Modificar	Filtro de pendientes y botón de aprobación rápida.
resources/views/dashboard.blade.php	Modificar	Alerta de usuarios pendientes y eliminación de menús de representante.
routes/web.php	Modificar	Reemplazar rutas de registro y eliminar prefijo representative/.
5. Estrategia de Seguridad y Respaldo de Datos
Backup Previo de Base de Datos: Antes de ejecutar cualquier migración destructiva (dropColumn), se realizará un dump SQL completo de la base de datos gestichalbaud.
Migración Segura en 2 Pasos:
Paso A: Crear las nuevas columnas en representatives y poblar datos cruzados desde users.
Paso B: Una vez validada la integridad de los nombres y cédulas, desvincular las llaves foráneas y depurar los usuarios con rol 4.
Compatibilidad hacia atrás: Ningún dato de boletines de notas ni historiales de egresados se verá alterado, ya que las llaves representative_id en students y enrollments continuarán apuntando a la tabla representatives.
6. Siguientes Pasos Recomendados

Una vez aprobado este plan, la ejecución se llevará a cabo en el siguiente orden secuencial:

Paso 1: Ejecución de migraciones y migración de datos existentes de representantes.
Paso 2: Actualización de Modelos y Servicios de Estudiantes (StudentRegistrationService).
Paso 3: Implementación del nuevo formulario /register y lógica en LoginController.
Paso 4: Implementación de la aprobación administrativa en UserController y creación automática del perfil docente.
Paso 5: Pruebas integrales de extremo a extremo (registro de usuario -> aprobación como docente -> asignación en aula; e inscripción de estudiante con datos de representante directos).