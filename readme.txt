=== Concepts Group Suite ===
Contributors: Many Project (Web App Marketing Sdn Bhd)
Requires at least: 5.9
Requires PHP: 7.4
Stable tag: 2.8.0

One plugin for the Concepts Group site. Combines:
- Concepts Group Sections 1.35.7 (global CSS, header, hero, footer, all WPBakery sections)
- Concepts Articles 1.2.1 (blog single + listing templates, TOC, SEO)
- Concepts CLQ Sites 1.7.2 (property pages, /properties/ hub, enquiry form + CSV export)

== Installation ==
1. Plugins > Add New > Upload Plugin > choose concepts-group-suite.zip > Install.
2. Activate. The three old plugins are switched off automatically.
3. Delete the three old plugins from the Plugins page when convenient.

All shortcodes, WPBakery elements, post types, meta keys, URLs and settings are unchanged,
so existing pages and CLQ site records keep working as before. No data is migrated or touched.

== Safety ==
- Modules load after every other plugin. If an old copy is still active, the suite uses it
  and skips its own copy (no "cannot redeclare" crash), and shows a notice.
- Old plugins cannot be re-activated from the Plugins page while the suite is active.

Made by Many Project (Web App Marketing Sdn Bhd) - https://manyproject.my
