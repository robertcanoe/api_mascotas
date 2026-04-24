# Autenticacion JWT

Las rutas de administracion de mascotas requieren token Bearer.

## Flujo

1. Cliente llama `POST /auth/login`.
2. API valida credenciales.
3. API devuelve token JWT con expiracion.
4. Cliente envia `Authorization: Bearer <token>` en rutas protegidas.

## Claims

- `iss`: emisor configurado.
- `iat`: fecha de emision.
- `exp`: expiracion.
- `sub`: id de usuario.
- `email`: correo del usuario.
- `role`: rol del usuario.

## Errores comunes

- `401 Unauthorized`: token ausente, invalido o expirado.
- `403 Forbidden`: reservado para reglas de autorizacion por rol.
