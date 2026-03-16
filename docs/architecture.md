# Architecture

This document outlines how Horizon Blocks is structured and where new functionality should live.

## Core principles

- Prefer block templates and patterns for layout.
- Keep PHP logic modular and isolated in `inc/`.
- Treat `resources/` as the source of truth for front-end code.
- Treat `assets/` as deployable output.
- Keep plugin integrations additive, not mandatory.

## Directory roles

### `inc/`

Holds PHP modules for theme behavior.

Examples:

- setup and theme supports
- asset enqueuing
- SEO metadata
- navigation rendering
- admin settings
- plugin integrations

When adding new PHP behavior, prefer a dedicated file in `inc/` and load it from `functions.php`.

### `parts/`

Reusable template parts shared across templates, such as:

- header
- footer

Use this for layout fragments that should be managed once and reused everywhere.

### `templates/`

Block templates for front-end views.

Examples:

- page
- single
- archive
- search
- 404
- WooCommerce archive and single product views

### `patterns/`

Reusable editorial layout sections and starter content.

Use this for:

- front page sections
- reusable marketing bands
- editorial content blocks

### `resources/`

Authoring workspace for front-end code.

Examples:

- `resources/styles/` for Sass entry files
- `resources/scripts/` for TypeScript entry files
- `resources/components/` for shared component code
- `resources/static/` for source images, fonts, media, and 3D assets

### `assets/`

Deployable output and runtime static assets.

Examples:

- compiled CSS and JavaScript
- copied and optimized images
- copied fonts
- copied Three.js models and textures

## Build automation

The build pipeline is intentionally staged:

1. copy static assets from `resources/static/`
2. optimize images in `assets/images/`
3. compile CSS
4. bundle JavaScript
5. package the deployable theme when requested

CI mirrors that flow so packaging failures surface before manual release work.

## Navigation architecture

Header navigation is rendered from a single WordPress menu location and used for both desktop and mobile presentation. Footer navigation is rendered from optional menu locations so sections appear only when assigned.

This keeps menu management in WordPress while preserving theme-level control over markup and behavior.

## Settings architecture

Theme-wide settings are stored in a single options array and exposed through the `Appearance > Horizon Settings` screen. These values are then consumed by:

- SEO metadata generation
- header CTA output
- footer output
- breadcrumb behavior

## Integration strategy

Third-party plugins should be handled through conditional logic and theme supports rather than hard dependencies.

Current supported integration targets include:

- WooCommerce
- Elementor
- Yoast SEO
- Contact Form 7

Any future plugin integration should follow the same pattern:

1. detect the plugin safely
2. register theme support or hooks conditionally
3. avoid fatal coupling when the plugin is inactive

## Extension guidance

When implementing new features:

- add PHP modules under `inc/`
- add source styles/scripts under `resources/`
- update compiled assets before shipping
- document new workflows in `docs/` when they affect contributors or deployment
