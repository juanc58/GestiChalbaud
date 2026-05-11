--------------------------------------------------------------------------------
# Manual de Identidad Visual: GestiChalbaud

Este documento define las pautas visuales para el sistema de gestión académica y administrativa de la **Unidad Educativa Bolivariana Coronel Carlos Delgado Chalbaud** [3, 4].

## 1. El Logotipo Institucional
La identidad visual del sistema nace directamente del emblema oficial de la institución. El logo es de carácter circular, representando la integración de la comunidad educativa [2].

### Elementos Clave del Logo:
*   **Sol Central:** Un sol de múltiples puntas en color amarillo que simboliza la luz del conocimiento y la energía del aprendizaje [2].
*   **Anillos Concéntricos:** Estructura que organiza la información jerárquicamente, desde el núcleo (el sol) hasta la identificación institucional exterior [2].
*   **Tipografía Institucional:** El texto "ESCUELA BASICA CNEL CARLOS DELGADO CHALBAUD" rodea el diseño en una disposición circular legible [2].

## 2. Paleta de Colores (Identificación HEX)
Los colores seleccionados mantienen la coherencia con la simbología patria y escolar presente en el logo institucional [2].

| Color | Muestra | Código HEX | Aplicación en el Sistema |
| :--- | :---: | :--- | :--- |
| **Rojo Institucional** | 🟥 | `#D32F2F` | Botones de acción importante, alertas y acentos de jerarquía alta. |
| **Azul Marino** | 🟦 | `#1A237E` | Barras de navegación (navbar), títulos de sección y textos principales. |
| **Amarillo Sol** | 🟨 | `#FBC02D` | Iconografía secundaria, estados de "Pendiente" y resaltados visuales. |
| **Crema / Beige** | 🟨 | `#FFF59D` | Fondos de formularios, contenedores de datos y áreas de lectura suave. |

## 3. Pautas de Aplicación en la Interfaz (UI) - Arquitectura SaaS
Para garantizar una experiencia de usuario (UX) moderna, escalable y profesional, el sistema utiliza una arquitectura tipo **SaaS Dashboard (App Shell)** construida con **Tailwind CSS**.

### 3.1 Estructura Principal (Layout)
1. **Contenedor Raíz Inmutable:** El `body` de la aplicación carece de scroll global. Se utiliza `flex h-screen bg-[#F9FAFB] overflow-hidden` para congelar la pantalla, delegando el scroll exclusivamente al área de contenido principal. Esto mantiene la barra lateral y superior siempre fijas.
2. **Sidebar (Navegación Lateral):** 
   * Diseño limpio con fondo blanco y bordes derechos sutiles (`border-r border-gray-200`).
   * Separado en categorías con títulos en mayúscula (GENERAL, ADMINISTRACIÓN, DOCENCIA).
   * **Estados Activos:** Usan el Azul Marino Institucional combinado con un fondo suave (ej. `bg-indigo-50`). Los inactivos usan tonos grises con transición al hacer hover.
   * El *footer* del sidebar se mantiene anclado en la parte inferior conteniendo los accesos de Perfil y Cerrar Sesión.
3. **Navbar Contextual (Top Bar):**
   * Incorpora *Breadcrumbs* que muestran dinámicamente en qué módulo se encuentra el usuario.
   * Incluye un indicador de "Sesión Activa" en verde y un Avatar circular con iniciales usando el fondo Azul Marino.
4. **Área de Contenido (Content Area):**
   * Posee su propio scroll independiente (`overflow-y-auto`).
   * El contenedor interno está centrado y topado (`mx-auto max-w-[1600px]`) para evitar deformaciones en pantallas ultrawide, manteniendo las proporciones ideales de lectura.

### 3.2 Componentes Modernizados
1. **Tarjetas y Widgets:** Se abandonaron las sombras pesadas y bordes redondeados extremos. El estándar actual exige:
   * Radios de borde medios: `rounded-xl`
   * Sombras sutiles y elegantes: `shadow-sm`
   * Bordes finos de separación: `border border-gray-200`
2. **Botones de Acción:**
   * *Acción Primaria (Crear/Avanzar):* Amarillo Sol (`#FBC02D`) con texto oscuro o Azul Marino para resaltar en la jerarquía visual.
   * *Acción Destructiva/Secundaria (Eliminar/Cancelar):* Rojo Institucional (`#D32F2F`) o textos grises (`text-gray-500`).
3. **Estados Vacíos (Empty States):**
   * Módulos o paneles sin datos deben mostrar un contenedor de estado vacío con fondo gris tenue (`bg-gray-50`), bordes punteados discretos y un texto orientativo, evitando espacios en blanco que denoten error.

## 4. Archivos Relacionados
*   **Logo original:** `logo.jpeg` [2, 8].