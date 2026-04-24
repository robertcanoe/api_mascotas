# API Mascotas - MVC + PWA

Aplicacion completa para gestion de adopciones de mascotas con:

- Backend MVC en PHP 8.x.
- API JSON con JWT.
- Frontend PWA en JavaScript Vanilla.
- Documentacion en MkDocs Material.

## Estructura principal

- `app/`: capas MVC y configuracion.
- `public/`: front controller y assets del backend web.
- `frontend/`: PWA estatica para despliegue en Netlify.
- `docs/`: documentacion MkDocs.

## Instalacion

```bash
composer install
cp .env.example .env
```

Configura tu base de datos PostgreSQL y ejecuta `app/config/schema.sql`.

Variables recomendadas para Koyeb:

- `DB_DRIVER=pgsql`
- `DB_PORT=5432`
- `DB_SSLMODE=require`

## Ejecucion local

```bash
php -S localhost:8080 -t public
```

## Endpoints clave

- `GET /mascotas`
- `POST /adopciones`
- `POST /auth/login`

## Credenciales admin de desarrollo

- Email: `admin@protectora.local`
- Password: `admin123`

## Pruebas

```bash
composer test
```

## Documentacion

```bash
pip install mkdocs mkdocs-material
mkdocs serve
```
