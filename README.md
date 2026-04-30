# API Mascotas — REST + MVC en PHP

API REST para gestion de adopciones de mascotas (PostgreSQL, JWT para rutas admin). Sin vistas HTML ni PWA en este repositorio.

## Estructura principal

- `app/`: MVC (Controllers, Services, Models, Forms, Core, Middleware).
- `public/`: front controller (`index.php` unicamente).
- `docs/`: documentacion MkDocs (endpoints, Postman, deploy).

## Instalacion local

```bash
composer install
cp .env.example .env
```

Configura PostgreSQL y ejecuta `app/config/schema.sql`.

Variables habituales:

- `DB_DRIVER=pgsql`
- `DB_PORT=5432`

## Ejecutar en local

```bash
php -S localhost:8080 -t public
```

## Documentacion API

- **Rutas y ejemplos:** [docs/api/endpoints.md](docs/api/endpoints.md)
- **Postman + credenciales de ejemplo:** [docs/api/postman.md](docs/api/postman.md)
- **Coleccion importable:** [docs/api/api-mascotas.postman_collection.json](docs/api/api-mascotas.postman_collection.json)

Produccion Render: `https://api-mascotas-70fo.onrender.com`

## Deploy en Render

Guia: [docs/deploy/render.md](docs/deploy/render.md) ([`render.yaml`](render.yaml) + [`Dockerfile`](Dockerfile)).

## Pruebas automatizadas

```bash
composer test
```

## Documentacion MkDocs

```bash
pip install mkdocs mkdocs-material
mkdocs serve
```
