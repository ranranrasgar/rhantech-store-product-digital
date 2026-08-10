<?php
$f = 'resources/views/tenant/dashboard.blade.php';
$c = file_get_contents($f);

// 1. Main wrapper background
$c = str_replace('bg-surface-container-lowest dark:bg-[#0d1117]', 'bg-surface-container-lowest dark:bg-transparent', $c);

// 2. Card backgrounds
$c = str_replace('bg-surface dark:bg-[#161b22]', 'bg-surface dark:bg-white/5', $c);

// 3. Borders
$c = str_replace('dark:border-[#30363d]', 'dark:border-white/10', $c);

// 4. Dividers
$c = str_replace('dark:divide-[#30363d]/50', 'dark:divide-white/10', $c);

// 5. Hover states in list items
$c = str_replace('dark:hover:bg-[#0d1117]', 'dark:hover:bg-white/10', $c);

// 6. Promo box backgrounds
$c = str_replace('dark:bg-[#0d1117]', 'dark:bg-white/5', $c);

file_put_contents($f, $c);
echo "Dashboard colors updated.\n";
