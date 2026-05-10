# Guía de Migración de Proyecto (Windows a Windows con Laragon)

Esta guía te ayudará a mover tu proyecto `sepaez` de una computadora a otra manteniendo toda la configuración y los datos.

## 1. Preparación en la Nueva Computadora
1.  **Instalar Laragon:** Descarga e instala Laragon (Full) desde [laragon.org](https://laragon.org/download/).
2.  **Transferir el Proyecto:** Copia la carpeta `sepaez` de la computadora vieja a la nueva, dentro de `C:\laragon\www\`.
3.  **Copiar el Backup:** Asegúrate de tener el archivo `backup_sepaez.sql` a mano (puedes ponerlo en el escritorio o dentro de la carpeta del proyecto).

## 2. Configuración de Base de Datos
1.  **Abrir Laragon:** Inicia Laragon y dale al botón **"Start All"**.
2.  **Crear la Base de Datos:**
    - Haz clic derecho en cualquier parte de Laragon -> **MySQL** -> **Create new database**.
    - Dale el nombre: `sepaez` (o el que quieras usar).
3.  **Importar el Backup:**
    - Abre la terminal de Laragon (botón **"Terminal"**).
    - Navega hasta donde está tu archivo SQL (ejemplo: `cd C:\Users\TuUsuario\Desktop`).
    - Ejecuta el comando de importación:
      ```bash
      mysql -u root sepaez < backup_sepaez.sql
      ```
      *(Si le pusiste contraseña a MySQL en la nueva PC, usa `mysql -u root -p sepaez < backup_sepaez.sql`)*.

## 3. Configuración del Proyecto (.env)
1.  Entra a `C:\laragon\www\sepaez`.
2.  Busca el archivo `.env`. Si no existe, copia el `.env.example` y renombralo a `.env`.
3.  Asegúrate de que los datos de la base de datos coincidan:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sepaez
    DB_USERNAME=root
    DB_PASSWORD=
    ```

## 4. Instalación de Dependencias
Abre la terminal de Laragon dentro de la carpeta del proyecto y ejecuta:

1.  **Instalar paquetes de PHP:**
    ```bash
    composer install
    ```
2.  **Generar la llave del sitio (solo si es un sitio nuevo):**
    ```bash
    php artisan key:generate
    ```
3.  **Instalar paquetes de diseño:**
    ```bash
    npm install
    npm run build
    ```

## 5. Correr Migraciones (Opcional)
Si ya importaste el `backup_sepaez.sql`, **no necesitas correr migraciones**, ya que el backup trae las tablas creadas.

Si prefieres borrar todo y empezar con una base de datos limpia pero con los datos iniciales (seeders):
```bash
php artisan migrate:fresh --seed
```

## 6. Acceder al proyecto
Laragon creará automáticamente un nombre virtual. Solo abre tu navegador y escribe:
`http://sepaez.test`

---
### Notas importantes:
- Si el sitio `.test` no carga, haz clic derecho en Laragon -> **Tools** -> **Quick app** -> **Fix Virtual Hosts**.
- Si cambiaste la versión de PHP en la computadora vieja, asegúrate de elegir la misma en la nueva (Clic derecho -> PHP -> Version).
