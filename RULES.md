# Concepts Group Suite: Editing Rules

Made by **Many Project (Web App Marketing Sdn Bhd)**, https://manyproject.my
Read this before you edit anything in this plugin.

---

## 1. Always bump the version number

Every edit needs a new version number, even a one-line CSS fix. If you skip it, browsers and caches keep serving the old files.

**Main plugin.** Update `concepts-group-suite.php` in both places:
- the header: `* Version: 2.0.1`
- the constant: `define( 'CG_SUITE_VERSION', '2.0.1' );`

**The module you touched.** Also bump its own constant:

| Module    | File                                     | Constant      |
|-----------|------------------------------------------|---------------|
| Sections  | `modules/sections/concepts-sections.php` | `CGS_VERSION` |
| Articles  | `modules/articles/concepts-articles.php` | `CGA_VERSION` |
| CLQ Sites | `modules/clq-sites/concepts-clq-sites.php` | `CCQ_VERSION` |

**Which number to change:**
- `2.0.0 → 2.0.1` for a fix or a small tweak
- `2.0.1 → 2.1.0` for a new section, shortcode or feature
- `2.1.0 → 3.0.0` for a big change that breaks something old

Also update `Stable tag` in `readme.txt` and add a line to the Changelog at the bottom of this file.

---

## 2. Every edit must NOT crash the site

1. **Lint before upload.** Run `php -l file.php` on every PHP file you changed. You need "No syntax errors".
2. **Keep the prefixes.** Every function and constant starts with its module prefix: `cgs_`, `cga_`, `ccq_` or `cg_suite_`. Never add an unprefixed function, because another plugin could use the same name and crash the site.
3. **Never declare the same function twice.** Search the whole plugin for the name before adding a new one.
4. **Guard third-party code.** Wrap WPBakery, Yoast and similar calls in a check like `function_exists( 'vc_map' )` or `defined( 'WPSEO_VERSION' )`.
5. **Don't rename existing items.** Leave shortcode tags, post types (`ccq_site`, `ccq_enquiry`), meta keys (`_ccq`, `_cga_seo_*`) and option names as they are. Renaming them breaks live pages and data.
6. **Don't touch the loader** (`cg_suite_load()` in the main file) unless you are adding a new module. It is what stops the site crashing when an old plugin copy is still active.
7. **Back up first.** Download the current plugin folder before you upload a new version.
8. **Test on staging first**, then check these live pages:
   - Homepage
   - One blog post
   - `/blog/`
   - `/properties/`
   - One property page
   - WP Admin → CLQ Sites → edit a site
9. **If it breaks:** rename the plugin folder over FTP or the File Manager. This turns the plugin off. Then upload the backup.

---

## 3. Credit and ownership

- `Author` stays as **Many Project (Web App Marketing Sdn Bhd)**.
- `Author URI` stays as **https://manyproject.my**.
- Keep the credit line in `readme.txt` and in this file.

---

## 4. Where things live

```
concepts-group-suite/
├── concepts-group-suite.php   ← main file: header, version, loader, activation
├── RULES.md                   ← this file
├── readme.txt
└── modules/
    ├── sections/   ← global CSS, header, hero, footer, all WPBakery sections
    ├── articles/   ← blog post + blog list templates
    └── clq-sites/  ← property pages, /properties/ hub, enquiry form
```

---

## Changelog

- **2.0.2** (2026-10-27): Header bar now spans the same width as the page content (removed the 1360px cap). Sections 1.35.9.
- **2.0.1** (2026-10-27): New white "since 1991" logo in header and footer (`CGS_LOGO_BRAND`); old white logos swap to it automatically. Sections 1.35.8.
- **2.0.0** (2026-10-27): Combined Concepts Group Sections 1.35.7, Concepts Articles 1.2.1 and Concepts CLQ Sites 1.7.2 into one plugin. The old plugins are switched off automatically on activation.
