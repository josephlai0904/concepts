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
| SEO       | `modules/seo/concepts-seo.php`           | `CGSEO_VERSION` |

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

## 2b. How deploying works (automatic)

The plugin lives in GitHub: `josephlai0904/concepts`. Every push to `main` runs **Test and deploy** (GitHub → Actions):

1. Tests: PHP lint, version numbers must match, smoke tests.
2. Upload to the server over SSH (key in the GitHub secret `SSH_KEY`).
3. On the server: lint again with the server's PHP, back up the live plugin to `~/cgs-backups/`, swap the files in, then confirm WordPress loads the new version.
4. Check the homepage and `/properties/` from outside.
5. If anything fails, it **rolls back automatically** to the backup.

Hostinger limits how many processes can run at once, so every server step retries. Any check that cannot run counts as a failure, never as a pass.

To test the rollback: Actions → Test and deploy → Run workflow → tick `test_rollback`. It restores the same files, so the site does not change.

Manual rollback (SSH): `rsync -a --delete ~/cgs-backups/<date>/ <wp>/wp-content/plugins/concepts-group-suite/`

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
    ├── clq-sites/  ← property pages, /properties/ hub, enquiry form
    └── seo/        ← page titles + descriptions, social tags, schema, sitemap
```

---

## Changelog

- **2.6.2** (2026-10-27): FAQPage schema also for the Recruitment FAQ, and for property FAQs once a site is no longer marked sample. Sections 1.40.4, CLQ Sites 1.7.7.
- **2.6.1** (2026-10-27): Works with Rank Math (now installed): any page, property or the blog without its own Rank Math title/description uses the built-in defaults. FAQPage schema stays on with SEO plugins. SEO 1.1.0.
- **2.6.0** (2026-10-27): New SEO module 1.0.0: a title and description for every page (editable in the "SEO (Google & AI)" box on each page), Facebook/WhatsApp/X share tags on all pages, posts and properties, company structured data (legal name, reg. no., founded 1991, phone), WebSite/WebPage/breadcrumb schema, FAQPage schema from the visible FAQ, blog and category descriptions, and a cleaner sitemap (no theme layout posts, author pages or sample properties). Steps aside if Yoast, Rank Math, AIOSEO or SEOPress is installed. Sections 1.40.3 (FAQ hand-off).
- **2.5.3** (2026-10-27): Removed the round dot icon before small labels (blog, posts, property pages). CLQ page titles and descriptions lead with "CLQ"; sample sites show no unverified bed figures; properties hub title "CLQ & Worker Accommodation in Klang Valley", default H1 "Centralised Labour Quarters in Klang Valley". Articles 1.2.2, CLQ Sites 1.7.6.
- **2.5.2** (2026-10-27): Phone menu: white sheet only as tall as the menu (slides from top, rounded bottom), dark overlay behind (tap to close). Footer credit removed (field kept, empty = hidden). Homepage contact band photo: Candid-hostel-conversation-by-the-bunks.webp. Sections 1.40.2.
- **2.5.1** (2026-10-27): Phone menu hierarchy: group labels small blue caps, pages 15-16px grey with a guide line. Our Approach cards: hover lift/colour change removed. Sections 1.40.1.
- **2.5.0** (2026-10-27): Phone header: menu toggle left (animates to X), logo centre, round arrow button right (Get in touch link); full-screen white panel slides down from the top behind the header bar, page scroll locked while open, Esc closes. Homepage CLQ buttons link to the matching published property page ("View property"), falling back to Enquire us. Contact band: inner padding inside the panel. Sections 1.40.0.
- **2.4.2** (2026-10-27): Workers parallax gentler (0.12 of scroll, max 12% of the workers height: ~72px desktop, ~31px phone). Phone hero scene shorter (5/4) with the building sitting on the wave (no empty sky). Sections 1.39.2.
- **2.4.1** (2026-10-27): CLQ map scroll-wheel zoom OFF (zoom with +/-, double-click, pinch). Homepage contact band: blue-grey (#eef3f9) behind the panel, panel insets to the footer card edges (5vw / 6vw phone) with the same corner radius. Sections 1.39.1, CLQ Sites 1.7.5.
- **2.4.0** (2026-10-27): Footer default is now the light "Card" style (white floating card: colour logo, tagline, social icons, Main Pages / Solutions / Contact, bottom bar with copyright, Back to top, credit; faint wordmark behind). Styles: Card / Modern / Classic. New field "Logo (dark, card style)". Homepage contact band narrows to a centred rounded panel as it scrolls into view (static for reduced motion). Sections 1.39.0.
- **2.3.1** (2026-10-27): Home hero buttons padding 14px 26px (halfway between 2.2.4 and 2.2.5). Sections 1.38.1.
- **2.3.0** (2026-10-27): Modern footer (default): logo + tagline, big "Do you have any questions?" heading with pill button, 5 link columns (Back to top, Main Pages, Solutions, Contact, Follow us), full-width "Concepts Group" wordmark (auto-fit), bottom bar with copyright + "Website by Many Project". Copyright "CONCEPTS ATC SDN BHD (Reg. No.: 199501034798 / 364000-P) © {year} All Rights Reserved." with automatic year. Restore: WPBakery > Concepts Footer > Layout > Style = Classic. Sections 1.38.0.
- **2.2.6** (2026-10-27): CLQ contact box background darker navy #071A33. CLQ Sites 1.7.4.
- **2.2.5** (2026-10-27): CLQ property page: "Get a quote" / "Get a written quote" -> "Talk to a specialist"; middle Surroundings photo 22% taller. Home Sky hero text ~10% smaller on desktop/tablet (H1 max 46px). Sections 1.37.5, CLQ Sites 1.7.3.
- **2.2.4** (2026-10-27): Hero eyebrow default "Workforce · Since 1991"; header button default "Get in touch"; Sky hero bottom padding slightly shorter (clamp(130px,13vw,220px)). Sections 1.37.4.
- **2.2.3** (2026-10-27): Sky hero readability: H1 tracking -.02em / line-height 1.1, darker blue H1 line (4.4:1) and button (5.0:1), eyebrow accent line, lead capped at 520px, fixed px spacing rhythm, stats with short divider; stronger text fade; bottom padding halfway (clamp(140px,14vw,240px)). Sections 1.37.3.
- **2.2.2** (2026-10-27): Sky hero: more bottom padding on desktop (clamp(170px,16vw,280px)) so the text has room above the wave. Sections 1.37.2.
- **2.2.1** (2026-10-27): Sky hero wave redrawn so the layers vary in thickness (each curve has its own rhythm); taller wave. Sections 1.37.1.
- **2.2.0** (2026-10-27): Sky hero: 3-layer wave (light + blue band behind the people, white in front), 3D enquiry card with icon and slide-up entry, workers parallax on scroll (off for reduced motion), workers bigger and further left, building further right. Sections 1.37.0.
- **2.1.2** (2026-10-27): Sky hero images: hostel with the full left wing (Concept-Group-Hostel-and-accommodation.webp, building 59vw) and new people photo (foreign-workers.webp); people scaled for tablet/phone. Sections 1.36.2.
- **2.1.1** (2026-10-27): Sky hero: people sized by hero height (heads in the top 30%), bigger building, "Since 1991" card removed, enquiry card moved to the right. Sections 1.36.1.
- **2.1.0** (2026-10-27): New "Sky" homepage hero (default): sky background (1472.webp), building + two-men cutouts standing on the wave, soft white fade behind the text. Restore the old look: WPBakery > Concepts Hero (Home) > Style = Classic. Sky, building and people images are editable in the Images tab. Sections 1.36.0.
- **2.0.4** (2026-10-27): Homepage hero H1 smaller: max(24px, min(6cqw, 52px)), was 62px max. Sections 1.35.11.
- **2.0.3** (2026-10-27): Header bar matches the content width on tablets too (901–1024px). Sections 1.35.10.
- **2.0.2** (2026-10-27): Header bar now spans the same width as the page content (removed the 1360px cap). Sections 1.35.9.
- **2.0.1** (2026-10-27): New white "since 1991" logo in header and footer (`CGS_LOGO_BRAND`); old white logos swap to it automatically. Sections 1.35.8.
- **2.0.0** (2026-10-27): Combined Concepts Group Sections 1.35.7, Concepts Articles 1.2.1 and Concepts CLQ Sites 1.7.2 into one plugin. The old plugins are switched off automatically on activation.
