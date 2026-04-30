# Despliegue Backend en Hosting PHP

## Requisitos

- PHP 8.x
- Composer
- PostgreSQL
- DocumentRoot apuntando a `public/`

## Pasos

1. Crear base de datos y usuario.
2. Subir proyecto excluyendo `.env`.
3. Crear `.env` en servidor con credenciales reales.
4. Ejecutar `composer install --no-dev --optimize-autoloader`.
5. Configurar vhost/subdominio para `public/`.

## Seguridad

- Nunca exponer `app/`, `.env` ni `vendor/bin` por web.
- Revisar permisos de `cache/` y `logs/`.
