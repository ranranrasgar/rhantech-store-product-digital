<?php
$f = 'resources/views/tenant/store/index.blade.php';
$c = file_get_contents($f);

// Backgrounds
$c = str_replace('dark:bg-transparent', 'dark:bg-[#0d1117]', $c);
$c = str_replace('dark:bg-[#0a1628]', 'dark:bg-[#010409]', $c); // Navigation tabs container
$c = str_replace('dark:bg-[#0a1628]/95', 'dark:bg-[#010409]/95', $c); // Sticky bottom bar
$c = str_replace('dark:bg-white/5', 'dark:bg-[#161b22]', $c); // Card backgrounds

// Borders
$c = str_replace('dark:border-white/10', 'dark:border-[#30363d]', $c);
$c = str_replace('dark:border-white/20', 'dark:border-[#30363d]', $c);

// Cyan accents -> GitHub blue (#2f81f7)
$c = str_replace('dark:border-[#00d4ff]', 'dark:border-[#2f81f7]', $c);
$c = str_replace('dark:text-[#00d4ff]', 'dark:text-[#2f81f7]', $c);
$c = str_replace('dark:bg-[#00d4ff]', 'dark:bg-[#2f81f7]', $c);
$c = str_replace('dark:hover:bg-[#00b3cc]', 'dark:hover:bg-[#1f6feb]', $c);
$c = str_replace('dark:focus:border-[#00d4ff]', 'dark:focus:border-[#2f81f7]', $c);
$c = str_replace('dark:focus:ring-[#00d4ff]/20', 'dark:focus:ring-[#2f81f7]/20', $c);
$c = str_replace('dark:file:bg-[#00d4ff]/10', 'dark:file:bg-[#2f81f7]/10', $c);
$c = str_replace('dark:file:text-[#00d4ff]', 'dark:file:text-[#2f81f7]', $c);
$c = str_replace('dark:hover:file:bg-[#00d4ff]/20', 'dark:hover:file:bg-[#2f81f7]/20', $c);

file_put_contents($f, $c);
echo "Store index updated.\n";
