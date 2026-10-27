<?php
/**
 * Concepts Group Suite module: Sections (header, hero, footer, page sections).
 * Loaded by ../../concepts-group-suite.php. Not a standalone plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CGS_VERSION', '1.36.0' );
define( 'CGS_UP', 'https://concepts.manyproject.my/wp-content/uploads/2026/10/' );
define( 'CGS_LOGO', 'https://concepts.manyproject.my/wp-content/uploads/2026/10/logo-white.png' );
define( 'CGS_LOGO_HD', 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concepts-Group-Logo.webp' );
define( 'CGS_LOGO_HDW', 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concepts-Group-White-Logo.webp' );
/* Current white brand logo, used in the header and footer. Change it here only. */
define( 'CGS_LOGO_BRAND', 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concepts-Group-Logo-since-1991-white.webp' );

/**
 * Header/footer logo: empty or an old white Concepts logo -> the current brand logo.
 * Any other image picked in the WPBakery "Logo" field is kept.
 */
function cgs_brand_logo( $url ) {
	$old = array( 'logo-white.png', 'Concepts-Group-White-Logo.webp' );
	$file = $url ? basename( (string) wp_parse_url( $url, PHP_URL_PATH ) ) : '';
	if ( '' === $file || in_array( $file, $old, true ) || preg_match( '/^Concepts-Group-White-Logo(-\d+x\d+|-scaled)?\.webp$/', $file ) ) {
		return CGS_LOGO_BRAND;
	}
	return $url;
}

/* ------------------------------------------------------------------
 * 1. GLOBAL CSS — scoped to body.cgs-site. Edit tokens here.
 * ---------------------------------------------------------------- */
function cgs_global_css() {
	return <<<'CSS'
body.cgs-site{
  --cgs-primary:#1e90ff;--cgs-secondary:#0b3f82;--cgs-footer:var(--cgs-navy);--cgs-blue:#1e90ff;--cgs-blue-d:#0b6fd1;--cgs-navy:#071A33;--cgs-ink:#14243b;--cgs-mute:#5d6d84;--cgs-soft:#f3f6fa;--cgs-line:#e1e8f1;--cgs-white:#fff;
  --cgs-fh:'Bricolage Grotesque',Inter,sans-serif;--cgs-fb:Inter,system-ui,sans-serif;
  --cgs-h1:clamp(34px,3.8vw,64px);--cgs-h2:clamp(13px,.9vw,16px);--cgs-h3:clamp(28px,2.9vw,48px);--cgs-h4:clamp(19px,1.5vw,26px);
  font-family:var(--cgs-fb);color:var(--cgs-ink);-webkit-font-smoothing:antialiased;overflow-x:hidden}
body.cgs-site #lqd-site-content,body.cgs-site #lqd-contents-wrap,body.cgs-site #lqd-contents-wrap.container,body.cgs-site .container{max-width:none!important;width:100%!important;padding-left:0;padding-right:0}
body.cgs-site #lqd-site-content :is(h1,h2,h3,h4){font-family:var(--cgs-fh);color:var(--cgs-navy);letter-spacing:-.035em;margin-top:0}
body.cgs-site #lqd-site-content h1{font-size:var(--cgs-h1);font-weight:700;line-height:1.04}
body.cgs-site #lqd-site-content h3{font-size:var(--cgs-h3);font-weight:600;line-height:1.06}
body.cgs-site #lqd-site-content h4{font-size:var(--cgs-h4);font-weight:600;line-height:1.2;letter-spacing:-.02em}
body.cgs-site #lqd-site-content :is(h3,h4){text-transform:capitalize}
body.cgs-site #lqd-site-content h2{font-family:var(--cgs-fb);font-size:var(--cgs-h2);font-weight:700;line-height:1.3;letter-spacing:.1em;text-transform:uppercase;color:var(--cgs-blue)}
@media(max-width:1024px){body.cgs-site{--cgs-h1:clamp(32px,5.2vw,48px);--cgs-h2:14px;--cgs-h3:clamp(26px,4.2vw,38px);--cgs-h4:20px}}
@media(max-width:767px){body.cgs-site{--cgs-h1:clamp(30px,8vw,38px);--cgs-h2:13px;--cgs-h3:clamp(24px,6.6vw,30px);--cgs-h4:18px}}
CSS;
}

/* ------------------------------------------------------------------
 * 2. HEADER CSS
 * ---------------------------------------------------------------- */
function cgs_header_css() {
	return <<<'CSS'
body.cgs-site{--cgs-hd-h:clamp(64px,5.4vw,78px)}
#header:has(.cgs-hd){position:relative!important;height:var(--cgs-hd-h)!important;min-height:var(--cgs-hd-h)!important;padding:0!important;margin:0!important;background:none!important;border:0!important;box-shadow:none!important;overflow:visible!important}
#header:has(.cgs-hd) .lqd-mobile-sec{display:none!important}
#header:has(.cgs-hd) :is(.vc_row,.vc_column_container,.vc_column-inner,.wpb_wrapper,.container,.lqd-head-col){padding:0!important;margin:0!important;min-height:0!important;max-width:none!important;background:none!important}
#header:has(.cgs-hd) .lqd-head-sec-wrap{display:block!important}
body:has(.cgs-hero) #header:has(.cgs-hd){height:0!important;min-height:0!important}
.cgs-hero:not(.cgs-hero-story){padding-top:calc(var(--cgs-hd-h) + clamp(48px,5vw,80px));padding-bottom:clamp(110px,12vw,180px)}
.cgs-ab2#company,.cgs-vm#vision,.cgs-vm#transport{padding-top:clamp(56px,5vw,80px)}
.cgs-hd{position:fixed;top:10px;left:0;right:0;z-index:999;font-family:var(--cgs-fb,Inter,sans-serif)}
.admin-bar .cgs-hd{top:42px}
@media(max-width:782px){.admin-bar .cgs-hd{top:56px}}
@media(max-width:600px){.admin-bar .cgs-hd{top:56px;transition:top .3s}.admin-bar .cgs-hd.s{top:10px}}
.cgs-hd *{box-sizing:border-box}
.cgs-hd a{color:inherit;text-decoration:none}
.cgs-hd .cgs-bar{position:relative;display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:2vw;height:clamp(52px,4.4vw,62px);max-width:none;width:calc(100% - 10vw);margin:0 auto;padding:0 10px 0 clamp(18px,2.1vw,30px);border-radius:60px;background:linear-gradient(90deg,#0b5fc4,var(--cgs-blue,#1e90ff));box-shadow:0 14px 40px -14px rgba(11,95,196,.7)}
.cgs-hd .cgs-lg{display:block;justify-self:center}
.cgs-hd .cgs-lg img{height:clamp(24px,2.2vw,32px);width:auto;display:block}
.cgs-hd nav{display:flex;align-items:center;justify-content:flex-start;gap:26px;font-weight:500;font-size:.94rem;color:#fff}
.cgs-hd nav a:not(.cgs-btn){transition:color .25s}
.cgs-hd nav a:not(.cgs-btn):hover{color:#d6ebff}
.cgs-hd .cgs-hr{display:flex;align-items:center;justify-content:flex-end;justify-self:end;gap:18px}
.cgs-hd .cgs-ph{color:#fff;line-height:1.15;text-align:right}
.cgs-hd .cgs-ph small{display:block;font-size:.66rem;letter-spacing:.1em;text-transform:uppercase;color:#cfe6ff}
.cgs-hd .cgs-ph b{font-size:.95rem}
.cgs-hd .cgs-btn{display:inline-flex;align-items:center;gap:10px;padding:clamp(9px,.8vw,11px) clamp(18px,1.6vw,22px);border-radius:50px;font:600 .88rem Inter,sans-serif;background:#fff;color:var(--cgs-blue-d,#0b6fd1);border:2px solid #fff;cursor:pointer;transition:background .3s,border-color .3s,color .3s}
.cgs-hd .cgs-btn:after{content:"\2192";display:inline-block;line-height:1;transform:translate(0,0);will-change:transform;transition:transform .3s}
.cgs-hd .cgs-btn:hover{background:var(--cgs-navy,#071A33);border-color:var(--cgs-navy,#071A33);color:#fff}
.cgs-hd .cgs-btn:hover:after{transform:translate(5px,0)}
.cgs-hd .cgs-nm{display:none}
.cgs-hd .cgs-burger{display:none;justify-self:end;background:#fff;color:var(--cgs-blue-d,#0b6fd1);border:0;width:clamp(38px,3vw,44px);height:clamp(38px,3vw,44px);border-radius:50%;font-size:clamp(15px,1.1vw,18px);cursor:pointer;line-height:1;padding:0}
.cgs-hd .cgs-dd{position:relative;display:flex;align-items:center}
.cgs-hd .cgs-ddt{display:inline-flex;align-items:center}
.cgs-hd .cgs-ddt:after{content:"";flex:none;width:6px;height:6px;border-right:2px solid currentColor;border-bottom:2px solid currentColor;transform:translateY(-2px) rotate(45deg);margin:0 0 0 8px;transition:transform .3s}
.cgs-hd .cgs-dd:hover>a:after{transform:translateY(2px) rotate(225deg)}
.cgs-hd .cgs-dd-menu{position:absolute;top:100%;left:-16px;min-width:clamp(230px,20vw,290px);padding-top:22px;opacity:0;visibility:hidden;transform:translateY(8px);transition:opacity .3s,transform .3s,visibility .3s;filter:drop-shadow(0 24px 30px rgba(7,26,51,.28))}
.cgs-hd .cgs-dd:hover .cgs-dd-menu,.cgs-hd .cgs-dd:focus-within .cgs-dd-menu{opacity:1;visibility:visible;transform:none}
.cgs-hd .cgs-dd-menu a,.cgs-hd .cgs-dd-menu .cgs-sub{display:block;background:#fff;color:var(--cgs-navy,#071A33);font-weight:500;font-size:.92rem;white-space:nowrap}
.cgs-hd .cgs-dd-menu a{padding:13px 18px;transition:background .25s,color .25s}
.cgs-hd .cgs-dd-menu a:hover,.cgs-hd nav .cgs-dd-menu a:not(.cgs-btn):hover{background:#eaf4ff;color:var(--cgs-navy,#071A33)}
.cgs-hd .cgs-dd-menu>:first-child{border-radius:20px 20px 0 0}
.cgs-hd .cgs-dd-menu>:last-child{border-radius:0 0 20px 20px}
.cgs-hd .cgs-dd-menu>:only-child{border-radius:20px}
.cgs-hd .cgs-sub{position:relative}
.cgs-hd .cgs-dd-menu a.cgs-subt{display:flex;justify-content:space-between;align-items:center;gap:16px}
.cgs-hd .cgs-subt:after{content:"";width:6px;height:6px;border-right:2px solid currentColor;border-top:2px solid currentColor;transform:rotate(45deg)}
.cgs-hd .cgs-sub-menu{position:absolute;top:0;left:100%;min-width:clamp(200px,17vw,250px);padding-left:8px;opacity:0;visibility:hidden;transform:translateX(8px);transition:opacity .25s,transform .25s,visibility .25s}
.cgs-hd .cgs-sub:hover>.cgs-sub-menu,.cgs-hd .cgs-sub:focus-within>.cgs-sub-menu{opacity:1;visibility:visible;transform:none}
.cgs-hd .cgs-sub-menu>:first-child{border-radius:20px 20px 0 0}
.cgs-hd .cgs-sub-menu>:last-child{border-radius:0 0 20px 20px}
.cgs-hd .cgs-sub-menu>:only-child{border-radius:20px}
@media(max-width:1024px){.cgs-hd .cgs-bar{width:calc(100% - 12vw)}}
@media(max-width:1200px){.cgs-hd .cgs-ph{display:none}.cgs-hd nav{gap:18px;font-size:.88rem}}
@media(max-width:900px){
.cgs-hd .cgs-bar{display:flex;justify-content:space-between;width:calc(100% - 12vw);padding:0 10px 0 16px}
.cgs-hd .cgs-lg{order:-1}
.cgs-hd .cgs-hr{display:none}
.cgs-hd .cgs-burger{display:block}
.cgs-hd nav{position:absolute;top:calc(100% + 10px);left:0;right:0;background:#fff;color:var(--cgs-navy,#071A33);flex-direction:column;align-items:stretch;justify-content:flex-start;gap:14px;padding:20px;display:none;border-radius:22px;box-shadow:0 20px 40px rgba(7,26,51,.2);font-size:15px;max-height:calc(100vh - var(--cgs-hd-h) - 20px);overflow-y:auto}
.cgs-hd nav.open{display:flex}
.cgs-hd nav a:not(.cgs-btn):hover{color:var(--cgs-blue,#1e90ff)}
.cgs-hd .cgs-nm{display:inline-flex;justify-content:center;margin-top:4px;background:var(--cgs-blue,#1e90ff);color:#fff;border-color:var(--cgs-blue,#1e90ff)}
.cgs-hd .cgs-dd{flex-direction:column;align-items:stretch}
.cgs-hd .cgs-dd-menu{position:static;min-width:0;opacity:1;visibility:visible;transform:none;filter:none;padding:6px 0 0 12px;display:none}
.cgs-hd .cgs-dd.open .cgs-dd-menu,.cgs-hd .cgs-dd.open:hover .cgs-dd-menu{display:block}
.cgs-hd .cgs-dd:hover .cgs-dd-menu{display:none}
.cgs-hd .cgs-dd-menu a,.cgs-hd .cgs-dd-menu .cgs-sub{border-radius:0!important;background:none;color:inherit}
.cgs-hd .cgs-dd-menu a{padding:8px 0}
.cgs-hd .cgs-subt:after{display:none}
.cgs-hd .cgs-sub-menu{position:static;min-width:0;opacity:1;visibility:visible;transform:none;padding:0 0 0 12px}
}
CSS;
}

/* ------------------------------------------------------------------
 * 3. FOOTER CSS
 * ---------------------------------------------------------------- */
function cgs_footer_css() {
	return <<<'CSS'
#footer:has(.cgs-ft){background:none!important;padding:0!important;margin:0!important;border:0!important}
#footer:has(.cgs-ft) :is(.vc_row,.vc_column_container,.vc_column-inner,.wpb_wrapper,.container,.ld-container,.row,.ld-row){padding:0!important;margin:0!important;max-width:none!important;width:100%!important;background:none!important}
.cgs-ft{background:var(--cgs-navy,#071A33);color:#fff;font-family:var(--cgs-fb,Inter,sans-serif);font-size:clamp(13px,.95vw,15px);line-height:1.6;padding:clamp(40px,4.4vw,64px) 0 0}
.cgs-ft *{box-sizing:border-box}
.cgs-ft a{color:inherit;text-decoration:none}
.cgs-ft .cgs-ft-in{width:100%;margin:0 auto;padding:0 5vw}
.cgs-ft .cgs-ft-top{display:flex;justify-content:space-between;gap:4.4vw;flex-wrap:wrap}
.cgs-ft .cgs-ft-brand img.cgs-ft-logo{height:var(--cgs-ft-lh,34px);width:auto;display:block}
@media(max-width:767px){.cgs-ft .cgs-ft-brand img.cgs-ft-logo{height:calc(var(--cgs-ft-lh,34px) * .85)}}
.cgs-ft .cgs-ft-soc{display:flex;gap:.7vw;margin-top:1.8vw}
.cgs-ft .cgs-ft-soc a{width:clamp(34px,3vw,42px);height:clamp(34px,3vw,42px);border-radius:50%;border:1.5px solid rgba(255,255,255,.4);display:grid;place-items:center;transition:.3s}
.cgs-ft .cgs-ft-soc img{width:clamp(14px,1.3vw,19px);height:clamp(14px,1.3vw,19px);display:block;filter:brightness(0) invert(1)}
.cgs-ft .cgs-ft-soc a:hover{background:var(--cgs-primary,#1e90ff);border-color:var(--cgs-primary,#1e90ff);transform:translateY(-3px)}
.cgs-ft .cgs-ft-cols{display:flex;gap:5.8vw;flex-wrap:wrap}
.cgs-ft .cgs-ft-col h4{color:#fff;font:600 clamp(14px,1.05vw,17px) Inter,sans-serif;letter-spacing:0;margin:0 0 1.5vw}
.cgs-ft .cgs-ft-col a{display:block;color:#c9d6e8;padding:.5vw 0;font-size:clamp(13px,.95vw,15px);transition:.25s}
.cgs-ft .cgs-ft-col a:hover{color:#5db4ff;transform:translateX(4px)}
.cgs-ft .cgs-ft-bar{margin-top:3.9vw;padding:1.8vw 0 2.1vw;border-top:1px solid rgba(255,255,255,.22);font-size:clamp(12px,.85vw,14px);color:#e6eefa}
@media(max-width:1024px){.cgs-ft .cgs-ft-cols{gap:3.5vw}}
@media(max-width:767px){.cgs-ft{padding-top:10vw}.cgs-ft .cgs-ft-in{padding:0 6vw}.cgs-ft .cgs-ft-top{gap:8vw}.cgs-ft .cgs-ft-cols{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:28px 20px;width:100%}.cgs-ft .cgs-ft-col:nth-child(3){grid-column:1/-1}.cgs-ft .cgs-ft-soc{margin-top:22px}.cgs-ft .cgs-ft-bar{margin-top:8vw;padding:20px 0 28px}}
CSS;
}

/* ------------------------------------------------------------------
 * 4. HERO CSS (Home2) — background image sits behind the content
 * ---------------------------------------------------------------- */
function cgs_hero_css() {
	return <<<'CSS'
.vc_row.cgs-row,.vc_row.cgs-row>.ld-container,.vc_row.cgs-row .ld-row,.vc_row.cgs-row .vc_column_container,.vc_row.cgs-row .vc_column-inner,.vc_row.cgs-row .wpb_wrapper{padding:0!important;margin-left:0!important;margin-right:0!important;max-width:none!important;width:100%!important}
.cgs-hero{position:relative;overflow:hidden;min-height:0;display:flex;align-items:flex-start;padding:clamp(72px,7vw,104px) 0 clamp(110px,10vw,150px);background:#dff0ff;font-family:var(--cgs-fb,Inter,sans-serif);color:var(--cgs-ink,#14243b)}
.cgs-hero *{box-sizing:border-box}
.cgs-hero::after{content:"";position:absolute;top:0;right:-18%;bottom:0;width:74%;z-index:0;background:var(--cgs-bg,none) center/cover no-repeat}
.cgs-hero-in{position:relative;z-index:2;width:100%;margin:0 auto;padding:0 5vw;display:grid;grid-template-columns:1.3fr 1fr;gap:2.2vw;align-items:center}
.cgs-hero-txt{container-type:inline-size;position:relative}
body.cgs-site #lqd-site-content .cgs-jr h2.cgs-hero-eb,body.cgs-site #lqd-site-content .cgs-ab2 h2.cgs-hero-eb,body.cgs-site #lqd-site-content .cgs-hero h2.cgs-hero-eb,body.cgs-site #lqd-site-content .cgs-vm h2.cgs-hero-eb,body.cgs-site #lqd-site-content .cgs-cm h2.cgs-hero-eb,body.cgs-site #lqd-site-content .cgs-cts h2.cgs-hero-eb{display:flex;align-items:center;gap:.9vw;margin:0 0 1.3vw;font:700 clamp(12px,.85vw,14px) var(--cgs-fb,Inter,sans-serif);letter-spacing:.1em;text-transform:uppercase;color:var(--cgs-blue-d,#0b6fd1)}
body.cgs-site #lqd-site-content .cgs-hero h1.cgs-hero-h{margin:0;font-family:var(--cgs-fh);font-weight:700;line-height:1.04;letter-spacing:-.035em;color:var(--cgs-navy,#071A33);font-size:max(24px,min(6cqw,52px))}
.cgs-hero-h .cgs-ln{display:block;overflow:hidden}
.cgs-hero-h .cgs-ln>span{display:block;transform:translateY(110%);animation:cgsUp 1s cubic-bezier(.2,.7,.2,1) forwards}
.cgs-hero-h .cgs-ln:nth-child(2)>span{animation-delay:.12s}
.cgs-hero-h em{font-style:normal;color:var(--cgs-blue,#1e90ff)}
.cgs-hero-lead{max-width:40vw;margin:1.6vw 0 2.2vw;font-size:clamp(15px,1.1vw,18px);line-height:1.65;color:#3f536d}
.cgs-hero .cgs-fade{opacity:0;animation:cgsIn 1s .5s forwards}
.cgs-hero-cta{display:flex;gap:1vw;flex-wrap:wrap}
.cgs-hero .cgs-hb,.cgs-ab2 .cgs-hb,.cgs-vm .cgs-hb{display:inline-flex;align-items:center;gap:.7vw;padding:clamp(12px,1.1vw,16px) clamp(20px,2.1vw,30px);border-radius:50px;font:600 clamp(14px,1vw,16px) Inter,sans-serif;text-decoration:none;border:1px solid var(--cgs-blue,#1e90ff);cursor:pointer;transition:.3s}
.cgs-hero .cgs-hb img,.cgs-ab2 .cgs-hb img,.cgs-vm .cgs-hb img{width:clamp(14px,1.2vw,18px);height:clamp(14px,1.2vw,18px);display:block;object-fit:contain;transition:.3s}
.cgs-hero .cgs-hb:hover img,.cgs-ab2 .cgs-hb:hover img,.cgs-vm .cgs-hb:hover img{transform:translateX(5px)}
.cgs-hero .cgs-hb1,.cgs-ab2 .cgs-hb1,.cgs-vm .cgs-hb1{background:var(--cgs-blue,#1e90ff);color:#fff}
.cgs-hero .cgs-hb1 img,.cgs-ab2 .cgs-hb1 img,.cgs-vm .cgs-hb1 img{filter:brightness(0) invert(1)}
.cgs-hero .cgs-hb1:hover,.cgs-ab2 .cgs-hb1:hover,.cgs-vm .cgs-hb1:hover{background:#fff;color:var(--cgs-blue,#1e90ff);border:1px solid var(--cgs-blue,#1e90ff)}
.cgs-hero .cgs-hb1:hover img,.cgs-ab2 .cgs-hb1:hover img,.cgs-vm .cgs-hb1:hover img{filter:invert(48%) sepia(79%) saturate(2476%) hue-rotate(190deg) brightness(100%) contrast(101%)}
.cgs-hero .cgs-hb2,.cgs-vm .cgs-hb2{background:#fff;color:var(--cgs-navy,#071A33);border-color:#fff}
.cgs-hero .cgs-hb2:hover,.cgs-vm .cgs-hb2:hover{background:var(--cgs-navy,#071A33);color:#fff;border-color:var(--cgs-navy,#071A33)}
.cgs-hero .cgs-hb2:hover img,.cgs-vm .cgs-hb2:hover img{filter:brightness(0) invert(1)}
.cgs-hero-stats{display:flex;gap:3vw;flex-wrap:wrap;margin-top:2.6vw}
.cgs-hero-stats b{display:block;font:700 clamp(26px,2.2vw,36px)/1 var(--cgs-fh);letter-spacing:-.03em;color:var(--cgs-navy,#071A33)}
.cgs-hero-stats span{font-size:clamp(12px,.85vw,14px);color:#4b607c}
.cgs-hero-vis{position:relative}
.cgs-hero-work{display:block;width:100%;max-width:clamp(300px,46vw,640px);margin:0 auto;height:auto;aspect-ratio:3/2;object-fit:cover;object-position:center 30%;border-radius:clamp(14px,1.2vw,20px);border:clamp(3px,.3vw,5px) solid #fff;box-shadow:0 26px 44px -20px rgba(11,74,150,.6)}
.cgs-hero:not(.cgs-hero-story) .cgs-hero-in{grid-template-columns:1.3fr .7fr}
.cgs-hero:not(.cgs-hero-story) .cgs-hero-vis{width:100%;max-width:clamp(280px,23vw,360px);aspect-ratio:330/430;margin:0 auto}
.cgs-hero:not(.cgs-hero-story) .cgs-hero-vis:before{content:"";position:absolute;inset:34px -26px -26px 34px;border-radius:260px 260px 36px 36px;background:linear-gradient(160deg,var(--cgs-blue,#1e90ff),var(--cgs-blue-d,#0b6fd1));transform:rotate(3deg);translate:0 var(--cgs-pb,0px);will-change:translate}
.cgs-hero-arch{position:absolute;inset:0;overflow:hidden;will-change:translate;border-radius:260px 260px 36px 36px;background:linear-gradient(180deg,#cfe6ff 0%,#8fc4ff 100%);box-shadow:0 40px 60px -30px rgba(7,26,51,.5)}
.cgs-hero:not(.cgs-hero-story) .cgs-hero-work{position:absolute;left:calc((100% - var(--cgs-ws,100%)) / 2);bottom:0;display:block;width:var(--cgs-ws,100%);max-width:none;height:auto;aspect-ratio:auto;margin:0;border:0;border-radius:0;box-shadow:none;pointer-events:none}
.cgs-hero-head{position:absolute;left:calc((100% - var(--cgs-ws,100%)) / 2);bottom:0;z-index:1;display:block;width:var(--cgs-ws,100%);height:auto;clip-path:inset(0 0 80% 0);pointer-events:none;will-change:translate}
.cgs-hero-enq{position:absolute;left:-110px;bottom:36px;z-index:2;display:block;padding:10px 16px;border-radius:18px;background:#fff;text-decoration:none;box-shadow:0 22px 44px -18px rgba(7,26,51,.5);transition:transform .3s}
.cgs-hero-enq small{display:block;font-size:.76rem;color:var(--cgs-mute,#5d6d84)}
.cgs-hero-enq b{font-size:.98rem;color:var(--cgs-blue-d,#0b6fd1)}
.cgs-hero-enq:hover{transform:translateY(-4px)}
.cgs-hero-chip{position:absolute;top:60px;right:-70px;z-index:2;padding:9px 14px;border-radius:18px;background:#fff;box-shadow:0 20px 40px -18px rgba(7,26,51,.45)}
.cgs-hero-chip b{display:block;font:700 1.15rem var(--cgs-fh,'Bricolage Grotesque',sans-serif);color:var(--cgs-navy,#071A33)}
.cgs-hero-chip span{font-size:.78rem;color:var(--cgs-mute,#5d6d84)}
@media(max-width:1024px){.cgs-hero-chip{right:-10px}}
@media(min-width:1200px){.cgs-hero-chip{right:-105px}.cgs-hero-enq{left:-150px}}
.cgs-hero-wave{position:absolute;left:0;bottom:-1px;width:100%;height:clamp(60px,6.4vw,110px);z-index:3;display:block;pointer-events:none}
.cgs-ct#contact:before{background:linear-gradient(180deg,rgba(7,26,51,.25) 0%,rgba(7,26,51,.15) 28%,rgba(7,26,51,.78) 62%,var(--cgs-navy,#071A33) 96%)}
.cgs-ct#contact:after{display:none}
.cgs-ct#contact .cgs-ct-in{padding-top:clamp(90px,8vw,120px)}
@media(max-width:767px){.cgs-ct#contact .cgs-ct-in{padding-top:80px}}
@media(max-width:1024px){.cgs-hero:not(.cgs-hero-story) .cgs-hero-in{grid-template-columns:1fr}.cgs-hero:not(.cgs-hero-story) .cgs-hero-vis{max-width:330px;margin:clamp(96px,14vw,120px) auto 0}.cgs-hero-enq{left:-10px}}
@keyframes cgsUp{to{transform:none}}
@keyframes cgsIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:none}}
@media(max-width:1024px){.cgs-hero{padding-top:clamp(72px,9vw,96px);padding-bottom:clamp(64px,9vw,96px)}.cgs-hero::after{right:0;width:100%;opacity:.35}.cgs-hero-in{grid-template-columns:1fr;padding:0 6vw}.cgs-hero-lead{max-width:none}.cgs-hero-vis{margin-top:clamp(28px,5vw,44px)}.cgs-hero-work{max-width:none;aspect-ratio:2/1}}
@media(max-width:767px){.cgs-hero{min-height:0;padding:clamp(60px,16vw,76px) 0 clamp(56px,14vw,72px)}.cgs-hero-cta{gap:14px}.cgs-hero-lead{margin-bottom:32px}.cgs-hero-work{aspect-ratio:3/2}.cgs-hero-vis{margin-top:clamp(28px,8vw,40px)}.cgs-hero-stats{gap:5vw}}
@media(max-width:767px){body.cgs-site #lqd-site-content .cgs-jr h2.cgs-hero-eb,body.cgs-site #lqd-site-content .cgs-ab2 h2.cgs-hero-eb,body.cgs-site #lqd-site-content .cgs-hero h2.cgs-hero-eb,body.cgs-site #lqd-site-content .cgs-vm h2.cgs-hero-eb,body.cgs-site #lqd-site-content .cgs-cm h2.cgs-hero-eb,body.cgs-site #lqd-site-content .cgs-cts h2.cgs-hero-eb{gap:2.4vw;margin-bottom:3vw}body.cgs-site #lqd-site-content .cgs-hero h1.cgs-hero-h{font-size:clamp(28px,7.6vw,34px);opacity:0;animation:cgsIn 1s .1s forwards}.cgs-hero-h .cgs-ln{display:inline;overflow:visible}.cgs-hero-h .cgs-ln+.cgs-ln{margin-left:.28em}.cgs-hero-h .cgs-ln>span{display:inline;transform:none;animation:none}}
@media(max-width:767px) and (prefers-reduced-motion:reduce){body.cgs-site #lqd-site-content .cgs-hero h1.cgs-hero-h{opacity:1;animation:none}}
@media(prefers-reduced-motion:reduce){.cgs-hero .cgs-fade{opacity:1;animation:none}.cgs-hero-h .cgs-ln>span{transform:none;animation:none}}
.cgs-hero.cgs-hero-story{padding:var(--cgs-hd-h) 0 clamp(28px,3vw,48px);background:linear-gradient(90deg,#fff 0%,#a9d4ff 100%);align-items:stretch}
.cgs-hero-story .cgs-hero-h em{color:var(--cgs-blue-d,#0b6fd1)}
.cgs-hero-story::after{display:none}
.cgs-hero-story .cgs-hero-in{padding:0;gap:0;grid-template-columns:1fr 1fr;align-items:stretch}
.cgs-hero-story .cgs-hero-txt{align-self:center;padding:clamp(32px,4vw,64px) 5vw}
.cgs-hero-story .cgs-hero-vis{margin:clamp(20px,3vw,40px) 5vw clamp(20px,3vw,40px) 0;min-height:clamp(300px,32vw,480px)}
.cgs-hero-story .cgs-hero-work{position:absolute;inset:0;width:100%;height:100%;max-width:none;aspect-ratio:auto;margin:0;border:clamp(4px,.4vw,6px) solid #fff;box-shadow:0 26px 44px -20px rgba(7,26,51,.45);object-position:center;border-radius:clamp(16px,1.6vw,24px)}
@media(min-width:1025px){body.cgs-site #lqd-site-content .cgs-hero-story h1.cgs-hero-h{font-size:max(24px,min(3.7vw,70px))}}
@media(min-width:768px) and (max-width:1024px){body.cgs-site #lqd-site-content .cgs-hero-story h1.cgs-hero-h{font-size:max(24px,min(6.5vw,70px))}}
/* Sky hero (2.1.0): sky background, building + people cutouts on the right, text on a soft white fade. */
.cgs-hero.cgs-hero-sky{background:#eaf4ff var(--cgs-sky,none) center 30%/cover no-repeat;min-height:clamp(620px,47vw,900px);align-items:center}
.cgs-hero-sky::after{display:none}
.cgs-hero-sky::before{content:"";position:absolute;inset:0;z-index:1;pointer-events:none;background:linear-gradient(90deg,rgba(255,255,255,.9) 0%,rgba(255,255,255,.7) 30%,rgba(255,255,255,.15) 50%,rgba(255,255,255,0) 60%)}
.cgs-hero-sky .cgs-hero-in{grid-template-columns:1fr}
.cgs-hero-sky .cgs-hero-txt{max-width:min(48vw,820px)}
.cgs-hero-sky .cgs-hero-lead{max-width:min(40vw,640px)}
.cgs-hero-sky .cgs-sky-scene{position:absolute;right:0;bottom:0;width:58vw;height:100%;z-index:2;pointer-events:none}
.cgs-hero-sky .cgs-sky-bld{position:absolute;right:-3vw;bottom:clamp(24px,2.4vw,48px);width:42vw;max-width:none;height:auto;display:block;filter:drop-shadow(0 30px 40px rgba(7,26,51,.18))}
.cgs-hero-sky .cgs-sky-team{position:absolute;right:12vw;bottom:0;width:35vw;max-width:none;height:auto;display:block;filter:drop-shadow(0 24px 30px rgba(7,26,51,.28))}
.cgs-hero-sky .cgs-hero-chip{top:auto;bottom:58%;right:4vw;pointer-events:auto}
.cgs-hero-sky .cgs-hero-enq{left:auto;right:42vw;bottom:clamp(120px,11vw,190px);pointer-events:auto}
.cgs-hero-sky .cgs-hb2{border-color:#d6e4f5;box-shadow:0 12px 28px -14px rgba(7,26,51,.35)}
@media(max-width:1024px){body .cgs-hero.cgs-hero-sky{min-height:0;flex-direction:column;align-items:stretch;padding-bottom:0}.cgs-hero-sky::before{background:linear-gradient(180deg,rgba(255,255,255,.92) 0%,rgba(255,255,255,.7) 45%,rgba(255,255,255,0) 70%)}.cgs-hero-sky .cgs-hero-txt,.cgs-hero-sky .cgs-hero-lead{max-width:none}.cgs-hero-sky .cgs-sky-scene{position:relative;width:100%;height:auto;aspect-ratio:5/4;margin-top:clamp(24px,5vw,40px)}.cgs-hero-sky .cgs-sky-bld{right:-6%;bottom:14%;width:84%}.cgs-hero-sky .cgs-sky-team{right:auto;left:2%;bottom:0;width:70%}.cgs-hero-sky .cgs-hero-chip{bottom:auto;top:4%;right:4%}.cgs-hero-sky .cgs-hero-enq{right:auto;left:4%;bottom:auto;top:10%}}
@media(max-width:767px){.cgs-hero-sky .cgs-hero-enq{display:none}.cgs-hero-sky .cgs-sky-scene{aspect-ratio:1/1}.cgs-hero-sky .cgs-sky-team{width:92%;left:-4%}.cgs-hero-sky .cgs-sky-bld{width:96%;right:-12%;bottom:22%}}
@media(max-width:1024px){.cgs-hero-story .cgs-hero-in{grid-template-columns:1fr}.cgs-hero-story .cgs-hero-txt{padding:clamp(48px,8vw,72px) 6vw clamp(12px,2.5vw,20px)}.cgs-hero-story .cgs-hero-vis{margin:clamp(12px,2vw,20px) 6vw 0;min-height:0}.cgs-hero-story .cgs-hero-work{position:static;height:auto;aspect-ratio:3/2}}
@media(min-width:768px) and (max-width:1024px){.cgs-hero-story .cgs-hero-work{aspect-ratio:2.2/1}}
@media(max-width:767px){body.page-id-488 .cgs-hero-story .cgs-hero-h .cgs-ln{display:block;white-space:nowrap}body.page-id-488 .cgs-hero-story .cgs-hero-h .cgs-ln+.cgs-ln{margin-left:0}}
.cgs-ab2{position:relative;background:var(--cgs-soft,#f3f6fa);padding:calc(94px + clamp(40px,4vw,70px)) 5vw clamp(60px,6vw,110px);font-family:var(--cgs-fb,Inter,sans-serif);color:var(--cgs-ink,#14243b);overflow:hidden}
.cgs-ab2 *{box-sizing:border-box}
.cgs-ab2-in{display:grid;grid-template-columns:1fr 1fr;gap:clamp(32px,6vw,100px);align-items:center}
.cgs-ab2-pics{position:relative;width:100%;max-width:620px;aspect-ratio:1/1}
.cgs-ab2-main{position:absolute;top:0;right:5%;width:68%;height:74%}
.cgs-ab2-main:before{content:"";position:absolute;inset:0;transform:translate(7%,5%);background:var(--cgs-blue,#1e90ff);border-radius:clamp(14px,1.2vw,22px)}
.cgs-ab2 img{position:relative;display:block;width:100%;height:100%;object-fit:cover;border-radius:clamp(14px,1.2vw,22px)}
.cgs-ab2-sub{position:absolute;left:0;bottom:0;width:48%;aspect-ratio:1/1;border:clamp(4px,.5vw,8px) solid #fff;border-radius:clamp(14px,1.2vw,22px);overflow:hidden;background:#fff;box-shadow:0 22px 40px -20px rgba(11,74,150,.55)}
.cgs-ab2-sub img{border-radius:0}
.cgs-ab2-card{position:absolute;left:12%;top:9%;z-index:2;background:#fff;border-radius:clamp(10px,.9vw,14px);padding:clamp(10px,1vw,16px) clamp(14px,1.4vw,22px);box-shadow:0 18px 36px -18px rgba(11,74,150,.45)}
.cgs-ab2-card b{display:block;font:700 clamp(22px,2vw,32px)/1 var(--cgs-fh);letter-spacing:-.03em;color:var(--cgs-blue,#1e90ff)}
.cgs-ab2-card span{font-size:clamp(12px,.85vw,14px);color:var(--cgs-mute,#5d6d84)}
.cgs-ab2{background:var(--cgs-navy,#071A33)}
body.cgs-site #lqd-site-content .cgs-ab2 h2.cgs-hero-eb{color:#fff}body.cgs-site #lqd-site-content .cgs-ab2 h3.cgs-about-h{color:#fff}
body.cgs-site #lqd-site-content .cgs-ab2 h3.cgs-about-h em{color:#7cc4ff}
.cgs-ab2 .cgs-about-txt p{color:#d6e8fb}
.cgs-ab2 .cgs-hb1:hover{color:var(--cgs-blue,#1e90ff)}
.cgs-ab2 .cgs-about-h{margin-bottom:1.6vw}
.cgs-ab2 .cgs-about-txt p{margin:0 0 1.2vw;max-width:none}
.cgs-ab2 .cgs-hb{margin-top:1vw}
@media(max-width:1024px){.cgs-ab2{padding:calc(94px + clamp(32px,5vw,48px)) 6vw clamp(48px,8vw,72px)}.cgs-ab2-in{gap:clamp(24px,4vw,40px)}.cgs-ab2 .cgs-about-h{margin-bottom:20px}.cgs-ab2 .cgs-about-txt p{margin-bottom:16px}.cgs-ab2 .cgs-hb{margin-top:8px}}
@media(max-width:1024px){.cgs-ab2-in{grid-template-columns:1fr;gap:40px}.cgs-ab2-pics{max-width:560px;margin:0 auto}.cgs-ab2 .cgs-about-txt{order:-1}}
@media(max-width:767px){.cgs-ab2{padding-top:calc(52px + 40px)}}
.cgs-jr{position:relative;background:#fff;padding:clamp(60px,6vw,110px) 5vw;font-family:var(--cgs-fb,Inter,sans-serif);color:var(--cgs-ink,#14243b);overflow:hidden}
.cgs-jr *{box-sizing:border-box}
.cgs-jr-head{display:grid;grid-template-columns:1fr 1fr;gap:clamp(24px,5vw,80px);align-items:center;padding:0 clamp(12px,1.2vw,18px);margin-bottom:clamp(32px,4vw,64px)}
@media(min-width:768px) and (max-width:1024px){.cgs-jr .cgs-about-h em{display:block}}
.cgs-jr .cgs-about-h{margin:0}
.cgs-jr-head p{margin:0;font-size:clamp(15px,1.05vw,17px);line-height:1.7;color:#3f536d}
.cgs-jr-list{list-style:none;margin:0;padding:0;border-top:1px solid var(--cgs-line,#e1e8f1)}
.cgs-jr-li{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);column-gap:clamp(24px,5vw,80px);padding:0 clamp(12px,1.2vw,18px);border-bottom:1px solid var(--cgs-line,#e1e8f1);border-radius:clamp(14px,1.2vw,22px);transition:background .3s}
.cgs-jr-li:hover,.cgs-jr-li.on{background:var(--cgs-soft,#f3f6fa)}
.cgs-jr-li.on{border-color:transparent}
body.cgs-site #lqd-site-content .cgs-jr button.cgs-jr-b{grid-column:1/-1;display:grid;grid-template-columns:subgrid;align-items:center;width:100%;padding:clamp(18px,1.8vw,28px) 0;background:none;border:0;border-radius:0;box-shadow:none;text-align:left;text-transform:none;letter-spacing:0;font:inherit;color:inherit;cursor:pointer}.cgs-jr-y{font:500 clamp(20px,2vw,30px)/1 var(--cgs-fh);letter-spacing:-.03em;color:var(--cgs-blue,#1e90ff)}
body.cgs-site #lqd-site-content .cgs-jr h4.cgs-jr-t{margin:0;font:600 clamp(18px,1.45vw,24px)/1.2 var(--cgs-fh);text-transform:capitalize;color:var(--cgs-navy,#071A33)}
.cgs-jr-s{display:block;margin-top:4px;font-size:clamp(15px,1.05vw,17px);line-height:1.7;color:#3f536d}
.cgs-jr-d{grid-column:2;display:none;padding:0 0 clamp(20px,2vw,32px)}
.cgs-jr .on .cgs-jr-d{display:block;animation:cgsIn .5s ease both}
.cgs-jr-dp{margin:0;max-width:70ch;font-size:clamp(15px,1.05vw,17px);line-height:1.7;color:#3f536d}
.cgs-jr-img{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,240px),1fr));max-width:600px;gap:12px;margin-top:20px}
.cgs-jr-img img{display:block;width:100%;aspect-ratio:2/1;object-fit:cover;border-radius:clamp(10px,.9vw,14px)}
@media(max-width:767px){.cgs-jr-head{grid-template-columns:1fr;gap:16px}.cgs-jr-li{grid-template-columns:minmax(64px,auto) minmax(0,1fr);column-gap:16px}.cgs-jr-y{font-size:18px}}
@media(prefers-reduced-motion:reduce){.cgs-jr .on .cgs-jr-d{animation:none}}
.cgs-ct.cgs-ct2{min-height:0;background:var(--cgs-navy,#071A33)}
.cgs-ct2 .cgs-ct-in{flex-direction:row;justify-content:space-between;align-items:center;text-align:left;gap:clamp(24px,5vw,80px);padding:calc(94px + clamp(64px,6.5vw,110px)) 5vw clamp(72px,8vw,130px)}
.cgs-ct2-txt{max-width:760px}
.cgs-ct2 .cgs-ct-btns{flex:none}
.cgs-ct2 .cgs-ct-b{white-space:nowrap}
body.cgs-site #lqd-site-content .cgs-cm h2.cgs-hero-eb,body.cgs-site #lqd-site-content .cgs-cts h2.cgs-hero-eb{color:#fff}
.cgs-ct2.cgs-cm .cgs-ct-h{max-width:10em}
.cgs-ct2.cgs-cm .cgs-ct-h em{display:inline}
.cgs-ct2.cgs-cm .cgs-ct-btns{justify-content:flex-start;margin-top:clamp(20px,2vw,32px)}.cgs-cm-pic{flex:none;width:44%;padding:clamp(16px,2vw,32px) clamp(16px,2vw,32px) 0;overflow:hidden;border-radius:clamp(14px,1.2vw,22px);background:#eaf4ff}
.cgs-cm-pic img{display:block;width:var(--cgs-w,100%);height:auto;aspect-ratio:auto;object-fit:contain;object-position:var(--cgs-p,center center);margin:0 auto}
@media(max-width:767px){.cgs-cm-pic{width:100%}}
.cgs-vm{position:relative;background:var(--cgs-soft,#f3f6fa);padding:calc(94px + clamp(40px,4vw,70px)) 5vw clamp(60px,6vw,110px);font-family:var(--cgs-fb,Inter,sans-serif);color:var(--cgs-ink,#14243b);overflow:hidden}
.cgs-vm *{box-sizing:border-box}
.cgs-vm-head{display:flex;flex-direction:column;align-items:center;text-align:center;margin-bottom:clamp(32px,4vw,64px)}
.cgs-vm .cgs-hero-eb{justify-content:center}
.cgs-vm .cgs-about-h{margin:0}
.cgs-vm-head p{max-width:90ch;margin:clamp(14px,1.4vw,20px) 0 0;font-size:clamp(15px,1.05vw,17px);line-height:1.7;color:#3f536d}
.cgs-vm-btns{display:flex;justify-content:center;flex-wrap:wrap;gap:12px;margin-top:clamp(20px,2vw,32px)}
.cgs-vm-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));grid-auto-rows:minmax(clamp(360px,34vw,520px),auto);gap:clamp(12px,1.4vw,20px)}
.cgs-vm-img{position:relative;overflow:hidden;border-radius:clamp(14px,1.2vw,22px)}
.cgs-vm-img img{position:absolute;inset:0;display:block;width:100%;height:100%;object-fit:cover}
.cgs-vm-mid{display:grid;grid-template-rows:1fr 1fr;gap:clamp(12px,1.4vw,20px)}
.cgs-vm-card{display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;padding:clamp(20px,2.2vw,36px);border-radius:clamp(14px,1.2vw,22px);background:var(--cgs-blue-d,#0b6fd1);color:#fff}
.cgs-vm-card+.cgs-vm-card{background:var(--cgs-navy,#071A33)}
body.cgs-site #lqd-site-content .cgs-vm h4.cgs-vm-t{margin:0 0 12px;font:600 clamp(18px,1.45vw,24px)/1.2 var(--cgs-fh);text-transform:capitalize;color:#fff}
.cgs-vm-card p{margin:0;font-size:clamp(14px,1.05vw,17px);line-height:1.7;color:#e6eefa}
@media(hover:hover) and (prefers-reduced-motion:no-preference){.cgs-vm-card,.cgs-vl-box{transition:transform .35s ease,box-shadow .35s ease}.cgs-vm-card:hover,.cgs-vl-box:hover{transform:translateY(-6px);box-shadow:0 22px 40px -20px rgba(11,74,150,.55)}.cgs-vm-img img{transition:transform .8s ease}.cgs-vm-img:hover img{transform:scale(1.06)}}
.cgs-vm.cgs-vl{background:#fff;padding-top:clamp(60px,6vw,110px)}
.cgs-vl .cgs-vm-head p{max-width:none}
.cgs-vl-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:clamp(12px,1.4vw,20px);align-items:stretch}
.cgs-vl-list{display:grid;grid-auto-rows:1fr;gap:clamp(12px,1.4vw,20px)}
.cgs-vl-box{display:grid;grid-template-columns:auto 1fr;align-items:center;gap:clamp(14px,1.6vw,24px);padding:clamp(18px,1.8vw,30px);border-radius:clamp(14px,1.2vw,22px);background:var(--cgs-soft,#f3f6fa);border:1px solid var(--cgs-line,#e1e8f1)}
.cgs-vl-ic{display:grid;place-items:center;width:clamp(48px,4vw,64px);height:clamp(48px,4vw,64px);border-radius:50%;background:var(--cgs-blue,#1e90ff)}
.cgs-vl-ic i{display:block;width:52%;height:52%;background:#fff;-webkit-mask:var(--cgs-ic) center/contain no-repeat;mask:var(--cgs-ic) center/contain no-repeat}
body.cgs-site #lqd-site-content .cgs-vl h4.cgs-vl-t{margin:0 0 6px;font:600 clamp(18px,1.45vw,24px)/1.2 var(--cgs-fh);text-transform:capitalize;color:var(--cgs-navy,#071A33)}
.cgs-vl-box p{margin:0;font-size:clamp(14px,1.05vw,17px);line-height:1.7;color:#3f536d}
body.cgs-site #lqd-site-content .cgs-fe h4.cgs-vl-t{font-size:clamp(15px,1.15vw,18px)}
.cgs-fe .cgs-vl-box p{font-size:clamp(13px,.9vw,15px)}
@media(max-width:767px){.cgs-vl-grid{grid-template-columns:1fr}.cgs-vl-box{grid-template-columns:1fr;justify-items:start}}
@media(max-width:1024px){.cgs-vm{padding:calc(94px + clamp(32px,5vw,48px)) 6vw clamp(48px,8vw,72px)}.cgs-vm-grid{grid-template-columns:repeat(2,minmax(0,1fr));grid-auto-rows:auto}.cgs-vm-img{min-height:clamp(240px,40vw,400px)}.cgs-vm-mid{grid-column:1/-1;grid-row:2;grid-template-rows:none;grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:767px){.cgs-vm{padding-top:calc(52px + 40px)}.cgs-vm-btns{width:100%;flex-direction:column}.cgs-vm .cgs-hb{justify-content:center}.cgs-vm-grid{grid-template-columns:1fr}.cgs-vm-mid{grid-row:auto;grid-template-columns:1fr}.cgs-vm-img{min-height:clamp(220px,60vw,360px)}}
.cgs-ct2-txt p{margin:clamp(16px,1.6vw,22px) 0 0;font-size:clamp(15px,1.1vw,17px);line-height:1.7;color:#d4e3f6}
.cgs-ct2 .cgs-ct-h{max-width:10em}
.cgs-ct2 .cgs-ct-h em{display:block}
.cgs-ct.cgs-ct2:after{content:"";position:absolute;left:5vw;right:5vw;bottom:0;height:1px;z-index:3;background:rgba(255,255,255,.22)}
@media(max-width:767px){.cgs-ct.cgs-ct2:after{left:6vw;right:6vw}.cgs-ct2 .cgs-ct-in{flex-direction:column;align-items:stretch;padding:calc(52px + 56px) 6vw 72px}}
CSS;
}

function cgs_header_js() {
	return <<<'JS'
(function(){var h=document.getElementById('cgs-hd');if(!h||h.dataset.init)return;h.dataset.init=1;
var n=h.querySelector('nav'),b=h.querySelector('.cgs-burger'),m=function(){return matchMedia('(max-width:900px)').matches};
function s(){h.classList.toggle('s',scrollY>60)}addEventListener('scroll',s,{passive:true});s();
b.addEventListener('click',function(){var o=n.classList.toggle('open');b.setAttribute('aria-expanded',o)});
h.querySelectorAll('.cgs-ddt').forEach(function(a){a.addEventListener('click',function(e){if(m()){e.preventDefault();a.parentNode.classList.toggle('open')}})});
h.querySelectorAll('nav a:not(.cgs-ddt)').forEach(function(a){a.addEventListener('click',function(){n.classList.remove('open')})});})();
JS;
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'cgs-fonts', 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700&family=Inter:wght@400;500;600;700&display=swap', array(), null );
	wp_register_style( 'cgs', false, array( 'cgs-fonts' ), CGS_VERSION );
	wp_enqueue_style( 'cgs' );
	wp_add_inline_style( 'cgs', cgs_global_css() . cgs_header_css() . cgs_footer_css() . cgs_hero_css() . cgs_about_css() . cgs_services_css() . cgs_clq_css() . cgs_process_css() . cgs_faq_css() . cgs_cta_css() . cgs_stats_css() . cgs_compliance_css() );
	wp_register_script( 'cgs-header', false, array(), CGS_VERSION, true );
	wp_add_inline_script( 'cgs-header', cgs_header_js() );
}, 30 );

add_filter( 'body_class', function ( $c ) {
	$c[] = 'cgs-site';
	return $c;
} );

/* ------------------------------------------------------------------
 * 5. MENUS — header and footer read Appearance > Menus
 * ---------------------------------------------------------------- */
function cgs_default_menu() {
	return array(
		array( 'label' => 'Home', 'url' => '/', 'children' => '' ),
		array( 'label' => 'About us', 'url' => '/#about', 'children' => '' ),
		array( 'label' => 'Services', 'url' => '/#services', 'children' => "Payroll management|/#services\nLocal worker recruitment|/#services\nHostel & transport management|/#clq2" ),
		array( 'label' => 'Miscellaneous', 'url' => '/#faq', 'children' => '' ),
	);
}

function cgs_menu_choices() {
	$out = array( 'Auto (primary menu)' => '' );
	foreach ( (array) wp_get_nav_menus() as $m ) {
		$out[ $m->name ] = $m->term_id;
	}
	return $out;
}

function cgs_menu_items( $id ) {
	$menu = $id ? wp_get_nav_menu_object( $id ) : false;
	if ( ! $menu ) {
		$locs = get_nav_menu_locations();
		$menu = ! empty( $locs['primary'] ) ? wp_get_nav_menu_object( $locs['primary'] ) : wp_get_nav_menu_object( 'primary' );
	}
	$items = $menu ? wp_get_nav_menu_items( $menu->term_id ) : array();
	if ( empty( $items ) ) {
		return cgs_default_menu();
	}
	$kids = array();
	foreach ( $items as $i ) {
		if ( (int) $i->menu_item_parent ) {
			$kids[ (int) $i->menu_item_parent ][] = $i;
		}
	}
	$top = array();
	foreach ( $items as $i ) {
		if ( ! (int) $i->menu_item_parent ) {
			$c = '';
			foreach ( isset( $kids[ $i->ID ] ) ? $kids[ $i->ID ] : array() as $k ) {
				$c .= $k->title . '|' . $k->url . "\n";
				foreach ( isset( $kids[ $k->ID ] ) ? $kids[ $k->ID ] : array() as $g ) {
					$c .= '>' . $g->title . '|' . $g->url . "\n";
				}
			}
			$top[ $i->ID ] = array( 'label' => $i->title, 'url' => $i->url, 'children' => $c );
		}
	}
	return array_values( $top );
}

/* ------------------------------------------------------------------
 * 6. HEADER ELEMENT — [cgs_header], editable in WPBakery backend.
 *    Liquid Header post: [vc_row][vc_column][cgs_header][/vc_column][/vc_row]
 * ---------------------------------------------------------------- */
add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	vc_map( array(
		'name'     => 'Concepts Header',
		'base'     => 'cgs_header',
		'category' => 'Concepts Group',
		'icon'     => 'icon-wpb-row',
		'params'   => array(
			array( 'type' => 'attach_image', 'heading' => 'Logo', 'param_name' => 'logo', 'description' => 'Empty = default logo.', 'group' => 'Brand' ),
			array( 'type' => 'textfield', 'heading' => 'Logo alt text', 'param_name' => 'logo_alt', 'value' => 'Concepts Group, Total HR Solution', 'group' => 'Brand' ),
			array( 'type' => 'textfield', 'heading' => 'Logo link', 'param_name' => 'logo_url', 'value' => '/', 'group' => 'Brand' ),
			array( 'type' => 'dropdown', 'heading' => 'Menu (Appearance > Menus)', 'param_name' => 'menu', 'group' => 'Menu', 'value' => cgs_menu_choices(), 'description' => 'Edit items under Appearance > Menus. Sub-items become the dropdown.' ),
			array( 'type' => 'textfield', 'heading' => 'Button text', 'param_name' => 'btn_text', 'value' => 'Get a quote', 'group' => 'Button' ),
			array( 'type' => 'textfield', 'heading' => 'Button link', 'param_name' => 'btn_url', 'value' => '/#contact', 'group' => 'Button' ),
			array( 'type' => 'textfield', 'heading' => 'Phone label (empty number hides it)', 'param_name' => 'phone_label', 'value' => 'For enquiry', 'group' => 'Button' ),			array( 'type' => 'textfield', 'heading' => 'Phone number', 'param_name' => 'phone', 'value' => '+603-3318 7773', 'group' => 'Button' ),
		),
	) );
} );

add_shortcode( 'cgs_header', function ( $atts ) {
	$a = shortcode_atts( array(
		'logo' => '', 'logo_alt' => 'Concepts Group, Total HR Solution', 'logo_url' => '/', 'menu' => '',
		'phone_label' => 'For enquiry', 'phone' => '+603-3318 7773', 'btn_text' => 'Get a quote', 'btn_url' => '/#contact',
	), $atts, 'cgs_header' );

	$menu = cgs_menu_items( (int) $a['menu'] );
	$logo = $a['logo'] ? wp_get_attachment_image_url( (int) $a['logo'], 'full' ) : '';
	$logo = cgs_brand_logo( $logo );
	$u    = function ( $url ) {
		$url = trim( (string) $url );
		if ( '' === $url ) {
			return '#';
		}
		return esc_url( ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) ? home_url( $url ) : $url );
	};
	wp_enqueue_script( 'cgs-header' );

	ob_start(); ?>
<div class="cgs-hd" id="cgs-hd"><div class="cgs-bar">
<nav id="cgs-nav" aria-label="Primary">
<?php foreach ( $menu as $it ) :
	$label = isset( $it['label'] ) ? $it['label'] : '';
	$url   = isset( $it['url'] ) ? $it['url'] : '#';
	$kids  = isset( $it['children'] ) ? trim( $it['children'] ) : '';
	if ( '' === $label ) { continue; }
	if ( '' !== $kids ) :
		$rows = array();
		foreach ( preg_split( '/\r\n|\r|\n/', $kids ) as $line ) {
			$line = trim( $line );
			if ( '' === $line ) { continue; }
			$sub = 0 === strpos( $line, '>' );
			$p   = array_map( 'trim', explode( '|', ltrim( $line, '>' ), 2 ) );
			if ( '' === $p[0] ) { continue; }
			if ( $sub && $rows ) {
				$rows[ count( $rows ) - 1 ]['k'][] = $p;
			} elseif ( ! $sub ) {
				$rows[] = array( 'p' => $p, 'k' => array() );
			}
		} ?>
<div class="cgs-dd"><a class="cgs-ddt" href="<?php echo $u( $url ); ?>"><?php echo esc_html( $label ); ?></a><div class="cgs-dd-menu">
<?php foreach ( $rows as $r ) :
	$p = $r['p'];
	if ( $r['k'] ) : ?>
<div class="cgs-sub"><a class="cgs-subt" href="<?php echo $u( isset( $p[1] ) ? $p[1] : '#' ); ?>"><?php echo esc_html( $p[0] ); ?></a><div class="cgs-sub-menu">
<?php foreach ( $r['k'] as $g ) : ?><a href="<?php echo $u( isset( $g[1] ) ? $g[1] : '#' ); ?>"><?php echo esc_html( $g[0] ); ?></a><?php endforeach; ?>
</div></div>
<?php else : ?>
<a href="<?php echo $u( isset( $p[1] ) ? $p[1] : '#' ); ?>"><?php echo esc_html( $p[0] ); ?></a>
<?php endif; endforeach; ?>
</div></div>
<?php else : ?>
<a href="<?php echo $u( $url ); ?>"><?php echo esc_html( $label ); ?></a>
<?php endif; endforeach; ?>
<a class="cgs-btn cgs-nm" href="<?php echo $u( $a['btn_url'] ); ?>"><?php echo esc_html( $a['btn_text'] ); ?></a>
</nav>
<a class="cgs-lg" href="<?php echo $u( $a['logo_url'] ); ?>"><img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $a['logo_alt'] ); ?>" width="162" height="40"></a>
<div class="cgs-hr">
<?php if ( '' !== trim( (string) $a['phone'] ) ) : ?><a class="cgs-ph" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $a['phone'] ) ); ?>"><small><?php echo esc_html( $a['phone_label'] ); ?></small><b><?php echo esc_html( $a['phone'] ); ?></b></a><?php endif; ?>
<a class="cgs-btn" href="<?php echo $u( $a['btn_url'] ); ?>"><?php echo esc_html( $a['btn_text'] ); ?></a>
</div>
<button class="cgs-burger" type="button" aria-label="Menu" aria-controls="cgs-nav" aria-expanded="false">&#9776;</button>
</div></div>
	<?php
	return ob_get_clean();
} );

/* ------------------------------------------------------------------
 * 7. HERO ELEMENT (Home2) — [cgs_hero], editable in WPBakery backend.
 *    Page content: [vc_row full_width="stretch_row" el_class="cgs-row"][vc_column][cgs_hero][/vc_column][/vc_row]
 * ---------------------------------------------------------------- */
define( 'CGS_HERO_DEF', array(
	'eyebrow' => 'Workforce · Accommodation · Since 1991',
	'h1' => 'Powering industry through', 'h2' => 'workforce solutions',
	'lead' => 'From workforce recruitment to accommodation and workforce management, Concepts Group provides integrated solutions that help businesses build, support and manage a reliable workforce.',
	'b1' => 'Talk to our team', 'u1' => '/#contact', 'b2' => 'Explore our solutions', 'u2' => '/#services',
	'arrow' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Black-Right-Arrow.svg',
	'n1' => '35+', 'l1' => 'Years of experience', 'n2' => '7,000+', 'l2' => 'Beds in development', 'n3' => '13', 'l3' => 'Subsidiaries', 'n4' => '', 'l4' => '',
	'wsize' => '100',
	'enq' => 'Need workers or accommodation?', 'enqb' => 'Enquire now →', 'enqu' => '/#contact', 'chip' => 'Since 1991', 'chips' => 'Workforce &amp; accommodation',
	'bld' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concept-Group-Hostel-Hero-Img.webp',
	'img' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Group-Concepts_Worker1.webp',
	/* Sky style (2.1.0). style = sky | classic (classic = the previous arch design). */
	'style' => 'sky',
	'sky'   => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/1472.webp',
	'bldc'  => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concept-Group-Hostel.webp',
	'team'  => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Natural-two-man-portrait-corrected-perspective.webp',
) );

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = CGS_HERO_DEF;
	$p = array();
	$f = array(
		'Content' => array( 'eyebrow' => 'Label (H2)', 'h1' => 'Heading line 1 (H3)', 'h2' => 'Heading line 2 (blue)', 'lead' => 'Body text' ),
		'Buttons' => array( 'b1' => 'Button 1 text', 'u1' => 'Button 1 link', 'b2' => 'Button 2 text', 'u2' => 'Button 2 link', 'arrow' => 'Arrow icon URL' ),
		'Stats'   => array( 'n1' => 'Number 1', 'l1' => 'Label 1', 'n2' => 'Number 2', 'l2' => 'Label 2', 'n3' => 'Number 3', 'l3' => 'Label 3', 'n4' => 'Number 4', 'l4' => 'Label 4' ),
		'Enquiry card' => array( 'enq' => 'Small text', 'enqb' => 'Link text', 'enqu' => 'Card link (page, #section or full URL)', 'chip' => 'Top chip title', 'chips' => 'Top chip text' ),
		'Images'  => array( 'sky' => 'Sky style: sky background', 'bldc' => 'Sky style: building (transparent PNG/WebP)', 'team' => 'Sky style: people (transparent PNG/WebP)', 'bld' => 'Classic style: hostel background image', 'img' => 'Classic style: worker image', 'wsize' => 'Classic style: worker size (% of column width, 100 = default)' ),
	);
	$p[] = array( 'type' => 'dropdown', 'heading' => 'Style', 'param_name' => 'style', 'value' => array( 'Sky (building + people on sky background)' => 'sky', 'Classic (blue arch, previous design)' => 'classic' ), 'std' => 'sky', 'admin_label' => true, 'description' => 'Choose Classic to restore the previous hero design.' );
	foreach ( $f as $group => $fields ) {
		foreach ( $fields as $k => $label ) {
			$p[] = array( 'type' => 'lead' === $k ? 'textarea' : ( in_array( $k, array( 'bld', 'img', 'arrow', 'sky', 'bldc', 'team' ), true ) ? 'attach_image' : 'textfield' ), 'heading' => $label, 'param_name' => $k, 'value' => $d[ $k ], 'group' => $group );
		}
	}
	vc_map( array( 'name' => 'Concepts Hero (Home)', 'base' => 'cgs_hero', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_hero', function ( $atts ) {
	$a = shortcode_atts( CGS_HERO_DEF, $atts, 'cgs_hero' );
	$u = function ( $url ) {
		$url = trim( (string) $url );
		return esc_url( ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) ? home_url( $url ) : $url );
	};
	wp_enqueue_script( 'cgs-hero' );
	$sky = 'classic' !== strtolower( trim( (string) $a['style'] ) );
	ob_start(); ?>
<?php if ( $sky ) : ?>
<section class="cgs-hero cgs-hero-sky" style="--cgs-sky:url('<?php echo esc_url( cgs_media_url( $a['sky'], CGS_HERO_DEF['sky'] ) ); ?>')"><div class="cgs-hero-in">
<?php else : ?>
<section class="cgs-hero" style="--cgs-bg:url('<?php echo esc_url( cgs_media_url( $a['bld'], CGS_HERO_DEF['bld'] ) ); ?>')"><div class="cgs-hero-in">
<?php endif; ?>
<div class="cgs-hero-txt">
<h2 class="cgs-hero-eb cgs-fade"><?php echo esc_html( $a['eyebrow'] ); ?></h2>
<h1 class="cgs-hero-h"><span class="cgs-ln"><span><?php echo esc_html( $a['h1'] ); ?></span></span><span class="cgs-ln"><span><em><?php echo esc_html( $a['h2'] ); ?></em></span></span></h1>
<p class="cgs-hero-lead cgs-fade"><?php echo esc_html( $a['lead'] ); ?></p>
<div class="cgs-hero-cta cgs-fade">
<a class="cgs-hb cgs-hb1" href="<?php echo $u( $a['u1'] ); ?>"><?php echo esc_html( $a['b1'] ); ?><img src="<?php echo esc_url( cgs_media_url( $a['arrow'], CGS_HERO_DEF['arrow'] ) ); ?>" alt="" width="18" height="18"></a>
<a class="cgs-hb cgs-hb2" href="<?php echo $u( $a['u2'] ); ?>"><?php echo esc_html( $a['b2'] ); ?><img src="<?php echo esc_url( cgs_media_url( $a['arrow'], CGS_HERO_DEF['arrow'] ) ); ?>" alt="" width="18" height="18"></a>
</div>
<div class="cgs-hero-stats cgs-fade"><?php foreach ( array( 1, 2, 3, 4 ) as $i ) :
	$n = trim( $a[ 'n' . $i ] );
	if ( '' === $n ) {
		continue;
	} ?><div><b data-n="<?php echo esc_attr( $n ); ?>"><?php echo esc_html( $n ); ?></b><span><?php echo esc_html( $a[ 'l' . $i ] ); ?></span></div><?php endforeach; ?></div>
</div>
<?php if ( $sky ) : ?>
</div>
<div class="cgs-sky-scene">
<img class="cgs-sky-bld" src="<?php echo esc_url( cgs_media_url( $a['bldc'], CGS_HERO_DEF['bldc'] ) ); ?>" alt="Concepts Group worker accommodation building" width="1328" height="1078" fetchpriority="high">
<img class="cgs-sky-team" src="<?php echo esc_url( cgs_media_url( $a['team'], CGS_HERO_DEF['team'] ) ); ?>" alt="Concepts Group team" width="1448" height="1086">
<div class="cgs-hero-chip"><b><?php echo esc_html( $a['chip'] ); ?></b><span><?php echo esc_html( $a['chips'] ); ?></span></div>
<a class="cgs-hero-enq" href="<?php echo $u( '' !== trim( (string) $a['enqu'] ) ? $a['enqu'] : $a['u1'] ); ?>"><small><?php echo esc_html( $a['enq'] ); ?></small><b><?php echo esc_html( $a['enqb'] ); ?></b></a>
</div>
<?php else : ?>
<div class="cgs-hero-vis" style="--cgs-ws:<?php echo (int) min( 160, max( 40, (int) $a['wsize'] ) ); ?>%">
<div class="cgs-hero-arch"><img class="cgs-hero-work" src="<?php echo esc_url( cgs_media_url( $a['img'], CGS_HERO_DEF['img'] ) ); ?>" alt="Concepts Group workforce"></div>
<img class="cgs-hero-head" src="<?php echo esc_url( cgs_media_url( $a['img'], CGS_HERO_DEF['img'] ) ); ?>" alt="" aria-hidden="true">
<div class="cgs-hero-chip"><b><?php echo esc_html( $a['chip'] ); ?></b><span><?php echo esc_html( $a['chips'] ); ?></span></div>
<a class="cgs-hero-enq" href="<?php echo $u( '' !== trim( (string) $a['enqu'] ) ? $a['enqu'] : $a['u1'] ); ?>"><small><?php echo esc_html( $a['enq'] ); ?></small><b><?php echo esc_html( $a['enqb'] ); ?></b></a>
</div>
</div>
<?php endif; ?>
<svg class="cgs-hero-wave" viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true"><path d="M0 100V46C240 8 480 0 720 34s480 44 720 4V100Z" fill="#fff"/></svg>
</section>
	<?php
	return ob_get_clean();
} );

/* Our Story hero — [cgs_story_hero], copy of the hero markup/classes with a .cgs-hero-story modifier (no bg/wave, full-bleed image). */
define( 'CGS_STORY_DEF', array(
	'eyebrow' => 'Our Story',
	'h1' => 'A Legacy of', 'h2' => 'Workforce Excellence',
	'lead' => 'Since 1991, Concepts Group has provided manpower and accommodation solutions, evolving to meet the needs of businesses across Malaysia.',
	'img' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concepts-Group_Receptionist-scaled-e1617339697455.webp',
) );

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$p = array();
	foreach ( array( 'eyebrow' => 'Label (H2)', 'h1' => 'Heading line 1 (H1)', 'h2' => 'Heading line 2 (blue)', 'lead' => 'Body text', 'img' => 'Right column image' ) as $k => $label ) {
		$p[] = array( 'type' => 'lead' === $k ? 'textarea' : ( 'img' === $k ? 'attach_image' : 'textfield' ), 'heading' => $label, 'param_name' => $k, 'value' => CGS_STORY_DEF[ $k ] );
	}
	vc_map( array( 'name' => 'Concepts Story Hero', 'base' => 'cgs_story_hero', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_story_hero', function ( $atts ) {
	$a = shortcode_atts( CGS_STORY_DEF, $atts, 'cgs_story_hero' );
	ob_start(); ?>
<section class="cgs-hero cgs-hero-story"><div class="cgs-hero-in">
<div class="cgs-hero-txt">
<h2 class="cgs-hero-eb cgs-fade"><?php echo esc_html( $a['eyebrow'] ); ?></h2>
<h1 class="cgs-hero-h"><span class="cgs-ln"><span><?php echo esc_html( $a['h1'] ); ?></span></span><span class="cgs-ln"><span><em><?php echo esc_html( $a['h2'] ); ?></em></span></span></h1>
<p class="cgs-hero-lead cgs-fade"><?php echo esc_html( $a['lead'] ); ?></p>
</div>
<div class="cgs-hero-vis">
<img class="cgs-hero-work" src="<?php echo esc_url( cgs_media_url( $a['img'], CGS_STORY_DEF['img'] ) ); ?>" alt="Concepts Group team">
</div>
</div>
</section>
	<?php
	return ob_get_clean();
} );

/* Our Story About — [cgs_story_about]: two-column images | text on the soft background. Reuses hero eyebrow/button and about heading classes. */
function cgs_story_about_defaults() {
	$base = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		'h2' => 'Our Company', 'h3' => 'About Concepts Group', 'h3e' => 'Concepts Group',
		'p1' => 'For more than three decades, Concepts Group has helped businesses address their workforce needs through practical, integrated solutions. Our services span local and foreign worker recruitment, payroll administration, worker welfare, accommodation, transportation and workforce support.',
		'p2' => 'Through our recruitment network, operational experience and partnerships with overseas associates, we work closely with employers across various industries to simplify workforce management and support efficient business operations.',
		'p3' => 'As workforce needs continue to evolve, we remain focused on providing dependable services that support both businesses and the people who drive them.',
		'btn' => 'Explore Our Services', 'url' => '/#services',
		'num' => '35+', 'lbl' => 'Years of experience',
		'img1' => $base . 'Concept-Group-Worker2.webp', 'img2' => $base . 'Concept-Group-24141.webp',
		'arrow' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Black-Right-Arrow.svg',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_story_about_defaults();
	$f = array( 'h2' => 'Label (H2)', 'h3' => 'Heading (H3)', 'h3e' => 'Accent words in heading', 'p1' => 'Paragraph 1', 'p2' => 'Paragraph 2', 'p3' => 'Paragraph 3', 'btn' => 'Button text', 'url' => 'Button link', 'num' => 'Badge number', 'lbl' => 'Badge label', 'img1' => 'Main image', 'img2' => 'Small image' );
	$p = array();
	foreach ( $f as $k => $label ) {
		$p[] = array( 'type' => in_array( $k, array( 'p1', 'p2', 'p3' ), true ) ? 'textarea' : ( in_array( $k, array( 'img1', 'img2' ), true ) ? 'attach_image' : 'textfield' ), 'heading' => $label, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	vc_map( array( 'name' => 'Concepts Story About', 'base' => 'cgs_story_about', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_story_about', function ( $atts ) {
	$d = cgs_story_about_defaults();
	$a = shortcode_atts( $d, $atts, 'cgs_story_about' );
	$u = trim( (string) $a['url'] );
	$u = esc_url( ( 0 === strpos( $u, '/' ) && 0 !== strpos( $u, '//' ) ) ? home_url( $u ) : $u );
	ob_start(); ?>
<section class="cgs-ab2" id="company"><div class="cgs-ab2-in">
<div class="cgs-ab2-pics">
<div class="cgs-ab2-main"><img src="<?php echo esc_url( cgs_media_url( $a['img1'], $d['img1'] ) ); ?>" alt="Concepts Group workforce" loading="lazy"></div>
<div class="cgs-ab2-sub"><img src="<?php echo esc_url( cgs_media_url( $a['img2'], $d['img2'] ) ); ?>" alt="Concepts Group team" loading="lazy"></div>
<div class="cgs-ab2-card"><b><?php echo esc_html( $a['num'] ); ?></b><span><?php echo esc_html( $a['lbl'] ); ?></span></div>
</div>
<div class="cgs-about-txt">
<h2 class="cgs-hero-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
<p><?php echo esc_html( $a['p1'] ); ?></p>
<p><?php echo esc_html( $a['p2'] ); ?></p>
<p><?php echo esc_html( $a['p3'] ); ?></p>
<a class="cgs-hb cgs-hb1" href="<?php echo $u; ?>"><?php echo esc_html( $a['btn'] ); ?><img src="<?php echo esc_url( cgs_media_url( $a['arrow'], $d['arrow'] ) ); ?>" alt="" width="18" height="18"></a>
</div>
</div></section>
	<?php
	return ob_get_clean();
} );

/* Foreign Worker Recruitment intro — [cgs_fwr_intro]: country ticker (replaces the Our Company top wave), full-width head,
 * text | 2-up benefit card slider. Reuses .cgs-ab2 (background, colours), .cgs-hero-eb/.cgs-about-h headings and the cgsTick keyframes. */
function cgs_fwr_intro_defaults() {
	$u = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		'h2' => 'Our Expertise', 'h3' => 'Finding the Right People', 'h3e' => 'Right People',
		'p1' => 'We support employers throughout the foreign worker recruitment process, from understanding manpower requirements and selecting suitable candidates to coordinating worker arrivals and ongoing support.',
		'p2' => 'Through our overseas recruitment network, we help businesses source workers from Indonesia, Bangladesh, Nepal, Myanmar, Cambodia, Laos, Sri Lanka and Thailand.',
		'btn' => 'Explore Our Process', 'url' => '#recruitment-process',
		'arrow' => $u . 'Black-Right-Arrow.svg',
		'cards' => array(
			array( 't' => 'Quality and Productivity', 'd' => 'We assure the Quality & Productivity of candidates are on par with industry standards. As a foreign worker agency in Malaysia, we make sure that all the candidates we offer to our clients are of the highest calibre and productivity.', 'im' => $u . 'Concepts-Group-2150518266.webp' ),
			array( 't' => 'Dependable Recruitment Services', 'd' => 'Recruitment services that are dependable and respond quickly to our client\'s needs. We take pride in being a leading foreign worker agency in Kuala Lumpur, Selangor, Melaka, Ipoh, Johor and Penang providing dependable and efficient recruitment services that meet our client\'s needs in timely manner.', 'im' => $u . 'Concepts-Group-32152.webp' ),
			array( 't' => 'Regulatory Compliance', 'd' => 'As a reputable foreign worker agency in Malaysia, we comply with all governmental and international regulations, that are required by the Malaysian and Source Country embassies which results in smooth in recruiting workers for our clients.', 'im' => $u . 'CG_Office-Meeting-Banner.webp' ),
			array( 't' => 'Licensed Recruitment Partners', 'd' => 'We work along with our recruitment licensed partners from Source Countries, to ensure our clients are set with a suitable team of foreign workers that meets their business operation requirements.', 'im' => $u . 'Concepts-Group_Receptionist-scaled-e1617339697455.webp' ),
		),
		'countries' => array(
			array( 'n' => 'Indonesia', 'im' => $u . 'Indonesia-flag.webp' ),
			array( 'n' => 'Bangladesh', 'im' => $u . 'Bangladesh-flag.webp' ),
			array( 'n' => 'Nepal', 'im' => $u . 'Flag-Nepal.webp' ),
			array( 'n' => 'Myanmar', 'im' => $u . 'myanmar-flag.webp' ),
			array( 'n' => 'Cambodia', 'im' => $u . 'Cambodia-flag.webp' ),
			array( 'n' => 'Laos', 'im' => $u . 'Laos-flag.webp' ),
			array( 'n' => 'Sri Lanka', 'im' => $u . 'sri-lanka-flag.webp' ),
			array( 'n' => 'Thailand', 'im' => $u . 'Thailand-flag.webp' ),
		),
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_fwr_intro_defaults();
	$p = array();
	foreach ( array( 'h2' => 'Eyebrow (H2)', 'h3' => 'Heading (H3)', 'h3e' => 'Highlighted words' ) as $k => $l ) {
		$p[] = array( 'type' => 'textfield', 'heading' => $l, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	foreach ( array( 'p1' => 'Paragraph 1', 'p2' => 'Paragraph 2' ) as $k => $l ) {
		$p[] = array( 'type' => 'textarea', 'heading' => $l, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	$p[] = array( 'type' => 'textfield', 'heading' => 'Button text', 'param_name' => 'btn', 'value' => $d['btn'] );
	$p[] = array( 'type' => 'textfield', 'heading' => 'Button link', 'param_name' => 'url', 'value' => $d['url'] );
	$p[] = array( 'type' => 'param_group', 'heading' => 'Benefit cards', 'param_name' => 'cards', 'value' => rawurlencode( wp_json_encode( $d['cards'] ) ), 'params' => array(
		array( 'type' => 'textfield', 'heading' => 'Title', 'param_name' => 't', 'admin_label' => true ),
		array( 'type' => 'textarea', 'heading' => 'Text', 'param_name' => 'd' ),
		array( 'type' => 'textfield', 'heading' => 'Image URL', 'param_name' => 'im' ),
	) );
	$p[] = array( 'type' => 'param_group', 'heading' => 'Countries', 'param_name' => 'countries', 'value' => rawurlencode( wp_json_encode( $d['countries'] ) ), 'params' => array(
		array( 'type' => 'textfield', 'heading' => 'Country', 'param_name' => 'n', 'admin_label' => true ),
		array( 'type' => 'textfield', 'heading' => 'Flag image URL', 'param_name' => 'im' ),
	) );
	vc_map( array( 'name' => 'Concepts Recruitment Intro', 'base' => 'cgs_fwr_intro', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_fwr_intro', function ( $atts ) {
	$d = cgs_fwr_intro_defaults();
	$a = shortcode_atts( array( 'h2' => $d['h2'], 'h3' => $d['h3'], 'h3e' => $d['h3e'], 'p1' => $d['p1'], 'p2' => $d['p2'], 'btn' => $d['btn'], 'url' => $d['url'], 'arrow' => $d['arrow'], 'cards' => '', 'countries' => '' ), $atts, 'cgs_fwr_intro' );
	foreach ( array( 'btn', 'url', 'arrow' ) as $k ) {
		if ( '' === trim( (string) $a[ $k ] ) ) {
			$a[ $k ] = $d[ $k ];
		}
	}
	$rows = function ( $raw, $def ) {
		$r = ( '' !== trim( (string) $raw ) && function_exists( 'vc_param_group_parse_atts' ) ) ? vc_param_group_parse_atts( $raw ) : array();
		$r = array_values( array_filter( (array) $r, function ( $x ) { return is_array( $x ) && ( ! empty( $x['im'] ) ); } ) );
		return $r ? $r : $def;
	};
	$cards = $rows( $a['cards'], $d['cards'] );
	$ctry  = $rows( $a['countries'], $d['countries'] );
	static $once = false;
	ob_start();
	if ( ! $once ) {
		$once = true; ?>
<style>
.cgs-fi{padding:0}
.cgs-fi-bar{background:rgba(0,0,0,.2);overflow:hidden;padding:clamp(14px,1.4vw,22px) 0}
.cgs-fi-bar .cgs-tick-track{animation-duration:45s}
.cgs-fi-bar:hover .cgs-tick-track{animation-play-state:paused}
.cgs-fi-c{display:flex;align-items:center;gap:clamp(10px,.9vw,14px);padding:0 clamp(24px,2.6vw,44px);white-space:nowrap;font:600 clamp(15px,1.3vw,20px)/1 var(--cgs-fh,'Bricolage Grotesque',sans-serif);color:#fff}
.cgs-fi .cgs-fi-c img{width:clamp(34px,2.9vw,46px);height:clamp(24px,2vw,32px);border-radius:5px;box-shadow:0 0 0 1px rgba(255,255,255,.35)}
.cgs-fi-body{padding:clamp(48px,5vw,84px) 5vw clamp(60px,6vw,110px)}
.cgs-fi-head{text-align:center;margin-bottom:clamp(28px,3.4vw,56px)}
.cgs-fi-head .cgs-about-h{margin-bottom:0}.cgs-fi-head .cgs-hero-eb{justify-content:center}.cgs-fi-l{display:flex;flex-direction:column;justify-content:space-between;gap:24px}.cgs-fi .cgs-about-txt p:last-child{margin-bottom:0}
@media(min-width:1025px){.cgs-fi .cgs-ab2-in{grid-template-columns:.8fr 1.4fr;align-items:stretch}.cgs-fi-nav.b{display:none}}@media(max-width:1024px){.cgs-fi-nav.l{display:none}.cgs-fi-nav.b{margin-top:20px;justify-content:center}.cgs-fi .cgs-about-txt{text-align:center}}
.cgs-fi-sl{min-width:0}
.cgs-fi-vp{overflow:hidden}
.cgs-fi-tr{display:flex;gap:20px;--n:2;align-items:flex-start;transition:transform .5s cubic-bezier(.3,.7,.2,1)}
.cgs-fi-card{position:relative;flex:0 0 calc((100% - (var(--n) - 1) * 20px) / var(--n));aspect-ratio:1/1;border-radius:clamp(14px,1.2vw,22px);overflow:hidden;color:#fff;background:var(--cgs-navy,#071A33);cursor:pointer;outline:0}
.cgs-fi .cgs-fi-card img{position:absolute;inset:0;width:100%;height:100%;border-radius:0;transition:transform .6s}
.cgs-fi-card:hover img,.cgs-fi-card:focus-within img,.cgs-fi-card.open img{transform:scale(1.06)}
.cgs-fi-card:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,63,130,0) 35%,rgba(11,40,90,.92) 100%);transition:.4s}
.cgs-fi-card:hover:after,.cgs-fi-card:focus-within:after,.cgs-fi-card.open:after{background:linear-gradient(180deg,rgba(11,63,130,.55) 0%,rgba(11,40,90,.95) 100%)}
.cgs-fi-cb{position:absolute;left:0;right:0;bottom:0;z-index:2;padding:clamp(16px,1.5vw,26px)}
.cgs-fi-cb h4{margin:0;font:600 clamp(18px,1.5vw,24px)/1.2 var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.02em;color:#fff}
body.cgs-site #lqd-site-content .cgs-fi-cb h4{color:#fff;text-transform:none}
.cgs-fi-cb p{margin:0;font-size:clamp(13.5px,.95vw,15px);line-height:1.6;color:#e3eefb;max-height:0;opacity:0;overflow:hidden;transition:max-height .5s,opacity .4s,margin .4s}
.cgs-fi-card:hover .cgs-fi-cb p,.cgs-fi-card:focus-within .cgs-fi-cb p,.cgs-fi-card.open .cgs-fi-cb p{max-height:320px;opacity:1;margin-top:12px}
.cgs-fi-nav{display:flex;align-items:center;gap:12px}
.cgs-fi-nav button{width:44px;height:44px;border-radius:50%;border:1px solid rgba(255,255,255,.5);background:none;color:#fff;font-size:20px;cursor:pointer;transition:.3s;line-height:1}
.cgs-fi-nav button:hover{background:#fff;color:var(--cgs-navy,#071A33)}
@media(max-width:1024px){.cgs-fi-body{padding:clamp(40px,6vw,56px) 6vw clamp(48px,8vw,72px)}}
@media(max-width:600px){.cgs-fi-tr{--n:1}}
@media(prefers-reduced-motion:reduce){.cgs-fi-bar .cgs-tick-track{animation:none}}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('.cgs-fi-sl').forEach(function(sl){
var tr=sl.querySelector('.cgs-fi-tr'),cs=[].slice.call(tr.children),sec=sl.closest('.cgs-fi'),i=0,t=null;
function per(){return window.innerWidth<=600?1:2}
function max(){return Math.max(0,cs.length-per())}
function go(n){var m=max();i=n<0?m:(n>m?0:n);var g=parseFloat(getComputedStyle(tr).columnGap)||20;tr.style.transform='translateX('+(-i*(cs[0].offsetWidth+g))+'px)'}
sec.querySelectorAll('.pv').forEach(function(b){b.onclick=function(){go(i-1)}});sec.querySelectorAll('.nx').forEach(function(b){b.onclick=function(){go(i+1)}});
cs.forEach(function(c){c.addEventListener('click',function(){if(matchMedia('(hover:none)').matches){var o=c.classList.contains('open');cs.forEach(function(x){x.classList.remove('open')});if(!o)c.classList.add('open')}})});
function play(){if(t||matchMedia('(prefers-reduced-motion:reduce)').matches)return;t=setInterval(function(){go(i+1)},4000)}
function stop(){clearInterval(t);t=null}
[sl,sec.querySelector('.cgs-fi-l')].forEach(function(e){e.addEventListener('mouseenter',stop);e.addEventListener('mouseleave',play);e.addEventListener('focusin',stop);e.addEventListener('focusout',play)});
var x0=null;sl.addEventListener('touchstart',function(e){x0=e.touches[0].clientX;stop()},{passive:true});
sl.addEventListener('touchend',function(e){if(x0!==null){var dx=e.changedTouches[0].clientX-x0;x0=null;if(Math.abs(dx)>40)go(dx<0?i+1:i-1)}setTimeout(play,6000)});
document.addEventListener('visibilitychange',function(){document.hidden?stop():play()});
window.addEventListener('resize',function(){go(i)});go(0);play()});});
</script>
	<?php } ?>
<section class="cgs-ab2 cgs-fi" id="expertise">
<div class="cgs-fi-bar" aria-label="Source countries"><div class="cgs-tick-track">
<?php for ( $r = 0; $r < 2; $r++ ) : foreach ( $ctry as $c ) : ?>
<div class="cgs-fi-c"<?php echo $r ? ' aria-hidden="true"' : ''; ?>><img src="<?php echo esc_url( $c['im'] ); ?>" alt="<?php echo $r ? '' : esc_attr( $c['n'] . ' flag' ); ?>" loading="lazy"><?php echo esc_html( $c['n'] ); ?></div>
<?php endforeach; endfor; ?>
</div></div>
<div class="cgs-fi-body">
<div class="cgs-fi-head">
<h2 class="cgs-hero-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
</div>
<div class="cgs-ab2-in">
<div class="cgs-fi-l"><div class="cgs-about-txt">
<p><?php echo esc_html( $a['p1'] ); ?></p>
<p><?php echo esc_html( $a['p2'] ); ?></p>
<a class="cgs-hb cgs-hb1" href="<?php echo esc_url( $a['url'] ); ?>"><?php echo esc_html( $a['btn'] ); ?><img src="<?php echo esc_url( cgs_media_url( $a['arrow'], $d['arrow'] ) ); ?>" alt="" width="18" height="18"></a>
</div>
<div class="cgs-fi-nav l"><button type="button" class="pv" aria-label="Previous">&#8592;</button><button type="button" class="nx" aria-label="Next">&#8594;</button></div></div>
<div class="cgs-fi-sl">
<div class="cgs-fi-vp"><div class="cgs-fi-tr">
<?php foreach ( $cards as $c ) : ?>
<article class="cgs-fi-card" tabindex="0"><img src="<?php echo esc_url( $c['im'] ); ?>" alt="" loading="lazy"><div class="cgs-fi-cb"><h4><?php echo esc_html( $c['t'] ); ?></h4><p><?php echo esc_html( $c['d'] ); ?></p></div></article>
<?php endforeach; ?>
</div></div>
<div class="cgs-fi-nav b"><button type="button" class="pv" aria-label="Previous">&#8592;</button><button type="button" class="nx" aria-label="Next">&#8594;</button></div>
</div>
</div>
</div>
</section>
	<?php
	return ob_get_clean();
} );

/* Foreign Worker Recruitment process — [cgs_fwr_process]: centred head (reuses .cgs-about head/eyebrow/heading) + the process
 * steps as alternating dark/light cards (small icon above the text, equal heights, connector lines on desktop). */
function cgs_fwr_process_steps_default() {
	$i = function ( $n ) {
		return plugins_url( 'img/small' . $n . '.png', __FILE__ );
	};
	return array(
		array( 'ic' => $i( 1 ), 'tx' => 'We provide a free consultation which enables employers to discuss their HR requirements and issues.' ),
		array( 'ic' => $i( 2 ), 'tx' => 'We assist you in preparing the documents for obtaining the Foreign Worker quota.' ),
		array( 'ic' => $i( 3 ), 'tx' => 'Our consultation covers advising the suitable nationality of Foreign Workers for your job scope.' ),
		array( 'ic' => $i( 4 ), 'tx' => 'Assist clients to obtain official attestation based on selected nationalities.' ),
		array( 'ic' => $i( 5 ), 'tx' => 'Conducting an on-site interview with clients to select candidates from Source Country.' ),
		array( 'ic' => '', 'tx' => 'Complete medical and passport renewal for chosen candidates, in the Source Country, before submitting their biodata to Malaysian Immigration for calling visa.' ),
		array( 'ic' => $i( 6 ), 'tx' => 'Assist client to obtain calling Visa for the chosen foreign worker.' ),
		array( 'ic' => $i( 7 ), 'tx' => 'Prepare Foreign Worker hostel a month before arrival.' ),
		array( 'ic' => $i( 8 ), 'tx' => 'Proceed for pre-departure.' ),
		array( 'ic' => $i( 9 ), 'tx' => 'Assist clients with airport clearance. (Clients are not required to be present at the airport).' ),
		array( 'ic' => $i( 10 ), 'tx' => 'Arrange transportation for workers to send them from airport to hostel.' ),
		array( 'ic' => $i( 11 ), 'tx' => 'Assist workers in FOMEMA check-ups, in order to get an official permit from the immigration' ),
		array( 'ic' => $i( 12 ), 'tx' => 'Manage workers\' welfare, during their stay in Malaysia.' ),
		array( 'ic' => $i( 13 ), 'tx' => 'Assist/Arrange the repatriation of workers upon the end of their contract with employers' ),
	);
}

function cgs_fwr_process_defaults() {
	return array(
		'h2'    => 'Recruitment Process', 'h3' => 'Foreign Worker Recruitment Process', 'h3e' => 'Recruitment Process',
		'steps' => '',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_fwr_process_defaults();
	$p = array();
	foreach ( array( 'h2' => 'Eyebrow (H2)', 'h3' => 'Heading (H3)', 'h3e' => 'Highlighted words' ) as $k => $l ) {
		$p[] = array( 'type' => 'textfield', 'heading' => $l, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	$p[] = array(
		'type'        => 'param_group',
		'heading'     => 'Process steps (cards alternate dark / light)',
		'param_name'  => 'steps',
		'group'       => 'Steps',
		'description' => 'Each row is one card. Leave untouched to show the 14 default steps. Icon is optional.',
		'value'       => rawurlencode( wp_json_encode( cgs_fwr_process_steps_default() ) ),
		'params'      => array(
			array( 'type' => 'attach_image', 'heading' => 'Icon (small, optional)', 'param_name' => 'ic' ),
			array( 'type' => 'textarea', 'heading' => 'Text', 'param_name' => 'tx', 'admin_label' => true ),
		),
	);
	vc_map( array( 'name' => 'Concepts Recruitment Process', 'base' => 'cgs_fwr_process', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_fwr_process', function ( $atts ) {
	$d = cgs_fwr_process_defaults();
	$a = shortcode_atts( $d, $atts, 'cgs_fwr_process' );
	foreach ( array( 'h2', 'h3', 'h3e' ) as $k ) {
		if ( '' === trim( (string) $a[ $k ] ) ) {
			$a[ $k ] = $d[ $k ];
		}
	}
	$steps = cgs_fwr_process_steps_default();
	if ( '' !== trim( (string) $a['steps'] ) ) {
		$dec   = json_decode( rawurldecode( $a['steps'] ), true );
		$steps = is_array( $dec ) ? $dec : array();
	}
	$steps = array_values( array_filter( $steps, function ( $s ) {
		return is_array( $s ) && ! empty( $s['tx'] );
	} ) );
	static $once = false;
	ob_start();
	if ( ! $once ) {
		$once = true; ?>
<style>
.cgs-fp{padding-bottom:clamp(60px,6vw,110px)}
body.cgs-site #lqd-site-content .cgs-fp h2.cgs-about-eb{margin:0 0 1.2vw;font:700 clamp(12px,.9vw,14px) var(--cgs-fb,Inter,sans-serif);letter-spacing:.1em;text-transform:uppercase;color:var(--cgs-blue-d,#0b6fd1)}
.cgs-fp-grid{--g:clamp(14px,1.6vw,24px);display:grid;grid-template-columns:repeat(6,minmax(0,1fr));grid-auto-rows:1fr;gap:var(--g)}
.cgs-fp-c{position:relative;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:clamp(10px,1vw,14px);padding:clamp(18px,1.8vw,26px) clamp(14px,1.4vw,20px);min-height:clamp(170px,14vw,210px);border-radius:clamp(22px,2.4vw,38px);background:#eae9ee;color:#0c398e;text-align:center}
.cgs-fp-c.d{background:#0c398e;color:#fff}
.cgs-fp-c img{display:block;width:auto;height:clamp(34px,3vw,48px);max-width:70%;object-fit:contain}
.cgs-fp-c p{margin:0;font-size:clamp(12.5px,.88vw,14.5px);line-height:1.45}
.cgs-fp-c:nth-child(7){grid-area:2/6}.cgs-fp-c:nth-child(8){grid-area:2/5}.cgs-fp-c:nth-child(9){grid-area:2/4}.cgs-fp-c:nth-child(10){grid-area:2/3}.cgs-fp-c:nth-child(11){grid-area:2/2}.cgs-fp-c:nth-child(12){grid-area:2/1}.cgs-fp-c:nth-child(13){grid-area:3/1}.cgs-fp-c:nth-child(14){grid-area:3/2}
.cgs-fp-c:nth-child(-n+5):after,.cgs-fp-c:nth-child(13):after,.cgs-fp-c:nth-child(n+7):nth-child(-n+11):after{content:"";position:absolute;top:50%;width:var(--g);height:3px;background:#0c398e}
.cgs-fp-c:nth-child(-n+5):after,.cgs-fp-c:nth-child(13):after{left:100%}
.cgs-fp-c:nth-child(n+7):nth-child(-n+11):after{right:100%}
.cgs-fp-c:nth-child(6):after,.cgs-fp-c:nth-child(12):after{content:"";position:absolute;left:50%;top:100%;width:3px;height:var(--g);margin-left:-1.5px;background:#0c398e}
.cgs-fp-grid.js .cgs-fp-c{opacity:0;transform:translateY(18px) scale(.97);transition:opacity .55s ease,transform .55s ease}
.cgs-fp-grid.js .cgs-fp-c.in{opacity:1;transform:none}
@media(max-width:1024px){.cgs-fp-grid{grid-template-columns:repeat(3,minmax(0,1fr))}.cgs-fp-c:nth-child(n){grid-area:auto}.cgs-fp-c:nth-child(n):after{display:none}}
@media(max-width:600px){.cgs-fp-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.cgs-fp-c{min-height:0}}
@media(prefers-reduced-motion:reduce){.cgs-fp-grid.js .cgs-fp-c{opacity:1;transform:none;transition:none}}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){var g=document.querySelector('.cgs-fp-grid');if(!g||matchMedia('(prefers-reduced-motion:reduce)').matches||!('IntersectionObserver' in window))return;
var cs=[].slice.call(g.children);g.classList.add('js');
new IntersectionObserver(function(es,o){if(!es[0].isIntersecting)return;o.disconnect();cs.forEach(function(c,i){setTimeout(function(){c.classList.add('in')},i*140)})},{threshold:.12}).observe(g)});
</script>
	<?php } ?>
<section class="cgs-about cgs-fp" id="recruitment-process"><div class="cgs-about-in">
<div class="cgs-about-head">
<div><h2 class="cgs-about-eb"><?php echo esc_html( $a['h2'] ); ?></h2><h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3></div>
</div>
<div class="cgs-fp-grid">
<?php foreach ( $steps as $n => $s ) :
	$ic = cgs_media_url( isset( $s['ic'] ) ? $s['ic'] : '', '' ); ?>
<div class="cgs-fp-c<?php echo 0 === $n % 2 ? ' d' : ''; ?>"><?php if ( '' !== $ic ) : ?><img src="<?php echo esc_url( $ic ); ?>" alt="" loading="lazy"><?php endif; ?><p><?php echo esc_html( $s['tx'] ); ?></p></div>
<?php endforeach; ?>
</div>
</div></section>
	<?php
	return ob_get_clean();
} );

/* Recruitment ethics — [cgs_fwr_ethics]: two columns on the Values layout (.cgs-vm.cgs-vl): heading, text and 2x2 icon boxes | image. */
function cgs_fwr_ethics_defaults() {
	$u = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		'h2' => 'Our Commitment', 'h3' => 'Our Recruitment Ethics', 'h3e' => 'Ethics',
		'p'  => 'We believe responsible recruitment begins with fair treatment, transparent processes and respect for every worker.',
		'i1' => $u . 'document.svg', 't1' => 'Adherence to the Labour Act',
		'i2' => $u . 'public-service.svg', 't2' => 'Respect Employee\'s Right',
		'i3' => $u . 'collaborate.svg', 't3' => 'Transparency in Terms of Engagement',
		'i4' => $u . 'recruit.svg', 't4' => 'Non-discrimination & Humane Treatment',
		'img' => $u . 'Concept-Groups-magnifying-glass-front-group-human-figures-recruitment-target-audience.webp',
		'alt' => 'Magnifying glass in front of a group of figures, representing fair and careful recruitment',
		'pos' => 'bottom',
		'imgm' => $u . 'Concept-Groups-magnifying-glass-front-group-human-figures-recruitment-target-audience-mobile.webp',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_fwr_ethics_defaults();
	$p = array(
		array( 'type' => 'textfield', 'heading' => 'Label (H2)', 'param_name' => 'h2', 'value' => $d['h2'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Heading (H3)', 'param_name' => 'h3', 'value' => $d['h3'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Accent words in heading', 'param_name' => 'h3e', 'value' => $d['h3e'], 'group' => 'Content' ),
		array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'p', 'value' => $d['p'], 'group' => 'Content' ),
		array( 'type' => 'attach_image', 'heading' => 'Image', 'param_name' => 'img', 'value' => $d['img'], 'group' => 'Content' ),
		array( 'type' => 'attach_image', 'heading' => 'Tablet / mobile image', 'param_name' => 'imgm', 'value' => $d['imgm'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Image description', 'param_name' => 'alt', 'value' => $d['alt'], 'group' => 'Content' ),
		array( 'type' => 'dropdown', 'heading' => 'Image position', 'param_name' => 'pos', 'value' => array( 'Bottom center' => 'bottom', 'Center' => 'center', 'Top center' => 'top' ), 'std' => 'bottom', 'group' => 'Content' ),
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		$g   = 'Box ' . $i;
		$p[] = array( 'type' => 'attach_image', 'heading' => 'Icon (SVG)', 'param_name' => "i{$i}", 'value' => $d[ "i{$i}" ], 'group' => $g );
		$p[] = array( 'type' => 'textfield', 'heading' => 'Title (H4)', 'param_name' => "t{$i}", 'value' => $d[ "t{$i}" ], 'group' => $g );
	}
	vc_map( array( 'name' => 'Concepts Recruitment Ethics', 'base' => 'cgs_fwr_ethics', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_fwr_ethics', function ( $atts ) {
	$d = cgs_fwr_ethics_defaults();
	$a = shortcode_atts( $d, $atts, 'cgs_fwr_ethics' );
	foreach ( $d as $k => $v ) {
		if ( '' === trim( (string) $a[ $k ] ) ) {
			$a[ $k ] = $v;
		}
	}
	static $once = false;
	ob_start();
	if ( ! $once ) {
		$once = true; ?>
<style>
.cgs-fe-l{display:flex;flex-direction:column;justify-content:center;gap:clamp(18px,2vw,28px)}
body.cgs-site #lqd-site-content .cgs-fe .cgs-fe-l h2.cgs-hero-eb{justify-content:flex-start;margin:0 0 calc(-1 * clamp(8px,1vw,14px))}
.cgs-fe-l .cgs-about-h{margin:0}
.cgs-fe-l>p{margin:0;font-size:clamp(15px,1.05vw,17px);line-height:1.7;color:#3f536d}
.cgs-fe .cgs-vl-list{grid-template-columns:repeat(2,minmax(0,1fr))}
.cgs-fe .cgs-vl-box{grid-template-columns:1fr;justify-items:start;align-items:start;gap:clamp(10px,1vw,14px);padding:clamp(14px,1.4vw,22px)}
.cgs-fe .cgs-vl-ic{width:clamp(40px,3.2vw,52px);height:clamp(40px,3.2vw,52px)}
@media(max-width:1024px){.cgs-fe .cgs-vl-grid{grid-template-columns:1fr;row-gap:clamp(32px,6vw,56px)}}
@media(max-width:600px){.cgs-fe .cgs-vl-list{grid-template-columns:1fr}}
@media(max-width:1024px){.cgs-fe .cgs-vm-img img{object-position:center center!important}.cgs-fe .cgs-vl-box{cursor:pointer;transition:transform .35s ease,box-shadow .35s ease,border-color .35s ease}.cgs-fe .cgs-vl-box:active{transform:scale(.97)}.cgs-fe .cgs-vl-box.on{transform:translateY(-6px);box-shadow:0 22px 40px -20px rgba(11,74,150,.55);border-color:var(--cgs-blue,#1e90ff)}}
</style>
<script>
document.addEventListener('click',function(e){var b=e.target.closest&&e.target.closest('.cgs-fe .cgs-vl-box');if(!b||window.innerWidth>1024)return;var o=b.classList.contains('on');b.parentNode.querySelectorAll('.on').forEach(function(x){x.classList.remove('on')});if(!o)b.classList.add('on')});
</script>
	<?php } ?>
<section class="cgs-vm cgs-vl cgs-fe" id="ethics">
<div class="cgs-vl-grid">
<div class="cgs-fe-l">
<h2 class="cgs-hero-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
<p><?php echo esc_html( $a['p'] ); ?></p>
<div class="cgs-vl-list">
<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
<div class="cgs-vl-box"><span class="cgs-vl-ic"><i style="--cgs-ic:url('<?php echo esc_url( cgs_media_url( $a[ "i{$i}" ], $d[ "i{$i}" ] ) ); ?>')"></i></span><div><h4 class="cgs-vl-t"><?php echo esc_html( $a[ "t{$i}" ] ); ?></h4></div></div>
<?php endfor; ?>
</div>
</div>
<div class="cgs-vm-img"><picture><source media="(max-width:1024px)" srcset="<?php echo esc_url( cgs_media_url( $a['imgm'], $d['imgm'] ) ); ?>"><img style="object-position:center <?php echo esc_attr( in_array( $a['pos'], array( 'top', 'center', 'bottom' ), true ) ? $a['pos'] : 'bottom' ); ?>" src="<?php echo esc_url( cgs_media_url( $a['img'], $d['img'] ) ); ?>" alt="<?php echo esc_attr( $a['alt'] ); ?>" loading="lazy"></picture></div>
</div>
</section>
	<?php
	return ob_get_clean();
} );

/* Recruitment FAQs — [cgs_fwr_faq]: the homepage FAQ look (.cgs-fq light-blue band, .cgs-fq-list accordion, .cgs-fq-card navy panel) rearranged:
 * head on top, accordion box left, contact panel + WhatsApp card right. Accordion script/CSS are the homepage ones (cgs-faq handle). */
function cgs_fwr_faq_items_default() {
	return array(
		array( 'q' => 'Which countries do you recruit foreign workers from?', 'a' => 'We work with recruitment partners in Indonesia, Bangladesh, Nepal, Myanmar, Cambodia, Laos, Sri Lanka and Thailand to help employers source suitable foreign workers.' ),
		array( 'q' => 'What is the foreign worker recruitment process?', 'a' => 'The process typically includes an initial consultation, quota application, candidate selection, documentation, visa arrangements, pre-departure preparation, airport clearance and post-arrival support.' ),
		array( 'q' => 'Can Concepts Group help with foreign worker quota applications?', 'a' => 'Yes. We assist employers with preparing the required documentation and navigating the quota application process, subject to applicable requirements and approval by the relevant authorities.' ),
		array( 'q' => 'Can we select and interview candidates before hiring?', 'a' => 'Yes. We coordinate candidate interviews with employers and recruitment partners in the source countries to help identify workers who match their job requirements.' ),
		array( 'q' => 'Do you assist with workers\' arrival in Malaysia?', 'a' => 'Yes. We assist with airport clearance and arrange transportation from the airport to the designated worker accommodation. We also coordinate relevant post-arrival procedures.' ),
		array( 'q' => 'Do you provide support after workers arrive in Malaysia?', 'a' => 'Yes. Our support includes coordinating accommodation, required medical screening, permit-related procedures and worker welfare during their stay in Malaysia.' ),
		array( 'q' => 'Can you arrange worker accommodation and transportation?', 'a' => 'Yes. We can coordinate worker accommodation and transportation as part of our workforce support services, depending on your business requirements.' ),
		array( 'q' => 'Do you assist with repatriation when a worker\'s contract ends?', 'a' => 'Yes. We assist with or arrange repatriation when workers complete their employment contracts, subject to the applicable procedures and arrangements.' ),
		array( 'q' => 'How do we get started with foreign worker recruitment?', 'a' => 'Contact our team for an initial consultation. We will discuss your manpower requirements, job scope and recruitment needs to help determine the appropriate next steps.' ),
	);
}

function cgs_fwr_faq_defaults() {
	$u = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		'h2' => 'Recruitment FAQs', 'h3' => 'Frequently Asked Questions', 'h3e' => 'Questions',
		't'   => 'Have More Questions?',
		'p'   => 'Our team is ready to help with your workforce and recruitment needs. Get in touch with us for reliable support.',
		'btn' => 'CONTACT US', 'url' => '/#contact',
		's1'  => 'Your Workforce, Our Priority', 's2' => 'Reliable Workforce Support',
		'wa'  => '+60 17-887 3361', 'wa_url' => 'https://wa.me/60178873361',
		'wa_icon'    => $u . 'whatsapp.svg',
		'icon_add'   => $u . 'add.svg',
		'icon_minus' => $u . 'minus-sign.svg',
		'items' => '',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_fwr_faq_defaults();
	$p = array(
		array( 'type' => 'textfield', 'heading' => 'Label (H2)', 'param_name' => 'h2', 'value' => $d['h2'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Heading (H3)', 'param_name' => 'h3', 'value' => $d['h3'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Accent words in heading', 'param_name' => 'h3e', 'value' => $d['h3e'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Panel title (H4)', 'param_name' => 't', 'value' => $d['t'], 'group' => 'Contact panel' ),
		array( 'type' => 'textarea', 'heading' => 'Panel description', 'param_name' => 'p', 'value' => $d['p'], 'group' => 'Contact panel' ),
		array( 'type' => 'textfield', 'heading' => 'Button text', 'param_name' => 'btn', 'value' => $d['btn'], 'group' => 'Contact panel' ),
		array( 'type' => 'textfield', 'heading' => 'Button link', 'param_name' => 'url', 'value' => $d['url'], 'group' => 'Contact panel' ),
		array( 'type' => 'textfield', 'heading' => 'Small text', 'param_name' => 's1', 'value' => $d['s1'], 'group' => 'WhatsApp card' ),
		array( 'type' => 'textfield', 'heading' => 'Bold text', 'param_name' => 's2', 'value' => $d['s2'], 'group' => 'WhatsApp card' ),
		array( 'type' => 'textfield', 'heading' => 'WhatsApp number (display)', 'param_name' => 'wa', 'value' => $d['wa'], 'group' => 'WhatsApp card' ),
		array( 'type' => 'textfield', 'heading' => 'WhatsApp link', 'param_name' => 'wa_url', 'value' => $d['wa_url'], 'group' => 'WhatsApp card' ),
		array( 'type' => 'attach_image', 'heading' => 'WhatsApp icon (SVG)', 'param_name' => 'wa_icon', 'value' => $d['wa_icon'], 'group' => 'WhatsApp card' ),
		array( 'type' => 'attach_image', 'heading' => 'Expand icon (SVG)', 'param_name' => 'icon_add', 'value' => $d['icon_add'], 'group' => 'Icons' ),
		array( 'type' => 'attach_image', 'heading' => 'Collapse icon (SVG)', 'param_name' => 'icon_minus', 'value' => $d['icon_minus'], 'group' => 'Icons' ),
		array( 'type' => 'param_group', 'heading' => 'Questions (numbered automatically)', 'param_name' => 'items', 'group' => 'Questions',
			'value'  => rawurlencode( wp_json_encode( cgs_fwr_faq_items_default() ) ),
			'params' => array(
				array( 'type' => 'textfield', 'heading' => 'Question', 'param_name' => 'q', 'admin_label' => true ),
				array( 'type' => 'textarea', 'heading' => 'Answer', 'param_name' => 'a' ),
			),
		),
	);
	vc_map( array( 'name' => 'Concepts Recruitment FAQ', 'base' => 'cgs_fwr_faq', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_fwr_faq', function ( $atts ) {
	$d = cgs_fwr_faq_defaults();
	$a = shortcode_atts( $d, $atts, 'cgs_fwr_faq' );
	foreach ( $d as $k => $v ) {
		if ( 'items' !== $k && '' === trim( (string) $a[ $k ] ) ) {
			$a[ $k ] = $v;
		}
	}
	$items = array();
	if ( '' !== trim( (string) $a['items'] ) ) {
		$dec   = json_decode( rawurldecode( $a['items'] ), true );
		$items = is_array( $dec ) ? $dec : array();
	}
	$items = array_values( array_filter( $items, function ( $i ) {
		return is_array( $i ) && ! empty( $i['q'] );
	} ) );
	if ( ! $items ) {
		$items = cgs_fwr_faq_items_default();
	}
	$url = trim( $a['url'] );
	$url = ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) ? home_url( $url ) : $url;
	wp_enqueue_script( 'cgs-faq' );
	static $once = false;
	ob_start();
	if ( ! $once ) {
		$once = true; ?>
<style>
.cgs-fqr-head{margin-bottom:clamp(28px,3.4vw,56px);text-align:center}
body.cgs-site #lqd-site-content .cgs-fqr h2.cgs-about-eb{margin:0 0 1.2vw;font:700 clamp(12px,.9vw,14px) var(--cgs-fb,Inter,sans-serif);letter-spacing:.1em;text-transform:uppercase;color:var(--cgs-blue-d,#0b6fd1)}
.cgs-fqr-grid{display:grid;grid-template-columns:1.6fr 1fr;gap:clamp(16px,2vw,28px);align-items:start}
.cgs-fqr-box{background:#fff;border-radius:clamp(22px,2.5vw,34px);padding:clamp(18px,2.5vw,34px);box-shadow:0 40px 70px -48px rgba(11,95,196,.45)}
.cgs-fqr-side{display:flex;flex-direction:column;gap:clamp(16px,2vw,28px)}
.cgs-fqr .cgs-fq-card{min-height:0}
body.cgs-site #lqd-site-content .cgs-fqr h4.cgs-fqr-t{margin:0;color:#fff;font:600 clamp(24px,2.2vw,34px)/1.1 var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.03em;text-transform:none}
.cgs-fqr-wa{display:flex;align-items:center;gap:clamp(14px,1.6vw,22px);padding:clamp(18px,2vw,28px);background:#fff;border-radius:clamp(18px,1.9vw,26px);text-decoration:none;box-shadow:0 40px 70px -48px rgba(11,95,196,.45)}
.cgs-fqr-wa small{display:block;font-size:clamp(12px,.85vw,14px);color:var(--cgs-mute,#5d6d84)}
.cgs-fqr-wa b{display:block;margin:4px 0;font:600 clamp(18px,1.45vw,24px)/1.2 var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.02em;color:var(--cgs-navy,#071A33)}
.cgs-fqr-wa span{font-weight:600;font-size:clamp(14px,1vw,16px);color:var(--cgs-blue-d,#0b6fd1)}
@media(min-width:1025px){html body.cgs-site:has(.cgs-fqr){overflow-x:clip!important}.cgs-fqr-side{position:sticky;top:calc(var(--cgs-hd-h,90px) + 24px)}}
@media(max-width:1024px){.cgs-fqr-grid{grid-template-columns:1fr}}
</style>
	<?php } ?>
<section class="cgs-fq cgs-fqr" id="recruitment-faq" style="--cgs-fq-add:url('<?php echo esc_url( cgs_media_url( $a['icon_add'], $d['icon_add'] ) ); ?>');--cgs-fq-minus:url('<?php echo esc_url( cgs_media_url( $a['icon_minus'], $d['icon_minus'] ) ); ?>')"><div class="cgs-fq-wrap">
<div class="cgs-fqr-head">
<h2 class="cgs-about-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
</div>
<div class="cgs-fqr-grid">
<div class="cgs-fqr-box cgs-rv"><div class="cgs-fq-list">
<?php foreach ( $items as $i => $it ) : ?>
<details name="cgs-faq"<?php echo 0 === $i ? ' open' : ''; ?>><summary><b class="cgs-fq-num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></b><span class="cgs-fq-q"><?php echo esc_html( $it['q'] ); ?></span><i class="cgs-fq-ic" aria-hidden="true"><span></span></i></summary><p><?php echo esc_html( isset( $it['a'] ) ? $it['a'] : '' ); ?></p></details>
<?php endforeach; ?>
</div></div>
<div class="cgs-fqr-side cgs-rv">
<div class="cgs-fq-card">
<div class="cgs-fq-txt">
<h4 class="cgs-fqr-t"><?php echo esc_html( $a['t'] ); ?></h4>
<p><?php echo esc_html( $a['p'] ); ?></p>
</div>
<a class="cgs-fq-btn" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $a['btn'] ); ?></a>
</div>
<a class="cgs-fqr-wa" href="<?php echo esc_url( $a['wa_url'] ); ?>" target="_blank" rel="noopener"><span class="cgs-vl-ic"><i style="--cgs-ic:url('<?php echo esc_url( cgs_media_url( $a['wa_icon'], $d['wa_icon'] ) ); ?>')"></i></span><div><small><?php echo esc_html( $a['s1'] ); ?></small><b><?php echo esc_html( $a['s2'] ); ?></b><span><?php echo esc_html( $a['wa'] ); ?></span></div></a>
</div>
</div>
</div></section>
	<?php
	return ob_get_clean();
} );

/* Recruitment support CTA — [cgs_fwr_cta]: the homepage CTA (.cgs-ct: photo, overlay, two waves, buttons) with everything left-aligned. */
function cgs_fwr_cta_defaults() {
	$u = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		'h2' => 'Recruitment Support', 'h3' => 'Find the Right Talent', 'h3e' => 'Right Talent',
		'p'  => 'Tell us about your manpower requirements and let our team help you explore suitable recruitment options.',
		'bg' => $u . 'Concepts-Group-7553.webp',
		'b1' => 'WhatsApp Us', 'u1' => 'https://api.whatsapp.com/send?phone=60178873361&text=Hello%20there,%20I%20would%20like%20to%20know%20about%20your%20services',
		'b2' => 'Get A Quote', 'u2' => '/#contact',
		'icon' => $u . 'Black-Right-Arrow.svg',
		'ov_color' => '#101a4a', 'ov_opacity' => '78',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_fwr_cta_defaults();
	$p = array();
	foreach ( array( 'h2' => 'Label (H2)', 'h3' => 'Heading (H3)', 'h3e' => 'Accent words in heading', 'b1' => 'Button 1 text', 'u1' => 'Button 1 link', 'b2' => 'Button 2 text', 'u2' => 'Button 2 link', 'ov_color' => 'Overlay colour', 'ov_opacity' => 'Overlay opacity (0-100)' ) as $k => $l ) {
		$p[] = array( 'type' => 'textfield', 'heading' => $l, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	$p[] = array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'p', 'value' => $d['p'] );
	$p[] = array( 'type' => 'attach_image', 'heading' => 'Background image', 'param_name' => 'bg', 'value' => $d['bg'] );
	$p[] = array( 'type' => 'attach_image', 'heading' => 'Button arrow icon (SVG)', 'param_name' => 'icon', 'value' => $d['icon'] );
	vc_map( array( 'name' => 'Concepts Recruitment CTA', 'base' => 'cgs_fwr_cta', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_fwr_cta', function ( $atts ) {
	$d = cgs_fwr_cta_defaults();
	$a = shortcode_atts( $d, $atts, 'cgs_fwr_cta' );
	foreach ( $d as $k => $v ) {
		if ( '' === trim( (string) $a[ $k ] ) ) {
			$a[ $k ] = $v;
		}
	}
	$u = function ( $url ) {
		$url = trim( (string) $url );
		return esc_url( ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) ? home_url( $url ) : $url, array( 'http', 'https', 'tel', 'mailto' ) );
	};
	$ext = function ( $url ) {
		return 0 === strpos( trim( $url ), 'http' ) ? ' target="_blank" rel="noopener"' : '';
	};
	$h3  = esc_html( $a['h3'] );
	$em  = esc_html( $a['h3e'] );
	if ( '' !== $em ) {
		$h3 = str_replace( $em, '<em>' . $em . '</em>', $h3 );
	}
	$hex = ltrim( trim( (string) $a['ov_color'] ), '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( ! preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		$hex = ltrim( $d['ov_color'], '#' );
	}
	$ov   = sprintf( 'rgba(%d,%d,%d,%s)', hexdec( substr( $hex, 0, 2 ) ), hexdec( substr( $hex, 2, 2 ) ), hexdec( substr( $hex, 4, 2 ) ), round( max( 0, min( 100, (float) $a['ov_opacity'] ) ) / 100, 2 ) );
	$icon = esc_url( cgs_media_url( $a['icon'], $d['icon'] ) );
	wp_enqueue_script( 'cgs-cta' );
	static $once = false;
	ob_start();
	if ( ! $once ) {
		$once = true; ?>
<style>
.cgs-cts:after{display:none}
.cgs-cts .cgs-ct-in{align-items:flex-start;text-align:left}
.cgs-cts .cgs-ct-in>p{margin-left:0}
.cgs-cts .cgs-ct-btns{justify-content:flex-start}
.cgs-cts .cgs-hero-eb{margin:0 0 clamp(14px,1.4vw,20px)}
</style>
	<?php } ?>
<section class="cgs-ct cgs-cts" id="recruitment-support" style="--cgs-img:url('<?php echo esc_url( cgs_media_url( $a['bg'], $d['bg'] ) ); ?>');--cgs-ov:<?php echo esc_attr( $ov ); ?>"><div class="cgs-ct-bg"></div><i class="cgs-ct-wv a" style="--c:#1e90ff"></i><i class="cgs-ct-wv" style="--c:#eaf4ff"></i><div class="cgs-ct-in">
<h2 class="cgs-hero-eb cgs-rv"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-ct-h cgs-rv"><?php echo $h3; ?></h3>
<p class="cgs-rv" style="--d:.1s"><?php echo esc_html( $a['p'] ); ?></p>
<div class="cgs-ct-btns cgs-rv" style="--d:.2s">
<a class="cgs-ct-b cgs-ct-b1" href="<?php echo $u( $a['u1'] ); ?>"<?php echo $ext( $a['u1'] ); ?>><?php echo esc_html( $a['b1'] ); ?><i style="--cgs-ic:url('<?php echo $icon; ?>')"></i></a>
<a class="cgs-ct-b cgs-ct-b2" href="<?php echo $u( $a['u2'] ); ?>"<?php echo $ext( $a['u2'] ); ?>><?php echo esc_html( $a['b2'] ); ?><i style="--cgs-ic:url('<?php echo $icon; ?>')"></i></a>
</div>
</div></section>
	<?php
	return ob_get_clean();
} );

/* Our Story Journey — [cgs_story_journey]: heading row + interactive timeline. Milestones are a repeater (param_group):
 * add, edit, delete and drag-reorder rows in the builder. Images (attach_images) are optional; no image = no image element. */
function cgs_story_journey_items_default() {
	$u = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		array( 'y' => '1991', 't' => 'The Beginning', 's' => 'Established to support employers\' manpower needs.', 'f' => 'Concepts Group was established to support employers\' manpower needs and create employment opportunities in Malaysia.', 'im' => $u . 'Concept-Group-Worker.jpg' ),
		array( 'y' => '1993', 't' => 'Developing Workforce Capabilities', 's' => 'Training centres to prepare and upskill workers.', 'f' => 'We established training centres to help prepare workers, develop their skills and improve workplace productivity.', 'im' => '' ),
		array( 'y' => '1995', 't' => 'Expanding Our Services', 's' => 'Worker transportation added to our services.', 'f' => 'Building on our growing reputation among investors and multinational companies, we expanded our services to include worker transportation.', 'im' => '' ),
		array( 'y' => '1998', 't' => 'Strengthening Workforce Support', 's' => 'TQM practices and worker hostel management.', 'f' => 'We introduced Total Quality Management (TQM) practices and expanded into worker hostel management, providing accommodation and essential facilities.', 'im' => $u . 'Concept-Group-Hostel-Hero-Img.webp' ),
		array( 'y' => '2001', 't' => 'Building Overseas Partnerships', 's' => 'Stronger ties with overseas partners.', 'f' => 'We strengthened relationships with overseas partners to support worker recruitment, training and related services across multiple countries.', 'im' => '' ),
		array( 'y' => '2004', 't' => 'Industry Recognition', 's' => 'Asia Pacific International Honesty Enterprise–Keris Award.', 'f' => 'Our commitment to reliable manpower services was recognised with the Asia Pacific International Honesty Enterprise–Keris Award.', 'im' => '' ),
		array( 'y' => '2005', 't' => 'Expanding Foreign Worker Services', 's' => 'Foreign worker outsourcing licence granted.', 'f' => 'We recorded the granting of a foreign worker outsourcing licence by Malaysia\'s Home Ministry, marking an important milestone in our service development.', 'im' => '' ),
		array( 'y' => '2006', 't' => 'Growing Our National Operations', 's' => 'Expanded into Ipoh, Penang, Melaka and Johor Bahru.', 'f' => 'We expanded into Ipoh, Penang, Melaka and Johor Bahru. During this period, the company reported supporting approximately 8,000 local and foreign workers, managing 900 hostel units, and operating a fleet of 70 buses and 30 vans.', 'im' => $u . 'Concept-Group-24141.webp' ),
		array( 'y' => '2008', 't' => 'Extending Our Recruitment Network', 's' => 'Recruitment and training network in Cambodia and Thailand.', 'f' => 'We expanded our overseas recruitment and training network, including activities in Cambodia and Thailand, to strengthen workforce sourcing capabilities.', 'im' => '' ),
		array( 'y' => '2009', 't' => 'Supporting Worker Development', 's' => 'Programmes for recruitment, training and placement.', 'f' => 'We participated in programmes relating to foreign worker management and developed initiatives focused on recruitment, training, placement and workplace skills.', 'im' => '' ),
		array( 'y' => '2011', 't' => 'Strengthening Standards and Welfare', 's' => 'Malaysia\'s 6P programme and better worker facilities.', 'f' => 'We participated in Malaysia\'s 6P foreign worker programme and continued improving worker accommodation, transportation and management practices, including efforts aligned with industry standards.', 'im' => '' ),
		array( 'y' => '2018', 't' => 'Further Regulatory Recognition', 's' => 'Category C licence accredited by the Malaysian government.', 'f' => 'Our company history records the accreditation of a Category C licence by the Malaysian government.', 'im' => '' ),
		array( 'y' => '2021', 't' => 'Prioritising Worker Welfare', 's' => 'Welfare and safety focus during COVID-19.', 'f' => 'During the COVID-19 pandemic, we focused on supporting worker welfare and maintaining applicable safety procedures, while assisting clients with accommodation-related requirements under Act 446.', 'im' => '' ),
		array( 'y' => 'Today', 't' => 'Building the Future of Workforce Solutions', 's' => '7,000+ beds in development across the Klang Valley.', 'f' => 'Today, Concepts Group continues to develop its integrated workforce capabilities, including worker accommodation and Centralised Labour Quarters (CLQ). With more than 7,000 beds in development across the Klang Valley, we are working to support the evolving accommodation needs of businesses and their workforce.', 'im' => $u . 'Concept-Group-HostelBgImg1.webp' ),
	);
}

define( 'CGS_JOURNEY_DEF', array(
	'h2' => 'Our Journey', 'h3' => 'A History of Growth', 'h3e' => 'Growth',
	'p' => 'From our beginnings in manpower supply to the development of integrated workforce solutions, each milestone reflects our continued growth and commitment to serving our clients.',
	'items' => '',
) );

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = CGS_JOURNEY_DEF;
	vc_map( array(
		'name'     => 'Concepts Story Journey',
		'base'     => 'cgs_story_journey',
		'category' => 'Concepts Group',
		'icon'     => 'icon-wpb-row',
		'params'   => array(
			array( 'type' => 'textfield', 'heading' => 'Label (H2)', 'param_name' => 'h2', 'value' => $d['h2'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Heading (H3)', 'param_name' => 'h3', 'value' => $d['h3'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Accent words in heading', 'param_name' => 'h3e', 'value' => $d['h3e'], 'group' => 'Content' ),
			array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'p', 'value' => $d['p'], 'group' => 'Content' ),
			array(
				'type'        => 'param_group',
				'heading'     => 'Milestones',
				'param_name'  => 'items',
				'group'       => 'Timeline',
				'description' => 'Each row is one milestone. Use + to add, drag to reorder, the trash icon to delete. Images are optional: a milestone with no image shows no image.',
				'value'       => rawurlencode( wp_json_encode( cgs_story_journey_items_default() ) ),
				'params'      => array(
					array( 'type' => 'textfield', 'heading' => 'Year', 'param_name' => 'y', 'admin_label' => true ),
					array( 'type' => 'textfield', 'heading' => 'Title', 'param_name' => 't', 'admin_label' => true ),
					array( 'type' => 'textfield', 'heading' => 'Short description (left list)', 'param_name' => 's' ),
					array( 'type' => 'textarea', 'heading' => 'Full description (right panel)', 'param_name' => 'f' ),
					array( 'type' => 'attach_images', 'heading' => 'Images (optional)', 'param_name' => 'im' ),
				),
			),
		),
	) );
} );

add_shortcode( 'cgs_story_journey', function ( $atts ) {
	$a     = shortcode_atts( CGS_JOURNEY_DEF, $atts, 'cgs_story_journey' );
	$items = cgs_story_journey_items_default();
	if ( '' !== trim( (string) $a['items'] ) ) {
		$dec   = json_decode( rawurldecode( $a['items'] ), true );
		$items = is_array( $dec ) ? $dec : array();
	}
	$items = array_values( array_filter( $items, function ( $i ) {
		return is_array( $i ) && ( ! empty( $i['y'] ) || ! empty( $i['t'] ) );
	} ) );
	wp_enqueue_script( 'cgs-journey' );
	ob_start(); ?>
<section class="cgs-jr" id="journey">
<h2 class="cgs-hero-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<div class="cgs-jr-head">
<h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
<p><?php echo esc_html( $a['p'] ); ?></p>
</div>
<ol class="cgs-jr-list" style="--n:<?php echo (int) max( 1, count( $items ) ); ?>">
<?php foreach ( $items as $k => $it ) :
	$imgs = array();
	foreach ( array_filter( array_map( 'trim', explode( ',', (string) ( $it['im'] ?? '' ) ) ) ) as $v ) {
		$u = cgs_media_url( $v, '' );
		if ( '' !== $u ) {
			$imgs[] = $u;
		}
	}
	$on = 0 === $k; ?>
<li class="cgs-jr-li<?php echo $on ? ' on' : ''; ?>">
<button type="button" class="cgs-jr-b" aria-expanded="<?php echo $on ? 'true' : 'false'; ?>" aria-controls="cgs-jr-d<?php echo (int) $k; ?>"><span class="cgs-jr-y"><?php echo esc_html( $it['y'] ?? '' ); ?></span><span><h4 class="cgs-jr-t"><?php echo esc_html( $it['t'] ?? '' ); ?></h4><?php if ( ! empty( $it['s'] ) ) : ?><span class="cgs-jr-s"><?php echo esc_html( $it['s'] ); ?></span><?php endif; ?></span></button>
<div class="cgs-jr-d" id="cgs-jr-d<?php echo (int) $k; ?>">
<p class="cgs-jr-dp"><?php echo esc_html( $it['f'] ?? '' ); ?></p>
<?php if ( $imgs ) : ?><div class="cgs-jr-img"><?php foreach ( $imgs as $src ) : ?><img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( ( $it['y'] ?? '' ) . ' ' . ( $it['t'] ?? '' ) ); ?>" loading="lazy"><?php endforeach; ?></div><?php endif; ?>
</div>
</li>
<?php endforeach; ?>
</ol>
</section>
	<?php
	return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_register_script( 'cgs-journey', false, array(), CGS_VERSION, true );
	wp_add_inline_script( 'cgs-journey', "(function(){document.querySelectorAll('.cgs-jr').forEach(function(s){var L=[].slice.call(s.querySelectorAll('.cgs-jr-li'));function set(l,o){l.classList.toggle('on',o);l.querySelector('.cgs-jr-b').setAttribute('aria-expanded',o)}L.forEach(function(l){l.querySelector('.cgs-jr-b').addEventListener('click',function(){var was=l.classList.contains('on');L.forEach(function(x){set(x,false)});if(!was)set(l,true)})})})})();" );
}, 38 );

/* Our Story CTA — [cgs_story_cta]: footer-coloured band with the homepage CTA's top wave; text left, button right.
 * Reuses the .cgs-ct section, heading, button and wave classes; .cgs-ct2 only changes the layout. */
define( 'CGS_STORY_CTA_DEF', array(
	'h3' => 'Looking Ahead to Our Future', 'h3e' => 'Our Future',
	'p' => 'Our journey continues as we build on our experience, strengthen our capabilities and develop solutions that respond to the changing needs of industry. Concepts Group remains committed to helping businesses manage their workforce more effectively while supporting the people behind their success.',
	'btn' => 'Contact Us', 'url' => '/#contact',
	'icon' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Black-Right-Arrow.svg',
) );

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = CGS_STORY_CTA_DEF;
	$p = array();
	foreach ( array( 'h3' => 'Heading (H3)', 'h3e' => 'Accent words in heading', 'p' => 'Description', 'btn' => 'Button text', 'url' => 'Button link', 'icon' => 'Button icon' ) as $k => $label ) {
		$p[] = array( 'type' => 'p' === $k ? 'textarea' : ( 'icon' === $k ? 'attach_image' : 'textfield' ), 'heading' => $label, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	vc_map( array( 'name' => 'Concepts Story CTA', 'base' => 'cgs_story_cta', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_story_cta', function ( $atts ) {
	$d = CGS_STORY_CTA_DEF;
	$a = shortcode_atts( $d, $atts, 'cgs_story_cta' );
	$u = trim( (string) $a['url'] );
	$u = esc_url( ( 0 === strpos( $u, '/' ) && 0 !== strpos( $u, '//' ) ) ? home_url( $u ) : $u, array( 'http', 'https', 'tel', 'mailto' ) );
	$ext = 0 === strpos( trim( (string) $a['url'] ), 'http' ) ? ' target="_blank" rel="noopener"' : '';
	ob_start(); ?>
<section class="cgs-ct cgs-ct2" id="future" style="--cgs-ov:transparent"><i class="cgs-ct-wv a" style="--c:#1e90ff"></i><i class="cgs-ct-wv" style="--c:#fff"></i><div class="cgs-ct-in">
<div class="cgs-ct2-txt">
<h3 class="cgs-ct-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
<p><?php echo esc_html( $a['p'] ); ?></p>
</div>
<div class="cgs-ct-btns"><a class="cgs-ct-b cgs-ct-b1" href="<?php echo $u; ?>"<?php echo $ext; ?>><?php echo esc_html( $a['btn'] ); ?><i style="--cgs-ic:url('<?php echo esc_url( cgs_media_url( $a['icon'], $d['icon'] ) ); ?>')"></i></a></div>
</div></section>
	<?php
	return ob_get_clean();
} );

/* Our Story Vision & Mission — [cgs_story_vision]: centred heading + buttons, then image | vision + mission cards | image. */
define( 'CGS_VISION_DEF', array(
	'h2' => 'Our Vision & Mission', 'h3' => 'Guided by Purpose & Vision', 'h3e' => 'Purpose & Vision',
	'p' => 'Our vision and mission define our direction and guide how we support businesses, build trusted partnerships and deliver workforce solutions that meet evolving industry needs.',
	'b1' => 'Discover our values', 'u1' => '/#about', 'b2' => 'Explore about us', 'u2' => '#company',
	'arrow' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Black-Right-Arrow.svg',
	'img1' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concepts-Group_481980218_1212637467333112_1930092158545274683_n.webp',
	'img2' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concept-Group_pexels-dsengul77-32415693.webp',
	'vt' => 'Our Vision', 'vp' => 'To be a trusted leader in workforce solutions, delivering reliable recruitment, worker accommodation and workforce support services that meet the evolving needs of businesses across Malaysia.',
	'mt' => 'Our Mission', 'mp' => 'To work closely with our clients and partners to deliver practical workforce solutions, optimise human resource operations, create employment opportunities and help businesses achieve sustainable growth.',
) );

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = CGS_VISION_DEF;
	$f = array( 'h2' => 'Label (H2)', 'h3' => 'Heading (H3)', 'h3e' => 'Accent words in heading', 'p' => 'Description', 'b1' => 'Button 1 text', 'u1' => 'Button 1 link', 'b2' => 'Button 2 text', 'u2' => 'Button 2 link', 'arrow' => 'Button icon', 'img1' => 'Left image', 'img2' => 'Right image', 'vt' => 'Vision title (H4)', 'vp' => 'Vision text', 'mt' => 'Mission title (H4)', 'mp' => 'Mission text' );
	$p = array();
	foreach ( $f as $k => $label ) {
		$p[] = array( 'type' => in_array( $k, array( 'p', 'vp', 'mp' ), true ) ? 'textarea' : ( in_array( $k, array( 'arrow', 'img1', 'img2' ), true ) ? 'attach_image' : 'textfield' ), 'heading' => $label, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	vc_map( array( 'name' => 'Concepts Story Vision', 'base' => 'cgs_story_vision', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_story_vision', function ( $atts ) {
	$d = CGS_VISION_DEF;
	$a = shortcode_atts( $d, $atts, 'cgs_story_vision' );
	$u = function ( $url ) {
		$url = trim( (string) $url );
		return esc_url( ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) ? home_url( $url ) : $url, array( 'http', 'https', 'tel', 'mailto' ) );
	};
	$arrow = cgs_media_url( $a['arrow'], $d['arrow'] );
	ob_start(); ?>
<section class="cgs-vm" id="vision">
<div class="cgs-vm-head">
<h2 class="cgs-hero-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
<p><?php echo esc_html( $a['p'] ); ?></p>
<div class="cgs-vm-btns">
<a class="cgs-hb cgs-hb1" href="<?php echo $u( $a['u1'] ); ?>"><?php echo esc_html( $a['b1'] ); ?><img src="<?php echo esc_url( $arrow ); ?>" alt="" width="18" height="18"></a>
<a class="cgs-hb cgs-hb2" href="<?php echo $u( $a['u2'] ); ?>"><?php echo esc_html( $a['b2'] ); ?><img src="<?php echo esc_url( $arrow ); ?>" alt="" width="18" height="18"></a>
</div>
</div>
<div class="cgs-vm-grid">
<div class="cgs-vm-img"><img src="<?php echo esc_url( cgs_media_url( $a['img1'], $d['img1'] ) ); ?>" alt="Concepts Group team" loading="lazy"></div>
<div class="cgs-vm-mid">
<div class="cgs-vm-card"><h4 class="cgs-vm-t"><?php echo esc_html( $a['vt'] ); ?></h4><p><?php echo esc_html( $a['vp'] ); ?></p></div>
<div class="cgs-vm-card"><h4 class="cgs-vm-t"><?php echo esc_html( $a['mt'] ); ?></h4><p><?php echo esc_html( $a['mp'] ); ?></p></div>
</div>
<div class="cgs-vm-img"><img src="<?php echo esc_url( cgs_media_url( $a['img2'], $d['img2'] ) ); ?>" alt="Concepts Group workforce" loading="lazy"></div>
</div>
</section>
	<?php
	return ob_get_clean();
} );

/* Our Core Values — [cgs_story_values]: centred heading, then three icon boxes | image. Reuses the .cgs-vm section/head/image classes. */
function cgs_story_values_defaults() {
	$u = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		'h2' => 'Our Core Values', 'h3' => 'Built on Strong Values', 'h3e' => 'Strong Values',
		'p' => 'Our core values shape how we work, build partnerships and deliver reliable workforce solutions while putting people first.',
		'i1' => $u . 'collaborate.svg', 't1' => 'Integrity', 'd1' => 'We uphold honesty, accountability and ethical business practices, building trust through transparency and professionalism in every relationship.',
		'i2' => $u . 'public-service.svg', 't2' => 'People-Centred', 'd2' => 'We value the people behind every business, prioritising worker welfare, respectful treatment and supportive working and living environments.',
		'i3' => $u . 'best-seller.svg', 't3' => 'Excellence', 'd3' => 'We strive for quality, reliability and continuous improvement, delivering workforce and accommodation solutions that meet our clients\' needs.',
		'img' => $u . 'Concepts-Group-Employee-welfare.webp',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_story_values_defaults();
	$p = array(
		array( 'type' => 'textfield', 'heading' => 'Label (H2)', 'param_name' => 'h2', 'value' => $d['h2'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Heading (H3)', 'param_name' => 'h3', 'value' => $d['h3'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Accent words in heading', 'param_name' => 'h3e', 'value' => $d['h3e'], 'group' => 'Content' ),
		array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'p', 'value' => $d['p'], 'group' => 'Content' ),
		array( 'type' => 'attach_image', 'heading' => 'Image', 'param_name' => 'img', 'value' => $d['img'], 'group' => 'Content' ),
	);
	for ( $i = 1; $i <= 3; $i++ ) {
		$g   = 'Box ' . $i;
		$p[] = array( 'type' => 'attach_image', 'heading' => 'Icon (SVG)', 'param_name' => "i{$i}", 'value' => $d[ "i{$i}" ], 'group' => $g );
		$p[] = array( 'type' => 'textfield', 'heading' => 'Title (H4)', 'param_name' => "t{$i}", 'value' => $d[ "t{$i}" ], 'group' => $g );
		$p[] = array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => "d{$i}", 'value' => $d[ "d{$i}" ], 'group' => $g );
	}
	vc_map( array( 'name' => 'Concepts Story Values', 'base' => 'cgs_story_values', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_story_values', function ( $atts ) {
	$d = cgs_story_values_defaults();
	$a = shortcode_atts( $d, $atts, 'cgs_story_values' );
	ob_start(); ?>
<section class="cgs-vm cgs-vl" id="values">
<div class="cgs-vm-head">
<h2 class="cgs-hero-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
<p><?php echo esc_html( $a['p'] ); ?></p>
</div>
<div class="cgs-vl-grid">
<div class="cgs-vl-list">
<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
<div class="cgs-vl-box"><span class="cgs-vl-ic"><i style="--cgs-ic:url('<?php echo esc_url( cgs_media_url( $a[ "i{$i}" ], $d[ "i{$i}" ] ) ); ?>')"></i></span><div><h4 class="cgs-vl-t"><?php echo esc_html( $a[ "t{$i}" ] ); ?></h4><p><?php echo esc_html( $a[ "d{$i}" ] ); ?></p></div></div>
<?php endfor; ?>
</div>
<div class="cgs-vm-img"><img src="<?php echo esc_url( cgs_media_url( $a['img'], $d['img'] ) ); ?>" alt="Concepts Group employee welfare" loading="lazy"></div>
</div>
</section>
	<?php
	return ob_get_clean();
} );

/* Our Commitment CTA — [cgs_story_commit]: footer-blue band (.cgs-ct/.cgs-ct2 classes) with the CLQ top wave; text + button left, image in a light frame right. */
define( 'CGS_COMMIT_DEF', array(
	'h2' => 'Our Commitment', 'h3' => 'Building a Better Future Together', 'h3e' => 'Better Future Together', 'pos' => 'center', 'size' => '100',
	'p' => 'We are committed to building long-term partnerships through dependable service, responsible workforce management and continuous improvement. By understanding the needs of both employers and workers, we aim to create solutions that support business performance and workforce well-being.',
	'btn' => 'Get in touch', 'url' => '/#contact',
	'icon' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Black-Right-Arrow.svg',
	'img' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concepts-Group-PDA-MT-ENG.webp',
) );

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = CGS_COMMIT_DEF;
	$p = array();
	foreach ( array( 'h2' => 'Label (H2)', 'h3' => 'Heading (H3)', 'h3e' => 'Accent words in heading', 'p' => 'Description', 'btn' => 'Button text', 'url' => 'Button link', 'icon' => 'Button icon', 'img' => 'Image' ) as $k => $label ) {
		$p[] = array( 'type' => 'p' === $k ? 'textarea' : ( in_array( $k, array( 'icon', 'img' ), true ) ? 'attach_image' : 'textfield' ), 'heading' => $label, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	$p[] = array( 'type' => 'dropdown', 'heading' => 'Image position', 'param_name' => 'pos', 'value' => array( 'Center center' => 'center', 'Top center' => 'top', 'Bottom center' => 'bottom' ), 'std' => 'center', 'group' => 'Image' );
		$p[] = array( 'type' => 'textfield', 'heading' => 'Image size (%)', 'param_name' => 'size', 'value' => '100', 'description' => 'Width of the image inside the frame, in percent (10-200). 100 = full frame width.', 'group' => 'Image' );
		vc_map( array( 'name' => 'Concepts Story Commitment', 'base' => 'cgs_story_commit', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_story_commit', function ( $atts ) {
	$d   = CGS_COMMIT_DEF;
	$a   = shortcode_atts( $d, $atts, 'cgs_story_commit' );
	$u   = trim( (string) $a['url'] );
	$u   = esc_url( ( 0 === strpos( $u, '/' ) && 0 !== strpos( $u, '//' ) ) ? home_url( $u ) : $u, array( 'http', 'https', 'tel', 'mailto' ) );
	$ext = 0 === strpos( trim( (string) $a['url'] ), 'http' ) ? ' target="_blank" rel="noopener"' : '';
	ob_start(); ?>
<section class="cgs-ct cgs-ct2 cgs-cm" id="commitment" style="--cgs-ov:transparent"><i class="cgs-ct-wv a" style="--c:#1e90ff"></i><i class="cgs-ct-wv" style="--c:#fff"></i><div class="cgs-ct-in">
<div class="cgs-ct2-txt">
<h2 class="cgs-hero-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-ct-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
<p><?php echo esc_html( $a['p'] ); ?></p>
<div class="cgs-ct-btns"><a class="cgs-ct-b cgs-ct-b1" href="<?php echo $u; ?>"<?php echo $ext; ?>><?php echo esc_html( $a['btn'] ); ?><i style="--cgs-ic:url('<?php echo esc_url( cgs_media_url( $a['icon'], $d['icon'] ) ); ?>')"></i></a></div>
</div>
<div class="cgs-cm-pic" style="--cgs-w:<?php echo min( 200, max( 10, (int) $a['size'] ) ); ?>%;--cgs-p:<?php echo in_array( $a['pos'], array( 'top', 'center', 'bottom' ), true ) ? $a['pos'] : 'center'; ?> center"><img src="<?php echo esc_url( cgs_media_url( $a['img'], $d['img'] ) ); ?>" alt="Concepts Group commitment" loading="lazy"></div>
</div></section>
	<?php
	return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_register_script( 'cgs-hero', false, array(), CGS_VERSION, true );
	wp_add_inline_script( 'cgs-hero', "(function(){var els=document.querySelectorAll('.cgs-hero-stats b');if(!els.length||matchMedia('(prefers-reduced-motion:reduce)').matches)return;els.forEach(function(el){var t=el.dataset.n,m=t.match(/^([\\d,]+)(.*)$/);if(!m)return;var end=+m[1].replace(/,/g,''),suf=m[2],big=m[1].indexOf(',')>-1,t0=performance.now()+600;el.textContent='0'+suf;(function k(n){var p=Math.min(Math.max((n-t0)/1600,0),1),v=Math.round(end*(1-Math.pow(1-p,3)));el.textContent=(big?v.toLocaleString('en-US'):v)+suf;if(p<1)requestAnimationFrame(k)})(t0)})})();" );
	wp_add_inline_script( 'cgs-hero', "(function(){var h=document.querySelector('.cgs-hero:not(.cgs-hero-story)');if(!h)return;var ar=h.querySelector('.cgs-hero-arch'),hd=h.querySelector('.cgs-hero-head'),wk=h.querySelector('.cgs-hero-work'),vs=h.querySelector('.cgs-hero-vis');if(!ar||!hd||!wk)return;function fit(){var a=ar.offsetHeight,w=wk.offsetHeight;if(!a||!w)return;var c=w>a?Math.min(100,(w-a+26)/w*100):0;hd.style.clipPath='inset(0 0 '+(100-c).toFixed(1)+'% 0)'}[wk,hd].forEach(function(i){if(i.complete)fit();else i.addEventListener('load',fit)});addEventListener('resize',fit);fit();if(matchMedia('(prefers-reduced-motion:reduce)').matches||!('translate' in document.documentElement.style))return;var t=0;function p(){t=0;var r=h.getBoundingClientRect();if(r.bottom<0||r.top>innerHeight)return;var y=Math.min(90,Math.max(0,-r.top*.22)).toFixed(1)+'px';ar.style.translate='0 '+y;hd.style.translate='0 '+y;if(vs)vs.style.setProperty('--cgs-pb',parseFloat(y).toFixed(1)+'px')}addEventListener('scroll',function(){if(!t)t=requestAnimationFrame(p)},{passive:true});p()})();" );
}, 31 );

/* ------------------------------------------------------------------
 * 8. FOOTER ELEMENT — [cgs_footer], editable in WPBakery backend.
 *    Liquid Footer post: [vc_row][vc_column][cgs_footer][/vc_column][/vc_row]
 *    Main pages = top-level items of the menu; Solutions = sub-items of the first item that has any.
 * ---------------------------------------------------------------- */
add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	vc_map( array(
		'name'     => 'Concepts Footer',
		'base'     => 'cgs_footer',
		'category' => 'Concepts Group',
		'icon'     => 'icon-wpb-row',
		'params'   => array(
			array( 'type' => 'attach_image', 'heading' => 'Logo (white)', 'param_name' => 'logo', 'description' => 'Empty = default logo.', 'group' => 'Brand' ),
			array( 'type' => 'textfield', 'heading' => 'Logo height (px)', 'param_name' => 'logo_h', 'value' => '34', 'description' => 'Number only, e.g. 34. Phones show it at 85%.', 'group' => 'Brand' ),
			array( 'type' => 'textfield', 'heading' => 'Facebook link', 'param_name' => 'fb', 'value' => 'https://www.facebook.com/conceptsgroup.my', 'group' => 'Social' ),
			array( 'type' => 'textfield', 'heading' => 'Email address', 'param_name' => 'email', 'value' => 'support@concepts-groups.com', 'group' => 'Social' ),
			array( 'type' => 'textfield', 'heading' => 'WhatsApp link', 'param_name' => 'wa', 'value' => 'https://api.whatsapp.com/send?phone=60178873361&text=Hello%20there,%20I%20would%20like%20to%20know%20about%20your%20services', 'group' => 'Social' ),
			array( 'type' => 'dropdown', 'heading' => 'Menu (Appearance > Menus)', 'param_name' => 'menu', 'group' => 'Menus', 'value' => cgs_menu_choices() ),
			array( 'type' => 'textfield', 'heading' => 'Main pages title', 'param_name' => 't1', 'value' => 'Main Pages', 'group' => 'Menus' ),
			array( 'type' => 'textfield', 'heading' => 'Solutions title', 'param_name' => 't2', 'value' => 'Solutions', 'group' => 'Menus' ),
			array( 'type' => 'textfield', 'heading' => 'Contact title', 'param_name' => 't3', 'value' => 'Contact', 'group' => 'Contact' ),
			array( 'type' => 'textfield', 'heading' => 'Phone', 'param_name' => 'phone', 'value' => '+60 17-887 3361', 'group' => 'Contact' ),
			array( 'type' => 'textfield', 'heading' => 'Copyright', 'param_name' => 'copy', 'value' => '© 2026 CONCEPTS ATC SDN BHD. All Rights Reserved.', 'group' => 'Contact' ),
		),
	) );
} );

add_shortcode( 'cgs_footer', function ( $atts ) {
	$a = shortcode_atts( array(
		'logo' => '', 'logo_h' => '34', 'fb' => 'https://www.facebook.com/conceptsgroup.my', 'email' => 'support@concepts-groups.com',
		'wa' => 'https://api.whatsapp.com/send?phone=60178873361&text=Hello%20there,%20I%20would%20like%20to%20know%20about%20your%20services',
		'menu' => '', 't1' => 'Main Pages', 't2' => 'Solutions', 't3' => 'Contact', 'phone' => '+60 17-887 3361',
		'copy' => '© 2026 CONCEPTS ATC SDN BHD. All Rights Reserved.',
	), $atts, 'cgs_footer' );

	$logo = $a['logo'] ? wp_get_attachment_image_url( (int) $a['logo'], 'full' ) : '';
	$logo = cgs_brand_logo( $logo );
	$menu = cgs_menu_items( (int) $a['menu'] );
	$u    = function ( $url ) {
		$url = trim( (string) $url );
		return esc_url( ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) ? home_url( $url ) : $url );
	};
	$sol = array();
	foreach ( $menu as $it ) {
		if ( ! empty( $it['children'] ) ) {
			foreach ( preg_split( '/\r\n|\r|\n/', trim( $it['children'] ) ) as $line ) {
				$p = array_map( 'trim', explode( '|', $line, 2 ) );
				if ( '' !== $p[0] ) {
					$sol[] = array( $p[0], isset( $p[1] ) ? $p[1] : '#' );
				}
			}
			break;
		}
	}
	$soc = array(
		array( $a['fb'], 'facebook.svg', 'Facebook', true ),
		array( 'mailto:' . $a['email'], 'email2.svg', 'Email', false ),
		array( $a['wa'], 'whatsapp.svg', 'WhatsApp', true ),
	);

	ob_start(); ?>
<footer class="cgs-ft" style="--cgs-ft-lh:<?php echo (int) max( 16, min( 120, (int) $a['logo_h'] ) ); ?>px"><div class="cgs-ft-in">
<div class="cgs-ft-top">
<div class="cgs-ft-brand">
<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img class="cgs-ft-logo" src="<?php echo esc_url( $logo ); ?>" alt="Concepts Group, Total HR Solution" width="204" height="50"></a>
<div class="cgs-ft-soc">
<?php foreach ( $soc as $s ) : ?>
<a href="<?php echo esc_url( $s[0], array( 'http', 'https', 'mailto' ) ); ?>"<?php echo $s[3] ? ' target="_blank" rel="noopener"' : ''; ?> aria-label="<?php echo esc_attr( $s[2] ); ?>"><img src="<?php echo esc_url( CGS_UP . $s[1] ); ?>" alt="" width="19" height="19"></a>
<?php endforeach; ?>
</div>
</div>
<div class="cgs-ft-cols">
<div class="cgs-ft-col"><h4><?php echo esc_html( $a['t1'] ); ?></h4>
<?php foreach ( $menu as $it ) : ?><a href="<?php echo $u( $it['url'] ); ?>"><?php echo esc_html( $it['label'] ); ?></a><?php endforeach; ?>
</div>
<div class="cgs-ft-col"><h4><?php echo esc_html( $a['t2'] ); ?></h4>
<?php foreach ( $sol as $s ) : ?><a href="<?php echo $u( $s[1] ); ?>"><?php echo esc_html( $s[0] ); ?></a><?php endforeach; ?>
</div>
<div class="cgs-ft-col"><h4><?php echo esc_html( $a['t3'] ); ?></h4>
<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $a['phone'] ) ); ?>"><?php echo esc_html( $a['phone'] ); ?></a>
<a href="mailto:<?php echo esc_attr( $a['email'] ); ?>"><?php echo esc_html( $a['email'] ); ?></a>
</div>
</div>
</div>
<div class="cgs-ft-bar"><?php echo esc_html( $a['copy'] ); ?></div>
</div></footer>
	<?php
	return ob_get_clean();
} );

/* ------------------------------------------------------------------
 * 9. MEDIA HELPER — accepts an uploaded media ID or a plain URL.
 * ---------------------------------------------------------------- */
function cgs_media_url( $v, $fallback ) {
	$v = trim( (string) $v );
	if ( '' === $v ) {
		return $fallback;
	}
	if ( ctype_digit( $v ) ) {
		$u = wp_get_attachment_url( (int) $v );
		return $u ? $u : $fallback;
	}
	return $v;
}

/* ------------------------------------------------------------------
 * 10. ABOUT CSS — white section, 4 image cards, capability ticker
 * ---------------------------------------------------------------- */
function cgs_about_css() {
	return <<<'CSS'
.cgs-about{background:#fff;padding:clamp(60px,6vw,110px) 0 0;font-family:var(--cgs-fb,Inter,sans-serif);color:var(--cgs-ink,#14243b);overflow:hidden}
.cgs-about *{box-sizing:border-box}
.cgs-about-in{padding:0 5vw}
.cgs-about-head{display:flex;flex-direction:column;align-items:center;text-align:center;gap:2vw;margin-bottom:3.5vw}
.cgs-about-eb{margin:0 0 1.2vw;font:700 clamp(12px,.9vw,14px) var(--cgs-fb,Inter,sans-serif);letter-spacing:.1em;text-transform:uppercase;color:var(--cgs-blue-d,#0b6fd1);display:flex;align-items:center;justify-content:center;gap:.9vw}
.cgs-about-h{margin:0;font:600 clamp(26px,2.9vw,48px)/1.06 var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.035em;text-transform:capitalize;color:var(--cgs-navy,#071A33)}
.cgs-about-h em{font-style:normal;color:var(--cgs-blue,#1e90ff)}
.cgs-about-txt p{margin:0 auto 1.2vw;max-width:60vw;font-size:clamp(15px,1.05vw,17px);line-height:1.7;color:#3f536d}
.cgs-about-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1.5vw}
.cgs-card{position:relative;display:flex;align-items:flex-end;aspect-ratio:3/4;border-radius:clamp(18px,1.8vw,26px);overflow:hidden;background:var(--cgs-navy,#071A33);text-decoration:none}
.cgs-card:before{content:"";position:absolute;inset:0;z-index:0;background:var(--cgs-img) center/cover no-repeat;transition:transform .9s cubic-bezier(.2,.7,.2,1)}
.cgs-card:after{content:"";position:absolute;inset:0;z-index:1;background:linear-gradient(0deg,rgba(7,26,51,.92) 5%,rgba(7,26,51,.1) 62%);transition:background .4s}
.cgs-card-num{display:none}
.cgs-card-body{position:relative;z-index:2;width:100%;padding:clamp(18px,1.8vw,26px)}
body.cgs-site #lqd-site-content .cgs-card h4{margin:0;color:#fff;text-transform:capitalize;white-space:nowrap;font-size:clamp(14px,1.25vw,20px)}
.cgs-card:hover:before,.cgs-card:focus-visible:before{transform:scale(1.08)}
.cgs-card:hover:after,.cgs-card:focus-visible:after{background:linear-gradient(0deg,rgba(11,95,196,.92) 5%,rgba(7,26,51,.1) 70%)}
.cgs-tick{margin-top:clamp(50px,5vw,90px);background:#fff;overflow:hidden;padding:clamp(14px,1.4vw,22px) 0}
.cgs-tick-track{display:flex;width:max-content;animation:cgsTick 30s linear infinite}
.cgs-tick-track span{font:600 clamp(18px,2.2vw,34px)/1 var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.02em;color:var(--cgs-navy,#071A33);white-space:nowrap;padding:0 2.2vw}
.cgs-tick-track span:after{content:"\2022";margin-left:4.4vw;color:var(--cgs-primary,#1e90ff)}
@keyframes cgsTick{to{transform:translateX(-50%)}}
.cgs-about#about{padding:0}
.cgs-about#about .cgs-about-in{padding:clamp(60px,6vw,110px) 5vw clamp(50px,5vw,90px);background:linear-gradient(180deg,#fff 0%,rgba(255,255,255,.97) 14%,rgba(255,255,255,.96) 86%,#fff 100%),var(--cgs-img) center/cover no-repeat}
.cgs-about#about .cgs-tick{margin-top:0}
@media(max-width:767px){.cgs-about#about .cgs-about-in{padding-left:6vw;padding-right:6vw}}
@media(max-width:1024px){.cgs-about-txt p{max-width:none}.cgs-about-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:4vw}}
@media(max-width:767px){.cgs-about-eb{gap:2.4vw;margin-bottom:3vw}.cgs-about-in{padding:0 6vw}.cgs-about-grid{grid-template-columns:1fr;gap:6vw}.cgs-card{aspect-ratio:4/3}}
@media(max-width:1024px){.cgs-tick-track span{font-size:clamp(26px,4vw,34px);padding:0 5vw}.cgs-tick-track span:after{margin-left:6vw}.cgs-about-head{margin-bottom:clamp(32px,6vw,48px)}}
@media(prefers-reduced-motion:reduce){.cgs-tick-track{animation:none}}
CSS;
}

/* ------------------------------------------------------------------
 * 11. ABOUT ELEMENT — [cgs_about], editable in WPBakery backend.
 *     Page content: [vc_row full_width="stretch_row" el_class="cgs-row"][vc_column][cgs_about][/vc_column][/vc_row]
 * ---------------------------------------------------------------- */
function cgs_about_defaults() {
	$base = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		'h2' => 'Who We Are',
		'bg' => $base . 'hero1.jpg',
		'h3' => 'Your Partner In Workforce Management',
		'h3e' => 'Workforce Management',
		'p1' => 'For more than 30 years, Concepts Group has supported businesses with reliable workforce solutions, specialising in worker accommodation and manpower supply. From recruitment and documentation to payroll, transportation, accommodation and worker welfare, we provide integrated support that helps employers manage their workforce efficiently and focus on growing their business.',
		'ticker' => 'Recruitment, Documentation, Payroll, Transportation, Accommodation, Welfare, Administration',
		'c1p' => 'Manpower', 'c1t' => 'Workforce Recruitment', 'c1d' => 'Find the right people for your business', 'c1i' => $base . 'Concept-Group-Worker.jpg',
		'c2p' => 'HR Support', 'c2t' => 'Workforce Management', 'c2d' => 'Simplify the day-to-day', 'c2i' => $base . 'Concept-Group-24141.webp',
		'c3p' => 'Accommodation', 'c3t' => 'Worker Accommodation', 'c3d' => 'Find the right people for your business', 'c3i' => $base . 'Concept-Group-Worker2.webp',
		'c4p' => 'Klang Valley', 'c4t' => 'CLQ Development', 'c4d' => 'Workforce infrastructure at scale', 'c4i' => $base . 'Concept-Group-HostelBgImg1.webp',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_about_defaults();
	$p = array(
		array( 'type' => 'textfield', 'heading' => 'Label (H2)', 'param_name' => 'h2', 'value' => $d['h2'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Heading (H3)', 'param_name' => 'h3', 'value' => $d['h3'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Accent words in heading (blue)', 'param_name' => 'h3e', 'value' => $d['h3e'], 'group' => 'Content' ),
		array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'p1', 'value' => $d['p1'], 'group' => 'Content' ),
		array( 'type' => 'attach_image', 'heading' => 'Background image', 'param_name' => 'bg', 'value' => $d['bg'], 'group' => 'Content' ),
		array( 'type' => 'textarea', 'heading' => 'Bottom ticker words (comma separated)', 'param_name' => 'ticker', 'value' => $d['ticker'], 'group' => 'Ticker' ),
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		$g = 'Card ' . $i;
		$p[] = array( 'type' => 'textfield', 'heading' => 'Title (H4)', 'param_name' => "c{$i}t", 'value' => $d[ "c{$i}t" ], 'group' => $g );
		$p[] = array( 'type' => 'attach_image', 'heading' => 'Image (click to upload)', 'param_name' => "c{$i}i", 'value' => $d[ "c{$i}i" ], 'group' => $g );
	}
	vc_map( array( 'name' => 'Concepts About', 'base' => 'cgs_about', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_about', function ( $atts ) {
	$d = cgs_about_defaults();
	$a = shortcode_atts( $d, $atts, 'cgs_about' );
	$words = array_filter( array_map( 'trim', explode( ',', $a['ticker'] ) ) );
	$about = home_url( '/#services' );

	ob_start(); ?>
<section class="cgs-about" id="about" style="--cgs-img:url('<?php echo esc_url( cgs_media_url( $a['bg'], $d['bg'] ) ); ?>')"><div class="cgs-about-in">
<div class="cgs-about-head">
<div><p class="cgs-about-eb"><?php echo esc_html( $a['h2'] ); ?></p><h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3></div>
<div class="cgs-about-txt"><p><?php echo esc_html( $a['p1'] ); ?></p></div>
</div>
<div class="cgs-about-grid">
<?php for ( $i = 1; $i <= 4; $i++ ) :
	$img = cgs_media_url( $a[ "c{$i}i" ], $d[ "c{$i}i" ] ); ?>
<a class="cgs-card" href="<?php echo esc_url( $about ); ?>" style="--cgs-img:url('<?php echo esc_url( $img ); ?>')"><span class="cgs-card-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i ) ); ?></span><div class="cgs-card-body"><h4><?php echo esc_html( $a[ "c{$i}t" ] ); ?></h4></div></a>
<?php endfor; ?>
</div>
</div>
<div class="cgs-tick" aria-label="Our capabilities"><div class="cgs-tick-track">
<?php for ( $r = 0; $r < 2; $r++ ) {
	foreach ( $words as $w ) { ?><span><?php echo esc_html( $w ); ?></span><?php }
} ?>
</div></div>
</section>
	<?php
	return ob_get_clean();
} );

/* ------------------------------------------------------------------
 * 12. SERVICES CSS — Core solutions: 3 columns (cards | image | cards)
 * ---------------------------------------------------------------- */
function cgs_services_css() {
	return <<<'CSS'
.cgs-sv{background:var(--cgs-soft,#f3f6fa);padding:clamp(60px,6vw,110px) 0;font-family:var(--cgs-fb,Inter,sans-serif);color:var(--cgs-ink,#14243b);overflow:hidden}
.cgs-sv *{box-sizing:border-box}
.cgs-sv-in{padding:0 5vw}
.cgs-sv-head{margin-bottom:clamp(28px,3.2vw,48px)}
.cgs-sv-row{display:flex;justify-content:space-between;align-items:center;gap:3vw}
body.cgs-site #lqd-site-content .cgs-sv h2.cgs-sv-eb{display:flex;align-items:center;gap:.9vw;margin:0 0 1.2vw;font:700 clamp(12px,.9vw,14px) var(--cgs-fb,Inter,sans-serif);letter-spacing:.1em;text-transform:uppercase;color:var(--cgs-blue-d,#0b6fd1)}
body.cgs-site #lqd-site-content .cgs-sv h3.cgs-sv-h{margin:0;text-transform:capitalize}
.cgs-sv-h span+span{margin-left:.28em}
@media(min-width:1025px){.cgs-sv-h{white-space:nowrap}}
@media(min-width:768px) and (max-width:1024px){.cgs-sv-grid{gap:24px}.cgs-sv-h span{display:block}.cgs-sv-h span+span{margin-left:0}}
.cgs-sv-h em{font-style:normal;color:var(--cgs-blue,#1e90ff)}
.cgs-sv-row>p{max-width:34vw;margin:0;font-size:clamp(15px,1.05vw,17px);line-height:1.7;color:var(--cgs-mute,#5d6d84)}
.cgs-sv-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:clamp(12px,1.1vw,16px)}
.cgs-sv-cards{display:grid;grid-auto-rows:1fr;gap:clamp(12px,1.1vw,16px)}
.cgs-sv-card{display:flex;flex-direction:column;padding:clamp(22px,2.1vw,32px) clamp(20px,2vw,30px);background:#fff;border:1.5px solid transparent;border-radius:clamp(18px,1.8vw,26px);color:inherit;text-decoration:none;transition:border-color .35s,box-shadow .35s,transform .35s}
.cgs-sv-card:hover,.cgs-sv-card.on{border-color:var(--cgs-blue,#1e90ff);box-shadow:0 26px 46px -32px rgba(30,144,255,.65);transform:translateY(-4px)}
.cgs-sv-ic{display:grid;place-items:center;width:clamp(52px,4.6vw,68px);height:clamp(52px,4.6vw,68px);margin-bottom:0;border-radius:50%;background:#dbeeff;transition:background .35s}
.cgs-sv-ic span{width:48%;height:48%;background:var(--cgs-blue-d,#0b6fd1);-webkit-mask:var(--cgs-ic) center/contain no-repeat;mask:var(--cgs-ic) center/contain no-repeat;transition:background .35s}
.cgs-sv-card:hover .cgs-sv-ic,.cgs-sv-card.on .cgs-sv-ic{background:var(--cgs-blue,#1e90ff)}
.cgs-sv-card:hover .cgs-sv-ic span,.cgs-sv-card.on .cgs-sv-ic span{background:#fff}
body.cgs-site #lqd-site-content .cgs-sv h4.cgs-sv-t{margin:clamp(12px,1.2vw,18px) 0 .6vw}
.cgs-sv-card p{margin:0;font-size:clamp(14px,.95vw,15px);line-height:1.55;color:var(--cgs-mute,#5d6d84)}
.cgs-sv-img{position:relative;min-height:clamp(260px,26vw,400px);border-radius:clamp(18px,1.8vw,26px);overflow:hidden;background:var(--cgs-navy,#071A33)}
.cgs-sv-img .cgs-sv-bg{position:absolute;inset:0;background:var(--cgs-img) center/cover no-repeat;opacity:0;transform:scale(1.06);transition:opacity .6s,transform 1.2s cubic-bezier(.2,.7,.2,1)}
.cgs-sv-img .cgs-sv-bg.on{opacity:1;transform:none}
.cgs-sv-img:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,rgba(7,26,51,.35),transparent 45%)}
.cgs-sv.cgs-js .cgs-rv{opacity:0}
.cgs-sv.cgs-js .cgs-rv.in{opacity:1;animation:cgsRv .8s var(--d,0s) cubic-bezier(.2,.7,.2,1) backwards}
@keyframes cgsRv{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:none}}
@media(max-width:1024px){.cgs-sv-row>p{max-width:45vw}.cgs-sv-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:24px}.cgs-sv-cards{gap:24px}.cgs-sv-img{grid-column:1/-1;grid-row:1;min-height:clamp(260px,42vw,380px)}}
@media(max-width:767px){.cgs-sv-in{padding:0 6vw}.cgs-sv-row{flex-direction:column;align-items:flex-start;gap:4vw}.cgs-sv-row>p{max-width:none}body.cgs-site #lqd-site-content .cgs-sv h2.cgs-sv-eb{gap:2.4vw;margin-bottom:3vw}.cgs-sv-grid{grid-template-columns:1fr;gap:24px}.cgs-sv-cards{gap:20px}.cgs-sv-card{min-height:0}.cgs-sv-ic{margin-bottom:0}body.cgs-site #lqd-site-content .cgs-sv h4.cgs-sv-t{margin:20px 0 6px}.cgs-sv-img{min-height:60vw}}
@media(prefers-reduced-motion:reduce){.cgs-sv.cgs-js .cgs-rv{opacity:1}.cgs-sv.cgs-js .cgs-rv.in{animation:none}.cgs-sv-card,.cgs-sv-img .cgs-sv-bg{transition:none}}
CSS;
}

/* ------------------------------------------------------------------
 * 13. SERVICES ELEMENT — [cgs_services], editable in WPBakery backend.
 *     Page content: [vc_row full_width="stretch_row" el_class="cgs-row"][vc_column][cgs_services][/vc_column][/vc_row]
 * ---------------------------------------------------------------- */
function cgs_services_defaults() {
	$b = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		'h2' => 'Core solutions',
		'h3' => 'Integrated workforce solutions',
		'h3e' => 'workforce solutions',
		'p' => 'From recruitment to daily workforce operations, our services are designed to work together, giving employers greater control, convenience and confidence.',
		's1t' => 'Recruit The Right People', 's1d' => 'Local and foreign worker recruitment tailored to your business needs.', 's1ic' => $b . 'recruit.svg', 's1img' => $b . 'Concept-Group-Worker.jpg', 's1u' => '/#contact',
		's2t' => 'Simplify The Day-to-day', 's2d' => 'Payroll, documentation, transportation and workforce administration under one solution.', 's2ic' => $b . 'document.svg', 's2img' => $b . 'Concept-Group-24141.webp', 's2u' => '/#contact',
		's3t' => 'Safe And Compliant Accommodation', 's3d' => 'Professionally managed accommodation for your workforce.', 's3ic' => $b . 'bed.svg', 's3img' => $b . 'Concept-Group-Worker2.webp', 's3u' => '/#clq2',
		's4t' => 'Scaled For Industrial Needs', 's4d' => 'Centralised Labour Quarters (CLQ) solutions for industrial employers across Klang Valley.', 's4ic' => $b . 'hostel.svg', 's4img' => $b . 'Concept-Group-HostelBgImg1.webp', 's4u' => '/#clq2',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_services_defaults();
	$p = array(
		array( 'type' => 'textfield', 'heading' => 'Label (H2)', 'param_name' => 'h2', 'value' => $d['h2'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Heading (H3)', 'param_name' => 'h3', 'value' => $d['h3'], 'group' => 'Content' ),
		array( 'type' => 'textfield', 'heading' => 'Accent words in heading (blue)', 'param_name' => 'h3e', 'value' => $d['h3e'], 'group' => 'Content' ),
		array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'p', 'value' => $d['p'], 'group' => 'Content' ),
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		$g   = 'Item ' . $i;
		$p[] = array( 'type' => 'textfield', 'heading' => 'Title (H4)', 'param_name' => "s{$i}t", 'value' => $d[ "s{$i}t" ], 'group' => $g );
		$p[] = array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => "s{$i}d", 'value' => $d[ "s{$i}d" ], 'group' => $g );
		$p[] = array( 'type' => 'attach_image', 'heading' => 'Icon (SVG)', 'param_name' => "s{$i}ic", 'value' => $d[ "s{$i}ic" ], 'group' => $g );
		$p[] = array( 'type' => 'attach_image', 'heading' => 'Middle image shown on hover', 'param_name' => "s{$i}img", 'value' => $d[ "s{$i}img" ], 'group' => $g );
		$p[] = array( 'type' => 'textfield', 'heading' => 'Link', 'param_name' => "s{$i}u", 'value' => $d[ "s{$i}u" ], 'group' => $g );
	}
	vc_map( array( 'name' => 'Concepts Services', 'base' => 'cgs_services', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_services', function ( $atts ) {
	$d = cgs_services_defaults();
	$a = shortcode_atts( $d, $atts, 'cgs_services' );
	$u = function ( $url ) {
		$url = trim( (string) $url );
		return esc_url( ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) ? home_url( $url ) : $url );
	};
	$h3 = esc_html( $a['h3'] );
	$em = esc_html( $a['h3e'] );
	$pos = '' !== $em ? strpos( $h3, $em ) : false;
	if ( false !== $pos ) {
		$pre = trim( substr( $h3, 0, $pos ) );
		$h3  = ( '' !== $pre ? '<span>' . $pre . '</span>' : '' ) . '<span><em>' . $em . '</em></span>' . trim( substr( $h3, $pos + strlen( $em ) ) );
	}
	wp_enqueue_script( 'cgs-services' );
	$card = function ( $i ) use ( $a, $d, $u ) {
		$ic = cgs_media_url( $a[ "s{$i}ic" ], $d[ "s{$i}ic" ] );
		ob_start(); ?>
<a class="cgs-sv-card cgs-rv<?php echo 1 === $i ? ' on' : ''; ?>" href="<?php echo $u( $a[ "s{$i}u" ] ); ?>" data-i="<?php echo (int) ( $i - 1 ); ?>" style="--d:<?php echo esc_attr( ( $i - 1 ) * 0.08 ); ?>s"><i class="cgs-sv-ic" style="--cgs-ic:url('<?php echo esc_url( $ic ); ?>')"><span></span></i><h4 class="cgs-sv-t"><?php echo esc_html( $a[ "s{$i}t" ] ); ?></h4><p><?php echo esc_html( $a[ "s{$i}d" ] ); ?></p></a>
		<?php
		return ob_get_clean();
	};

	ob_start(); ?>
<section class="cgs-sv" id="services"><div class="cgs-sv-in">
<div class="cgs-sv-head cgs-rv">
<h2 class="cgs-sv-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<div class="cgs-sv-row"><h3 class="cgs-sv-h"><?php echo $h3; ?></h3>
<p><?php echo esc_html( $a['p'] ); ?></p></div>
</div>
<div class="cgs-sv-grid">
<div class="cgs-sv-cards"><?php echo $card( 1 ) . $card( 2 ); ?></div>
<div class="cgs-sv-img cgs-rv" aria-hidden="true">
<?php for ( $i = 1; $i <= 4; $i++ ) : ?><div class="cgs-sv-bg<?php echo 1 === $i ? ' on' : ''; ?>" style="--cgs-img:url('<?php echo esc_url( cgs_media_url( $a[ "s{$i}img" ], $d[ "s{$i}img" ] ) ); ?>')"></div><?php endfor; ?>
</div>
<div class="cgs-sv-cards"><?php echo $card( 3 ) . $card( 4 ); ?></div>
</div>
</div></section>
	<?php
	return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_register_script( 'cgs-services', false, array(), CGS_VERSION, true );
	wp_add_inline_script( 'cgs-services', "(function(){var s=document.querySelector('.cgs-sv');if(!s)return;var cards=s.querySelectorAll('.cgs-sv-card'),bgs=s.querySelectorAll('.cgs-sv-bg'),rv=s.querySelectorAll('.cgs-rv');function set(i){cards.forEach(function(c,k){c.classList.toggle('on',k===i)});bgs.forEach(function(b,k){b.classList.toggle('on',k===i)})}cards.forEach(function(c,i){['mouseenter','focus','touchstart'].forEach(function(e){c.addEventListener(e,function(){set(i)},{passive:true})})});if(matchMedia('(prefers-reduced-motion:reduce)').matches||!('IntersectionObserver' in window))return;s.classList.add('cgs-js');var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}})},{threshold:.15});rv.forEach(function(el){io.observe(el)})})();" );
}, 32 );

/* ------------------------------------------------------------------
 * 14. CLQ CSS — Centralised Labour Quarters: heading + location tabs
 * ---------------------------------------------------------------- */
function cgs_clq_css() {
	$wave  = "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 64' preserveAspectRatio='none'%3E%3Cpath d='M0 0H1440V32C1230 70 990 4 720 30S250 62 0 26Z'/%3E%3C/svg%3E\")";
	$wavea = "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 94' preserveAspectRatio='none'%3E%3Cpath d='M0 0H1440V40C1200 98 940 28 680 66S220 96 0 44Z'/%3E%3C/svg%3E\")";
	return <<<CSS
.cgs-cq{position:relative;overflow:hidden;background:linear-gradient(180deg,var(--cgs-navy,#071A33),#0a2748);color:#fff;padding:clamp(56px,5vw,80px) 0 clamp(48px,4.5vw,72px);font-family:var(--cgs-fb,Inter,sans-serif)}
.cgs-cq *{box-sizing:border-box}

.cgs-cq-wv{position:absolute;left:0;right:0;top:-1px;height:64px;background:var(--c);pointer-events:none;z-index:2;-webkit-mask:{$wave} center/100% 100% no-repeat;mask:{$wave} center/100% 100% no-repeat}
.cgs-cq-wv.a{height:94px;z-index:1;-webkit-mask-image:{$wavea};mask-image:{$wavea}}
.cgs-cq-in{position:relative;z-index:3;padding:0 5vw}
.cgs-cq-row{display:flex;justify-content:space-between;align-items:flex-start;gap:clamp(24px,4vw,60px);flex-wrap:wrap;width:100%;margin-bottom:clamp(24px,3vw,36px)}
.cgs-cq-row>p{max-width:clamp(300px,34vw,470px);margin:0;padding-top:6px;font-size:clamp(15px,1.05vw,17px);line-height:1.7;color:#dbe9fb}
@media(min-width:1025px){.cgs-cq-row{display:grid;grid-template-columns:1fr 1fr;gap:clamp(28px,4vw,56px)}.cgs-cq-row>p{max-width:none}}
.cgs-cq-grid{display:grid;grid-template-columns:1fr 1fr;gap:clamp(28px,4vw,56px);align-items:stretch}
.cgs-cq-pic{position:relative;min-height:0;border-radius:clamp(18px,2vw,28px);overflow:hidden;background:#0a2748}
.cgs-cq-bg{position:absolute;inset:0;background:var(--cgs-img) center/cover no-repeat;opacity:0;transform:scale(1.05);transition:opacity .7s,transform 1.2s cubic-bezier(.2,.7,.2,1)}
.cgs-cq-bg.on{opacity:1;transform:none}
.cgs-cq-pic:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,rgba(7,26,51,.4),transparent 40%)}
.cgs-cq-chip{position:absolute;top:22px;left:22px;z-index:2;padding:8px 18px;border-radius:30px;background:rgba(255,255,255,.2);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px);font:600 .82rem Inter,sans-serif;color:#fff}
.cgs-cq-item{border-bottom:1px solid rgba(93,180,255,.28)}
.cgs-cq-item.on{border-bottom-color:var(--cgs-blue,#1e90ff)}
body.cgs-site #lqd-site-content .cgs-cq button.cgs-cq-hd{width:100%;display:flex;justify-content:space-between;align-items:center;gap:20px;padding:clamp(14px,1.3vw,18px) 0;background:none;border:0;border-radius:0;box-shadow:none;color:#fff;font:500 clamp(18px,1.6vw,22px) var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.02em;text-transform:none;text-align:left;cursor:pointer}
.cgs-cq-hd i{flex:none;position:relative;width:40px;height:40px;border-radius:50%;background:var(--cgs-blue,#1e90ff);transition:background .3s,transform .3s}
.cgs-cq-hd i:before{content:"";position:absolute;left:50%;top:50%;width:8px;height:8px;border-right:2px solid #fff;border-bottom:2px solid #fff;transform:translate(-50%,-70%) rotate(45deg);transition:transform .35s,border-color .3s}
.cgs-cq-item.on .cgs-cq-hd i:before{transform:translate(-50%,-30%) rotate(225deg)}
.cgs-cq-hd:hover i{background:#fff;transform:scale(1.08)}
.cgs-cq-hd:hover i:before{border-color:var(--cgs-navy,#071A33)}
.cgs-cq-pn{display:grid;grid-template-rows:0fr;transition:grid-template-rows .55s cubic-bezier(.2,.7,.2,1)}
.cgs-cq-item.on .cgs-cq-pn{grid-template-rows:1fr}
.cgs-cq-pi{overflow:hidden;min-height:0;display:flex;gap:18px;align-items:stretch;opacity:0;visibility:hidden;transition:opacity .5s}
.cgs-cq-item.on .cgs-cq-pi{opacity:1;visibility:visible;padding-bottom:18px}
.cgs-cq-tx{flex:1;display:flex;flex-direction:column;justify-content:space-between;gap:14px}
.cgs-cq-tx p{margin:0;max-width:380px;font-size:clamp(14px,1vw,16px);line-height:1.65;color:#b2c8e6}
.cgs-cq-th{flex:none;width:clamp(110px,10vw,140px);min-height:clamp(110px,10vw,140px);border-radius:20px;background:var(--cgs-img) center/cover no-repeat}
.cgs-cq-head{display:flex;flex-direction:column;align-items:flex-start;text-align:left}
body.cgs-site #lqd-site-content .cgs-cq h2.cgs-cq-eb{display:flex;align-items:center;justify-content:flex-start;gap:.9vw;margin:0 0 1.2vw;font:700 clamp(12px,.9vw,14px) var(--cgs-fb,Inter,sans-serif);letter-spacing:.1em;text-transform:uppercase;color:#fff}
body.cgs-site #lqd-site-content .cgs-cq h3.cgs-cq-h{margin:0;color:#fff;text-transform:capitalize}
.cgs-cq-h span+span{margin-left:.28em}
@media(min-width:1025px){.cgs-cq-h{white-space:nowrap}}
body.cgs-site #lqd-site-content .cgs-cq h3.cgs-cq-h em{font-style:normal;color:#5db4ff}
.cgs-cq .cgs-cq-btn{align-self:flex-start;display:inline-flex;align-items:center;gap:12px;margin-top:clamp(4px,.8vw,10px);font:600 clamp(13px,.95vw,15px) Inter,sans-serif;color:#fff;text-decoration:none;transition:color .3s}
.cgs-cq-btn b{display:grid;place-items:center;width:clamp(34px,2.7vw,40px);height:clamp(34px,2.7vw,40px);border-radius:50%;background:none;transition:background .3s,transform .3s}
.cgs-cq-btn b span{width:42%;height:42%;background:#fff;-webkit-mask:var(--cgs-ic) center/contain no-repeat;mask:var(--cgs-ic) center/contain no-repeat;transition:background .3s}
.cgs-cq .cgs-cq-btn:hover{color:#fff}
.cgs-cq-btn:hover b{transform:translateX(4px)}
.cgs-cq.cgs-js .cgs-rv{opacity:0}
.cgs-cq.cgs-js .cgs-rv.in{opacity:1;animation:cgsRv .8s var(--d,0s) cubic-bezier(.2,.7,.2,1) backwards}
@media(max-width:1024px){.cgs-cq-grid{grid-template-columns:1fr}.cgs-cq-pic{position:relative;top:0;min-height:clamp(260px,50vw,380px)}}
@media(max-width:767px){.cgs-cq-wv{height:36px}.cgs-cq-wv.a{height:52px}.cgs-cq-in{padding:0 6vw}body.cgs-site #lqd-site-content .cgs-cq h2.cgs-cq-eb{gap:2.4vw;margin-bottom:3vw}.cgs-cq-pi{flex-direction:column}.cgs-cq-th{width:100%;min-height:170px}}
@media(prefers-reduced-motion:reduce){.cgs-cq.cgs-js .cgs-rv{opacity:1}.cgs-cq.cgs-js .cgs-rv.in{animation:none}.cgs-cq-pn,.cgs-cq-pi,.cgs-cq-bg{transition:none}}
CSS;
}

/* ------------------------------------------------------------------
 * 15. CLQ ELEMENT — [cgs_clq], editable in WPBakery backend.
 *     Locations are a repeater (param_group): add, edit, reorder, delete rows in the builder.
 *     Page content: [vc_row full_width="stretch_row" el_class="cgs-row"][vc_column][cgs_clq][/vc_column][/vc_row]
 * ---------------------------------------------------------------- */
function cgs_clq_locations_default() {
	$img = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concept-Group-HostelBgImg1.webp';
	return array(
		array( 'name' => 'Shah Alam', 'image' => $img, 'desc' => 'Strategically located to support manufacturers and businesses across Shah Alam’s industrial areas, providing convenient access to key employment hubs.' ),
		array( 'name' => 'Telok Panglima Garang', 'image' => $img, 'desc' => 'Supporting the workforce needs of businesses in southern Klang Valley, with accommodation solutions for nearby manufacturing and industrial operations.' ),
		array( 'name' => 'Port Klang', 'image' => $img, 'desc' => 'Serving the needs of businesses around Port Klang’s industrial and logistics hubs, supporting workforces in manufacturing, warehousing and related sectors.' ),
		array( 'name' => 'Jenjarom', 'image' => $img, 'desc' => 'Expanding workforce accommodation capacity in the surrounding industrial areas, supporting employers seeking practical accommodation solutions for their workers.' ),
	);
}

function cgs_clq_defaults() {
	return array(
		'h2' => 'Centralised Labour Quarters',
		'h3' => 'Supporting Industry with Purpose-Built CLQs',
		'h3e' => 'Purpose-Built CLQs',
		'p' => 'As part of our expanding workforce infrastructure capabilities, Concepts Group is developing more than 7,000 beds across Klang Valley, supporting industrial employers and their workforce.',
		'btn_text' => 'Enquire us',
		'btn_url' => 'https://concepts.manyproject.my/contact/',
		'btn_icon' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Black-Right-Arrow.svg',
		'locations' => '',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_clq_defaults();
	vc_map( array(
		'name'     => 'Concepts CLQ Locations',
		'base'     => 'cgs_clq',
		'category' => 'Concepts Group',
		'icon'     => 'icon-wpb-row',
		'params'   => array(
			array( 'type' => 'textfield', 'heading' => 'Label (H2)', 'param_name' => 'h2', 'value' => $d['h2'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Heading (H3)', 'param_name' => 'h3', 'value' => $d['h3'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Accent words in heading (light blue)', 'param_name' => 'h3e', 'value' => $d['h3e'], 'group' => 'Content' ),
			array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'p', 'value' => $d['p'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Button text (all locations)', 'param_name' => 'btn_text', 'value' => $d['btn_text'], 'group' => 'Button' ),
			array( 'type' => 'textfield', 'heading' => 'Button link (default for all locations; each location can override it)', 'param_name' => 'btn_url', 'value' => $d['btn_url'], 'group' => 'Button' ),
			array( 'type' => 'attach_image', 'heading' => 'Button icon (SVG)', 'param_name' => 'btn_icon', 'value' => $d['btn_icon'], 'group' => 'Button' ),
			array(
				'type'        => 'param_group',
				'heading'     => 'Locations (tabs)',
				'param_name'  => 'locations',
				'group'       => 'Locations',
				'description' => 'Each row is one tab. Use the + button to add, drag to reorder, the trash icon to delete. Leave untouched to show the four defaults.',
				'value'       => rawurlencode( wp_json_encode( cgs_clq_locations_default() ) ),
				'params'      => array(
					array( 'type' => 'textfield', 'heading' => 'Location name (tab + title)', 'param_name' => 'name', 'admin_label' => true ),
					array( 'type' => 'attach_image', 'heading' => 'Image', 'param_name' => 'image' ),
					array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'desc' ),
						array( 'type' => 'textfield', 'heading' => 'Enquire button link (empty = the default link above)', 'param_name' => 'url' ),
				),
			),
		),
	) );
} );

add_shortcode( 'cgs_clq', function ( $atts ) {
	$d    = cgs_clq_defaults();
	$a    = shortcode_atts( $d, $atts, 'cgs_clq' );
	$locs = array();
	if ( '' !== trim( (string) $a['locations'] ) ) {
		$dec = json_decode( rawurldecode( $a['locations'] ), true );
		if ( is_array( $dec ) ) {
			$locs = $dec;
		}
	} else {
		$locs = cgs_clq_locations_default();
	}
	$locs = array_values( array_filter( $locs, function ( $l ) {
		return is_array( $l ) && ! empty( $l['name'] );
	} ) );
	$u = function ( $url ) {
		$url = trim( (string) $url );
		return esc_url( ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) ? home_url( $url ) : $url );
	};
	$h3  = esc_html( $a['h3'] );
	$em  = esc_html( $a['h3e'] );
	$pos = '' !== $em ? strpos( $h3, $em ) : false;
	if ( false !== $pos ) {
		$pre = trim( substr( $h3, 0, $pos ) );
		$h3  = ( '' !== $pre ? '<span>' . $pre . '</span>' : '' ) . '<span><em>' . $em . '</em></span>' . trim( substr( $h3, $pos + strlen( $em ) ) );
	}
	$icon = cgs_media_url( $a['btn_icon'], $d['btn_icon'] );
	$fallback_img = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concept-Group-HostelBgImg1.webp';
	wp_enqueue_script( 'cgs-clq' );

	ob_start(); ?>
<section class="cgs-cq" id="clq2"><div class="cgs-cq-in">
<div class="cgs-cq-head cgs-rv">
<h2 class="cgs-cq-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<div class="cgs-cq-row"><h3 class="cgs-cq-h"><?php echo $h3; ?></h3>
<p><?php echo esc_html( $a['p'] ); ?></p></div>
</div>
<div class="cgs-cq-grid cgs-rv">
<div class="cgs-cq-pic"><?php foreach ( $locs as $i => $l ) : ?><i class="cgs-cq-bg<?php echo 0 === $i ? ' on' : ''; ?>" style="--cgs-img:url('<?php echo esc_url( cgs_media_url( isset( $l['image'] ) ? $l['image'] : '', $fallback_img ) ); ?>')"></i><?php endforeach; ?><span class="cgs-cq-chip"><?php echo esc_html( isset( $locs[0] ) ? $locs[0]['name'] : '' ); ?></span></div>
<div class="cgs-cq-acc">
<?php foreach ( $locs as $i => $l ) :
	$img = cgs_media_url( isset( $l['image'] ) ? $l['image'] : '', $fallback_img ); ?>
<div class="cgs-cq-item<?php echo 0 === $i ? ' on' : ''; ?>">
<button type="button" class="cgs-cq-hd" id="cgs-cq-t<?php echo (int) $i; ?>" aria-controls="cgs-cq-p<?php echo (int) $i; ?>" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>"><span><?php echo esc_html( $l['name'] ); ?></span><i aria-hidden="true"></i></button>
<div class="cgs-cq-pn" id="cgs-cq-p<?php echo (int) $i; ?>" role="region" aria-labelledby="cgs-cq-t<?php echo (int) $i; ?>"><div class="cgs-cq-pi"><div class="cgs-cq-tx"><p><?php echo esc_html( isset( $l['desc'] ) ? $l['desc'] : '' ); ?></p><a class="cgs-cq-btn" href="<?php echo $u( ! empty( $l['url'] ) ? $l['url'] : $a['btn_url'] ); ?>"><?php echo esc_html( $a['btn_text'] ); ?><b style="--cgs-ic:url('<?php echo esc_url( $icon ); ?>')"><span></span></b></a></div><div class="cgs-cq-th" style="--cgs-img:url('<?php echo esc_url( $img ); ?>')"></div></div></div>
</div>
<?php endforeach; ?>
</div>
</div>
</div></section>
	<?php
	return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_register_script( 'cgs-clq', false, array(), CGS_VERSION, true );
	wp_add_inline_script( 'cgs-clq', "(function(){var s=document.querySelector('.cgs-cq');if(!s)return;var items=[].slice.call(s.querySelectorAll('.cgs-cq-item')),bgs=[].slice.call(s.querySelectorAll('.cgs-cq-bg')),chip=s.querySelector('.cgs-cq-chip');function set(i){items.forEach(function(it,k){var on=k===i;it.classList.toggle('on',on);it.querySelector('.cgs-cq-hd').setAttribute('aria-expanded',on)});bgs.forEach(function(b,k){b.classList.toggle('on',k===i)});if(chip)chip.textContent=items[i].querySelector('.cgs-cq-hd span').textContent}items.forEach(function(it,i){it.querySelector('.cgs-cq-hd').addEventListener('click',function(){set(i)})});if(matchMedia('(prefers-reduced-motion:reduce)').matches||!('IntersectionObserver' in window))return;s.classList.add('cgs-js');var rv=s.querySelectorAll('.cgs-rv'),io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}})},{threshold:.1});rv.forEach(function(el){io.observe(el)})})();" );
}, 33 );


/* ------------------------------------------------------------------
 * 16. PROCESS CSS — How it works: photo background + 4 glass cards
 * ---------------------------------------------------------------- */
function cgs_process_css() {
	return <<<'CSS'
.cgs-pr{position:relative;overflow:hidden;display:flex;background:var(--cgs-navy,#071A33);color:#fff;padding:clamp(70px,7vw,110px) 0 clamp(48px,4vw,60px);font-family:var(--cgs-fb,Inter,sans-serif)}
.cgs-pr *{box-sizing:border-box}
.cgs-pr-bg{position:absolute;inset:-6% 0;z-index:0;background:var(--cgs-img) center/cover no-repeat;will-change:transform}
.cgs-pr:before{content:"";position:absolute;inset:0;z-index:1;background:rgba(7,26,51,.9)}
.cgs-pr-in{position:relative;z-index:2;display:flex;flex-direction:column;justify-content:center;gap:clamp(28px,3vw,44px);width:100%;padding:0 5vw}
.cgs-pr-btn{display:inline-flex;align-items:center;gap:10px;padding:14px 28px;border-radius:50px;background:#fff;color:var(--cgs-navy,#071A33);font:600 .92rem var(--cgs-fb,Inter,sans-serif);text-decoration:none;white-space:nowrap;transition:background .3s,color .3s}
.cgs-pr-btn:after{content:"\2192";transition:transform .3s}
.cgs-pr-btn:hover{background:var(--cgs-blue,#1e90ff);color:#fff}.cgs-pr-btn:hover:after{transform:translateX(4px)}
.cgs-pr-head{display:flex;justify-content:space-between;align-items:flex-end;gap:24px 40px;flex-wrap:wrap}
body.cgs-site #lqd-site-content .cgs-pr h2.cgs-pr-eb{display:flex;align-items:center;justify-content:flex-start;gap:.9vw;margin:0 0 1.2vw;font:700 clamp(12px,.9vw,14px) var(--cgs-fb,Inter,sans-serif);letter-spacing:.1em;text-transform:uppercase;color:#fff}
body.cgs-site #lqd-site-content .cgs-pr h3.cgs-pr-h{margin:0;color:#fff;text-transform:capitalize}
.cgs-pr-h span+span{margin-left:.28em}
@media(min-width:768px){.cgs-pr-h{white-space:nowrap}}
body.cgs-site #lqd-site-content .cgs-pr h3.cgs-pr-h em{font-style:normal;color:var(--cgs-blue,#91c9e2)}
.cgs-pr-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:clamp(8px,.7vw,10px)}
.cgs-pr-card{display:flex;flex-direction:column;align-items:center;text-align:center;min-height:clamp(200px,17vw,250px);padding:clamp(22px,2vw,28px) clamp(20px,1.9vw,26px) clamp(24px,2.1vw,30px);background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.28);border-radius:clamp(18px,1.8vw,24px);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px);transition:background .35s,border-color .35s,transform .35s}
.cgs-pr-card:hover{background:#fff;border-color:#fff;transform:translateY(-6px)}
.cgs-pr-num{display:grid;place-items:center;width:clamp(48px,4vw,56px);height:clamp(48px,4vw,56px);border-radius:50%;background:var(--cgs-blue,#91c9e2);color:#fff;font:700 clamp(16px,1.2vw,18px) var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.02em}
body.cgs-site #lqd-site-content .cgs-pr h4.cgs-pr-t{margin:clamp(22px,2.4vw,34px) 0 8px;color:#fff;transition:color .35s}
.cgs-pr-card p{margin:0;font-size:clamp(14px,.95vw,15px);line-height:1.55;color:#d4e3f6;transition:color .35s}
body.cgs-site #lqd-site-content .cgs-pr-card:hover h4.cgs-pr-t{color:var(--cgs-navy,#071A33)}
.cgs-pr-card:hover p{color:var(--cgs-mute,#5d6d84)}
.cgs-pr.cgs-js .cgs-rv{opacity:0}
.cgs-pr.cgs-js .cgs-rv.in{opacity:1;animation:cgsRv .8s var(--d,0s) cubic-bezier(.2,.7,.2,1) backwards}
@media(max-width:1024px){.cgs-pr{}.cgs-pr-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.cgs-pr-card{min-height:0}}
@media(max-width:767px){.cgs-pr-in{padding:0 6vw}body.cgs-site #lqd-site-content .cgs-pr h2.cgs-pr-eb{gap:2.4vw;margin-bottom:3vw}.cgs-pr-grid{grid-template-columns:1fr}body.cgs-site #lqd-site-content .cgs-pr h4.cgs-pr-t{margin-top:18px}}
@media(prefers-reduced-motion:reduce){.cgs-pr.cgs-js .cgs-rv{opacity:1}.cgs-pr.cgs-js .cgs-rv.in{animation:none}.cgs-pr-card{transition:none}}
CSS;
}

/* ------------------------------------------------------------------
 * 17. PROCESS ELEMENT — [cgs_process], editable in WPBakery backend.
 *     Steps are a repeater (param_group); numbers are generated from the row order.
 *     Page content: [vc_row full_width="stretch_row" el_class="cgs-row"][vc_column][cgs_process][/vc_column][/vc_row]
 * ---------------------------------------------------------------- */
function cgs_process_steps_default() {
	return array(
		array( 'title' => 'Understand', 'desc' => 'Identify your workforce needs and business requirements.' ),
		array( 'title' => 'Recruit', 'desc' => 'Source and deploy suitable local and foreign workers.' ),
		array( 'title' => 'Support', 'desc' => 'Manage payroll, documentation, transport and accommodation.' ),
		array( 'title' => 'Manage', 'desc' => 'Provide ongoing workforce administration and welfare support.' ),
	);
}

function cgs_process_defaults() {
	return array(
		'h2' => 'How it works',
		'h3' => 'Our integrated workforce approach',
		'h3e' => 'workforce approach',
		'bg' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Concepts-Group-hostel-dormitory-beds-arranged-dorm-room.webp',
		'btn' => 'Explore our solutions',
		'btnu' => '/#services',
		'steps' => '',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_process_defaults();
	vc_map( array(
		'name'     => 'Concepts Process',
		'base'     => 'cgs_process',
		'category' => 'Concepts Group',
		'icon'     => 'icon-wpb-row',
		'params'   => array(
			array( 'type' => 'textfield', 'heading' => 'Label (H2)', 'param_name' => 'h2', 'value' => $d['h2'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Heading (H3)', 'param_name' => 'h3', 'value' => $d['h3'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Accent words in heading', 'param_name' => 'h3e', 'value' => $d['h3e'], 'group' => 'Content' ),
			array( 'type' => 'attach_image', 'heading' => 'Background image', 'param_name' => 'bg', 'value' => $d['bg'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Button text (empty hides it)', 'param_name' => 'btn', 'value' => $d['btn'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Button link', 'param_name' => 'btnu', 'value' => $d['btnu'], 'group' => 'Content' ),
			array(
				'type'        => 'param_group',
				'heading'     => 'Steps (numbered automatically)',
				'param_name'  => 'steps',
				'group'       => 'Steps',
				'description' => 'Each row is one column. Use + to add, drag to reorder, the trash icon to delete. Leave untouched to show the four defaults.',
				'value'       => rawurlencode( wp_json_encode( cgs_process_steps_default() ) ),
				'params'      => array(
					array( 'type' => 'textfield', 'heading' => 'Title (H4)', 'param_name' => 'title', 'admin_label' => true ),
					array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'desc' ),
				),
			),
		),
	) );
} );

add_shortcode( 'cgs_process', function ( $atts ) {
	$d     = cgs_process_defaults();
	$a     = shortcode_atts( $d, $atts, 'cgs_process' );
	$steps = cgs_process_steps_default();
	if ( '' !== trim( (string) $a['steps'] ) ) {
		$dec   = json_decode( rawurldecode( $a['steps'] ), true );
		$steps = is_array( $dec ) ? $dec : array();
	}
	$steps = array_values( array_filter( $steps, function ( $s ) {
		return is_array( $s ) && ! empty( $s['title'] );
	} ) );
	$h3  = esc_html( $a['h3'] );
	$em  = esc_html( $a['h3e'] );
	$pos = '' !== $em ? strpos( $h3, $em ) : false;
	if ( false !== $pos ) {
		$pre = trim( substr( $h3, 0, $pos ) );
		$h3  = ( '' !== $pre ? '<span>' . $pre . '</span>' : '' ) . '<span><em>' . $em . '</em></span>' . trim( substr( $h3, $pos + strlen( $em ) ) );
	}
	wp_enqueue_script( 'cgs-process' );

	ob_start(); ?>
<section class="cgs-pr" id="process" style="--cgs-img:url('<?php echo esc_url( cgs_media_url( $a['bg'], $d['bg'] ) ); ?>')"><div class="cgs-pr-bg" data-par="-.08"></div><div class="cgs-pr-in">
<div class="cgs-pr-head cgs-rv">
<div><h2 class="cgs-pr-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-pr-h"><?php echo $h3; ?></h3></div>
<?php if ( '' !== trim( (string) $a['btn'] ) ) : ?><a class="cgs-pr-btn" href="<?php echo esc_url( home_url( $a['btnu'] ) ); ?>"><?php echo esc_html( $a['btn'] ); ?></a><?php endif; ?>
</div>
<div class="cgs-pr-grid">
<?php foreach ( $steps as $i => $s ) : ?>
<article class="cgs-pr-card cgs-rv" style="--d:<?php echo esc_attr( $i * 0.1 ); ?>s"><b class="cgs-pr-num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></b><h4 class="cgs-pr-t"><?php echo esc_html( $s['title'] ); ?></h4><p><?php echo esc_html( isset( $s['desc'] ) ? $s['desc'] : '' ); ?></p></article>
<?php endforeach; ?>
</div>
</div></section>
	<?php
	return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_register_script( 'cgs-process', false, array(), CGS_VERSION, true );
	wp_add_inline_script( 'cgs-process', "(function(){var s=document.querySelector('.cgs-pr');if(!s||matchMedia('(prefers-reduced-motion:reduce)').matches)return;var bg=s.querySelector('.cgs-pr-bg'),t=0;function par(){t=0;var r=s.getBoundingClientRect();if(r.bottom<0||r.top>innerHeight)return;bg.style.transform='translate3d(0,'+((r.top+r.height/2-innerHeight/2)*-.08).toFixed(1)+'px,0)'}addEventListener('scroll',function(){if(!t)t=requestAnimationFrame(par)},{passive:true});par();if(!('IntersectionObserver' in window))return;s.classList.add('cgs-js');var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}})},{threshold:.1});s.querySelectorAll('.cgs-rv').forEach(function(el){io.observe(el)})})();" );
}, 34 );

/* ------------------------------------------------------------------
 * 18. FAQ CSS — outer section, white box, navy intro card + accordion
 * ---------------------------------------------------------------- */
function cgs_faq_css() {
	return <<<'CSS'
.cgs-fq{background:#eaf4ff;padding:clamp(60px,7vw,100px) 0;font-family:var(--cgs-fb,Inter,sans-serif);color:var(--cgs-ink,#14243b)}
.cgs-fq *{box-sizing:border-box}
.cgs-fq-wrap{padding:0 5vw}
.cgs-fq-box{display:grid;grid-template-columns:.9fr 1.1fr;gap:clamp(24px,3.6vw,48px);padding:clamp(18px,2.5vw,34px);background:#fff;border-radius:clamp(22px,2.5vw,34px);box-shadow:0 40px 70px -48px rgba(11,95,196,.45)}
.cgs-fq-card{background:var(--cgs-navy,#071A33);color:#fff;border-radius:clamp(18px,1.9vw,26px);padding:clamp(28px,3vw,42px) clamp(22px,2.5vw,34px);min-height:clamp(320px,34vw,480px);display:flex;flex-direction:column;justify-content:space-between;gap:clamp(28px,3vw,48px)}
.cgs-fq .cgs-fq-btn{display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:clamp(14px,1.4vw,19px);border-radius:50px;background:var(--cgs-blue,#1e90ff);color:#fff;font:700 clamp(14px,1vw,16px) Inter,sans-serif;text-decoration:none;transition:background .3s,color .3s}
.cgs-fq-btn:after{content:"\2192";transition:transform .3s}
.cgs-fq .cgs-fq-btn:hover{background:#fff;color:var(--cgs-navy,#071A33)}
.cgs-fq-btn:hover:after{transform:translateX(5px)}
body.cgs-site #lqd-site-content .cgs-fq h2.cgs-fq-eb{display:flex;align-items:center;gap:.9vw;margin:0 0 1.2vw;font:700 clamp(12px,.9vw,14px) var(--cgs-fb,Inter,sans-serif);letter-spacing:.1em;text-transform:uppercase;color:#fff}
body.cgs-site #lqd-site-content .cgs-fq h3.cgs-fq-h{margin:0;color:#fff;text-transform:none;overflow-wrap:anywhere}
.cgs-fq-h span{display:block}
body.cgs-site #lqd-site-content .cgs-fq h3.cgs-fq-h em{font-style:normal;color:#5db4ff}
.cgs-fq-card p{margin:clamp(16px,1.6vw,22px) 0 0;max-width:none;font-size:clamp(14px,1vw,16px);line-height:1.7;color:#b2c8e6}
.cgs-fq-list{display:flex;flex-direction:column;padding:6px 10px 6px 0}
.cgs-fq-list details{border-bottom:1px solid #e1ebf6}
.cgs-fq-list details:last-child{border:0}
.cgs-fq-list summary{list-style:none;cursor:pointer;display:flex;align-items:center;gap:clamp(14px,1.5vw,20px);padding:clamp(16px,1.5vw,20px) 0}
.cgs-fq-list summary::-webkit-details-marker{display:none}
.cgs-fq-list summary:focus-visible{outline:2px solid var(--cgs-blue,#1e90ff);outline-offset:2px;border-radius:12px}
.cgs-fq-num{flex:none;display:grid;place-items:center;width:clamp(44px,4vw,54px);height:clamp(44px,4vw,54px);border-radius:clamp(12px,1.2vw,16px);background:#e6f1ff;color:var(--cgs-blue-d,#0b6fd1);font:700 clamp(14px,1.05vw,17px) var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.02em;transition:background .3s,color .3s}
.cgs-fq-q{flex:1;font:600 clamp(16px,1.15vw,18px)/1.3 var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.02em;color:var(--cgs-navy,#071A33)}
.cgs-fq-ic{flex:none;display:grid;place-items:center;width:clamp(32px,2.7vw,36px);height:clamp(32px,2.7vw,36px);border-radius:50%;background:var(--cgs-soft,#f3f6fa);transition:background .3s}
.cgs-fq-ic span{width:44%;height:44%;background:var(--cgs-blue,#1e90ff);-webkit-mask:var(--cgs-fq-add) center/contain no-repeat;mask:var(--cgs-fq-add) center/contain no-repeat;transition:background .3s}
.cgs-fq-list details[open] .cgs-fq-ic{background:var(--cgs-blue,#1e90ff)}
.cgs-fq-list details[open] .cgs-fq-ic span{background:#fff;-webkit-mask-image:var(--cgs-fq-minus);mask-image:var(--cgs-fq-minus)}
.cgs-fq-list details[open] .cgs-fq-num,.cgs-fq-list summary:hover .cgs-fq-num{background:var(--cgs-blue,#1e90ff);color:#fff}
.cgs-fq-list details p{margin:0;padding:0 clamp(12px,3.6vw,56px) clamp(18px,1.7vw,24px) clamp(58px,5.5vw,74px);font-size:clamp(14px,1vw,16px);line-height:1.7;color:var(--cgs-mute,#5d6d84);animation:cgsRv .5s cubic-bezier(.2,.7,.2,1)}
.cgs-fq.cgs-js .cgs-rv{opacity:0}
.cgs-fq.cgs-js .cgs-rv.in{opacity:1;animation:cgsRv .8s cubic-bezier(.2,.7,.2,1) backwards}
@media(max-width:1024px){.cgs-fq-box{grid-template-columns:1fr}.cgs-fq-card{min-height:0}.cgs-fq-card p{max-width:none}.cgs-fq-list{padding:0 8px 8px}}
@media(max-width:767px){.cgs-fq-wrap{padding:0 6vw}body.cgs-site #lqd-site-content .cgs-fq h2.cgs-fq-eb{gap:2.4vw;margin-bottom:3vw}.cgs-fq-list{padding:0 4px 4px}.cgs-fq-list details p{padding:0 8px 20px 58px}}
@media(prefers-reduced-motion:reduce){.cgs-fq.cgs-js .cgs-rv{opacity:1}.cgs-fq.cgs-js .cgs-rv.in,.cgs-fq-list details p{animation:none}}
CSS;
}

/* ------------------------------------------------------------------
 * 19. FAQ ELEMENT — [cgs_faq], editable in WPBakery backend.
 *     Questions are a repeater (param_group): add, edit, reorder, delete rows.
 *     Page content: [vc_row full_width="stretch_row" el_class="cgs-row"][vc_column][cgs_faq][/vc_column][/vc_row]
 * ---------------------------------------------------------------- */
function cgs_faq_items_default() {
	return array(
		array( 'q' => 'What is our role as a worker supply agency in Malaysia?', 'a' => 'A Manpower Agency is a Total HR Provider that recruits and manages general workers on behalf of employers. Manpower agencies provide you with a wide range of workers that are suitable for your business production while saving on cost and time.' ),
		array( 'q' => 'Why is it important to plan on your manpower intake?', 'a' => 'To meet the eligibility of quota application, for instance, by planning how many workers would you require in order to achieve your targeted output to save on the cost which ranges from (workers’ hostel, transport, salary etc).' ),
		array( 'q' => 'How to recruit through manpower supplier in Malaysia?', 'a' => 'Firstly, employers must undergo a quota application assessment to check their eligibility for foreign workers recruitment. Followed by quota application and coordination with Source Countries partners pertaining to documentation. For more information on these procedures, contact us.' ),
		array( 'q' => 'Why should you choose us as your worker supply & management agency?', 'a' => 'We hold 31 years of expertise in manpower recruitment for factories and provide job opportunities for global citizens making us one of the most reliable manpower agency in Malaysia. We have partners at Source Countries which contribute to our high success rate in recruitment.' ),
		array( 'q' => 'Which industries do you support?', 'a' => 'We support businesses across manufacturing, semiconductor, automotive, healthcare, plantation, construction, services and other labour-intensive industries.' ),
	);
}

function cgs_faq_defaults() {
	$b = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		'h2' => 'FAQ',
		'h3' => 'Frequently asked questions',
		'h3e' => 'questions',
		'p' => 'Find answers to common questions about our recruitment, workforce management and accommodation services to help you understand how Concepts Group can support your business needs.',
		'icon_add' => $b . 'add.svg',
		'icon_minus' => $b . 'minus-sign.svg',
		'btn_text' => 'Talk to our team',
		'btn_url' => '/#contact',
		'items' => '',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_faq_defaults();
	vc_map( array(
		'name'     => 'Concepts FAQ',
		'base'     => 'cgs_faq',
		'category' => 'Concepts Group',
		'icon'     => 'icon-wpb-row',
		'params'   => array(
			array( 'type' => 'textfield', 'heading' => 'Label (H2)', 'param_name' => 'h2', 'value' => $d['h2'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Heading (H3)', 'param_name' => 'h3', 'value' => $d['h3'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Accent words in heading (light blue)', 'param_name' => 'h3e', 'value' => $d['h3e'], 'group' => 'Content' ),
			array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'p', 'value' => $d['p'], 'group' => 'Content' ),
			array( 'type' => 'attach_image', 'heading' => 'Expand icon (SVG)', 'param_name' => 'icon_add', 'value' => $d['icon_add'], 'group' => 'Icons' ),
			array( 'type' => 'attach_image', 'heading' => 'Collapse icon (SVG)', 'param_name' => 'icon_minus', 'value' => $d['icon_minus'], 'group' => 'Icons' ),
			array( 'type' => 'textfield', 'heading' => 'Button text (empty = hide button)', 'param_name' => 'btn_text', 'value' => $d['btn_text'], 'group' => 'Button' ),
			array( 'type' => 'textfield', 'heading' => 'Button link', 'param_name' => 'btn_url', 'value' => $d['btn_url'], 'group' => 'Button' ),
			array(
				'type'        => 'param_group',
				'heading'     => 'Questions (numbered automatically)',
				'param_name'  => 'items',
				'group'       => 'Questions',
				'description' => 'Each row is one accordion item. Use + to add, drag to reorder, the trash icon to delete. Leave untouched to show the five defaults.',
				'value'       => rawurlencode( wp_json_encode( cgs_faq_items_default() ) ),
				'params'      => array(
					array( 'type' => 'textfield', 'heading' => 'Question', 'param_name' => 'q', 'admin_label' => true ),
					array( 'type' => 'textarea', 'heading' => 'Answer', 'param_name' => 'a' ),
				),
			),
		),
	) );
} );

add_shortcode( 'cgs_faq', function ( $atts ) {
	$d     = cgs_faq_defaults();
	$a     = shortcode_atts( $d, $atts, 'cgs_faq' );
	$items = cgs_faq_items_default();
	if ( '' !== trim( (string) $a['items'] ) ) {
		$dec   = json_decode( rawurldecode( $a['items'] ), true );
		$items = is_array( $dec ) ? $dec : array();
	}
	$items = array_values( array_filter( $items, function ( $i ) {
		return is_array( $i ) && ! empty( $i['q'] );
	} ) );
	$h3  = esc_html( $a['h3'] );
	$em  = esc_html( $a['h3e'] );
	$pos = '' !== $em ? strpos( $h3, $em ) : false;
	if ( false !== $pos ) {
		$pre = trim( substr( $h3, 0, $pos ) );
		$h3  = ( '' !== $pre ? '<span>' . $pre . '</span>' : '' ) . '<span><em>' . $em . '</em></span>' . trim( substr( $h3, $pos + strlen( $em ) ) );
	}
	$add   = cgs_media_url( $a['icon_add'], $d['icon_add'] );
	$minus = cgs_media_url( $a['icon_minus'], $d['icon_minus'] );
	wp_enqueue_script( 'cgs-faq' );

	ob_start(); ?>
<section class="cgs-fq" id="faq" style="--cgs-fq-add:url('<?php echo esc_url( $add ); ?>');--cgs-fq-minus:url('<?php echo esc_url( $minus ); ?>')"><div class="cgs-fq-wrap"><div class="cgs-fq-box cgs-rv">
<div class="cgs-fq-card">
<div class="cgs-fq-txt">
<h2 class="cgs-fq-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-fq-h"><?php echo $h3; ?></h3>
<p><?php echo esc_html( $a['p'] ); ?></p>
</div>
<?php if ( '' !== trim( $a['btn_text'] ) ) : ?><a class="cgs-fq-btn" href="<?php echo esc_url( ( 0 === strpos( trim( $a['btn_url'] ), '/' ) && 0 !== strpos( trim( $a['btn_url'] ), '//' ) ) ? home_url( trim( $a['btn_url'] ) ) : trim( $a['btn_url'] ) ); ?>"><?php echo esc_html( $a['btn_text'] ); ?></a><?php endif; ?>
</div>
<div class="cgs-fq-list">
<?php foreach ( $items as $i => $it ) : ?>
<details name="cgs-faq"<?php echo 0 === $i ? ' open' : ''; ?>><summary><b class="cgs-fq-num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></b><span class="cgs-fq-q"><?php echo esc_html( $it['q'] ); ?></span><i class="cgs-fq-ic" aria-hidden="true"><span></span></i></summary><p><?php echo esc_html( isset( $it['a'] ) ? $it['a'] : '' ); ?></p></details>
<?php endforeach; ?>
</div>
</div></div></section>
	<?php
	return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_register_script( 'cgs-faq', false, array(), CGS_VERSION, true );
	wp_add_inline_script( 'cgs-faq', "(function(){var s=document.querySelector('.cgs-fq');if(!s)return;var ds=[].slice.call(s.querySelectorAll('details'));ds.forEach(function(d){d.addEventListener('toggle',function(){if(d.open)ds.forEach(function(o){if(o!==d)o.open=false})})});if(matchMedia('(prefers-reduced-motion:reduce)').matches||!('IntersectionObserver' in window))return;s.classList.add('cgs-js');var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}})},{threshold:.08});s.querySelectorAll('.cgs-rv').forEach(function(el){io.observe(el)})})();" );
}, 35 );

/* ------------------------------------------------------------------
 * 20. CTA CSS — photo background, bottom gradient, centred content
 * ---------------------------------------------------------------- */
function cgs_cta_css() {
	$wave  = "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 64' preserveAspectRatio='none'%3E%3Cpath d='M0 0H1440V32C1230 70 990 4 720 30S250 62 0 26Z'/%3E%3C/svg%3E\")";
	$wavea = "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 94' preserveAspectRatio='none'%3E%3Cpath d='M0 0H1440V40C1200 98 940 28 680 66S220 96 0 44Z'/%3E%3C/svg%3E\")";
	$phone = "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23000' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z'/%3E%3C/svg%3E\")";
	return <<<CSS
.cgs-ct{position:relative;overflow:hidden;display:flex;align-items:center;min-height:clamp(460px,44vw,620px);background:var(--cgs-navy,#071A33);color:#fff;font-family:var(--cgs-fb,Inter,sans-serif);--cgs-ct-ph:{$phone}}
.cgs-ct *{box-sizing:border-box}
.cgs-ct-bg{position:absolute;inset:0;z-index:0;background:var(--cgs-img) center 18%/cover no-repeat fixed}
.cgs-ct:before{content:"";position:absolute;inset:0;z-index:1;background:var(--cgs-ov,rgba(16,26,74,.78))}
.cgs-ct:after{content:"";position:absolute;left:0;right:0;bottom:0;height:clamp(160px,24vw,320px);z-index:2;background:linear-gradient(180deg,rgba(7,26,51,0) 0%,rgba(7,26,51,.55) 55%,var(--cgs-navy,#071A33) 96%);pointer-events:none}
.cgs-ct-wv{position:absolute;left:0;right:0;top:-1px;height:64px;background:var(--c);pointer-events:none;z-index:3;transform:scaleX(-1);-webkit-mask:{$wave} center/100% 100% no-repeat;mask:{$wave} center/100% 100% no-repeat}
.cgs-ct-wv.a{height:94px;z-index:2;-webkit-mask-image:{$wavea};mask-image:{$wavea}}
.cgs-ct-in{position:relative;z-index:4;width:100%;display:flex;flex-direction:column;align-items:center;text-align:center;padding:clamp(140px,13.5vw,190px) 5vw clamp(64px,6vw,96px)}
body.cgs-site #lqd-site-content .cgs-ct h3.cgs-ct-h{margin:0;color:#fff;text-transform:capitalize}
.cgs-ct-h span{display:block}
body.cgs-site #lqd-site-content .cgs-ct h3.cgs-ct-h em{font-style:normal;color:#5db4ff}
.cgs-ct-in>p{max-width:min(620px,90%);margin:clamp(16px,1.6vw,22px) auto clamp(24px,2.5vw,34px);font-size:clamp(15px,1.1vw,17px);line-height:1.7;color:#d4e3f6}
.cgs-ct-btns{display:flex;justify-content:center;gap:12px;flex-wrap:wrap}
.cgs-ct .cgs-ct-b{display:inline-flex;align-items:center;gap:12px;padding:clamp(13px,1.1vw,15px) clamp(22px,2vw,28px);border-radius:50px;font:600 clamp(14px,1vw,15px) Inter,sans-serif;text-decoration:none;transition:background .3s,color .3s,border-color .3s}
.cgs-ct-b i{display:block;width:clamp(16px,1.2vw,18px);height:clamp(16px,1.2vw,18px);background:currentColor;-webkit-mask:var(--cgs-ic) center/contain no-repeat;mask:var(--cgs-ic) center/contain no-repeat;transition:transform .3s}
.cgs-ct-b.ph i{-webkit-mask-image:var(--cgs-ct-ph);mask-image:var(--cgs-ct-ph)}
.cgs-ct .cgs-ct-b1{background:#fff;color:var(--cgs-navy,#071A33);border:1px solid #fff}
.cgs-ct .cgs-ct-b1:hover{background:var(--cgs-blue,#1e90ff);border-color:var(--cgs-blue,#1e90ff);color:#fff}
.cgs-ct .cgs-ct-b2{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.45);color:#fff;-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px)}
.cgs-ct .cgs-ct-b2:hover{background:#fff;color:var(--cgs-navy,#071A33)}
.cgs-ct .cgs-ct-b:hover i{transform:translateX(3px)}
.cgs-ct.cgs-js .cgs-rv{opacity:0}
.cgs-ct.cgs-js .cgs-rv.in{opacity:1;animation:cgsRv .8s var(--d,0s) cubic-bezier(.2,.7,.2,1) backwards}
@media(max-width:1024px){.cgs-ct-in>p{max-width:min(620px,80%)}}
@media(max-width:767px){.cgs-ct{min-height:clamp(480px,120vw,560px)}.cgs-ct-bg{background-position:center center}.cgs-ct-wv{height:36px}.cgs-ct-wv.a{height:52px}.cgs-ct-in{padding:128px 6vw 56px}.cgs-ct-in>p{max-width:none}.cgs-ct-btns{width:100%;flex-direction:column}.cgs-ct .cgs-ct-b{justify-content:center;width:100%}}
@media(prefers-reduced-motion:reduce){.cgs-ct.cgs-js .cgs-rv{opacity:1}.cgs-ct.cgs-js .cgs-rv.in{animation:none}}
CSS;
}

/* ------------------------------------------------------------------
 * 21. CTA ELEMENT — [cgs_cta], editable in WPBakery backend.
 *     Page content: [vc_row full_width="stretch_row" el_class="cgs-row"][vc_column][cgs_cta][/vc_column][/vc_row]
 *     Defaults reuse the site's existing links: the WhatsApp link from the footer, the phone number from the header.
 * ---------------------------------------------------------------- */
function cgs_cta_defaults() {
	$b = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		'h3' => 'Let us support your workforce needs',
		'h3e' => 'workforce needs',
		'p' => 'Whether you need reliable manpower, workforce management support or accommodation for your employees, Concepts Group can help you build a workforce solution around your business.',
		'bg' => $b . 'Concepts-Group_e41orq.webp',
		'b1' => 'WhatsApp us',
		'u1' => 'https://api.whatsapp.com/send?phone=60178873361&text=Hello%20there,%20I%20would%20like%20to%20know%20about%20your%20services',
		'i1' => $b . 'Black-Right-Arrow.svg',
		'b2' => 'Talk to our specialists',
		'u2' => 'tel:+60333187773',
		'i2' => $b . 'Black-Right-Arrow.svg',
		'ov_color' => '#101a4a',
		'ov_opacity' => '78',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_cta_defaults();
	vc_map( array(
		'name'     => 'Concepts CTA',
		'base'     => 'cgs_cta',
		'category' => 'Concepts Group',
		'icon'     => 'icon-wpb-row',
		'params'   => array(
			array( 'type' => 'textfield', 'heading' => 'Heading (H3)', 'param_name' => 'h3', 'value' => $d['h3'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Accent words in heading (light blue)', 'param_name' => 'h3e', 'value' => $d['h3e'], 'group' => 'Content' ),
			array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'p', 'value' => $d['p'], 'group' => 'Content' ),
			array( 'type' => 'attach_image', 'heading' => 'Background image', 'param_name' => 'bg', 'value' => $d['bg'], 'group' => 'Content' ),
			array( 'type' => 'textfield', 'heading' => 'Button 1 text', 'param_name' => 'b1', 'value' => $d['b1'], 'group' => 'Button 1' ),
			array( 'type' => 'textfield', 'heading' => 'Button 1 link', 'param_name' => 'u1', 'value' => $d['u1'], 'group' => 'Button 1' ),
			array( 'type' => 'attach_image', 'heading' => 'Button 1 icon (SVG)', 'param_name' => 'i1', 'value' => $d['i1'], 'group' => 'Button 1' ),
			array( 'type' => 'textfield', 'heading' => 'Button 2 text', 'param_name' => 'b2', 'value' => $d['b2'], 'group' => 'Button 2' ),
			array( 'type' => 'textfield', 'heading' => 'Button 2 link', 'param_name' => 'u2', 'value' => $d['u2'], 'group' => 'Button 2' ),
			array( 'type' => 'attach_image', 'heading' => 'Button 2 icon (SVG)', 'param_name' => 'i2', 'value' => $d['i2'], 'group' => 'Button 2' ),
			array( 'type' => 'colorpicker', 'heading' => 'Overlay colour', 'param_name' => 'ov_color', 'value' => $d['ov_color'], 'group' => 'Overlay' ),
			array( 'type' => 'textfield', 'heading' => 'Overlay opacity (0 = clear, 100 = solid)', 'param_name' => 'ov_opacity', 'value' => $d['ov_opacity'], 'description' => 'Number from 0 to 100.', 'group' => 'Overlay' ),
		),
	) );
} );

add_shortcode( 'cgs_cta', function ( $atts ) {
	$d = cgs_cta_defaults();
	$a = shortcode_atts( $d, $atts, 'cgs_cta' );
	$u = function ( $url ) {
		$url = trim( (string) $url );
		return esc_url( ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) ? home_url( $url ) : $url, array( 'http', 'https', 'tel', 'mailto' ) );
	};
	$h3  = esc_html( $a['h3'] );
	$em  = esc_html( $a['h3e'] );
	$pos = '' !== $em ? strpos( $h3, $em ) : false;
	if ( false !== $pos ) {
		$pre = trim( substr( $h3, 0, $pos ) );
		$h3  = ( '' !== $pre ? '<span>' . $pre . '</span>' : '' ) . '<span><em>' . $em . '</em></span>' . trim( substr( $h3, $pos + strlen( $em ) ) );
	}
	$i1  = cgs_media_url( $a['i1'], $d['i1'] );
	$i2  = cgs_media_url( $a['i2'], $d['i2'] );
	$hex = ltrim( trim( (string) $a['ov_color'] ), '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( ! preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		$hex = ltrim( $d['ov_color'], '#' );
	}
	$ov = sprintf( 'rgba(%d,%d,%d,%s)', hexdec( substr( $hex, 0, 2 ) ), hexdec( substr( $hex, 2, 2 ) ), hexdec( substr( $hex, 4, 2 ) ), round( max( 0, min( 100, (float) $a['ov_opacity'] ) ) / 100, 2 ) );
	$ext = function ( $url ) {
		return 0 === strpos( trim( $url ), 'http' ) ? ' target="_blank" rel="noopener"' : '';
	};
	wp_enqueue_script( 'cgs-cta' );

	ob_start(); ?>
<section class="cgs-ct" id="contact" style="--cgs-img:url('<?php echo esc_url( cgs_media_url( $a['bg'], $d['bg'] ) ); ?>');--cgs-ov:<?php echo esc_attr( $ov ); ?>"><div class="cgs-ct-bg"></div><div class="cgs-ct-in">
<h3 class="cgs-ct-h cgs-rv"><?php echo $h3; ?></h3>
<p class="cgs-rv" style="--d:.1s"><?php echo esc_html( $a['p'] ); ?></p>
<div class="cgs-ct-btns cgs-rv" style="--d:.2s">
<a class="cgs-ct-b cgs-ct-b1" href="<?php echo $u( $a['u1'] ); ?>"<?php echo $ext( $a['u1'] ); ?>><?php echo esc_html( $a['b1'] ); ?><i style="--cgs-ic:url('<?php echo esc_url( $i1 ); ?>')"></i></a>
<a class="cgs-ct-b cgs-ct-b2<?php echo '' === $i2 ? ' ph' : ''; ?>" href="<?php echo $u( $a['u2'] ); ?>"<?php echo $ext( $a['u2'] ); ?>><?php echo esc_html( $a['b2'] ); ?><i<?php echo '' !== $i2 ? ' style="--cgs-ic:url(\'' . esc_url( $i2 ) . '\')"' : ''; ?>></i></a>
</div>
</div></section>
	<?php
	return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_register_script( 'cgs-cta', false, array(), CGS_VERSION, true );
	wp_add_inline_script( 'cgs-cta', "(function(){var s=document.querySelector('.cgs-ct');if(!s||matchMedia('(prefers-reduced-motion:reduce)').matches||!('IntersectionObserver' in window))return;s.classList.add('cgs-js');var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}})},{threshold:.1});s.querySelectorAll('.cgs-rv').forEach(function(el){io.observe(el)})})();" );
}, 36 );


/* ------------------------------------------------------------------
 * 22. STATS CSS — solid dark band directly under the hero (the hero wave flows into it)
 * ---------------------------------------------------------------- */
function cgs_stats_css() {
	return <<<'CSS'
.cgs-st{position:relative;background:var(--cgs-navy,#071A33);padding:6px 5vw 44px;font-family:var(--cgs-fb,Inter,sans-serif)}
.cgs-st *{box-sizing:border-box}
.cgs-st-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr))}
.cgs-st-item{text-align:center;padding:12px 20px;border-right:1px solid rgba(255,255,255,.2)}
.cgs-st-item:last-child{border-right:0}
.cgs-st-n{display:block;font:700 clamp(1.9rem,3vw,2.6rem)/1.05 var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.03em;color:#fff;font-variant-numeric:tabular-nums}
.cgs-st-l{display:block;margin-top:8px;font-size:.74rem;letter-spacing:.08em;text-transform:uppercase;color:#bfdcff}
.cgs-st-l:before{content:"";display:block;width:28px;height:2px;border-radius:3px;background:var(--cgs-blue,#1e90ff);margin:0 auto 10px}
.cgs-st.cgs-js .cgs-rv{opacity:0}
.cgs-st.cgs-js .cgs-rv.in{opacity:1;animation:cgsRv .8s var(--d,0s) cubic-bezier(.2,.7,.2,1) backwards}
@media(max-width:1024px){.cgs-st-item{padding:12px 10px}}
@media(max-width:767px){.cgs-st-item{padding:12px 20px}.cgs-st-grid{grid-template-columns:1fr 1fr;row-gap:26px}.cgs-st-item:nth-child(2){border-right:0}.cgs-st{padding-bottom:40px}}
@media(max-width:520px){.cgs-st{padding-left:6vw;padding-right:6vw}.cgs-st-item{padding:8px 10px}.cgs-st-l{font-size:.7rem}}
@media(prefers-reduced-motion:reduce){.cgs-st.cgs-js .cgs-rv{opacity:1}.cgs-st.cgs-js .cgs-rv.in{animation:none}}
CSS;
}

/* ------------------------------------------------------------------
 * 23. STATS ELEMENT — [cgs_stats], editable in WPBakery backend.
 *     Page content: [vc_row full_width="stretch_row" el_class="cgs-row"][vc_column][cgs_stats][/vc_column][/vc_row]
 *     placed directly after the hero row.
 * ---------------------------------------------------------------- */
function cgs_stats_defaults() {
	return array(
		'n1' => '35+', 'l1' => 'Years of experience',
		'n2' => '7,000+', 'l2' => 'Beds in development',
		'n3' => '4', 'l3' => 'Klang Valley locations',
		'n4' => '13', 'l4' => 'Subsidiaries',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_stats_defaults();
	$p = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$g   = 'Stat ' . $i;
		$p[] = array( 'type' => 'textfield', 'heading' => 'Number (e.g. 35+ or 7,000+)', 'param_name' => "n{$i}", 'value' => $d[ "n{$i}" ], 'group' => $g );
		$p[] = array( 'type' => 'textfield', 'heading' => 'Label (leave number empty to hide this stat)', 'param_name' => "l{$i}", 'value' => $d[ "l{$i}" ], 'group' => $g );
	}
	vc_map( array( 'name' => 'Concepts Stats', 'base' => 'cgs_stats', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_stats', function ( $atts ) {
	$a = shortcode_atts( cgs_stats_defaults(), $atts, 'cgs_stats' );
	wp_enqueue_script( 'cgs-stats' );
	ob_start(); ?>
<section class="cgs-st" aria-label="Key figures"><div class="cgs-st-grid">
<?php foreach ( array( 1, 2, 3, 4 ) as $i ) :
	$n = trim( $a[ 'n' . $i ] );
	if ( '' === $n ) {
		continue;
	} ?>
<div class="cgs-st-item cgs-rv" style="--d:<?php echo esc_attr( ( $i - 1 ) * 0.08 ); ?>s"><b class="cgs-st-n" data-n="<?php echo esc_attr( $n ); ?>"><?php echo esc_html( $n ); ?></b><span class="cgs-st-l"><?php echo esc_html( $a[ 'l' . $i ] ); ?></span></div>
<?php endforeach; ?>
</div></section>
	<?php
	return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_register_script( 'cgs-stats', false, array(), CGS_VERSION, true );
	wp_add_inline_script( 'cgs-stats', "(function(){var s=document.querySelector('.cgs-st');if(!s||matchMedia('(prefers-reduced-motion:reduce)').matches||!('IntersectionObserver' in window))return;s.classList.add('cgs-js');function count(el){var t=el.dataset.n.match(/^([\\d,]+)(.*)$/);if(!t)return;var end=+t[1].replace(/,/g,''),suf=t[2],big=t[1].indexOf(',')>-1,t0=performance.now();(function k(n){var p=Math.min((n-t0)/1600,1),v=Math.round(end*(1-Math.pow(1-p,3)));el.textContent=(big?v.toLocaleString('en-US'):v)+suf;if(p<1)requestAnimationFrame(k)})(t0)}var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');var n=e.target.querySelector('.cgs-st-n');if(n)count(n);io.unobserve(e.target)}})},{threshold:.35});s.querySelectorAll('.cgs-rv').forEach(function(el){io.observe(el)})})();" );
}, 37 );

/* ------------------------------------------------------------------
 * 24. MOTION — GSAP + ScrollTrigger scroll animation for every section
 *     (loaded from cdnjs once per page; skipped for reduced-motion users;
 *      if GSAP fails to load nothing is hidden, the page just doesn't animate)
 * ---------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', function () {
	if ( is_admin() ) {
		return;
	}
	wp_register_script( 'cgs-gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), '3.12.5', true );
	wp_register_script( 'cgs-gsap-st', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array( 'cgs-gsap' ), '3.12.5', true );
	wp_register_script( 'cgs-motion', false, array( 'cgs-gsap', 'cgs-gsap-st' ), CGS_VERSION, true );
	wp_enqueue_script( 'cgs-motion' );
	wp_add_inline_script( 'cgs-motion', cgs_motion_js() );
	wp_add_inline_style( 'cgs', 'body.cgs-motion .cgs-rv{opacity:1!important;animation:none!important}@media(prefers-reduced-motion:no-preference){html{scroll-behavior:smooth;scroll-padding-top:calc(clamp(64px,5.4vw,78px) + 12px)}}' );
}, 40 );

function cgs_motion_js() {
	return <<<'JS'
(function(){
if(matchMedia('(prefers-reduced-motion:reduce)').matches||!window.gsap||!window.ScrollTrigger)return;
gsap.registerPlugin(ScrollTrigger);
document.body.classList.add('cgs-motion');
var G=[
['.cgs-hero:not(.cgs-hero-story) .cgs-hero-vis',{y:60,s:.94,d:1.1,delay:.45,now:1}],
['.cgs-ab2 .cgs-hero-eb,.cgs-ab2 .cgs-hb',{y:30,st:.12}],
['.cgs-ab2-main',{x:60,y:0,d:1.1}],
['.cgs-ab2-sub',{x:-40,y:40,d:1.1,delay:.2}],
['.cgs-ab2-card',{y:0,s:.7,e:'back.out(1.7)',d:.8,delay:.5}],
['.cgs-st-item',{y:30,st:.1}],
['.cgs-about .cgs-about-eb,.cgs-about .cgs-about-h,.cgs-about .cgs-about-txt p',{y:30,st:.12}],
['.cgs-ab2 .cgs-about-h,.cgs-ab2 .cgs-about-txt p',{y:30,st:.12}],
['.cgs-jr .cgs-hero-eb,.cgs-jr .cgs-about-h,.cgs-jr-head p',{y:30,st:.12}],
['.cgs-jr-b',{y:30,st:.06}],
['.cgs-vm:not(.cgs-vl) .cgs-hero-eb,.cgs-vm:not(.cgs-vl) .cgs-about-h,.cgs-vm:not(.cgs-vl) .cgs-vm-head p,.cgs-vm-btns a',{y:30,st:.12}],
['.cgs-vm-grid>.cgs-vm-img,.cgs-vm-card',{y:40,st:.12}],
['.cgs-vl .cgs-hero-eb,.cgs-vl .cgs-about-h,.cgs-vl .cgs-vm-head p',{y:30,st:.12}],
['.cgs-vl-box,.cgs-vl-grid>.cgs-vm-img',{y:40,st:.12}],
['.cgs-cm .cgs-hero-eb',{y:30}],
['.cgs-cm-pic',{x:40,y:0,d:1}],
['.cgs-jr-li.on .cgs-jr-d',{y:20,d:.8}],
['.cgs-card',{y:50,s:.96,st:.12}],
['.cgs-tick',{y:0}],
['.cgs-sv-eb,.cgs-sv-h,.cgs-sv-row>p',{y:30,st:.12}],
['.cgs-sv-img',{x:-60,y:0,d:1.1}],
['.cgs-sv-card',{y:40,st:.1}],
['.cgs-cq-eb,.cgs-cq-h,.cgs-cq-row>p',{y:30,st:.12}],
['.cgs-cq-item',{y:20,st:.08}],
['.cgs-cq-pic',{y:40}],
['.cgs-pr-eb,.cgs-pr-h',{y:30,st:.12}],
['.cgs-pr-card',{y:40,st:.15}],
['.cgs-pr-num',{y:0,s:.5,e:'back.out(1.7)',st:.15,d:.8}],
['.cgs-cp-row',{y:30,st:.12}],
['.cgs-fp-c',{y:40,st:.15}],
['.cgs-fq-box',{y:50,d:1}],
['.cgs-fq-eb,.cgs-fq-h,.cgs-fq-card p,.cgs-fq-btn',{y:24,st:.1,delay:.2}],
['.cgs-fq-list details',{x:40,y:0,st:.1}],
['.cgs-ct-h,.cgs-ct-in>p,.cgs-ct2-txt p,.cgs-ct-btns a',{y:30,st:.14}],
['.cgs-ft-brand,.cgs-ft-col',{y:30,st:.1}],
['.cgs-ft-bar',{y:0}]
];
G.forEach(function(g){
 var els=document.querySelectorAll(g[0]);if(!els.length)return;
 var o=g[1]||{},v={opacity:0,y:o.y===undefined?36:o.y,x:o.x||0,scale:o.s||1,duration:o.d||.9,ease:o.e||'power3.out',stagger:o.st||0,clearProps:'transform,opacity,visibility,transition',
  onStart:function(){this.targets().forEach(function(e){e.style.transition='none'})}};
 if(o.delay)v.delay=o.delay;
 if(!o.now)v.scrollTrigger={trigger:els[0],start:'top 88%',once:true};
 gsap.from(els,v);
});
var sw=document.querySelector('.cgs-hero-story .cgs-hero-work');
if(sw)gsap.fromTo(sw,{clipPath:'inset(0 0 0 100%)',scale:1.18},{clipPath:'inset(0 0 0 0%)',scale:1,duration:1.5,ease:'power3.inOut',delay:.3,clearProps:'clipPath,transform',onStart:function(){sw.style.transition='none'}});
addEventListener('load',function(){ScrollTrigger.refresh()});
})();
JS;
}


/* Workforce compliance — [cgs_compliance]: intro text left | icon-row list right, following the "How we work" reference. */
function cgs_compliance_css() {
	return <<<'CSS'
.cgs-cp{padding-bottom:clamp(60px,6vw,110px)}
.cgs-cp-in{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.15fr);gap:clamp(36px,5vw,96px);align-items:center}
.cgs-cp-txt .cgs-about-eb{justify-content:flex-start}
.cgs-cp-txt .cgs-about-h{text-align:left}
@media(min-width:1025px){.cgs-cp-txt .cgs-about-h{max-width:9.5em}}
@media(max-width:1024px){.cgs-cp-txt .cgs-about-h{margin-bottom:clamp(14px,3vw,24px)}}
.cgs-cp-txt p{margin:0;font-size:clamp(15px,1.05vw,17px);line-height:1.7;color:#3f536d}
.cgs-cp-img{display:block;width:100%;height:auto;aspect-ratio:16/10;object-fit:cover;margin-top:clamp(22px,2.2vw,36px);border-radius:clamp(16px,1.6vw,24px)}
.cgs-cp-list{margin:0;padding:0}
.cgs-cp-row{display:flex;align-items:center;gap:clamp(16px,1.6vw,24px);padding:clamp(16px,1.5vw,22px) 0;border-bottom:1px solid var(--cgs-line,#e1e8f1)}
.cgs-cp-ic{flex:none;display:grid;place-items:center;width:clamp(48px,3.8vw,56px);height:clamp(48px,3.8vw,56px);border-radius:50%;background:var(--cgs-blue,#1e90ff)}
.cgs-cp-ic img{width:46%;height:46%;object-fit:contain;filter:brightness(0) invert(1)}
.cgs-cp-row p{margin:0;font-size:clamp(15px,1.05vw,17px);line-height:1.6;color:#3f536d}
@media(max-width:1024px){.cgs-cp-in{grid-template-columns:1fr}}
CSS;
}

define( 'CGS_CP_DEF', array(
	'h2'   => 'Workforce Compliance',
	'h3'   => 'Foreign Worker Permit Renewal',
	'h3e'  => 'Permit Renewal',
	'p'    => 'We frequently monitor the validation of your foreign workers permit, and ensure the renewal procedure is done prior to the expiry, to avoid any compounds or penalties by the authorities.',
	'img'  => 'https://concepts.manyproject.my/wp-content/uploads/2021/06/local-workers-manpower-recruitment-agency.jpg',
	'rows' => '',
) );

function cgs_cp_rows_default() {
	$ic    = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/recruit.svg';
	$texts = array(
		'Workers FOMEMA registration upon arrival in Malaysia and/or every alternate year.',
		'Managing their health check-up routine for the first 3 years and subsequent alternate years.',
		'Keep track of their medical record, for the qualification of working permit renewal.',
		'Handling visa procedures and validation/monitoring of passport.',
		'Register and monitor (SPIKPA) a health insurance scheme, complying with the work permit renewal procedures.',
		'Alternate years additional mandatory FOMEMA medical examinations for foreign workers.',
	);
	$rows = array();
	foreach ( $texts as $tx ) {
		$rows[] = array( 'ic' => $ic, 'tx' => $tx );
	}
	return $rows;
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = CGS_CP_DEF;
	vc_map( array(
		'name'     => 'Concepts Workforce Compliance',
		'base'     => 'cgs_compliance',
		'category' => 'Concepts Group',
		'icon'     => 'icon-wpb-row',
		'params'   => array(
			array( 'type' => 'textfield', 'heading' => 'Label (H2)', 'param_name' => 'h2', 'value' => $d['h2'] ),
			array( 'type' => 'textfield', 'heading' => 'Heading (H3)', 'param_name' => 'h3', 'value' => $d['h3'] ),
			array( 'type' => 'textfield', 'heading' => 'Highlighted words', 'param_name' => 'h3e', 'value' => $d['h3e'], 'description' => 'Words from the heading shown in the accent colour. Leave empty for no highlight.' ),
			array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'p', 'value' => $d['p'] ),
			array( 'type' => 'attach_image', 'heading' => 'Photo (below the description)', 'param_name' => 'img', 'value' => $d['img'] ),
			array(
				'type'        => 'param_group',
				'heading'     => 'Icon boxes (one row each)',
				'param_name'  => 'rows',
				'group'       => 'Icon boxes',
				'description' => 'Leave untouched to show the six default rows.',
				'value'       => rawurlencode( wp_json_encode( cgs_cp_rows_default() ) ),
				'params'      => array(
					array( 'type' => 'attach_image', 'heading' => 'Icon (SVG)', 'param_name' => 'ic' ),
					array( 'type' => 'textarea', 'heading' => 'Text', 'param_name' => 'tx', 'admin_label' => true ),
				),
			),
		),
	) );
} );

add_shortcode( 'cgs_compliance', function ( $atts ) {
	$a = shortcode_atts( CGS_CP_DEF, $atts, 'cgs_compliance' );
	$h3 = esc_html( $a['h3'] );
	$e  = trim( (string) $a['h3e'] );
	if ( '' !== $e ) {
		$h3 = str_replace( esc_html( $e ), '<em>' . esc_html( $e ) . '</em>', $h3 );
	}
	$rows = cgs_cp_rows_default();
	if ( '' !== trim( (string) $a['rows'] ) ) {
		$dec  = json_decode( rawurldecode( $a['rows'] ), true );
		$rows = is_array( $dec ) ? $dec : array();
	}
	$rows = array_values( array_filter( $rows, function ( $r ) {
		return is_array( $r ) && ! empty( $r['tx'] );
	} ) );
	ob_start();
	?>
<section class="cgs-about cgs-cp" id="compliance"><div class="cgs-about-in cgs-cp-in">
<div class="cgs-cp-txt">
<h2 class="cgs-about-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-about-h"><?php echo $h3; ?></h3>
<p><?php echo esc_html( $a['p'] ); ?></p>
<img class="cgs-cp-img" src="<?php echo esc_url( cgs_media_url( $a['img'], CGS_CP_DEF['img'] ) ); ?>" alt="" loading="lazy">
</div>
<div class="cgs-cp-list">
<?php foreach ( $rows as $r ) : ?>
<div class="cgs-cp-row"><span class="cgs-cp-ic"><img src="<?php echo esc_url( cgs_media_url( isset( $r['ic'] ) ? $r['ic'] : '', cgs_cp_rows_default()[0]['ic'] ) ); ?>" alt="" loading="lazy"></span><p><?php echo esc_html( $r['tx'] ); ?></p></div>
<?php endforeach; ?>
</div>
</div></section>
	<?php
	return ob_get_clean();
} );
/* Transport intro — [cgs_transport_intro]: text | 3-up image slider on the .cgs-vm soft background with the same double wave. Reuses .cgs-vm / hero eyebrow / about heading. */
function cgs_transport_defaults() {
	$b = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	$img = array( $b . 'Concept-Group-Worker2.webp', $b . 'Concept-Group-24141.webp', $b . 'Concepts-Group-Employee-welfare.webp', $b . 'Concepts-Group-Employee-welfare.webp' );
	$t = array(
		'Daily transportation arrangement commute to/from work',
		'Emergency support during/after working hours',
		'FOMEMA appointment',
		'Take workers to clinic/hospital for check-ups (if sick)',
		'Bank Account Registration',
		'Taken to the airport for repatriation to source country due to end of employment/return from home country for holiday/clearance for new arrivals',
		'Passport renewal arrangement at the embassy',
		'Worker\'s clearance from the airport to the hostel',
	);
	$s = array();
	foreach ( $t as $i => $x ) {
		$s[] = array( 't' => $x, 'im' => $img[ $i % 4 ] );
	}
	return array(
		'h2' => 'Transport Solutions', 'h3' => 'Reliable Daily Transport', 'h3e' => 'Daily Transport',
		'p1' => 'We have our own buses and vans, which covers all routes and connects to major industrial areas, throughout Peninsular Malaysia and our team obtained driver\'s license from:',
		'lic' => "National Land Public Transport Commission (SPAD)\nApproved Service Route from State Regulators (SPAD)\nApproved Roadworthy Vehicles with License & Regulatory permits from both Road Transport Department (JPJ) and SPAD.",
		'p2' => 'Our team of 50 over competent professional drivers ensures workers travel to work safely on time. Our vehicles undergo regular maintenance which to ensure that it is safe for workers to travel in.',
		'slides' => $s,
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_transport_defaults();
	$p = array();
	foreach ( array( 'h2' => 'Label (H2)', 'h3' => 'Heading (H3)', 'h3e' => 'Accent words in heading', 'p1' => 'Description', 'lic' => 'Licence list (one per line)', 'p2' => 'Closing paragraph' ) as $k => $l ) {
		$p[] = array( 'type' => in_array( $k, array( 'p1', 'lic', 'p2' ), true ) ? 'textarea' : 'textfield', 'heading' => $l, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	$p[] = array( 'type' => 'param_group', 'heading' => 'Slides', 'param_name' => 'slides', 'value' => rawurlencode( wp_json_encode( $d['slides'] ) ), 'params' => array(
		array( 'type' => 'textfield', 'heading' => 'Title (H4 if short, else text)', 'param_name' => 't', 'admin_label' => true ),
		array( 'type' => 'attach_image', 'heading' => 'Image', 'param_name' => 'im' ),
	) );
	vc_map( array( 'name' => 'Concepts Transport Intro', 'base' => 'cgs_transport_intro', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_transport_intro', function ( $atts ) {
	$d = cgs_transport_defaults();
	$a = shortcode_atts( array( 'h2' => $d['h2'], 'h3' => $d['h3'], 'h3e' => $d['h3e'], 'p1' => $d['p1'], 'lic' => $d['lic'], 'p2' => $d['p2'], 'slides' => '' ), $atts, 'cgs_transport_intro' );
	$rows = ( '' !== trim( (string) $a['slides'] ) && function_exists( 'vc_param_group_parse_atts' ) ) ? vc_param_group_parse_atts( $a['slides'] ) : array();
	$rows = array_values( array_filter( (array) $rows, function ( $x ) { return is_array( $x ) && ! empty( $x['t'] ); } ) );
	$rows = $rows ? $rows : $d['slides'];
	$lic  = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $a['lic'] ) ) );
	static $once = false;
	ob_start();
	if ( ! $once ) {
		$once = true; ?>
<style>
.cgs-tr .cgs-hero-eb{justify-content:flex-start}
.cgs-tr-in{display:grid;grid-template-columns:minmax(0,.8fr) minmax(0,1.4fr);gap:clamp(32px,5vw,80px);align-items:stretch}
.cgs-tr-l .cgs-about-h{margin:0 0 clamp(14px,1.4vw,20px)}
.cgs-tr-l p,.cgs-tr-l li{font-size:clamp(15px,1.05vw,17px);line-height:1.7;color:#3f536d}
.cgs-tr-l p{margin:0 0 clamp(12px,1.2vw,18px)}
.cgs-tr-l p.cgs-tr-note{margin:clamp(8px,1vw,16px) 0 0;padding:clamp(18px,1.8vw,30px);border-radius:clamp(14px,1.2vw,22px);background:var(--cgs-navy,#071A33);color:#fff}
.cgs-tr-l ul{margin:0 0 clamp(12px,1.2vw,18px);padding-left:1.2em;list-style:disc}
.cgs-tr-l li{margin:0 0 6px}.cgs-tr-l li::marker{color:var(--cgs-blue,#1e90ff)}
.cgs-tr-sl{position:relative;min-width:0;display:flex;flex-direction:column}
.cgs-tr-vp{overflow:hidden;flex:1;min-height:0}
.cgs-tr-tr{display:flex;gap:clamp(14px,1.4vw,20px);--n:3;align-items:stretch;height:100%;transition:transform .5s cubic-bezier(.3,.7,.2,1)}
.cgs-tr-c{flex:0 0 calc((100% - (var(--n) - 1) * clamp(14px,1.4vw,20px)) / var(--n));position:relative;overflow:hidden;border-radius:clamp(14px,1.2vw,22px)}
.cgs-tr-c img{position:absolute;inset:0;display:block;width:100%;height:100%;object-fit:cover}
body.cgs-site #lqd-site-content .cgs-tr-c h4,.cgs-tr-c p{margin:0;font:600 clamp(15px,1.15vw,18px)/1.35 var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.01em;text-transform:none;color:var(--cgs-ink,#14243b)}
.cgs-tr-dots{position:absolute;top:100%;left:0;right:0;display:flex;justify-content:center;gap:10px;padding-top:clamp(12px,1.2vw,18px)}
body.cgs-site #lqd-site-content .cgs-tr-dots button{display:block;width:10px;height:10px;min-width:0;min-height:0;padding:0;border:0;border-radius:10px;box-shadow:none;background:#c7d3e3;line-height:0;cursor:pointer;transition:width .3s,background .3s}
body.cgs-site #lqd-site-content .cgs-tr-dots button.on{width:26px;background:var(--cgs-blue,#1e90ff)}
.cgs-tr-c:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,rgba(7,26,51,.9) 0%,rgba(7,26,51,.2) 55%,transparent 78%)}
body.cgs-site #lqd-site-content .cgs-tr-c h4,body.cgs-site #lqd-site-content .cgs-tr-c p{position:absolute;left:0;right:0;bottom:0;z-index:1;padding:clamp(14px,1.4vw,20px);color:#fff}
@media(max-width:1024px){.cgs-tr-in{grid-template-columns:1fr}.cgs-tr-tr{--n:2}.cgs-tr-vp{flex:none;height:clamp(340px,60vw,480px)}.cgs-tr-sl{margin-bottom:34px}}
@media(max-width:600px){.cgs-tr-tr{--n:1}}
</style>
<script>
document.addEventListener('DOMContentLoaded',function(){document.querySelectorAll('.cgs-tr-sl').forEach(function(sl){
var tr=sl.querySelector('.cgs-tr-tr'),cs=[].slice.call(tr.children),i=0;
function per(){return window.innerWidth<=600?1:window.innerWidth<=1024?2:3}
var dots=sl.querySelector('.cgs-tr-dots');
function go(n){var m=Math.max(0,cs.length-per());i=n<0?m:(n>m?0:n);var g=parseFloat(getComputedStyle(tr).columnGap)||20;tr.style.transform='translateX('+(-i*(cs[0].offsetWidth+g))+'px)';[].forEach.call(dots.children,function(b,k){b.className=k===i?'on':''})}
function build(){var m=Math.max(0,cs.length-per());dots.innerHTML='';for(var k=0;k<=m;k++){(function(k){var b=document.createElement('button');b.type='button';b.setAttribute('aria-label','Slide '+(k+1));b.onclick=function(){go(k)};dots.appendChild(b)})(k)}dots.style.display=m?'flex':'none'}
var x0=null;sl.addEventListener('touchstart',function(e){x0=e.touches[0].clientX},{passive:true});
sl.addEventListener('touchend',function(e){if(x0!==null){var dx=e.changedTouches[0].clientX-x0;x0=null;if(Math.abs(dx)>40)go(dx<0?i+1:i-1)}});
window.addEventListener('resize',function(){build();go(i)});build();go(0)});});
</script>
	<?php } ?>
<section class="cgs-vm cgs-tr" id="transport">
<div class="cgs-tr-in">
<div class="cgs-tr-l">
<h2 class="cgs-hero-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
<p><?php echo esc_html( $a['p1'] ); ?></p>
<?php if ( $lic ) : ?><ul><?php foreach ( $lic as $x ) : ?><li><?php echo esc_html( $x ); ?></li><?php endforeach; ?></ul><?php endif; ?>
<p class="cgs-tr-note"><?php echo esc_html( $a['p2'] ); ?></p>
</div>
<div class="cgs-tr-sl">
<div class="cgs-tr-vp"><div class="cgs-tr-tr">
<?php foreach ( $rows as $r ) : $t = (string) $r['t']; ?>
<article class="cgs-tr-c"><img src="<?php echo esc_url( cgs_media_url( isset( $r['im'] ) ? $r['im'] : '', $d['slides'][0]['im'] ) ); ?>" alt="" loading="lazy"><?php echo mb_strlen( $t ) > 70 ? '<p>' . esc_html( $t ) . '</p>' : '<h4>' . esc_html( $t ) . '</h4>'; ?></article>
<?php endforeach; ?>
</div></div>
<div class="cgs-tr-dots"></div>
</div>
</div>
</section>
	<?php
	return ob_get_clean();
} );


/* Industry experience — [cgs_industry]: heading | button on top, six icon boxes below. Reuses .cgs-vm.cgs-vl (white section) and the .cgs-vl-box/-ic/-t value-box classes; icons are masked SVG data URIs. */
function cgs_industry_icons() {
	$p = array(
		'chip'  => 'M9 3v2H7a2 2 0 0 0-2 2v2H3v2h2v2H3v2h2v2a2 2 0 0 0 2 2h2v2h2v-2h2v2h2v-2h2a2 2 0 0 0 2-2v-2h2v-2h-2v-2h2V9h-2V7a2 2 0 0 0-2-2h-2V3h-2v2h-2V3zM8 7h8a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1zm2 3v4h4v-4z',
		'hand'  => 'M21 7a2 2 0 0 0-2-2 2 2 0 0 0-.5.07V4a2 2 0 0 0-2-2 2 2 0 0 0-.9.2A2 2 0 0 0 13.6 1a2 2 0 0 0-2 2v.4a2 2 0 0 0-3 1.7v7.4l-1.7-1.8a1.7 1.7 0 0 0-2.5 0 1.7 1.7 0 0 0 0 2.4L8.5 20A5 5 0 0 0 12 21.5h2.5A5.5 5.5 0 0 0 20 16V7z',
		'food'  => 'M11 9H9V2H7v7H5V2H3v7a4 4 0 0 0 3 3.87V22h2v-9.13A4 4 0 0 0 11 9zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z',
		'car'   => 'M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h12v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-8l-2.08-5.99zM6.5 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm11 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zM5 11l1.5-4.5h11L19 11H5z',
		'drink' => 'M3 2l2.01 18.23C5.13 21.23 5.97 22 7 22h10c1.03 0 1.87-.77 1.99-1.77L21 2H3zm9 17c-1.66 0-3-1.34-3-3 0-2 3-5.4 3-5.4s3 3.4 3 5.4c0 1.66-1.34 3-3 3zm6.33-11H5.67l-.44-4h13.53l-.43 4z',
		'cart'  => 'M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0 0 20 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z',
	);
	$o = array();
	foreach ( $p as $k => $path ) {
		$o[ $k ] = 'data:image/svg+xml,' . rawurlencode( "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><path fill-rule='evenodd' d='{$path}'/></svg>" );
	}
	return $o;
}

function cgs_industry_defaults() {
	return array(
		'h2' => 'Industry Experience', 'h3' => 'Supporting Diverse Industries', 'h3e' => 'Diverse Industries',
		'btn' => 'Discuss Your Needs', 'url' => '/#contact',
		'arrow' => 'https://concepts.manyproject.my/wp-content/uploads/2026/10/Black-Right-Arrow.svg',
		'items' => array(
			array( 't' => 'Electronics', 'k' => 'chip' ), array( 't' => 'Medical Gloves', 'k' => 'hand' ), array( 't' => 'Food Production', 'k' => 'food' ),
			array( 't' => 'Automotive', 'k' => 'car' ), array( 't' => 'Beverage Production', 'k' => 'drink' ), array( 't' => 'E-commerce', 'k' => 'cart' ),
		),
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_industry_defaults();
	$p = array();
	foreach ( array( 'h2' => 'Label (H2)', 'h3' => 'Heading (H3)', 'h3e' => 'Accent words in heading', 'btn' => 'Button text', 'url' => 'Button link', 'arrow' => 'Button icon' ) as $k => $l ) {
		$p[] = array( 'type' => 'arrow' === $k ? 'attach_image' : 'textfield', 'heading' => $l, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	$p[] = array( 'type' => 'param_group', 'heading' => 'Industries', 'param_name' => 'items', 'value' => rawurlencode( wp_json_encode( $d['items'] ) ), 'params' => array(
		array( 'type' => 'textfield', 'heading' => 'Title (H4)', 'param_name' => 't', 'admin_label' => true ),
		array( 'type' => 'dropdown', 'heading' => 'Built-in icon', 'param_name' => 'k', 'value' => array( 'Electronics' => 'chip', 'Gloves' => 'hand', 'Food' => 'food', 'Automotive' => 'car', 'Beverage' => 'drink', 'E-commerce' => 'cart' ) ),
		array( 'type' => 'attach_image', 'heading' => 'Custom icon (SVG, overrides built-in)', 'param_name' => 'im' ),
	) );
	vc_map( array( 'name' => 'Concepts Industry', 'base' => 'cgs_industry', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_industry', function ( $atts ) {
	$d = cgs_industry_defaults();
	$a = shortcode_atts( array( 'h2' => $d['h2'], 'h3' => $d['h3'], 'h3e' => $d['h3e'], 'btn' => $d['btn'], 'url' => $d['url'], 'arrow' => $d['arrow'], 'items' => '' ), $atts, 'cgs_industry' );
	$rows = ( '' !== trim( (string) $a['items'] ) && function_exists( 'vc_param_group_parse_atts' ) ) ? vc_param_group_parse_atts( $a['items'] ) : array();
	$rows = array_values( array_filter( (array) $rows, function ( $x ) { return is_array( $x ) && ! empty( $x['t'] ); } ) );
	$rows = $rows ? $rows : $d['items'];
	$ic   = cgs_industry_icons();
	$url  = trim( (string) ( '' !== trim( (string) $a['url'] ) ? $a['url'] : $d['url'] ) );
	$url  = esc_url( ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) ? home_url( $url ) : $url );
	static $once = false;
	ob_start();
	if ( ! $once ) {
		$once = true; ?>
<style>
.cgs-ind-top{display:flex;align-items:flex-end;justify-content:space-between;gap:24px;margin-bottom:clamp(28px,3.4vw,56px)}
.cgs-ind .cgs-hero-eb{justify-content:flex-start}
.cgs-vm.cgs-vl.cgs-ind{background:var(--cgs-soft,#f3f6fa)}.cgs-ind .cgs-vl-box{background:#fff}
.cgs-ind-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:clamp(12px,1.4vw,20px)}
.cgs-ind .cgs-vl-box{grid-template-columns:auto 1fr;justify-items:stretch;cursor:pointer;transition:transform .35s ease,box-shadow .35s ease,border-color .35s ease}
body.cgs-site #lqd-site-content .cgs-ind h4.cgs-vl-t{margin:0}
.cgs-ind .cgs-vl-ic{transition:background .3s}
@media(hover:hover){.cgs-ind .cgs-vl-box:hover{transform:translateY(-6px);box-shadow:0 22px 40px -20px rgba(11,74,150,.55);border-color:var(--cgs-blue,#1e90ff)}.cgs-ind .cgs-vl-box:hover .cgs-vl-ic{background:var(--cgs-navy,#071A33)}}
.cgs-ind .cgs-vl-box.on{transform:translateY(-6px);box-shadow:0 22px 40px -20px rgba(11,74,150,.55);border-color:var(--cgs-blue,#1e90ff)}.cgs-ind .cgs-vl-box.on .cgs-vl-ic{background:var(--cgs-navy,#071A33)}
@media(max-width:1024px){.cgs-ind-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.cgs-ind-top{flex-direction:column;align-items:flex-start}}
@media(max-width:600px){.cgs-ind-grid{grid-template-columns:1fr;gap:16px}.cgs-ind-top .cgs-hb{width:100%;justify-content:center}}
</style>
<script>
document.addEventListener('click',function(e){var b=e.target.closest&&e.target.closest('.cgs-ind .cgs-vl-box');if(!b||matchMedia('(hover:hover)').matches)return;var o=b.classList.contains('on');b.parentNode.querySelectorAll('.on').forEach(function(x){x.classList.remove('on')});if(!o)b.classList.add('on')});
</script>
	<?php } ?>
<section class="cgs-vm cgs-vl cgs-ind" id="industry">
<div class="cgs-ind-top">
<div>
<h2 class="cgs-hero-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
</div>
<a class="cgs-hb cgs-hb1" href="<?php echo $url; ?>"><?php echo esc_html( $a['btn'] ); ?><img src="<?php echo esc_url( cgs_media_url( $a['arrow'], $d['arrow'] ) ); ?>" alt="" width="18" height="18"></a>
</div>
<div class="cgs-ind-grid">
<?php foreach ( $rows as $r ) :
	$k   = isset( $r['k'] ) ? $r['k'] : '';
	$src = ! empty( $r['im'] ) ? cgs_media_url( $r['im'], '' ) : ( isset( $ic[ $k ] ) ? $ic[ $k ] : $ic['chip'] ); ?>
<div class="cgs-vl-box"><span class="cgs-vl-ic"><i style="--cgs-ic:url('<?php echo esc_attr( $src ); ?>')"></i></span><h4 class="cgs-vl-t"><?php echo esc_html( $r['t'] ); ?></h4></div>
<?php endforeach; ?>
</div>
</section>
	<?php
	return ob_get_clean();
} );


/* Client experiences — [cgs_testimonials]: the homepage FAQ container pair (.cgs-fq band > .cgs-fq-wrap > .cgs-fq-box), head row (H3 | text) above a 4-image gallery. Images open in the same lightbox as the Recruitment process graphic. */
function cgs_testimonials_defaults() {
	$b = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	return array(
		'h2' => 'Client Experiences', 'h3' => 'Trusted by Our Partners', 'h3e' => 'Our Partners',
		'p'  => 'Hear how our clients experience working with Concepts Group to manage workforce documentation and administrative requirements.',
		'imgs' => array(
			array( 'im' => $b . 'Concept-Group-2-e1684735136841.webp', 'alt' => 'Client testimonial 1' ),
			array( 'im' => $b . 'Concept-Group-3-e1684735169282-889x1024-1.webp', 'alt' => 'Client testimonial 2' ),
			array( 'im' => $b . 'Group-Conept-4-e1684735235744.webp', 'alt' => 'Client testimonial 3' ),
			array( 'im' => $b . 'Concept-Group-1-1-e1684735208297.webp', 'alt' => 'Client testimonial 4' ),
		),
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_testimonials_defaults();
	$p = array();
	foreach ( array( 'h2' => 'Label (H2)', 'h3' => 'Heading (H3)', 'h3e' => 'Accent words in heading', 'p' => 'Description' ) as $k => $l ) {
		$p[] = array( 'type' => 'p' === $k ? 'textarea' : 'textfield', 'heading' => $l, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	$p[] = array( 'type' => 'param_group', 'heading' => 'Gallery images', 'param_name' => 'imgs', 'value' => rawurlencode( wp_json_encode( $d['imgs'] ) ), 'params' => array(
		array( 'type' => 'textfield', 'heading' => 'Image URL (or media ID)', 'param_name' => 'im', 'admin_label' => true ),
		array( 'type' => 'textfield', 'heading' => 'Description (alt text)', 'param_name' => 'alt' ),
	) );
	vc_map( array( 'name' => 'Concepts Testimonials', 'base' => 'cgs_testimonials', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_testimonials', function ( $atts ) {
	$d = cgs_testimonials_defaults();
	$a = shortcode_atts( array( 'h2' => $d['h2'], 'h3' => $d['h3'], 'h3e' => $d['h3e'], 'p' => $d['p'], 'imgs' => '' ), $atts, 'cgs_testimonials' );
	$rows = ( '' !== trim( (string) $a['imgs'] ) && function_exists( 'vc_param_group_parse_atts' ) ) ? vc_param_group_parse_atts( $a['imgs'] ) : array();
	$rows = array_values( array_filter( (array) $rows, function ( $x ) { return is_array( $x ) && ! empty( $x['im'] ); } ) );
	$rows = $rows ? $rows : $d['imgs'];
	static $once = false;
	ob_start();
	if ( ! $once ) {
		$once = true; ?>
<style>
.cgs-fq.cgs-tm{background:#fff}.cgs-tm .cgs-fq-box{display:block;padding:clamp(24px,4vw,64px);background:var(--cgs-soft,#f3f6fa)}
body.cgs-site #lqd-site-content .cgs-tm h2.cgs-hero-eb{display:flex;align-items:center;justify-content:flex-start;gap:.9vw;margin:0 0 1.3vw;font:700 clamp(12px,.85vw,14px) var(--cgs-fb,Inter,sans-serif);letter-spacing:.1em;text-transform:uppercase;color:var(--cgs-blue-d,#0b6fd1)}
@media(max-width:767px){body.cgs-site #lqd-site-content .cgs-tm h2.cgs-hero-eb{gap:2.4vw;margin-bottom:3vw}}
.cgs-tm-row{display:grid;grid-template-columns:1fr 1fr;align-items:center;gap:clamp(16px,3vw,48px);margin:0 0 clamp(36px,4.5vw,72px)}
.cgs-tm-row .cgs-about-h{margin:0}
.cgs-tm-row p{margin:0;font-size:clamp(15px,1.05vw,17px);line-height:1.7;color:#3f536d}
.cgs-tm-g{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:clamp(16px,2.2vw,32px)}
.cgs-tm-fr{overflow:hidden;border-radius:clamp(14px,1.2vw,22px);background:var(--cgs-soft,#f3f6fa);border:2px solid var(--cgs-line,#e1e8f1)}
.cgs-tm-fr img{display:block;width:100%;aspect-ratio:7/8;object-fit:cover;cursor:zoom-in}
@media(hover:hover) and (prefers-reduced-motion:no-preference){.cgs-tm-fr img{transition:transform .8s ease}.cgs-tm-fr:hover img{transform:scale(1.05)}}
.cgs-lb{position:fixed;inset:0;z-index:99999;display:flex;background:rgba(8,24,52,.9);overflow:auto;padding:clamp(16px,3vw,40px);cursor:zoom-out}
.cgs-lb img{flex:none;display:block;margin:auto;width:100%;max-width:1500px;height:auto;border-radius:clamp(10px,1vw,16px);background:#fff}
.cgs-lb-x{position:fixed;top:14px;right:18px;width:44px;height:44px;border:0;border-radius:50%;background:#fff;color:var(--cgs-navy,#071A33);font-size:28px;line-height:1;cursor:pointer}
@media(max-width:1024px){.cgs-tm-row{grid-template-columns:1fr;gap:14px}.cgs-tm-g{grid-template-columns:repeat(2,minmax(0,1fr))}}
.cgs-lb.cgs-lb-p img{max-width:min(100%,760px)}
</style>
<script>
document.addEventListener('click',function(e){var t=e.target.closest&&e.target.closest('.cgs-tm-fr img');if(!t)return;
var o=document.createElement('div'),im=document.createElement('img'),x=document.createElement('button'),h=document.documentElement;
o.className='cgs-lb cgs-lb-p';o.setAttribute('role','dialog');o.setAttribute('aria-modal','true');im.src=t.currentSrc||t.src;im.alt=t.alt;x.type='button';x.className='cgs-lb-x';x.setAttribute('aria-label','Close');x.textContent='×';
o.appendChild(im);o.appendChild(x);document.body.appendChild(o);h.style.overflow='hidden';x.focus();
function c(){o.remove();h.style.overflow='';document.removeEventListener('keydown',k)}function k(ev){if(ev.key==='Escape')c()}
o.addEventListener('click',c);document.addEventListener('keydown',k)});
</script>
	<?php } ?>
<section class="cgs-fq cgs-tm" id="clients"><div class="cgs-fq-wrap"><div class="cgs-fq-box">
<h2 class="cgs-hero-eb"><?php echo esc_html( $a['h2'] ); ?></h2>
<div class="cgs-tm-row">
<h3 class="cgs-about-h"><?php echo str_replace( esc_html( $a['h3e'] ), '<em>' . esc_html( $a['h3e'] ) . '</em>', esc_html( $a['h3'] ) ); ?></h3>
<p><?php echo esc_html( $a['p'] ); ?></p>
</div>
<div class="cgs-tm-g">
<?php foreach ( $rows as $r ) : ?>
<div class="cgs-tm-fr"><img src="<?php echo esc_url( cgs_media_url( $r['im'], '' ) ); ?>" alt="<?php echo esc_attr( isset( $r['alt'] ) ? $r['alt'] : '' ); ?>" loading="lazy"></div>
<?php endforeach; ?>
</div>
</div></div></section>
	<?php
	return ob_get_clean();
} );

/* ------------------------------------------------------------------
 * Contact — [cgs_contact]: navy info card (Address / Contact rows + Stay Connected icons, every row editable and repeatable)
 * on the left, the Formidable form (key "contact-form", id 1) restyled with the site's inputs/buttons on the wider right column.
 * ---------------------------------------------------------------- */
function cgs_contact_defaults() {
	return array(
		'ha'   => 'Address', 'hc' => 'Contact', 'hs' => 'Stay Connected',
		'eb'   => 'Contact Us', 'h3' => 'Get Your Free Quote Today!', 'h3e' => 'Free Quote',
		'form' => 'contact-form', 'fid' => '1',
		'addr' => '', 'cont' => '', 'soc' => '',
	);
}

function cgs_contact_rows_default( $k ) {
	$u = 'https://concepts.manyproject.my/wp-content/uploads/2026/10/';
	$a = 'No 2-9, block 9, Jalan Batu Nilam 16, Bandar Bukit Tinggi 2, Klang, Malaysia';
	$rows = array(
		'addr' => array(
			array( 'ic' => $u . 'maps-and-location.svg', 'tx' => $a, 'url' => 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $a ) ),
		),
		'cont' => array(
			array( 'ic' => $u . 'phone.svg', 'tx' => '+60 17-887 3361', 'url' => 'tel:+60178873361' ),
			array( 'ic' => $u . 'email.svg', 'tx' => 'support@concepts-groups.com', 'url' => 'mailto:support@concepts-groups.com' ),
		),
		'soc'  => array(
			array( 'ic' => $u . 'facebook-1.svg', 'tx' => 'Facebook', 'url' => 'https://www.facebook.com/conceptsgroup.my' ),
			array( 'ic' => $u . 'whatsapp-2.png', 'tx' => 'WhatsApp', 'url' => 'https://api.whatsapp.com/send?phone=60178873361&text=Hello%20there,%20I%20would%20like%20to%20know%20about%20your%20services' ),
		),
	);
	return $rows[ $k ];
}

function cgs_contact_rows( $raw, $k ) {
	$rows = cgs_contact_rows_default( $k );
	if ( '' !== trim( (string) $raw ) ) {
		$dec  = json_decode( rawurldecode( $raw ), true );
		$rows = is_array( $dec ) ? $dec : array();
	}
	return array_values( array_filter( $rows, function ( $r ) {
		return is_array( $r ) && ( ! empty( $r['tx'] ) || ! empty( $r['ic'] ) );
	} ) );
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_contact_defaults();
	$p = array();
	foreach ( array( 'ha' => 'Address heading', 'hc' => 'Contact heading', 'hs' => 'Social heading (bottom)', 'eb' => 'Form eyebrow (H2)', 'h3' => 'Form heading (H3)', 'h3e' => 'Highlighted words', 'form' => 'Formidable form key', 'fid' => 'Formidable form ID (used if key is empty)' ) as $k => $l ) {
		$p[] = array( 'type' => 'textfield', 'heading' => $l, 'param_name' => $k, 'value' => $d[ $k ] );
	}
	$row = array(
		array( 'type' => 'attach_image', 'heading' => 'Icon', 'param_name' => 'ic' ),
		array( 'type' => 'textarea', 'heading' => 'Text (label for social icons)', 'param_name' => 'tx', 'admin_label' => true ),
		array( 'type' => 'textfield', 'heading' => 'Link (https://, tel:, mailto:)', 'param_name' => 'url' ),
	);
	foreach ( array( 'addr' => 'Address rows', 'cont' => 'Contact rows', 'soc' => 'Social icons' ) as $k => $l ) {
		$p[] = array(
			'type'        => 'param_group',
			'heading'     => $l,
			'param_name'  => $k,
			'group'       => 'Left card',
			'description' => 'Use + to add, drag to reorder, the trash icon to delete. Leave untouched to show the defaults.',
			'value'       => rawurlencode( wp_json_encode( cgs_contact_rows_default( $k ) ) ),
			'params'      => $row,
		);
	}
	vc_map( array( 'name' => 'Concepts Contact', 'base' => 'cgs_contact', 'category' => 'Concepts Group', 'icon' => 'icon-wpb-row', 'params' => $p ) );
} );

add_shortcode( 'cgs_contact', function ( $atts ) {
	$d = cgs_contact_defaults();
	$a = shortcode_atts( $d, $atts, 'cgs_contact' );
	foreach ( array( 'ha', 'hc', 'hs' ) as $k ) {
		if ( '' === trim( (string) $a[ $k ] ) ) {
			$a[ $k ] = $d[ $k ];
		}
	}
	$rows = array(
		'addr' => cgs_contact_rows( $a['addr'], 'addr' ),
		'cont' => cgs_contact_rows( $a['cont'], 'cont' ),
	);
	$soc  = cgs_contact_rows( $a['soc'], 'soc' );
	$h3   = esc_html( $a['h3'] );
	$em   = esc_html( $a['h3e'] );
	if ( '' !== $em ) {
		$h3 = str_replace( $em, '<em>' . $em . '</em>', $h3 );
	}
	$form = '';
	if ( '' !== trim( (string) $a['form'] ) ) {
		$form = do_shortcode( '[formidable key="' . esc_attr( $a['form'] ) . '"]' );
	}
	if ( '' === trim( $form ) && '' !== trim( (string) $a['fid'] ) ) {
		$form = do_shortcode( '[formidable id="' . (int) $a['fid'] . '"]' );
	}
	$url = function ( $u ) {
		$u = trim( (string) $u );
		return '' === $u ? '' : esc_url( ( 0 === strpos( $u, '/' ) && 0 !== strpos( $u, '//' ) ) ? home_url( $u ) : $u );
	};
	$ext = function ( $u ) {
		return 0 === strpos( (string) $u, 'http' ) ? ' target="_blank" rel="noopener"' : '';
	};
	static $once = false;
	ob_start();
	if ( ! $once ) {
		$once = true; ?>
<style>
.cgs-cf{padding:clamp(40px,4vw,70px) 0 clamp(60px,6vw,110px);font-family:var(--cgs-fb,Inter,sans-serif);color:var(--cgs-ink,#14243b)}
.cgs-cf *{box-sizing:border-box}
.cgs-cf-in{display:grid;grid-template-columns:minmax(0,.8fr) minmax(0,1.4fr);gap:clamp(24px,3.5vw,56px);align-items:stretch;padding:0 5vw}
.cgs-cf-card{display:flex;flex-direction:column;justify-content:space-between;gap:clamp(28px,3vw,48px);padding:clamp(24px,2.6vw,40px);border-radius:clamp(18px,2vw,28px);background:var(--cgs-navy,#071A33);color:#fff}
.cgs-cf-top{display:flex;flex-direction:column;gap:clamp(26px,2.8vw,42px)}
body.cgs-site #lqd-site-content .cgs-cf h4.cgs-cf-t{margin:0 0 clamp(12px,1.2vw,18px);font:600 clamp(18px,1.5vw,24px)/1.2 var(--cgs-fh,'Bricolage Grotesque',sans-serif);letter-spacing:-.02em;text-transform:none;color:#fff}
.cgs-cf-list{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:12px}
.cgs-cf-list li{margin:0;padding:0}
.cgs-cf-list a,.cgs-cf-list span.cgs-cf-r{display:flex;align-items:flex-start;gap:12px;font-size:clamp(14px,1vw,16px);line-height:1.6;color:#d6e3f5;text-decoration:none;transition:color .25s}
.cgs-cf-list a:hover{color:#fff}
.cgs-cf-list i{flex:none;width:22px;height:22px;margin-top:2px;background:#5db4ff;-webkit-mask:var(--cgs-ic) center/contain no-repeat;mask:var(--cgs-ic) center/contain no-repeat}
.cgs-cf-soc{display:flex;flex-wrap:wrap;gap:12px}
.cgs-cf-soc a{display:grid;place-items:center;width:44px;height:44px;border-radius:50%;background:#fff;transition:transform .3s,box-shadow .3s}
.cgs-cf-soc a:hover{transform:translateY(-3px);box-shadow:0 12px 20px -10px rgba(0,0,0,.5)}
.cgs-cf-soc i{display:block;width:22px;height:22px;background:var(--cgs-blue,#1e90ff);-webkit-mask:var(--cgs-ic) center/contain no-repeat;mask:var(--cgs-ic) center/contain no-repeat}
body.cgs-site #lqd-site-content .cgs-cf h2.cgs-cf-eb{display:flex;align-items:center;margin:0 0 clamp(8px,.8vw,12px);font:700 clamp(12px,.9vw,14px) var(--cgs-fb,Inter,sans-serif);letter-spacing:.1em;text-transform:uppercase;color:var(--cgs-blue-d,#0b6fd1)}
.cgs-cf-form .cgs-about-h{margin:0 0 clamp(18px,2vw,30px)}
.cgs-cf-form .cgs-about-h em{font-style:normal;color:var(--cgs-blue,#1e90ff)}
body.cgs-site #lqd-site-content .cgs-cf-form .frm_primary_label{margin:0 0 8px;font:600 14px var(--cgs-fb,Inter,sans-serif);color:var(--cgs-navy,#071A33)}
body.cgs-site #lqd-site-content .cgs-cf-form .frm_required{color:var(--cgs-blue,#1e90ff)}
body.cgs-site #lqd-site-content .cgs-cf-form .frm_hidden_container .frm_primary_label{visibility:visible}
body.cgs-site #lqd-site-content .cgs-cf-form .frm_fields_container{column-gap:clamp(14px,1.6vw,22px);row-gap:clamp(14px,1.4vw,20px)}
body.cgs-site #lqd-site-content .cgs-cf-form .frm_form_field{margin:0}
body.cgs-site #lqd-site-content .cgs-cf-form input:not([type=checkbox]):not([type=radio]):not([type=submit]):not([type=hidden]):not([type=button]),body.cgs-site #lqd-site-content .cgs-cf-form select,body.cgs-site #lqd-site-content .cgs-cf-form textarea{width:100%;max-width:100%;height:auto;padding:14px 18px;border:1px solid var(--cgs-line,#e1e8f1);border-radius:14px;background-color:var(--cgs-soft,#f3f6fa);font:400 15px/1.4 var(--cgs-fb,Inter,sans-serif);color:var(--cgs-ink,#14243b);box-shadow:none;outline:0;transition:border-color .25s,background-color .25s,box-shadow .25s}
body.cgs-site #lqd-site-content .cgs-cf-form textarea{min-height:150px;resize:vertical}
body.cgs-site #lqd-site-content .cgs-cf-form input::placeholder,body.cgs-site #lqd-site-content .cgs-cf-form textarea::placeholder{color:#8a99ad}
body.cgs-site #lqd-site-content .cgs-cf-form input:focus,body.cgs-site #lqd-site-content .cgs-cf-form select:focus,body.cgs-site #lqd-site-content .cgs-cf-form textarea:focus{border-color:var(--cgs-blue,#1e90ff);background-color:#fff;box-shadow:0 0 0 4px rgba(30,144,255,.14)}
body.cgs-site #lqd-site-content .cgs-cf-form .frm_submit{margin-top:clamp(6px,.8vw,12px)}
body.cgs-site #lqd-site-content .cgs-cf-form .frm_submit button,body.cgs-site #lqd-site-content .cgs-cf-form .frm_submit input[type=submit],body.cgs-site #lqd-site-content .cgs-cf-form .frm_button_submit{display:inline-flex;align-items:center;justify-content:center;width:auto;height:auto;padding:14px 32px;border:2px solid var(--cgs-blue,#1e90ff);border-radius:50px;background:var(--cgs-blue,#1e90ff);color:#fff;font:600 15px var(--cgs-fb,Inter,sans-serif);text-transform:none;letter-spacing:0;box-shadow:none;cursor:pointer;transition:background .3s,border-color .3s}
body.cgs-site #lqd-site-content .cgs-cf-form .frm_submit button:hover,body.cgs-site #lqd-site-content .cgs-cf-form .frm_submit input[type=submit]:hover,body.cgs-site #lqd-site-content .cgs-cf-form .frm_button_submit:hover{background:var(--cgs-navy,#071A33);border-color:var(--cgs-navy,#071A33);color:#fff}
body.cgs-site #lqd-site-content .cgs-cf-form .frm_error,body.cgs-site #lqd-site-content .cgs-cf-form .frm_error_style{font-size:13px;color:#d6372c}
body.cgs-site #lqd-site-content .cgs-cf-form .frm_message{padding:14px 18px;border-radius:14px;background:#eaf4ff;color:var(--cgs-navy,#071A33)}
@media(max-width:1024px){.cgs-cf-in{grid-template-columns:1fr}.cgs-cf-form{order:-1}}
@media(max-width:767px){.cgs-cf-in{padding:0 6vw}}
</style>
	<?php } ?>
<section class="cgs-cf" id="contact-details"><div class="cgs-cf-in">
<div class="cgs-cf-card">
<div class="cgs-cf-top">
<?php foreach ( array( 'addr' => 'ha', 'cont' => 'hc' ) as $k => $hk ) :
	if ( empty( $rows[ $k ] ) ) {
		continue;
	} ?>
<div class="cgs-cf-blk"><h4 class="cgs-cf-t"><?php echo esc_html( $a[ $hk ] ); ?></h4><ul class="cgs-cf-list">
<?php foreach ( $rows[ $k ] as $r ) :
	$ic = cgs_media_url( isset( $r['ic'] ) ? $r['ic'] : '', '' );
	$lk = $url( isset( $r['url'] ) ? $r['url'] : '' );
	$in = ( '' !== $ic ? '<i style="--cgs-ic:url(\'' . esc_url( $ic ) . '\')"></i>' : '' ) . '<span>' . esc_html( isset( $r['tx'] ) ? $r['tx'] : '' ) . '</span>'; ?>
<li><?php echo '' !== $lk ? '<a href="' . $lk . '"' . $ext( $lk ) . '>' . $in . '</a>' : '<span class="cgs-cf-r">' . $in . '</span>'; ?></li>
<?php endforeach; ?>
</ul></div>
<?php endforeach; ?>
</div>
<?php if ( $soc ) : ?>
<div class="cgs-cf-bot"><h4 class="cgs-cf-t"><?php echo esc_html( $a['hs'] ); ?></h4><div class="cgs-cf-soc">
<?php foreach ( $soc as $r ) :
	$ic = cgs_media_url( isset( $r['ic'] ) ? $r['ic'] : '', '' );
	$lk = $url( isset( $r['url'] ) ? $r['url'] : '' );
	if ( '' === $ic ) {
		continue;
	}
	$im = '<i style="--cgs-ic:url(\'' . esc_url( $ic ) . '\')"></i>'; ?>
<?php echo '' !== $lk ? '<a href="' . $lk . '"' . $ext( $lk ) . ' aria-label="' . esc_attr( isset( $r['tx'] ) ? $r['tx'] : '' ) . '">' . $im . '</a>' : '<a>' . $im . '</a>'; ?>
<?php endforeach; ?>
</div></div>
<?php endif; ?>
</div>
<div class="cgs-cf-form">
<?php if ( '' !== trim( (string) $a['eb'] ) ) : ?><h2 class="cgs-cf-eb"><?php echo esc_html( $a['eb'] ); ?></h2><?php endif; ?>
<?php if ( '' !== trim( (string) $a['h3'] ) ) : ?><h3 class="cgs-about-h"><?php echo $h3; ?></h3><?php endif; ?>
<?php echo $form; ?>
</div>
</div></section>
	<?php
	return ob_get_clean();
} );

/* ------------------------------------------------------------------
 * Map — [cgs_map]: one big rounded Google map (embed by address, or paste a Google "Embed a map" URL / iframe code).
 * ---------------------------------------------------------------- */
function cgs_map_defaults() {
	return array(
		'q'     => 'No 2-9, block 9, Jalan Batu Nilam 16, Bandar Bukit Tinggi 2, Klang, Malaysia',
		'embed' => '',
		'title' => 'Concepts Group location',
		'h'     => '520',
		'gray'  => 'no',
	);
}

add_action( 'vc_before_init', function () {
	if ( ! function_exists( 'vc_map' ) ) {
		return;
	}
	$d = cgs_map_defaults();
	vc_map( array(
		'name'     => 'Concepts Map',
		'base'     => 'cgs_map',
		'category' => 'Concepts Group',
		'icon'     => 'icon-wpb-row',
		'params'   => array(
			array( 'type' => 'textfield', 'heading' => 'Address or place to show', 'param_name' => 'q', 'value' => $d['q'] ),
			array( 'type' => 'textarea', 'heading' => 'Or paste a Google "Embed a map" URL / iframe code (overrides the address)', 'param_name' => 'embed', 'value' => '' ),
			array( 'type' => 'textfield', 'heading' => 'Map title (accessibility)', 'param_name' => 'title', 'value' => $d['title'] ),
			array( 'type' => 'textfield', 'heading' => 'Maximum height on desktop (px)', 'param_name' => 'h', 'value' => $d['h'] ),
			array( 'type' => 'dropdown', 'heading' => 'Soft grey map look', 'param_name' => 'gray', 'value' => array( 'No (Google colours)' => 'no', 'Yes (light grey)' => 'yes' ) ),
		),
	) );
} );

add_shortcode( 'cgs_map', function ( $atts ) {
	$d = cgs_map_defaults();
	$a = shortcode_atts( $d, $atts, 'cgs_map' );
	$src = '';
	$raw = trim( html_entity_decode( (string) $a['embed'] ) );
	if ( '' !== $raw ) {
		if ( preg_match( '/src=["\']([^"\']+)["\']/i', $raw, $m ) ) {
			$raw = $m[1];
		}
		$src = esc_url( $raw );
	}
	if ( '' === $src ) {
		$q = '' !== trim( (string) $a['q'] ) ? $a['q'] : $d['q'];
		$src = esc_url( 'https://www.google.com/maps?q=' . rawurlencode( $q ) . '&output=embed' );
	}
	$h = min( 900, max( 240, (int) $a['h'] ) );
	static $once = false;
	ob_start();
	if ( ! $once ) {
		$once = true; ?>
<style>
.cgs-mp{padding:0 5vw clamp(60px,6vw,110px)}
.cgs-mp-box{position:relative;height:clamp(300px,38vw,var(--cgs-mh,520px));border-radius:clamp(18px,2vw,28px);overflow:hidden;background:#eef1f5;box-shadow:0 22px 40px -28px rgba(7,26,51,.35)}
.cgs-mp-box iframe{position:absolute;inset:0;display:block;width:100%;height:100%;border:0}
.cgs-mp-box.g iframe{filter:grayscale(.9) contrast(.95)}
@media(max-width:767px){.cgs-mp{padding:0 6vw clamp(48px,10vw,72px)}}
</style>
	<?php } ?>
<section class="cgs-mp" id="map"><div class="cgs-mp-box<?php echo 'yes' === $a['gray'] ? ' g' : ''; ?>" style="--cgs-mh:<?php echo (int) $h; ?>px"><iframe src="<?php echo $src; ?>" title="<?php echo esc_attr( $a['title'] ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div></section>
	<?php
	return ob_get_clean();
} );
