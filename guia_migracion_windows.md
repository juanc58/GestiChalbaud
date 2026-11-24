# 📘 Guía Maestra de Despliegue y Migración en Windows (con Laragon)
### GestiChalbaud

---

## 🎯 1. Introducción y Propósito

Esta guía didáctica está diseñada para que cualquier persona, sin importar su experiencia técnica previa, pueda **desplegar y poner en marcha el sistema GestiChalbaud** en cualquier computadora con sistema operativo Windows utilizando **Laragon**.

El paquete de despliegue ha sido preparado de manera **completamente autónoma, limpia y ligera**:
* ✅ **No requiere descargar Composer** ni paquetes de PHP (la carpeta `vendor/` ya viene completa e integrada).
* ✅ **No requiere instalar Node.js ni compilar estilos** (la interfaz carga Tailwind CSS y Alpine.js mediante CDN optimizado).
* ✅ **Incluye la base de datos actual completa** con sus 35 tablas, catálogo geográfico de Venezuela, 75 usuarios registrados y 102 estudiantes con sus historiales.
* ✅ **Incluye un script de 1 solo clic** (`importar_base_datos.bat`) para importar la base de datos sin tener que escribir comandos.

---

## 📦 2. Contenido del Paquete de Despliegue (`GESTICHALBAUD_Despliegue`)

En tu **Escritorio** se encuentra la carpeta `GESTICHALBAUD_Despliegue`. Su estructura es la siguiente:

```text
GESTICHALBAUD_Despliegue/
├── 📁 GestiChalbaud/                    -> Carpeta completa del proyecto lista para operar.
│   ├── 📁 app/                         -> Controladores, modelos y lógica de negocio.
│   ├── 📁 bootstrap/                   -> Arranque del framework y configuración inicial.
│   ├── 📁 config/                      -> Configuraciones generales del sistema.
│   ├── 📁 database/                    -> Migraciones, seeders y copia del respaldo SQL.
│   ├── 📁 public/                      -> Punto de entrada web, logotipos y recursos visuales.
│   ├── 📁 resources/                   -> Vistas en Blade y componentes de la interfaz.
│   ├── 📁 routes/                      -> Enrutamiento del sistema web (web.php).
│   ├── 📁 storage/                     -> Registros (logs), sesiones y archivos temporales listos.
│   ├── 📁 vendor/                      -> Todas las librerías de PHP ya descargadas y enlazadas.
│   ├── 📄 .env                         -> Configuración preestablecida para Laragon (root, sin clave).
│   ├── 📄 artisan                      -> Consola de herramientas de Laravel.
│   └── 📄 composer.json                -> Definición de dependencias de PHP.
├── 📄 gestichalbaud_backup_actual.sql   -> Respaldo SQL completo y limpio en UTF-8 (35 tablas con datos).
├── ⚡ importar_base_datos.bat           -> Asistente de doble clic para importar la base de datos en 3 segundos.
├── 📄 LEEME_PRIMERO.txt                 -> Resumen de lectura rápida en 3 pasos.
└── 📘 GUIA_MIGRACION_WINDOWS.md         -> Esta guía exhaustiva paso a paso.
```

---

## 💻 3. Requisitos Previos en la Computadora Destino

1. **Sistema Operativo:** Windows 10 o Windows 11 (64 bits).
2. **Laragon Full:** 
   - Si la computadora destino aún no tiene Laragon, descárgalo gratis desde su web oficial: [https://laragon.org/download/](https://laragon.org/download/) (selecciona **Laragon - Wamp (PHP 8.x, MySQL 8.x, Apache)**).
   - Instálalo dejando la ruta recomendada: `C:\laragon`.
3. **Navegador Web:** Google Chrome, Microsoft Edge, Mozilla Firefox o Brave.

---

## 🚀 4. Proceso de Despliegue Paso a Paso

Sigue estos sencillos pasos en orden cronológico:

---

### Paso 1: Copiar la carpeta del proyecto a Laragon

1. Abre la carpeta `GESTICHALBAUD_Despliegue` que está en el **Escritorio**.
2. Copia la carpeta llamada `GestiChalbaud`.
3. Dirígete a la unidad `C:` de tu equipo y entra a la ruta donde está instalado Laragon:
   ```text
   C:\laragon\www\
   ```
4. Pega allí la carpeta `GestiChalbaud`.
   
> 💡 **Verificación:** Debe quedar exactamente en: `C:\laragon\www\GestiChalbaud`.

---

### Paso 2: Iniciar Laragon

1. Abre el programa **Laragon** desde el acceso directo de tu Escritorio o el menú Inicio.
2. Haz clic en el botón principal **"Start All"** (o "Iniciar todo").
3. Verás que los textos de **Apache** y **MySQL** quedan activos mostrando sus puertos (`Apache: 80, 443` y `MySQL: 3306`).

> ⚠️ *Si el Firewall de Windows te pide permiso para permitir las redes de Apache o MySQL, marca ambas casillas y pulsa "Permitir acceso".*

---

### Paso 3: Importar la Base de Datos Actual

Tienes **3 opciones** para hacer esto. La opción A es la más fácil y recomendada:

#### 🟢 Opción A: Mediante el Asistente de 1 Clic (Recomendado)
1. En la carpeta `GESTICHALBAUD_Despliegue` del Escritorio, haz doble clic sobre el archivo:
   ```text
   importar_base_datos.bat
   ```
2. El script detectará automáticamente tu instalación de Laragon, creará la base de datos `gestichalbaud` (con codificación UTF-8 internacional) e importará todas las tablas y registros.
3. Al finalizar, verás el mensaje: `ÉXITO: La base de datos 'gestichalbaud' fue importada satisfactoriamente!`. Presiona cualquier tecla para cerrar.

---

#### 🔵 Opción B: Mediante HeidiSQL (Interfaz Visual de Laragon)
Si prefieres ver la base de datos de manera gráfica:
1. En la ventana de Laragon, haz clic en el botón **"Database"** (abrirá el gestor gráfico HeidiSQL).
2. En la pantalla de conexión, deja los valores por defecto:
   - **Tipo de red:** MariaDB o MySQL (TCP/IP).
   - **Hostname / IP:** `127.0.0.1` o `localhost`.
   - **Usuario:** `root`.
   - **Contraseña:** *(vacía o en blanco)*.
   - **Puerto:** `3306`.
   - Haz clic en **"Abrir"**.
3. En la barra superior de HeidiSQL, ve a **Archivo** ➔ **Cargar archivo SQL...** (o pulsa `Ctrl + O`).
4. Selecciona el archivo `gestichalbaud_backup_actual.sql` ubicado en la carpeta `GESTICHALBAUD_Despliegue`.
5. Si HeidiSQL te pregunta si deseas ejecutarlo de inmediato o cargarlo en el editor, elige **"Ejecutar directamente"** (o ábrelo y pulsa la tecla `F9` / icono del botón azul de reproducción ▶).
6. Presiona `F5` en la lista de bases de datos de la izquierda y verás aparecer `gestichalbaud` con todas sus tablas.

---

#### 🟡 Opción C: Mediante la Terminal de Laragon (Línea de Comandos)
1. En la ventana de Laragon, haz clic en el botón **"Terminal"**.
2. Ejecuta el comando para crear la base de datos:
   ```bash
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS gestichalbaud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```
3. Luego realiza la importación indicando la ruta del archivo:
   ```bash
   mysql -u root gestichalbaud < "C:\Users\juank\Desktop\GESTICHALBAUD_Despliegue\gestichalbaud_backup_actual.sql"
   ```
   *(Ajusta la ruta si tu usuario de Windows es distinto).*

---

### Paso 4: Verificación del Archivo de Entorno (`.env`)

Dentro de `C:\laragon\www\GestiChalbaud`, el archivo `.env` ya viene completamente preconfigurado para Laragon con los siguientes valores estándar:

```env
APP_NAME=GestiChalbaud
APP_ENV=local
APP_KEY=base64:DkqLtiJeIvH3wzt2aNGAGSDRfAbRpnPBS96wsSqm0Yc=
APP_DEBUG=true
APP_URL=http://gestichalbaud.test

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_ES

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestichalbaud
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

> 📌 **¿Cuándo deberías editar este archivo?**
> Únicamente si en la computadora destino le colocaste una contraseña al usuario `root` de MySQL (por ejemplo `root` o `1234`). En ese caso, abre `.env` con el Bloc de notas y cambia `DB_PASSWORD=tu_contraseña`.

---

### Paso 5: Acceder al Sistema en el Navegador

Laragon genera automáticamente un dominio virtual local.

1. Abre tu navegador web favorito (Chrome, Edge, Firefox).
2. Escribe en la barra de direcciones:
   ```text
   http://gestichalbaud.test
   ```
   *(o simplemente `gestichalbaud.test`)*.

3. ¡Listo! Verás la página de bienvenida oficial de **GestiChalbaud**, con el escudo de la institución, banner institucional y botones de inicio de sesión.

> 💡 **Enlace alternativo directo:**
> Si por alguna configuración de red tu computadora no resuelve de inmediato los dominios `.test`, puedes ingresar directamente escribiendo:
> ```text
> http://localhost/GestiChalbaud/public
> ```

---

## 🔑 5. Credenciales de Acceso al Sistema

El sistema autentica a los usuarios mediante su **Cédula de Identidad**, **Contraseña** y un sencillo **cálculo aritmético (captcha)** de seguridad.

### 👑 Cuenta Administrador Principal:
* **Cédula:** `12345678`
* **Contraseña:** `Admin123*`
* **Rol:** Administrador (`admin`)
* **Nombre registrado:** Administrador Chalbaud
* **Acceso completo:** Gestión de directivos, docentes, secciones, asignaturas, grados (Inicial y Primaria), inscripciones estudiantiles, egresados, cédulas escolares y reportes.

### 👨‍🏫 Usuarios y Roles Registrados en la Base de Datos:
* La base de datos ya contiene **75 usuarios** registrados y clasificados por roles:
  * **1 Administrador** (`admin`)
  * **1 Docente** (`docente`)
  * **21 Trabajadores / Personal escolar** (`worker`)
  * **52 Representantes** (`representative`)
* Total de **102 estudiantes** activos e históricos matriculados en las secciones correspondientes.

---

## 🛠️ 6. Mantenimiento y Comandos Útiles (Opcional)

Si en el futuro realizas modificaciones o notas que alguna plantilla no refresca sus cambios:

1. **Limpiar cachés de vistas, rutas y configuración:**
   Abre la **Terminal** de Laragon (`C:\laragon\www\GestiChalbaud`) y ejecuta:
   ```bash
   php artisan optimize:clear
   ```
2. **¿Es necesario ejecutar `php artisan migrate`?**
   **NO.** Porque al importar `gestichalbaud_backup_actual.sql`, todas las tablas, relaciones e índices ya quedaron creados exactamente igual que en el entorno de desarrollo original.

---

## ❓ 7. Preguntas Frecuentes y Solución de Problemas

### ❌ 1. "Entro a `gestichalbaud.test` y el navegador dice que no encuentra el sitio"
* **Solución 1:** En Laragon, haz clic derecho en cualquier parte de la ventana ➔ ve a **Tools** ➔ **Quick app** ➔ haz clic en **Fix Virtual Hosts**. Laragon te pedirá permisos de administrador para añadir la redirección en el archivo `hosts` de Windows.
* **Solución 2:** Como alternativa inmediata, entra por la dirección:
  `http://localhost/GestiChalbaud/public`

---

### ❌ 2. "Error SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'"
* **Causa:** Laragon en esa máquina tiene una contraseña configurada en MySQL y el archivo `.env` tiene `DB_PASSWORD=` vacío.
* **Solución:** Abre `C:\laragon\www\GestiChalbaud\.env` con el Bloc de notas y asigna la contraseña en `DB_PASSWORD=`.

---

### ❌ 3. "Error SQLSTATE[HY000] [1049] Unknown database 'gestichalbaud'"
* **Causa:** No se ha importado el respaldo de la base de datos.
* **Solución:** Ejecuta el archivo `importar_base_datos.bat` de la carpeta `GESTICHALBAUD_Despliegue` o importa el archivo mediante HeidiSQL.

---

### ❌ 4. "Error SQL (1273) Unknown collation: 'utf8mb4_0900_ai_ci'"
* **Causa:** Ocurre en versiones antiguas de MariaDB o MySQL 5.7 cuando intentan leer colaciones modernas de MySQL 8.
* **Solución:** El respaldo incluido en este paquete (`gestichalbaud_backup_actual.sql`) ya fue exportado y saneado con **`utf8mb4_unicode_ci`**, por lo que es universalmente compatible y no presentará este error.

---

### ❌ 5. "Error 500 (Server Error) al cargar la página"
* **Solución:** Abre la **Terminal** de Laragon y escribe:
  ```bash
  cd C:\laragon\www\GestiChalbaud
  php artisan optimize:clear
  ```

---

### ❌ 6. "¿Cómo genero un respaldo nuevo de la base de datos en el futuro?"
Para crear un nuevo archivo de respaldo después de haber registrado nuevos estudiantes:
1. Abre la **Terminal** de Laragon.
2. Ejecuta:
   ```bash
   mysqldump -u root --default-character-set=utf8mb4 --routines --triggers --no-tablespaces --result-file="C:\laragon\www\GestiChalbaud\database\nuevo_respaldo.sql" gestichalbaud
   ```
3. Tu nuevo respaldo quedará guardado con todas las actualizaciones.

---

## 🏆 Resumen Final

Siguiendo esta guía, el sistema **GestiChalbaud** queda completamente desplegado y operativo en cualquier máquina con Windows y Laragon. ¡Éxito en la gestión escolar!