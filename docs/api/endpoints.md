# Endpoints API (REST)

**Base produccion (Render):** `https://api-mascotas-70fo.onrender.com`

**Base local:** `http://localhost:8080` (con `php -S localhost:8080 -t public`)

Todas las respuestas son JSON con cabecera `Content-Type: application/json; charset=utf-8`.

Cabeceras CORS permitidas (browser): `GET`, `POST`, `PUT`, `DELETE`, `OPTIONS`. Para Postman no hace falta CORS.

---

## Resumen

| Metodo | Ruta | Auth | Descripcion |
|--------|------|------|-------------|
| GET | `/` | No | Informacion del API y lista de rutas |
| GET | `/health` | No | Estado del servicio |
| POST | `/auth/login` | No | Obtener JWT |
| GET | `/mascotas` | No | Listar mascotas activas |
| GET | `/mascotas/{id}` | No | Detalle de una mascota |
| POST | `/mascotas` | JWT (admin) | Crear mascota |
| PUT | `/mascotas/{id}` | JWT (admin) | Actualizar mascota |
| DELETE | `/mascotas/{id}` | JWT (admin) | Baja logica (activo=0) |
| POST | `/adopciones` | No | Solicitud de adopcion |

---

## GET /

**Respuesta 200**

```json
{
  "data": {
    "name": "API Mascotas",
    "env": "production",
    "documentation": { "...": "..." },
    "endpoints": ["GET /", "GET /health", "..."]
  }
}
```

---

## GET /health

**Respuesta 200**

```json
{
  "data": {
    "status": "ok",
    "timestamp": "2026-04-30T12:00:00+00:00",
    "env": "production"
  }
}
```

---

## POST /auth/login

**Body (JSON)**

```json
{
  "email": "admin@protectora.local",
  "password": "Admin1234!"
}
```

Credenciales de ejemplo documentadas en [postman.md](postman.md) (configura `ADMIN_PASSWORD_HASH` en Render con el hash indicado).

**Respuesta 200**

```json
{
  "message": "Login correcto.",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "token_type": "Bearer",
    "expires_in": 3600,
    "user": {
      "id": 1,
      "nombre": "Administrador",
      "email": "admin@protectora.local",
      "rol": "admin"
    }
  }
}
```

**Errores:** `400` validacion, `401` credenciales incorrectas.

---

## GET /mascotas

**Respuesta 200**

```json
{
  "data": [
    {
      "id": "1",
      "nombre": "Luna",
      "especie": "Perro",
      "edad": "3",
      "foto_url": "https://...",
      "descripcion": "..."
    }
  ]
}
```

---

## GET /mascotas/{id}

**Respuesta 200:** objeto mascota en `data`.

**Errores:** `400` id invalido, `404` no encontrada.

---

## POST /mascotas (admin)

**Headers**

- `Authorization: Bearer <token>`
- `Content-Type: application/json`

**Body**

```json
{
  "nombre": "Nala",
  "especie": "Perro",
  "edad": 2,
  "foto_url": "https://example.com/foto.jpg",
  "descripcion": "Cariñosa"
}
```

**Respuesta 201:** `message`, `data` con la mascota creada (incluye `id`).

**Errores:** `400` validacion, `401` sin token o token invalido.

---

## PUT /mascotas/{id} (admin)

**Headers:** igual que POST /mascotas.

**Body:** mismo formato que crear (todos los campos segun validador).

**Respuesta 200:** mascota actualizada.

**Errores:** `400`, `401`, `404`, `405` si usas metodo incorrecto.

---

## DELETE /mascotas/{id} (admin)

**Headers:** `Authorization: Bearer <token>`.

**Body:** vacio.

**Respuesta 200:** mensaje de baja logica.

**Errores:** `401`, `404`, `405`.

---

## POST /adopciones

**Body**

```json
{
  "mascota_id": 1,
  "solicitante": "Ana Perez",
  "email": "ana@example.com",
  "mensaje": "Tengo patio amplio."
}
```

**Respuesta 201** o **202** segun disponibilidad de BD (ver logica en `AdopcionService`).

**Errores:** `400` validacion, `404` mascota inexistente.

---

## Codigos HTTP habituales

| Codigo | Significado |
|--------|-------------|
| 200 | OK |
| 201 | Creado |
| 202 | Aceptado (fallback sin BD) |
| 400 | Validacion / datos incorrectos |
| 401 | No autorizado (JWT) |
| 404 | Ruta o recurso no encontrado |
| 405 | Metodo HTTP no permitido |
| 500 | Error interno |

---

## Ejemplos cURL (produccion)

```bash
curl -sS "https://api-mascotas-70fo.onrender.com/health"
```

```bash
curl -sS -X POST "https://api-mascotas-70fo.onrender.com/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@protectora.local","password":"Admin1234!"}'
```

```bash
TOKEN="..."

curl -sS -X POST "https://api-mascotas-70fo.onrender.com/mascotas" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Toby","especie":"Perro","edad":4,"foto_url":"https://example.com/t.jpg","descripcion":"Sociable"}'
```

```bash
curl -sS -X PUT "https://api-mascotas-70fo.onrender.com/mascotas/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Toby","especie":"Perro","edad":5,"foto_url":"https://example.com/t.jpg","descripcion":"Actualizado"}'
```

```bash
curl -sS -X DELETE "https://api-mascotas-70fo.onrender.com/mascotas/1" \
  -H "Authorization: Bearer $TOKEN"
```
