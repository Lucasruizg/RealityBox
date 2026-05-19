# RealityBox

RealityBox es una aplicacion web hecha con Laravel para simular una tienda de cajas sorpresa. La tematica principal es la venta de cajas relacionadas con gaming, tecnologia, moda, cartas coleccionables y franquicias como Pokemon.

El proyecto permite navegar por un catalogo, consultar productos, registrarse, iniciar sesion, administrar productos desde un panel de administrador y simular la apertura online de cajas usando saldo demo.

## Estado del Proyecto

El proyecto cubre la mayoria de requisitos de una tienda sencilla en Laravel:

- Registro funcional de usuarios.
- Login funcional.
- Catalogo publico de productos.
- Detalle de producto.
- Introduccion, edicion y eliminacion de productos desde un dashboard de administrador.
- Simulacion de compra/apertura de cajas con saldo demo.
- Inventario de recompensas para usuarios autenticados.
- Diseno responsive con CSS personalizado.
- Base de datos mediante migraciones, seeders y archivo SQLite incluido.

Nota importante: actualmente no hay una cesta de compra clasica con cantidades, total y checkout. La simulacion de compra se realiza mediante la apertura de cajas con saldo demo.

## Tecnologias Usadas

- PHP 8.2 o superior.
- Laravel 12.
- Laravel Breeze para autenticacion.
- Blade para las vistas.
- MySQL configurado en `.env`.
- SQLite incluido en `database/database.sqlite`.
- CSS personalizado en `public/css/style.css`.
- Vite y npm para assets base.
- PHPUnit para tests.

## Requisitos

Para ejecutar el proyecto necesitas:

- PHP 8.2 o superior.
- Composer.
- Node.js y npm.
- MySQL/MariaDB si usas la configuracion actual del `.env`.
- Laragon, XAMPP o entorno equivalente en Windows.

## Instalacion

Entra en la carpeta del proyecto:

```bash
cd DeployApp
```

Instala dependencias de PHP:

```bash
composer install
```

Instala dependencias de Node:

```bash
npm install
```

Copia el archivo de entorno si no existe:

```bash
copy .env.example .env
```

Genera la clave de la aplicacion:

```bash
php artisan key:generate
```

## Configuracion de Base de Datos

El archivo `.env` actual esta preparado para MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=deploysapps
DB_USERNAME=root
DB_PASSWORD=
```

Antes de migrar, crea en MySQL una base de datos llamada:

```text
deploysapps
```

Despues ejecuta:

```bash
php artisan migrate
php artisan db:seed
```

Tambien existe un archivo SQLite en:

```text
DeployApp/database/database.sqlite
```

Si prefieres usar SQLite, cambia el `.env` a:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

## Usuarios de Prueba

El seeder principal crea estos usuarios:

| Rol | Email | Password |
| --- | --- | --- |
| Administrador | `admin@realitybox.local` | `1234` |
| Usuario normal | `test@realitybox.local` | `1234` |

El usuario administrador puede acceder al dashboard y gestionar productos.

## Ejecutar el Proyecto

Servidor Laravel:

```bash
php artisan serve
```

Servidor de assets:

```bash
npm run dev
```

Normalmente la aplicacion quedara disponible en:

```text
http://127.0.0.1:8000
```

Si usas Laragon, tambien puedes acceder mediante el dominio local que tengas configurado.

## Funcionalidades

### Registro

El registro esta implementado con Laravel Breeze.

Ruta:

```text
GET /register
POST /register
```

Cuando un usuario se registra, se guarda en la tabla `users` con la contrasena encriptada.

### Login

El login tambien esta implementado con Breeze.

Ruta:

```text
GET /login
POST /login
```

Al iniciar sesion, el usuario recibe saldo demo para abrir cajas.

### Catalogo

El catalogo muestra las cajas disponibles y permite:

- Buscar por nombre o descripcion.
- Filtrar por categoria.
- Filtrar por rango de precio.
- Ordenar por precio o valoracion.
- Ver detalle de cada caja.

Rutas:

```text
GET /
GET /products
GET /products/{product}
```

### Productos

Los productos se guardan en la tabla `products`.

Campos principales:

- `name`
- `description`
- `price`
- `image`
- `category`
- `rating`
- `reviews`
- `is_featured`
- `is_popular`

Categorias usadas:

- `gaming`
- `fitness`
- `moda`
- `franquicia`
- `tech`
- `cards`
- `lowcost`

### Dashboard de Administrador

El dashboard esta protegido por login y por el middleware `admin`.

Rutas:

```text
GET /dashboard
GET /dashboard/products/create
POST /dashboard/products
GET /dashboard/products/{product}/edit
PUT /dashboard/products/{product}
DELETE /dashboard/products/{product}
```

Desde el dashboard se pueden:

- Crear productos.
- Editar productos.
- Eliminar productos.
- Marcar productos como populares.
- Marcar productos como destacados.

Las imagenes deben estar en:

```text
public/images/products/
```

En el formulario solo se guarda el nombre del archivo, por ejemplo:

```text
gamer-pro.png
```

### Simulacion de Compra

El proyecto no usa una cesta tradicional. En su lugar, simula la compra mediante apertura de cajas.

Funcionamiento:

1. El usuario elige una caja.
2. Pulsa en abrir caja.
3. Se descuenta el precio del saldo demo si el usuario esta autenticado.
4. Se genera una recompensa aleatoria.
5. La recompensa se guarda en el inventario del usuario.
6. El usuario puede guardarla, canjearla o venderla por saldo demo.

Rutas relacionadas:

```text
GET /open-box/{product}
GET /inventory
GET /openings/{boxOpening}
POST /openings/{boxOpening}/{action}
```

Acciones disponibles:

- `keep`: guardar recompensa.
- `redeem`: canjear recompensa.
- `sell`: vender recompensa.

### Inventario

Los usuarios autenticados pueden ver sus aperturas y recompensas desde:

```text
GET /inventory
```

El inventario muestra:

- Premio obtenido.
- Caja de origen.
- Rareza.
- Valor estimado.
- Precio de venta rapida.
- Estado de la recompensa.

### Test de Recomendacion

El proyecto incluye un test que recomienda una caja segun intereses, estilo de premio y presupuesto.

Rutas:

```text
GET /test
POST /resultado
```

## Probabilidades de Recompensa

Las aperturas usan estas probabilidades:

| Rareza | Probabilidad |
| --- | --- |
| Comun | 72% |
| Raro | 20% |
| Epico | 7.2% |
| Legendario | 0.8% |

El valor de venta rapida se calcula como el 70% del valor estimado de la recompensa.

## Base de Datos

Tablas principales:

- `users`: usuarios registrados.
- `products`: productos/cajas del catalogo.
- `box_openings`: aperturas realizadas y recompensas obtenidas.
- `sessions`: sesiones de Laravel.
- `cache`: cache de Laravel.
- `jobs`: cola de trabajos.

Migraciones relevantes:

```text
database/migrations/0001_01_01_000000_create_users_table.php
database/migrations/2026_04_22_071635_create_products_table.php
database/migrations/2026_04_22_080804_add_store_fields_to_products_table.php
database/migrations/2026_04_29_100000_add_is_admin_to_users_table.php
database/migrations/2026_05_13_100000_add_demo_balance_to_users_table.php
database/migrations/2026_05_13_100001_create_box_openings_table.php
```

## Rutas Principales

Publicas:

```text
GET /
GET /products
GET /products/{product}
GET /open-box/{product}
GET /test
POST /resultado
```

Autenticacion:

```text
GET /login
POST /login
GET /register
POST /register
POST /logout
```

Usuario autenticado:

```text
GET /inventory
GET /openings/{boxOpening}
POST /openings/{boxOpening}/{action}
```

Administrador:

```text
GET /dashboard
GET /dashboard/products/create
POST /dashboard/products
GET /dashboard/products/{product}/edit
PUT /dashboard/products/{product}
DELETE /dashboard/products/{product}
```

## Estructura del Proyecto

```text
DeployApp/
  app/
    Http/
      Controllers/
        ProductController.php
      Middleware/
        AdminMiddleware.php
    Models/
      Product.php
      User.php
      BoxOpening.php

  database/
    migrations/
    seeders/
    database.sqlite

  public/
    css/
      style.css
    images/
      products/

  resources/
    views/
      auth/
      products/
      layouts/
      dashboard.blade.php
      dashboard-form.blade.php
      inventory.blade.php
      test.blade.php
      resultado.blade.php
      welcome.blade.php

  routes/
    web.php
    auth.php
```

## Tests

Ejecutar tests:

```bash
php artisan test
```

Tambien:

```bash
composer test
```

Aviso: algunos tests base de Breeze pueden estar desactualizados respecto a las redirecciones actuales del proyecto. El registro y login redirigen a `home`, no al dashboard.

## Comandos Utiles

Ver rutas:

```bash
php artisan route:list
```

Limpiar configuracion:

```bash
php artisan config:clear
```

Recrear base de datos con seeders:

```bash
php artisan migrate:fresh --seed
```

Ejecutar solo el seeder de productos:

```bash
php artisan db:seed --class=ProductSeeder
```

## Evaluacion Segun el Enunciado

| Requisito | Estado |
| --- | --- |
| Entrega de codigo base Laravel | Cumple |
| Entrega de base de datos | Cumple mediante migraciones, seeders y SQLite incluido |
| Registro funcional | Cumple |
| Login funcional | Cumple |
| Introducir productos a vender | Cumple desde dashboard admin |
| Simulacion cesta de compra | Parcial: se simula con saldo demo y apertura de cajas |
| Buen Look & Feel | Cumple |
| Responsive | Cumple |

## Mejoras Pendientes

Para que el proyecto cumpla mejor una entrega de e-commerce clasico, se podrian anadir:

- Cesta de compra tradicional.
- Cantidades por producto.
- Total de compra.
- Confirmacion de pedido.
- Historial de pedidos.
- Pasarela de pago simulada.

Estas mejoras no son imprescindibles para la simulacion actual de RealityBox, pero reforzarian el requisito de "cistella de compra".
