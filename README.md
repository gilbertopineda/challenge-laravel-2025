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

---
Proyecto listo para desarrollo local con Docker.
