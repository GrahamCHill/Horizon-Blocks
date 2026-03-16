# Build And Assets

This document describes what the theme build does, what the packaging step includes, and how to handle non-code assets such as images and 3D model files.

## Command summary

- `npm run build`
  Copies static assets, optimizes images, and builds production CSS and JavaScript into `assets/`.
- `npm run build:prod`
  Runs the build and removes temporary intermediate files.
- `npm run lint`
  Runs TypeScript and Sass lint checks against the source workspace.
- `npm run package`
  Runs the production build and creates `dist/horizon-blocks.zip`.
- `npm run dev`
  Starts the static asset watcher plus the Sass and TypeScript watchers.

## What the build actually processes

The current build pipeline compiles and bundles:

- `resources/static/**/*` -> `assets/**/*` excluding `.gitkeep`
- `assets/images/**/*.{jpg,jpeg,png,webp,svg}` -> optimized in place
- `resources/styles/main.scss` -> `assets/css/main.css`
- `resources/scripts/main.ts` -> `assets/js/main.js`

Static files are copied into `assets/`. Image files inside `assets/images/` are then optimized in place.

## Overwrite behavior

The build intentionally overwrites generated output:

- `assets/css/main.css`
- `assets/js/main.js`

The static copy step also refreshes these asset directories from `resources/static/`:

- `assets/images/`
- `assets/models/`
- `assets/fonts/`
- `assets/media/`

It does not touch `assets/css/` or `assets/js/` beyond the normal CSS and JavaScript build outputs.

During `npm run dev`, changes inside `resources/static/` also trigger the same copy step.
During `npm run build` and `npm run package`, copied files in `assets/images/` are also run through the image optimizer.

The following file types are optimized:

- images such as `.png`, `.jpg`, `.jpeg`, `.webp`, `.svg`

The following file types are copied as-is:

- fonts such as `.woff`, `.woff2`, `.ttf`
- 3D assets such as `.obj`, `.gltf`, `.glb`
- video or other media files

## What goes into the ZIP package

`npm run package` creates `dist/horizon-blocks.zip` from the production-ready theme files.

The ZIP currently includes:

- `assets/`
- `inc/`
- `parts/`
- `patterns/`
- `templates/`
- `functions.php`
- `README.md`
- `style.css`
- `theme.json`

Because the entire `assets/` directory is included, any static files copied there during build will be packaged with the theme.

## CI automation

GitHub Actions CI is defined in `.github/workflows/ci.yml`.

On push and pull request it runs:

1. `npm install`
2. `npm run lint`
3. `npm run build`
4. `npm run package`

The packaged ZIP is uploaded as a workflow artifact.

## Recommended asset placement

Place source static assets under `resources/static/` in a stable directory structure such as:

```text
resources/static/
|-- css/
|-- images/
|-- models/
|-- fonts/
|-- media/
```

Examples:

- `resources/static/images/hero-home.webp`
- `resources/static/models/product-viewer.glb`
- `resources/static/models/showroom.gltf`

At build time those files are copied into the matching path under `assets/`.

Placeholder `.gitkeep` files can be committed to keep the folders present in git. The copy step ignores `.gitkeep`.

## Three.js-specific guidance

If the theme uses Three.js or another WebGL stack:

- keep runtime models and textures in `assets/models/` and `assets/images/`
- store the source files in `resources/static/models/` and `resources/static/images/`
- reference the deployed files by theme-relative URL under `assets/`
- do not assume the current build will fingerprint or optimize those files

If you want a more advanced workflow later, the build can be extended to:

- copy static files from `resources/` into `assets/`
- optimize images during build
- fingerprint assets for cache busting
- support loader-friendly asset imports from TypeScript

That is not implemented in the current scaffold.

## Practical rule

If a file must exist on the live site after `npm run package`, and it is not generated CSS or JS, put it in `resources/static/` so the build copies it into `assets/`.
