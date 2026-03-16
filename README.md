# Horizon Blocks

Horizon Blocks is a modern WordPress block theme built around Full Site Editing, reusable template parts, and a predictable front-end build pipeline. It is designed to be practical for client work and maintainable as an open-source project: shared theme logic is isolated in `inc/`, presentation is driven through block templates and patterns, and front-end assets are authored from a dedicated `resources/` workspace.

## Overview

- Block-based theme architecture with shared header and footer template parts
- Editable front page powered by reusable block patterns
- Coverage for the standard WordPress page types, including archives, search, single posts, pages, and 404
- SEO-oriented metadata and structured data built into the theme
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
  Compiles Sass and TypeScript into the production assets in `assets/`.
- `npm run build:prod`
  Runs the full build and removes temporary intermediate files.
- `npm run dev`
  Starts concurrent watch processes for styles and scripts.

### Asset pipeline

Source assets live in `resources/` and compiled output is written to `assets/`.

- `resources/styles/main.scss`
  Main Sass entrypoint for global theme styles.
- `resources/scripts/main.ts`
  Main TypeScript entrypoint for front-end behavior.
- `resources/components/`
  Shared component-level Sass and TypeScript modules.

The CSS pipeline compiles Sass first, then passes the result through PostCSS with Autoprefixer. JavaScript is bundled from TypeScript using esbuild.

## Project structure

```text
.
|-- assets/             # Compiled front-end assets committed with the theme
|-- inc/                # PHP bootstrap, admin settings, SEO, and integrations
|-- parts/              # Shared template parts such as header and footer
|-- patterns/           # Reusable block patterns and front-page composition
|-- resources/          # Authoring workspace for Sass, TypeScript, and components
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
- default social sharing image
- footer copy
- breadcrumb toggle

These values feed the theme’s SEO and presentation layers where appropriate.

## SEO and metadata

Horizon Blocks includes a built-in SEO baseline intended to be useful without becoming invasive:

- meta descriptions
- canonical URLs
- Open Graph and Twitter metadata
- JSON-LD schema for site, page, organization, and blog posting contexts
- `noindex` handling for search and 404 pages

If a supported SEO plugin is active, the theme avoids outputting duplicate metadata.

## Plugin integrations

### WooCommerce

WooCommerce support is registered at the theme level, including product gallery features and dedicated block templates for product archives and single-product pages.

### Elementor

Elementor theme support is enabled and theme locations are registered when Elementor Pro is available. The theme also disables Elementor’s default color and typography schemes on activation so the theme remains the primary design system.

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
3. Deploy the full theme directory, including compiled files in `assets/`.

The production server does not need Node.js or npm as long as the compiled assets are included in the deployed theme.

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
