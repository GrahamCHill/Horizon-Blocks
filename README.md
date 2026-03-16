# Horizon Blocks

[![WordPress 6.5+](https://img.shields.io/badge/WordPress-6.5%2B-21759b)](https://wordpress.org)
[![PHP 8.1+](https://img.shields.io/badge/PHP-8.1%2B-777bb4)](https://www.php.net/)
[![Node 18+](https://img.shields.io/badge/Node-18%2B-5fa04e)](https://nodejs.org/)

Horizon Blocks is a modern WordPress block theme built around Full Site Editing, reusable template parts, and a predictable front-end build pipeline. It is structured for long-term maintenance: shared PHP logic lives in `inc/`, layout is defined through block templates and patterns, and front-end assets are authored from a dedicated `resources/` workspace.

## At a glance

| Area | What it gives you |
| --- | --- |
| Block theme architecture | Shared template parts, patterns, and core template coverage |
| Front-end tooling | Sass, TypeScript, esbuild, PostCSS, and packaging scripts |
| SEO baseline | Canonicals, social metadata, JSON-LD, and plugin-aware behavior |
| Admin settings | Shared organization, contact, footer, social, and CTA fields |
| Plugin support | WooCommerce, Elementor, and Yoast integration hooks |

## Overview

- Block-based theme architecture with shared header and footer template parts
- Editable front page powered by reusable block patterns
- Coverage for core WordPress views, including archives, search, single posts, pages, and 404
- Built-in SEO metadata and structured data baseline
- Admin-facing theme settings for shared business and presentation data
- Compatibility hooks for WooCommerce, Elementor, and Yoast SEO
- npm-based asset pipeline using Sass, TypeScript, esbuild, PostCSS, and Autoprefixer

## Requirements

- WordPress `6.5+`
- PHP `8.1+`
- Node.js `18+`
- npm `9+`

## Installation

1. Place the theme in `wp-content/themes/horizon-blocks`.
2. From the theme directory, run `npm install`.
3. Run `npm run build`.
4. Activate the theme in the WordPress admin.
5. Open the Site Editor to assign navigation, update template parts, and tailor the front page.

## Development

### Available scripts

- `npm run build`
  Copies static assets from `resources/static/` into `assets/`, then compiles Sass and TypeScript.
- `npm run build:prod`
  Runs the full build and removes temporary intermediate files.
- `npm run lint`
  Runs TypeScript and Sass-oriented lint checks for the source workspace.
- `npm run package`
  Builds production assets and creates a deployable ZIP in `dist/`.
- `npm run dev`
  Starts concurrent watch processes for static assets, styles, and scripts.

### Asset pipeline

Source assets live in `resources/` and compiled output is written to `assets/`.

- `resources/styles/main.scss`
  Main Sass entrypoint for global theme styles.
- `resources/scripts/main.ts`
  Main TypeScript entrypoint for front-end behavior.
- `resources/components/`
  Shared component-level Sass and TypeScript modules.
- `resources/static/`
  Source folder for deployable static assets such as images, fonts, video, `.obj`, `.gltf`, and `.glb` files.

The build pipeline now:

1. copies static files from `resources/static/` into `assets/`
2. compiles Sass and passes the result through PostCSS with Autoprefixer
3. bundles JavaScript from TypeScript using esbuild

Placeholder folders are included under `resources/static/` with `.gitkeep` files. Those placeholders are ignored by the copy step and are not shipped as runtime assets.

Build overwrite behavior:

- `assets/css/main.css` is overwritten by the CSS build
- `assets/js/main.js` is overwritten by the JavaScript build
- `assets/images/`, `assets/models/`, `assets/fonts/`, and `assets/media/` are refreshed by the static copy step during `npm run build`
- `npm run dev` also watches `resources/static/` and re-runs the static copy step when those files change

## Project structure

```text
.
|-- assets/             # Compiled front-end assets committed with the theme
|-- docs/               # Project and build documentation
|-- inc/                # PHP bootstrap, admin settings, SEO, navigation, and integrations
|-- parts/              # Shared template parts such as header and footer
|-- patterns/           # Reusable block patterns and front-page composition
|-- resources/          # Authoring workspace for Sass, TypeScript, components, and static assets
|-- templates/          # Block templates for core page and plugin-specific views
|-- functions.php       # Theme bootstrap loader
|-- style.css           # WordPress theme header
|-- theme.json          # Global styles, settings, and template part registration
```

## Theme configuration

The theme adds a settings screen at `Appearance > Horizon Settings` for shared site-level data:

- organization name
- contact email
- contact phone
- contact address
- default social sharing image
- optional header CTA label and URL
- footer copy
- breadcrumb toggle

These values feed the theme's SEO and presentation layers where appropriate.

## SEO and metadata

Horizon Blocks includes a built-in SEO baseline intended to be useful without becoming invasive:

- meta descriptions
- canonical URLs
- Open Graph and Twitter metadata
- JSON-LD schema for site, page, organization, and blog posting contexts
- `noindex` handling for search and 404 pages

If a supported SEO plugin is active, the theme avoids outputting duplicate metadata.

## Navigation and accessibility

The theme renders the primary header navigation from a single WordPress menu location for both desktop and mobile views. It also includes:

- a skip link for keyboard users
- keyboard-aware submenu behavior
- a mobile menu toggle backed by theme JavaScript
- optional header CTA and WooCommerce cart link output

Footer navigation supports multiple optional menu groups through dedicated menu locations.

## Plugin integrations

### WooCommerce

WooCommerce support is registered at the theme level, including product gallery features, product-grid defaults, a live header cart link, and dedicated block templates for product archives and single-product pages.

### Elementor

Elementor theme support is enabled and theme locations are registered when Elementor Pro is available. The theme also disables Elementor's default color and typography schemes on activation so the theme remains the primary design system.

### Yoast SEO

Yoast breadcrumb output is supported through a theme shortcode and can be toggled from the admin settings page.

## Editing approach

This theme is intended to be customized primarily through:

- the Site Editor for templates, patterns, and template parts
- `theme.json` for global design tokens and editor settings
- `resources/` for front-end code and styling
- `inc/` for PHP-level behavior and integrations

That separation keeps layout concerns, asset authoring, and application logic from bleeding into each other.

## Deployment

The production deployment flow is straightforward:

1. Run `npm install`.
2. Run `npm run build` or `npm run build:prod`.
3. Optionally run `npm run package` to generate `dist/horizon-blocks.zip`.
4. Deploy the full theme directory, including compiled files in `assets/`, or use the generated ZIP.

The production server does not need Node.js or npm as long as the compiled assets are included in the deployed theme.

For operational details and contributor workflow, see:

- [docs/README.md](C:\Users\Graham\Documents\Code Projects\Wordpress\Wp-theme\docs\README.md)
- [docs/build-and-assets.md](C:\Users\Graham\Documents\Code Projects\Wordpress\Wp-theme\docs\build-and-assets.md)
- [docs/development.md](C:\Users\Graham\Documents\Code Projects\Wordpress\Wp-theme\docs\development.md)
- [docs/architecture.md](C:\Users\Graham\Documents\Code Projects\Wordpress\Wp-theme\docs\architecture.md)

## Contributing

Contributions should preserve the current architecture:

- keep reusable PHP concerns in `inc/`
- prefer block templates and patterns over ad hoc PHP templating
- treat `resources/` as the source of truth for front-end assets
- commit compiled asset output in `assets/` when build inputs change
- maintain compatibility with supported plugins without introducing hard dependencies

When adding features, prioritize composability, conservative defaults, and clear separation between editor-managed content and code-driven behavior.

## Notes

- This repository currently includes compiled assets so the theme can be deployed without a build step on the target server.
- Validate theme behavior in a local WordPress installation before shipping to production, especially when changing PHP or plugin integration logic.
