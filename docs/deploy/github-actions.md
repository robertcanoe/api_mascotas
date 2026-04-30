# GitHub Actions

## Docs en GitHub Pages

Workflow incluido: `.github/workflows/docs-pages.yml`

Que hace:

1. Instala Python + MkDocs Material
2. Ejecuta `mkdocs build --strict`
3. Publica `site/` en GitHub Pages

## Activacion en GitHub

1. Repo -> **Settings** -> **Pages**
2. En **Build and deployment**, selecciona **GitHub Actions**
3. Haz push a `main` en `docs/` o `mkdocs.yml`

## Backends

Si quieres CD para backend (Render, SSH, FTP), puedes anadir workflows separados.
