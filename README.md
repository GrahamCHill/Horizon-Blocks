# Horizon Blocks

Horizon Blocks is a modern WordPress block theme scaffold built for easy extension. It ships with:

- Full Site Editing templates for the common page types
- Separate header and footer template parts
- An editable front page powered by a block pattern
- npm-based CSS and TypeScript tooling
- A clean `inc/` structure for PHP setup code

## Quick start

1. Copy this folder into `wp-content/themes/horizon-blocks`.
2. Run `npm install`.
3. Run `npm run build` for a production build, or `npm run dev` while developing.
4. Activate the theme in WordPress.
5. Open the Site Editor to customize template parts, styles, and the front page layout.

## Asset workspace

Add theme assets in the `resources/` folder:

- `resources/styles/` for Sass entry files and global styles
- `resources/scripts/` for TypeScript entry files
- `resources/components/` for reusable component-level Sass and TypeScript modules

`npm run build` compiles:

- `resources/styles/main.scss` to `assets/css/main.css`
- `resources/scripts/main.ts` to `assets/js/main.js`

The production CSS build also runs through PostCSS with Autoprefixer.
