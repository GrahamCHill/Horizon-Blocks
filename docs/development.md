# Development Guide

This document covers the commands and workflows used during theme development.

## Prerequisites

- Node.js `18+`
- npm `9+`
- WordPress `6.5+`
- PHP `8.1+`

## First-time setup

1. Install dependencies:

```powershell
npm install
```

2. Build production assets:

```powershell
npm run build
```

3. Activate the theme in a local WordPress installation.

## Daily commands

### Start development watchers

```powershell
npm run dev
```

This runs:

- static asset watch and copy for `resources/static/`
- Sass watch mode for `resources/styles/main.scss`
- esbuild watch mode for `resources/scripts/main.ts`

### Run a one-off production build

```powershell
npm run build
```

### Run the full production build and cleanup

```powershell
npm run build:prod
```

### Run linting

```powershell
npm run lint
```

This currently runs:

- TypeScript checks via `tsc --noEmit`
- Sass/style checks via Stylelint

### Create a deployment ZIP

```powershell
npm run package
```

This will:

1. run the production build
2. remove temporary build files
3. create `dist/horizon-blocks.zip`

## Source of truth

Use these directories consistently:

- `resources/`
  Front-end source files
- `assets/`
  Compiled CSS/JS and deployable static assets
- `inc/`
  PHP behavior, settings, integrations, and helper modules
- `parts/`
  Shared template parts
- `templates/`
  Block templates
- `patterns/`
  Reusable block patterns

## Working with static assets

If a file must be available on the live site after packaging, and it is not generated CSS or JS, place it in `resources/static/`.

Examples:

- `resources/static/images/hero.webp`
- `resources/static/models/scene.glb`
- `resources/static/fonts/brand.woff2`

The build will copy those files into the matching location inside `assets/`.

## Recommended development loop

1. Run `npm run dev`.
2. Edit code in `resources/`, `inc/`, `templates/`, `parts/`, or `patterns/`.
3. Test in local WordPress.
4. Run `npm run lint`.
5. Run `npm run build:prod` before packaging or shipping.

## Validation expectations

Before deployment, verify:

- primary navigation on desktop and mobile
- footer menu assignments
- front page pattern rendering
- WooCommerce views if WooCommerce is active
- SEO metadata behavior with and without SEO plugins
- admin settings save and render correctly
