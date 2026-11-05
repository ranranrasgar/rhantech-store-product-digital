{{-- Gaya halaman brand. CSS ditulis di sini karena layout toko memakai CSS hasil build. --}}
<style>
    .bp {
        --bp-bg: #f6f7f9;
        --bp-surface: #ffffff;
        --bp-line: #e6e8ec;
        --bp-ink: #0f172a;
        --bp-muted: #64748b;
        --brand-soft: color-mix(in srgb, var(--brand) 11%, var(--bp-surface));
        --brand-text: color-mix(in srgb, var(--brand) 78%, #000);
        background: var(--bp-bg);
        color: var(--bp-ink);
        min-height: 100vh;
    }
    html.dark .bp {
        --bp-bg: #0b0f17;
        --bp-surface: #131924;
        --bp-line: #252e3f;
        --bp-ink: #f1f5f9;
        --bp-muted: #94a3b8;
        --brand-soft: color-mix(in srgb, var(--brand) 22%, var(--bp-surface));
        --brand-text: color-mix(in srgb, var(--brand) 58%, #fff);
    }

    .bp-cover { width: 100%; aspect-ratio: 3 / 1; max-height: 280px; background-size: cover; background-position: center; }
    .bp-shell { max-width: 1240px; margin: 0 auto; padding: 0 16px 32px; }

    /* Identitas */
    .bp-card { position: relative; }
    .bp-avatar {
        width: 84px; height: 84px; margin-top: -42px; border-radius: 22px; overflow: hidden; position: relative;
        border: 4px solid var(--bp-bg); background: var(--brand); color: var(--brand-ink);
        display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 26px;
    }
    .bp-avatar.is-pro { outline: 2px solid #f59e0b; outline-offset: -2px; }
    .bp-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .bp-name { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; margin: 10px 0 0; font-size: 23px; line-height: 1.2; font-weight: 800; letter-spacing: -0.02em; }
    .bp-name .ph-seal-check { color: var(--brand-text); font-size: 20px; }
    .bp-pro { padding: 2px 7px; border-radius: 6px; background: #0f172a; color: #fbbf24; font-size: 10px; font-weight: 800; letter-spacing: 0.04em; }
    .bp-bio { margin: 6px 0 0; color: var(--bp-muted); font-size: 14px; line-height: 1.5; overflow-wrap: anywhere; }
    .bp-meta { display: flex; flex-wrap: wrap; gap: 4px 14px; margin: 10px 0 0; padding: 0; list-style: none; color: var(--bp-muted); font-size: 13px; }
    .bp-meta li { display: flex; align-items: center; gap: 4px; }
    .bp-meta b { color: var(--bp-ink); font-weight: 700; }
    .bp-meta .ph-star { color: #f59e0b; }

    .bp-actions { display: flex; gap: 8px; margin-top: 14px; }
    .bp-btn {
        flex: 1; min-height: 44px; padding: 0 14px; border-radius: 12px; border: 1px solid transparent; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        font-size: 14px; font-weight: 700; white-space: nowrap; transition: filter .15s, background .15s;
    }
    .bp-btn .ph { font-size: 17px; }
    .bp-btn-primary { background: var(--brand); color: var(--brand-ink); }
    .bp-btn-primary:hover { filter: brightness(1.08); }
    .bp-btn-ghost { background: var(--bp-surface); border-color: var(--bp-line); color: var(--bp-ink); }
    .bp-btn-ghost:hover { background: var(--brand-soft); }
    .bp-btn-icon { flex: 0 0 44px; padding: 0; }
    .bp-btn-block { width: 100%; margin-top: 8px; }

    .bp-socials { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 12px; }
    .bp-socials a {
        width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
        background: var(--bp-surface); border: 1px solid var(--bp-line); color: var(--bp-muted); transition: color .15s, border-color .15s;
    }
    .bp-socials a:hover { color: var(--brand-text); border-color: var(--brand); }

    /* Tautan: geser menyamping di ponsel, daftar di desktop */
    .bp-links-wrap { margin-top: 18px; }
    .bp-links-label { margin: 0 0 8px; font-size: 13px; font-weight: 700; color: var(--bp-muted); }
    .bp-links {
        display: flex; gap: 8px; margin: 0 -16px; padding: 0 16px 2px; overflow-x: auto;
        scroll-snap-type: x proximity; scroll-padding-left: 16px; scrollbar-width: none;
    }
    .bp-links::-webkit-scrollbar { display: none; }
    .bp-link {
        flex: 0 0 76%; max-width: 280px; min-height: 64px; padding: 10px; border-radius: 14px; scroll-snap-align: start;
        display: flex; align-items: center; gap: 10px; background: var(--bp-surface); border: 1px solid var(--bp-line);
        color: var(--bp-ink); text-decoration: none; transition: border-color .15s, transform .15s;
    }
    .bp-link:only-child { flex-basis: 100%; max-width: none; }
    .bp-link:hover { border-color: var(--brand); }
    .bp-link:active { transform: scale(0.985); }
    .bp-link-tile {
        width: 44px; height: 44px; flex: none; border-radius: 11px; overflow: hidden; color: #fff;
        display: flex; align-items: center; justify-content: center;
    }
    .bp-link-tile img { width: 100%; height: 100%; object-fit: cover; }
    .bp-link-tile .material-symbols-outlined { font-size: 22px; }
    .bp-link-text { min-width: 0; flex: 1; }
    .bp-link-title { display: block; font-size: 14px; font-weight: 700; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .bp-link-sub { display: block; margin-top: 1px; font-size: 12px; color: var(--bp-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .bp-link-go { flex: none; color: var(--bp-muted); font-size: 16px; }

    /* Etalase */
    .bp-main { min-width: 0; margin-top: 24px; }
    .bp-section { margin-bottom: 24px; }
    .bp-title { margin: 0 0 10px; font-size: 18px; font-weight: 800; letter-spacing: -0.01em; }
    .bp-title small { margin-left: 4px; font-size: 13px; font-weight: 600; color: var(--bp-muted); }
    .bp-vouchers { display: grid; gap: 12px; }

    .bp-tools { display: flex; gap: 8px; margin-bottom: 10px; }
    .bp-search { flex: 1; min-width: 0; position: relative; }
    .bp-search .ph { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--bp-muted); font-size: 17px; pointer-events: none; }
    .bp-search input, .bp-sort {
        width: 100%; height: 44px; border-radius: 12px; border: 1px solid var(--bp-line); background: var(--bp-surface);
        color: var(--bp-ink); font-size: 16px; box-shadow: none;
    }
    .bp-search input { padding: 0 12px 0 36px; }
    .bp-search input:focus, .bp-sort:focus { outline: 2px solid var(--brand); outline-offset: -1px; border-color: transparent; box-shadow: none; }
    .bp-sort { flex: 0 0 auto; width: auto; max-width: 42%; padding: 0 28px 0 10px; font-size: 13px; font-weight: 600; }

    .bp-chips { display: flex; gap: 6px; margin: 0 -16px 14px; padding: 0 16px; overflow-x: auto; scrollbar-width: none; }
    .bp-chips::-webkit-scrollbar { display: none; }
    .bp-chip {
        flex: none; padding: 7px 13px; border-radius: 999px; border: 1px solid var(--bp-line); background: var(--bp-surface);
        color: var(--bp-muted); font-size: 13px; font-weight: 600; text-decoration: none; white-space: nowrap;
    }
    .bp-chip.is-active { background: var(--brand); border-color: var(--brand); color: var(--brand-ink); }

    .bp-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .bp-product {
        position: relative; display: flex; flex-direction: column; overflow: hidden; border-radius: 16px; text-decoration: none;
        background: var(--bp-surface); border: 1px solid var(--bp-line); color: var(--bp-ink); transition: border-color .15s, transform .15s;
    }
    .bp-product:hover { border-color: var(--brand); }
    .bp-product:active { transform: scale(0.985); }
    .bp-thumb { position: relative; aspect-ratio: 1; background: var(--brand-soft); overflow: hidden; }
    .bp-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
    .bp-product:hover .bp-thumb img { transform: scale(1.04); }
    .bp-thumb-empty { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--brand-text); font-size: 34px; }
    .bp-off { position: absolute; top: 8px; left: 8px; padding: 2px 7px; border-radius: 7px; background: #e11d48; color: #fff; font-size: 11px; font-weight: 800; }
    .bp-info { display: flex; flex-direction: column; flex: 1; padding: 10px 11px 12px; }
    .bp-pname {
        margin: 0; font-size: 14px; line-height: 1.35; font-weight: 600;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .bp-stars { display: flex; align-items: center; gap: 3px; margin-top: 4px; font-size: 12px; color: var(--bp-muted); }
    .bp-stars .ph { color: #f59e0b; }
    .bp-price { margin-top: auto; padding-top: 8px; }
    .bp-price b { display: block; font-size: 16px; font-weight: 800; color: var(--brand-text); }
    .bp-price s { font-size: 12px; color: var(--bp-muted); }

    .bp-empty { padding: 36px 16px; text-align: center; color: var(--bp-muted); font-size: 14px; border: 1px dashed var(--bp-line); border-radius: 16px; }
    .bp-empty a { display: inline-block; margin-top: 8px; color: var(--brand-text); font-weight: 700; }
    .bp-pages { margin-top: 18px; }
    .bp-powered { margin: 8px 0 0; text-align: center; font-size: 12px; }
    .bp-powered a { color: var(--bp-muted); text-decoration: none; }

    @media (min-width: 640px) {
        .bp-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
        .bp-vouchers { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (min-width: 1024px) {
        .bp-cover { max-height: 240px; aspect-ratio: auto; height: 240px; }
        .bp-shell {
            display: grid; grid-template-columns: 340px minmax(0, 1fr); gap: 32px; align-items: start;
            padding: 0 32px 48px;
        }
        .bp-side { position: sticky; top: 84px; margin-top: -72px; z-index: 2; }
        .bp-card {
            padding: 20px; background: var(--bp-surface); border: 1px solid var(--bp-line); border-radius: 22px;
            max-height: calc(100vh - 104px); overflow-y: auto; scrollbar-width: thin;
        }
        .bp-avatar { margin-top: 0; border: 0; width: 88px; height: 88px; }
        .bp-btn-ghost { background: transparent; }
        .bp-links { flex-direction: column; margin: 0; padding: 0; overflow: visible; }
        .bp-link { flex: none; max-width: none; background: var(--bp-bg); border-color: transparent; }
        .bp-socials a { background: transparent; }
        .bp-main { margin-top: 28px; }
        .bp-chips { margin-left: 0; margin-right: 0; padding: 0; flex-wrap: wrap; overflow: visible; }
        .bp-sort { max-width: none; }
    }

    @media (min-width: 1200px) {
        .bp-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }

    @media (prefers-reduced-motion: reduce) {
        .bp-link, .bp-product, .bp-thumb img { transition: none; }
    }
</style>
