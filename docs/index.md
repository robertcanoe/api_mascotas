# Protectora de Mascotas

Proyecto completo con:

- Backend API en PHP 8.x con arquitectura MVC estricta.
- Frontend PWA en JavaScript Vanilla.
- Documentacion con MkDocs Material.
- Despliegue continuo con GitHub Actions.

## Componentes

- API MVC: enruta peticiones, aplica validacion y responde en JSON.
- PWA: lista mascotas, filtra en tiempo real y envia solicitudes de adopcion.
- Seguridad: JWT para rutas de administracion, validaciones server-side y consultas PDO preparadas.

## Requisitos

- PHP 8.1 o superior.
- Composer.
- PostgreSQL.
- Opcional: Python para compilar la documentacion MkDocs.

## Arranque rapido

```bash
composer install
cp .env.example .env
php -S localhost:8080 -t public
```

Abre `http://localhost:8080` para la aplicacion web.

## Documentacion

```bash
pip install mkdocs mkdocs-material
mkdocs serve
```

La documentacion se publica en `http://127.0.0.1:8000`.
