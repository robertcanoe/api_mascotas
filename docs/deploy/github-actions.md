# GitHub Actions para CD

## Backend por FTP

Workflow: `.github/workflows/backend-ftp-deploy.yml`

Secrets requeridos:

- `FTP_SERVER`
- `FTP_USERNAME`
- `FTP_PASSWORD`
- `FTP_SERVER_DIR` (ejemplo: `/public_html/api_mascotas`)

## Backend por SSH/rsync (alternativa)

Workflow: `.github/workflows/backend-ssh-rsync.yml`

Secrets requeridos:

- `SSH_HOST`
- `SSH_PORT`
- `SSH_USER`
- `SSH_PRIVATE_KEY`
- `SSH_TARGET_DIR`

## Frontend en Netlify

Workflow: `.github/workflows/frontend-netlify-deploy.yml`

Secrets requeridos:

- `NETLIFY_AUTH_TOKEN`
- `NETLIFY_SITE_ID`

Los workflows hacen despliegue continuo al hacer push a `main`.
