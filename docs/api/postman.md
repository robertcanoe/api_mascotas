# Guia Postman — API Mascotas

**URL base produccion:** `https://api-mascotas-70fo.onrender.com`

Documentacion de rutas: [endpoints.md](endpoints.md).

---

## 1. Credenciales de ejemplo (documentacion)

Para poder repetir los mismos pasos que esta guia:

| Campo | Valor |
|-------|--------|
| Email admin | `admin@protectora.local` |
| Password | `Admin1234!` |

En **Render** (Web Service → Environment), configura:

- `ADMIN_EMAIL` = `admin@protectora.local`
- `ADMIN_PASSWORD_HASH` = **exactamente** este hash bcrypt:

```
$2y$10$t6N1tDs0ZINJmA2R68MMF.V1gXGh/tfcuruSWkV71z95bCua8nnae
```

(Ese hash corresponde a la password `Admin1234!`. Si cambias la password, genera un hash nuevo con `php -r "echo password_hash('TU_CLAVE', PASSWORD_BCRYPT), PHP_EOL;"` y actualiza Render.)

---

## 2. Importar la coleccion

1. Postman → **Import**.
2. Elige el archivo [`api-mascotas.postman_collection.json`](api-mascotas.postman_collection.json) del repo (`docs/api/`).
3. La coleccion trae la variable `baseUrl` apuntando a produccion y `token` vacia.

---

## 3. Flujo recomendado

### 3.1 Login y guardar token

1. Carpeta **Auth** → **POST /auth/login**.
2. Body raw JSON (ya precargado):

```json
{
  "email": "admin@protectora.local",
  "password": "Admin1234!"
}
```

3. **Send**. Si es 200, el script **Tests** de la peticion guarda automaticamente `data.token` en la variable de coleccion **`token`**.

### 3.2 Rutas publicas

Ejecuta en orden (o las que necesites):

- **GET /** — indice del API.
- **GET /health** — comprobacion rapida.
- **GET /mascotas** — listado.
- **GET /mascotas/{id}** — ajusta el `id` en la URL si hace falta.
- **POST /adopciones** — body de ejemplo en la peticion.

### 3.3 Rutas admin (JWT)

Las peticiones **POST /mascotas**, **PUT /mascotas/{id}** y **DELETE /mascotas/{id}** llevan cabecera:

`Authorization: Bearer {{token}}`

Tras el login, `{{token}}` se rellena solo. Si caduca el JWT, vuelve a ejecutar login.

---

## 4. Codigos de respuesta

Ver tabla en [endpoints.md](endpoints.md#codigos-http-habituales).

---

## 5. Capturas de pantalla (rellenar tu)

Sustituye estas imagenes en la carpeta [screenshots/](screenshots/) y enlazalas aqui si quieres documentacion visual en MkDocs:

![Login 200 OK](screenshots/login-200.png)

![GET mascotas](screenshots/get-mascotas.png)

![POST mascotas con Bearer](screenshots/post-mascotas-admin.png)

Instrucciones: [screenshots/README.md](screenshots/README.md).
