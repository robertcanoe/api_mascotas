# Arquitectura MVC

## Capas

- Controller: recibe request/response y coordina el flujo.
- Service: reglas de negocio y orquestacion.
- Model: acceso a datos con PDO.
- Form: sanitizacion + validacion de entrada.

## Flujo de una peticion

1. `public/index.php` recibe la solicitud.
2. `Router` resuelve metodo + URI.
3. `Dispatcher` ejecuta middleware y accion.
4. El controlador delega en `Form` y `Service`.
5. `Service` usa `Model` para persistencia.
6. Se responde JSON o HTML segun la ruta.

## Seguridad minima aplicada

- DocumentRoot en `public/`.
- Variables sensibles en `.env` fuera de git.
- Escape de salida en vistas con `htmlspecialchars` via helper `e()`.
- Validacion estricta de metodo POST para escrituras.
- Queries preparadas con `PDO::prepare`.
- Errores controlados con excepciones y logging en `logs/`.
