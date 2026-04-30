# Deploy en Render (Blueprint)

Este proyecto incluye [`render.yaml`](../../render.yaml) en la raíz del repo: crea un **Web Service** Docker (`api-mascotas`) y una base **PostgreSQL** (`mascotas-db`) gestionada por Render.

La aplicación usa la URL publica que Render inyecta como `RENDER_EXTERNAL_URL`; [`app/bootstrap.php`](../../app/bootstrap.php) la copia a `APP_URL` automaticamente, asi que no hace falta definir `APP_URL` a mano en el panel.

## Requisitos

- Cuenta en [Render](https://render.com)
- Repositorio en GitHub (u otro proveedor soportado) con este codigo
- Cliente `psql` en tu maquina para cargar el esquema SQL (opcionalmente desde CI)

## Pasos

### 1. Subir el codigo

```bash
git add Dockerfile render.yaml .dockerignore
git commit -m "chore: Render Blueprint + Docker"
git push origin main
```

### 2. Crear el Blueprint en Render

1. En el dashboard: **New +** → **Blueprint**.
2. Conecta el repositorio que contiene `render.yaml`.
3. Revisa los recursos (BD `mascotas-db`, web `api-mascotas`).
4. Cuando pida variables con `sync: false`, introduce **`ADMIN_PASSWORD_HASH`**: hash bcrypt de tu contrasena de admin.

Generar el hash en local:

```bash
php -r "echo password_hash('TU_PASSWORD_SEGURA', PASSWORD_BCRYPT), PHP_EOL;"
```

Pega el resultado completo (empieza por `$2y$`) como valor de `ADMIN_PASSWORD_HASH`. El email de login debe coincidir con `ADMIN_EMAIL` (por defecto `admin@protectora.local` en el blueprint).

5. Pulsa **Apply** y espera a que terminen el build y el deploy del servicio web.

### 3. Cargar el esquema en PostgreSQL

1. En Render, abre la instancia **mascotas-db** → **Info** (o **Connect**).
2. Copia la **External Database URL** (o los datos host / user / password / database).
3. Desde la raiz del repo:

```bash
export EXTERNAL_DB_URL='postgresql://...'   # pegar la URL externa
psql "$EXTERNAL_DB_URL" -f app/config/schema.sql
```

Esto crea las tablas `mascotas`, `adopciones`, `usuarios` y los datos de ejemplo en `mascotas`.

**Usuario admin en BD:** Si quieres que el login use solo PostgreSQL (sin depender del fallback de env), inserta un usuario en `usuarios` con el mismo email que `ADMIN_EMAIL` y el mismo hash que `ADMIN_PASSWORD_HASH`. El login tambien funciona con [`AuthService::fallbackAdminUser`](../../app/Services/AuthService.php) usando solo `ADMIN_EMAIL` + `ADMIN_PASSWORD_HASH` si la tabla falla o no hay fila.

### 4. Verificar

Sustituye la URL por la de tu servicio (visible en el dashboard del Web Service):

```bash
curl -sS https://TU-SERVICIO.onrender.com/health
curl -sS https://TU-SERVICIO.onrender.com/mascotas
```

- `GET /health` debe responder JSON con `"status":"ok"`.
- `GET /mascotas` debe listar mascotas (tras aplicar el schema) o datos de seed segun el entorno.

Login (ejemplo):

```bash
curl -sS -X POST https://TU-SERVICIO.onrender.com/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@protectora.local","password":"LA_QUE_USASTE_PARA_EL_HASH"}'
```

## Archivos relevantes

| Archivo | Proposito |
|--------|-----------|
| [`Dockerfile`](../../Dockerfile) | Imagen PHP 8.2 + Apache, `public/` como document root, extensiones `pdo_pgsql` y `mbstring` |
| [`render.yaml`](../../render.yaml) | Blueprint: Postgres + Web Service y variables enlazadas |
| [`app/config/schema.sql`](../../app/config/schema.sql) | Esquema compatible con Render Postgres |

## Notas

- **Plan free:** el servicio web puede “dormirse” por inactividad; el primer request puede tardar.
- **SSL:** `DB_SSLMODE=require` en el blueprint es adecuado para conexiones a Render Postgres desde fuera o desde la plataforma.
- **Preview environments:** las variables con `sync: false` no se rellenan en previews; consulta la [documentacion de Render](https://render.com/docs/preview-environments#placeholder-environment-variables) si usas PR previews.
