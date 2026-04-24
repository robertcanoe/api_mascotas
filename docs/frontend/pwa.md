# Frontend PWA

La PWA se implementa en JavaScript Vanilla y consume la API por `fetch`.

## Funcionalidades

- Listado de mascotas por `GET /mascotas`.
- Filtro en tiempo real por nombre/especie/edad.
- Formulario de adopcion con `POST /adopciones`.
- Mensajes de exito/error en la UI.

## Archivos clave

- `public/assets/js/app.js`: logica principal.
- `public/assets/css/app.css`: estilos responsivos.
- `public/manifest.json`: metadatos de instalacion.
- `public/sw.js`: cache offline basica.

## Frontend estatico para Netlify

Existe una version desacoplada en `frontend/` para despliegue estatico:

- `frontend/index.html`
- `frontend/assets/*`
- `frontend/manifest.json`
- `frontend/sw.js`

Configura `window.APP_CONFIG.apiOrigin` con la URL publica de tu backend.
