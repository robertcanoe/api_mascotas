# Endpoints API

Base local sugerida:

- `http://localhost:8080`

Todas las respuestas API son JSON con cabecera `Content-Type: application/json`.

## Salud

- `GET /health`

## Mascotas

- `GET /mascotas`
- `GET /mascotas/{id}`
- `POST /mascotas` (protegida con JWT)
- `POST /mascotas/{id}/actualizar` (protegida con JWT)
- `POST /mascotas/{id}/eliminar` (protegida con JWT)

## Adopciones

- `POST /adopciones`

Body JSON esperado:

```json
{
  "mascota_id": 1,
  "solicitante": "Ana Perez",
  "email": "ana@example.com",
  "mensaje": "Tengo patio amplio y experiencia con perros."
}
```

## Auth

- `POST /auth/login`

Body JSON esperado:

```json
{
  "email": "admin@protectora.local",
  "password": "admin123"
}
```

## Ejemplos cURL

```bash
curl -X GET http://localhost:8080/mascotas
```

```bash
curl -X POST http://localhost:8080/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@protectora.local","password":"admin123"}'
```

```bash
curl -X POST http://localhost:8080/mascotas \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Toby","especie":"Perro","edad":4,"foto_url":"https://...","descripcion":"Muy sociable"}'
```
