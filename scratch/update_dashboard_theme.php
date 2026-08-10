<?php
$f = 'resources/views/tenant/dashboard.blade.php';
$c = file_get_contents($f);

$c = str_replace('dark:bg-transparent', 'dark:bg-[#0d1117]', $c);
$c = str_replace('dark:bg-white/5', 'dark:bg-[#161b22]', $c);
$c = str_replace('dark:border-white/10', 'dark:border-[#30363d]', $c);
$c = str_replace('dark:divide-white/10', 'dark:divide-[#30363d]', $c);
$c = str_replace('dark:hover:bg-white/10', 'dark:hover:bg-[#21262d]', $c);

// Also fix any text colors if needed, but text-gray-400 / white are usually fine.
// GitHub blue is #2f81f7. Let's see if we had any cyan.
// The primary color in Tailwind config is used, but we can change dark mode specific ones if they exist.

file_put_contents($f, $c);
echo "Dashboard updated.\n";
