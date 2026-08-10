<?php
$f = 'resources/views/layouts/tenant.blade.php';
$c = file_get_contents($f);

// Body
$c = str_replace('background: #060d1a;', 'background: #0d1117;', $c);

// Sidebar
$c = preg_replace('/html\.dark \.tenant-sidebar \{[^}]+\}/', 'html.dark .tenant-sidebar { background: #010409; border-right-color: #30363d; }', $c);
$c = preg_replace('/html\.dark \.sidebar-brand \{[^}]+\}/', 'html.dark .sidebar-brand { border-bottom-color: #30363d; }', $c);

// Brand dot
$c = preg_replace('/html\.dark \.brand-dot-s \{[^}]+\}/', 'html.dark .brand-dot-s { background: #2f81f7; box-shadow: 0 0 10px rgba(47,129,247,0.4); }', $c);
$c = preg_replace('/@keyframes pd-dark \{[^}]+\}/', '@keyframes pd-dark { 0%,100%{box-shadow:0 0 8px rgba(47,129,247,0.5)} 50%{box-shadow:0 0 18px rgba(47,129,247,0.8)} }', $c);

// Nav section label
$c = str_replace('html.dark .nav-section-label { color: rgba(255,255,255,0.2); }', 'html.dark .nav-section-label { color: #8b949e; }', $c);

// Nav links
$c = str_replace('html.dark .nav-link { color: rgba(255,255,255,0.55); }', 'html.dark .nav-link { color: #8b949e; }', $c);
$c = str_replace('html.dark .nav-link:hover { background: rgba(255,255,255,0.06); color: #fff; }', 'html.dark .nav-link:hover { background: #161b22; color: #c9d1d9; }', $c);
$c = str_replace('html.dark .nav-link.active { background: rgba(0,212,255,0.1); color: #00d4ff; }', 'html.dark .nav-link.active { background: #161b22; color: #e6edf3; }', $c);
$c = str_replace('html.dark .nav-link.active::before { background: #00d4ff; }', 'html.dark .nav-link.active::before { background: #2f81f7; }', $c);

// Sidebar user
$c = preg_replace('/html\.dark \.sidebar-user \{[^}]+\}/', 'html.dark .sidebar-user { border-top-color: #30363d; }', $c);

// Topbar
$c = preg_replace('/html\.dark \.tenant-topbar \{[^}]+\}/', 'html.dark .tenant-topbar { background: #010409; border-bottom-color: #30363d; }', $c);

// Search
$c = str_replace('html.dark .topbar-search:focus-within { border-color: #00d4ff; box-shadow: 0 0 0 2px rgba(0,212,255,0.12); }', 'html.dark .topbar-search:focus-within { border-color: #2f81f7; box-shadow: 0 0 0 2px rgba(47,129,247,0.12); }', $c);

// Dropdown
$c = preg_replace('/html\.dark \.topbar-dropdown \{[^}]+\}/', 'html.dark .topbar-dropdown { background: #161b22; border-color: #30363d; box-shadow: 0 4px 12px rgba(0,0,0,0.5); }', $c);
$c = str_replace('html.dark .dropdown-item:hover { background: rgba(255,255,255,0.06); color: #fff; }', 'html.dark .dropdown-item:hover { background: #21262d; color: #c9d1d9; }', $c);

// Dropdown Border
$c = str_replace('html.dark .html-dark-border { border-bottom-color: rgba(255,255,255,0.06) !important; }', 'html.dark .html-dark-border { border-bottom-color: #30363d !important; }', $c);

file_put_contents($f, $c);
echo "Tenant layout updated.\n";
