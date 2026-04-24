# Despliegue Frontend en Netlify

## Opcion recomendada

Desplegar carpeta `frontend/` como sitio estatico.

## Configuracion manual en Netlify

1. Conecta el repositorio en Netlify.
2. Build command: vacio.
3. Publish directory: `frontend`.
4. Deploy branch: `main`.

## Ajustes necesarios

- Edita `frontend/index.html` y asigna `window.APP_CONFIG.apiOrigin` con la URL de tu API.
- Si el backend requiere CORS, deja habilitado `Access-Control-Allow-Origin`.
