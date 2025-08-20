# Prueba Técnica OlaClick

Este proyecto es una aplicación Laravel dockerizada que utiliza **PostgreSQL** como base de datos y **Redis** como sistema de caché.

## Requerimientos

- Docker
- Docker Compose

## Servicios incluidos

- **app**: Contenedor con PHP 8.2 (php-fpm-alpine) y Laravel.
- **db**: Contenedor PostgreSQL con volumen persistente.
- **redis**: Contenedor Redis con volumen persistente.

## Configuración del archivo `.env`

En tu archivo `.env` debes asegurar que las siguientes variables estén configuradas correctamente:

```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

CACHE_DRIVER=redis

REDIS_CLIENT=predis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## Configuración del archivo `.env.testing`

Para ejecutar los tests correctamente, crea o actualiza el archivo `.env.testing` con las siguientes variables:

```env
APP_ENV=testing

BCRYPT_ROUNDS=4

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel_testing
DB_USERNAME=laravel
DB_PASSWORD=secret

SESSION_DRIVER=array

QUEUE_CONNECTION=sync

CACHE_DRIVER=array
```

Esto asegura que la base de datos de testing y la configuración de cache y sesiones estén aisladas del entorno de desarrollo.

## Levantar el proyecto

Ejecuta:

```bash
docker-compose up --build
```

Esto iniciará los contenedores de **app**, **db** y **redis**.

El contenedor `app` ejecutará automáticamente:

- `composer install`
- `php artisan migrate --force`
- `php artisan serve --host=0.0.0.0 --port=8000`

La aplicación quedará disponible en:  
[http://0.0.0.0:8000](http://0.0.0.0:8000)

## Endpoints principales

### 1. Listar órdenes
**GET** `/api/orders`  
Retorna todas las órdenes activas (`status != 'delivered'`).  
Usa Redis para cachear el resultado (TTL: 30s).

### 2. Crear una nueva orden
**POST** `/api/orders`

Ejemplo JSON:
```json
{
  "client_name": "Carlos Gómez",
  "items": [
    { "description": "Lomo saltado", "quantity": 1, "unit_price": 60 },
    { "description": "Inka Kola", "quantity": 2, "unit_price": 10 }
  ]
}
```

Estado inicial: `initiated`.

### 3. Avanzar estado de una orden
**POST** `/api/orders/{id}/advance`

Transiciones:
- `initiated → sent → delivered`

Cuando llega a `delivered`, la orden es eliminada de la base de datos y del caché.

### 4. Ver detalle de una orden
**GET** `/api/orders/{id}`  
Muestra datos completos incluyendo items, totales y estado actual.

## Volúmenes persistentes

- Base de datos PostgreSQL → `pgdata`
- Redis → `redisdata`

De esta forma, los datos no se perderán al reiniciar los contenedores.

## Ejecutar pruebas automatizadas

Este proyecto incluye tests para validar la funcionalidad de la API. Los tests usan **Pest** y se ejecutan dentro del contenedor `app`.

### 1. Levantar el proyecto e iniciar los contenedores

### 2. Ejecutar los tests

Dentro del contenedor, ejecuta:

```bash
php artisan test
```

Esto correrá todos los tests ubicados en el directorio `tests/Feature`.

### 3. Qué validan los tests

Los tests incluidos comprueban:

1. Crear una orden con items correctamente.
2. Consultar los detalles de una orden.
3. Listar todas las órdenes existentes y validar la cantidad.
4. Avanzar el estado de una orden (`initiated → sent → delivered`).
5. Eliminar una orden cuando llega a estado `delivered`.

### 4. Consideraciones

- Antes de ejecutar los tests, asegúrate de que la base de datos de testing esté configurada correctamente y que `.env.testing` contenga las variables recomendadas.
- Las factories se utilizan para generar órdenes e items de manera dinámica durante las pruebas.
- La cache y las sesiones se manejan en memoria usando el driver `array`.

---
Proyecto listo para desarrollo local con Docker y ejecución de pruebas automatizadas.
