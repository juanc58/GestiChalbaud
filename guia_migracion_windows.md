# Guía de Migración de Proyecto GestiChalbaud (Windows a Windows con Laragon)

Esta guía te ayudará a mover el proyecto **GestiChalbaud** de una computadora a otra manteniendo toda la configuración y los datos.


## 2. Preparación en la Nueva Computadora

1. **Instalar Laragon:** Descarga e instala Laragon (Full) desde [laragon.org](https://laragon.org/download/).
2. **Transferir el Proyecto:** Copia la carpeta completa de `GestiChalbaud` de la computadora vieja a la nueva, dentro de:
   `C:\laragon\www\GestiChalbaud`
3. **Copiar el Backup:** Coloca tu archivo `backup_gestichalbaud.sql` en un lugar accesible en la nueva PC (como el Escritorio).

---

## 3. Configuración de Base de Datos (PC Destino)

1. **Abrir Laragon:** Inicia Laragon y haz clic en **"Start All"**.
2. **Crear la Base de Datos:**
   * Haz clic derecho en cualquier parte de la ventana de Laragon -> **MySQL** -> **Create new database**.
   * Escribe el nombre exacto: `gestichalbaud` y haz clic en Aceptar.
3. **Importar el Backup:**
   
   * **Método 1 (Por HeidiSQL):**
     1. Entra a HeidiSQL desde Laragon mediante el boton que dice "base de datos".
     2. Ve a **File** (Archivo) -> **Load SQL file...** (Cargar archivo SQL...) y abre `backup_gestichalbaud.sql`.
     3. Si te pregunta si quieres cargar el archivo completo en el editor de pestañas, di que Sí.
     4. Haz clic en el botón azul de **Play** (Ejecutar SQL) en la barra de herramientas o presiona `F9`.
* **Método 2 (Por la Terminal):**
     1. Haz clic en el botón **"Terminal"** de Laragon.
     2. Navega al escritorio o carpeta donde guardaste tu backup:
        ```bash
        cd C:\Users\TuUsuario\Desktop
        ```
     3. Ejecuta el comando de importación:
        ```bash
        mysql -u root gestichalbaud < backup_gestichalbaud.sql
        ```
---

## 4. Configuración del Proyecto (.env)

1. Entra a la carpeta del proyecto `C:\laragon\www\GestiChalbaud`.
2. Busca el archivo `.env`. Si no existe, haz una copia del archivo `.env.example` y cámbiale el nombre a `.env`.
3. Asegúrate de que las credenciales de conexión de la base de datos sean correctas:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gestichalbaud
   DB_USERNAME=root
   DB_PASSWORD=
   ```

---

## 5. Acceder al Proyecto

Laragon creará automáticamente un host virtual para el proyecto. Abre tu navegador web favorito y accede a:
`http://gestichalbaud.test`

---

### Solución de Problemas Frecuentes

* **El enlace `.test` no carga o da error 404:**
  Haz clic derecho en Laragon -> **Tools** -> **Quick app** -> **Fix Virtual Hosts**. Luego reinicia Laragon (Stop -> Start All).
* **Error de conexión a la base de datos (Database Connection Error):**
  Verifica que el servicio MySQL de Laragon esté activo y que los valores del archivo `.env` coincidan exactamente con la base de datos creada en el paso 3.
* **Error de permisos o archivos bloqueados:**
  Asegúrate de ejecutar la terminal de Laragon o tu terminal con permisos de Administrador si notas problemas al compilar o renombrar carpetas.
* **Error SQL (1273) Unknown collation: 'utf8mb4_0900_ai_ci':**
  Este error ocurre porque la PC de origen exportó la base de datos usando la colación moderna de MySQL 8.0+, pero la PC de destino tiene una versión anterior de MySQL o MariaDB que no la reconoce.
  
  **Solución rápida (Reemplazar la colación en el archivo SQL):**
  1. Abre el archivo `backup_gestichalbaud.sql` con un editor de texto (como VS Code, Notepad++ o el Bloc de Notas).
  2. Presiona `Ctrl + H` (o ve a Buscar y Reemplazar).
  3. En **Buscar** escribe: `utf8mb4_0900_ai_ci`
  4. En **Reemplazar con** escribe: `utf8mb4_unicode_ci` (o `utf8mb4_general_ci`).
  5. Selecciona **Reemplazar todo** y guarda el archivo.
  6. Vuelve a importar el archivo SQL corregido.
  
  *(Si el archivo es muy pesado y tu editor se congela al abrirlo, abre la terminal de PowerShell en la carpeta donde tienes el archivo y ejecuta este comando para corregirlo al instante:)*
  ```powershell
  (Get-Content -Path "backup_gestichalbaud.sql" -Encoding UTF8) -replace 'utf8mb4_0900_ai_ci', 'utf8mb4_unicode_ci' | Set-Content -Path "backup_gestichalbaud.sql" -Encoding UTF8
  ```
