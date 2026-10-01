import re

with open('resources/views/tenant/dashboard.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Make the outer container of the first block the unified wrapper.
# Find: <!-- Header Hero & Quick Info -->
# Followed by: <div class="rounded-2xl bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 p-6 md:p-7 shadow-xs">
header_hero_original = '<div class="rounded-2xl bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 p-6 md:p-7 shadow-xs">'
header_hero_replacement = '<div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 md:p-8 space-y-6 shadow-xs">\n            <div class="pb-6 border-b border-zinc-100 dark:border-zinc-800">'

if header_hero_original in content:
    content = content.replace(header_hero_original, header_hero_replacement, 1)

# Cuplikan tren pembeli:
# Find: class="bg-white dark:bg-[#000000] border border-slate-200/80 dark:border-slate-800 rounded-2xl p-3 sm:px-5 sm:py-3"
cuplikan_original = 'class="bg-white dark:bg-[#000000] border border-slate-200/80 dark:border-slate-800 rounded-2xl p-3 sm:px-5 sm:py-3"'
cuplikan_replacement = 'class="bg-slate-50/50 dark:bg-zinc-900/40 rounded-xl p-3 sm:px-5 sm:py-3 border border-zinc-100 dark:border-zinc-800"'
if cuplikan_original in content:
    content = content.replace(cuplikan_original, cuplikan_replacement, 1)

# Promosi & Bagikan Toko:
# Find: }" class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 md:p-6">
promosi_original = '}" class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 md:p-6">'
promosi_replacement = '}" class="pb-6 border-b border-zinc-100 dark:border-zinc-800">'
if promosi_original in content:
    content = content.replace(promosi_original, promosi_replacement, 1)

# The end of the unified card is after the 6 Metrics grid.
# The 6 metrics grid ends right before the Analitik Pengunjung & Minat Pembeli (which we moved).
# Actually, the 6 Metrics ends exactly where `</div>` corresponds to the grid.
# Let's find "<!-- Section: Analitik Pengunjung & Minat Pembeli" and insert `</div><!-- End Unified Top Card -->` before it.
analitik_marker = "<!-- Section: Analitik Pengunjung & Minat Pembeli (Visitor Traffic, Top Clicked, Top Searches) -->"
idx_analitik = content.find(analitik_marker)
if idx_analitik != -1:
    content = content[:idx_analitik] + "        </div>\n        <!-- End Unified Top Card -->\n\n        " + content[idx_analitik:]

# And we need to add the closing div for the Header Hero wrapper itself?
# The header hero was originally `<div ...> </div>`. We changed `<div ...>` to `<div ...> \n <div pb-6>`. 
# So there's an extra `</div>` needed after Header Hero.
# It ends right before "<!-- Cuplikan Tren Pembeli (Classic Ticker di Bawah Card Banner) -->"
cuplikan_marker = "<!-- Cuplikan Tren Pembeli (Classic Ticker di Bawah Card Banner) -->"
idx_cup = content.find(cuplikan_marker)
if idx_cup != -1:
    content = content[:idx_cup] + "        </div>\n        " + content[idx_cup:]

with open('resources/views/tenant/dashboard.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Modification complete.")
