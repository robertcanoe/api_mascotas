# API Mascotas

> API REST en PHP para adopciones de mascotas.

## Resumen

- Arquitectura MVC en backend (`Controllers`, `Services`, `Models`, `Forms`).
- Endpoints REST (`GET`, `POST`, `PUT`, `DELETE`).
- JWT para operaciones administrativas.
- PostgreSQL con PDO y consultas preparadas.

## Inicio rapido

```bash
composer install
cp .env.example .env
php -S localhost:8080 -t public
```

Base URL local: `http://localhost:8080`

## Navegacion

- [Endpoints](api/endpoints.md)
- [Guia Postman](api/postman.md)
- [Autenticacion JWT](api/autenticacion.md)
- [Deploy en Render](deploy/render.md)

## Documentacion local

```bash
pip install mkdocs mkdocs-material pymdown-extensions
mkdocs serve
```

## Publicacion

Esta documentacion se despliega automaticamente en GitHub Pages con el workflow:

- `.github/workflows/docs-pages.yml`
