# RealityBox

RealityBox es una aplicación web Laravel para una tienda de cajas sorpresa. Permite explorar cajas por categoría, consultar premios posibles, abrir una caja online con una ruleta de rarezas y administrar el catálogo desde un panel protegido para usuarios administradores.

## Características

- Catálogo público de cajas sorpresa.
- Buscador por nombre o descripción.
- Filtros por categoría, rango de precio y ordenación.
- Página de detalle con premios posibles, rarezas y probabilidades.
- Simulación de apertura online con recompensa aleatoria.
- Test de recomendación basado en intereses, estilo de premio y presupuesto.
- Autenticación con Laravel Breeze.
- Dashboard de administración para crear, editar y eliminar cajas.
- Marcado de productos como populares o destacados.

## Stack

- PHP `^8.2` y Laravel `^12.0`.
- Laravel Breeze para autenticación.
- Blade como motor de vistas.
- SQLite por defecto en `.env.example`.
- Tailwind/Vite instalado para assets base de Breeze.
- CSS principal personalizado en `public/css/style.css`.
- PHPUnit para tests.

## Requisitos

- PHP 8.2 o superior.
- Composer.
- Node.js y npm.
- SQLite, o una base de datos compatible configurada en `.env`.

En este entorno se detectó PHP `8.4.13` y npm `11.6.2`. Composer no estaba disponible en el `PATH` al analizar el proyecto.

## Instalación

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Si usas SQLite, crea antes el archivo de base de datos si no existe:

```bash
type nul > database/database.sqlite
```

En Windows también puedes crearlo manualmente dentro de `database/`.

## Ejecución

Servidor Laravel:

```bash
php artisan serve
```

Assets con Vite:

```bash
npm run dev
```

Build de producción:

```bash
npm run build
```

El proyecto también incluye un script Composer para desarrollo que levanta servidor, cola, logs y Vite:

```bash
composer run dev
```

## Base de Datos

Tablas principales:

- `users`: usuarios autenticados. Incluye el campo `is_admin`.
- `products`: cajas del catálogo.
- `cache`, `jobs` y sesiones: tablas estándar generadas por Laravel.

Campos de `products`:

- `name`
- `description`
- `price`
- `image`
- `category`
- `rating`
- `reviews`
- `is_featured`
- `is_popular`

Categorías usadas por el dashboard:

- `gaming`
- `fitness`
- `moda`
- `franquicia`
- `tech`
- `cards`
- `lowcost`

## Seeders

Seeder base:

```bash
php artisan db:seed
```

Este seeder crea un usuario de prueba:

- Email: `test@example.com`

Seeder de administrador:

```bash
php artisan db:seed --class=AdminUserSeeder
```

Crea o actualiza el usuario administrador:

- Email: `lucas@realitybox.local`
- Password: `lucasrg01`

Nota: estas credenciales están en el código del seeder y deberían cambiarse antes de usar el proyecto en un entorno real.

## Rutas Principales

Rutas públicas:

- `GET /`: home con productos destacados, populares y categorías.
- `GET /products`: catálogo con búsqueda, filtros y paginación.
- `GET /products/{product}`: detalle de una caja.
- `GET /open-box/{product}`: apertura online de una caja.
- `GET /test`: test de recomendación.
- `POST /resultado`: resultado del test.

Rutas de autenticación:

- `GET /login`
- `POST /login`
- `GET /register`
- `POST /register`
- `POST /logout`
- Rutas de recuperación y confirmación de contraseña incluidas por Breeze.

Rutas de administración, protegidas por `auth` y `admin`:

- `GET /dashboard`
- `GET /dashboard/products/create`
- `POST /dashboard/products`
- `GET /dashboard/products/{product}/edit`
- `PUT /dashboard/products/{product}`
- `DELETE /dashboard/products/{product}`

## Funcionamiento de la Apertura

La apertura de cajas se implementa en `ProductController::open`.

Probabilidades:

- Común: `72%`
- Raro: `20%`
- Épico: `7.2%`
- Legendario: `0.8%`

El controlador:

1. Selecciona una rareza con `rollRarity()`.
2. Busca recompensas de esa rareza para la categoría del producto.
3. Calcula un precio de venta rápida del `70%` del valor estimado.
4. Renderiza la vista `resources/views/products/open.blade.php`.

Las recompensas están definidas actualmente en el propio `ProductController`, dentro de `getRewardsByCategory()`.

## Panel de Administración

El acceso al dashboard requiere:

- Usuario autenticado.
- Campo `is_admin = true`.

El middleware `admin` está definido en `app/Http/Middleware/AdminMiddleware.php` y registrado en `bootstrap/app.php`.

Desde el dashboard se pueden gestionar:

- Nombre.
- Categoría.
- Precio.
- Imagen.
- Rating.
- Número de reviews.
- Descripción.
- Estado popular.
- Estado destacado.

Las imágenes de producto deben existir en:

```text
public/images/products/
```

El formulario guarda solo el nombre del archivo, por ejemplo:

```text
gamer-pro.png
```

## Estructura del Proyecto

```text
app/
  Http/
    Controllers/
      ProductController.php
      ProfileController.php
    Middleware/
      AdminMiddleware.php
  Models/
    Product.php
    User.php

database/
  migrations/
  seeders/

public/
  css/style.css
  images/products/

resources/
  views/
    welcome.blade.php
    products/
    dashboard.blade.php
    dashboard-form.blade.php
    test.blade.php
    resultado.blade.php

routes/
  web.php
  auth.php
```

## Tests

Ejecutar la suite:

```bash
php artisan test
```

O mediante el script de Composer:

```bash
composer test
```

El proyecto conserva tests base de Laravel Breeze para autenticación y perfil.

## Comandos Útiles

Limpiar caché de configuración:

```bash
php artisan config:clear
```

Ver rutas:

```bash
php artisan route:list
```

Recrear base de datos local:

```bash
php artisan migrate:fresh --seed
php artisan db:seed --class=AdminUserSeeder
```

## Notas de Desarrollo

- El layout público usado por la tienda está en `resources/views/layouts/app.blade.php`.
- El archivo `resources/views/layouts/navigation.blade.php` pertenece al layout original de Breeze y no es el menú principal actual de la tienda.
- El CSS visual de RealityBox está centralizado en `public/css/style.css`.
- El controlador `ProductController` concentra catálogo, dashboard, test y lógica de premios. Si el proyecto crece, conviene separar la lógica de recompensas y recomendación en servicios propios.
- Algunas cadenas muestran caracteres mal codificados en los archivos (`ComÃºn`, `Ã‰pico`, etc.). Conviene normalizar los archivos a UTF-8 y corregir esos textos.
