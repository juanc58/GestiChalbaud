# Guía de Instalación: SEPAEZ en Linux (XAMPP)

Esta guía detalla los pasos para instalar y configurar el proyecto en un entorno Linux utilizando XAMPP.

## 1. Requisitos Previos
*   Tener instalado **XAMPP for Linux** (LAMPP) en `/opt/lampp`.
*   Tener instalado **Composer** y **Node.js/NPM** en el sistema Linux.
*   Archivo de respaldo `backup_sepaez.sql` generado en Windows.

## 2. Transferencia de Archivos
1.  Copia la carpeta del proyecto `sepaez` a tu directorio de usuario en Linux (por ejemplo: `~/Escritorio/sepaez`).
2.  Asegúrate de que el archivo `backup_sepaez.sql` esté dentro de esa carpeta o en un lugar accesible.

## 3. Configuración de la Base de Datos
1.  **Iniciar XAMPP:**
    ```bash
    sudo /opt/lampp/lampp start
    ```
2.  **Crear la base de datos:**
    Abre la terminal y ejecuta:
    ```bash
    /opt/lampp/bin/mysql -u root -e "CREATE DATABASE IF NOT EXISTS sepaez;"
    ```
3.  **Importar el respaldo:**
    ```bash
    /opt/lampp/bin/mysql -u root sepaez < backup_sepaez.sql
    ```
    *Nota: Si te da error de acceso, intenta con `-h 127.0.0.1` o verifica si tienes contraseña establecida.*

## 4. Configuración del Proyecto (.env)
Edita el archivo `.env` en la carpeta del proyecto en Linux:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sepaez
DB_USERNAME=root
DB_PASSWORD=
# Si usas el socket de XAMPP específicamente:
# DB_SOCKET=/opt/lampp/var/mysql/mysql.sock
```

## 5. Permisos de Carpetas (CRÍTICO)
Laravel necesita permisos de escritura en ciertas carpetas para funcionar en Linux:
```bash
cd ~/Escritorio/sepaez
sudo chown -R $USER:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 6. Instalación de Dependencias
Ejecuta estos comandos dentro de la carpeta del proyecto:
```bash
composer install
npm install
npm run build
```

## 7. Comandos de Uso Diario
Para iniciar tu entorno de trabajo cada vez que enciendas la PC:

1.  **Iniciar XAMPP:** `sudo /opt/lampp/lampp start`
2.  **Iniciar Servidor Laravel:** `php artisan serve`
3.  **Iniciar Compilador (Vite):** `npm run dev` (en otra terminal)

---
**Solución a errores comunes:**
*   **Error 2002 (Socket):** Asegúrate de que MySQL esté corriendo (`sudo /opt/lampp/lampp status`). Si sigue fallando, usa `127.0.0.1` en el `DB_HOST`.
*   **Permisos Denegados:** Vuelve a ejecutar los comandos del paso 5.
