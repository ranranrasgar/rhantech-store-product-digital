import re

with open('resources/views/tenant/dashboard.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Simplify Metric Cards
colors = ['emerald', 'purple', 'orange', 'amber', 'indigo', 'pink']
for color in colors:
    pattern = f"bg-{color}-500/10 text-{color}-600 dark:text-{color}-400"
    replacement = "bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 shadow-sm"
    content = content.replace(pattern, replacement)

# 2. Simplify Share Buttons (Desktop and Mobile)
share_classes_to_replace = [
    r"bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 font-semibold text-xs border border-emerald-200 dark:border-emerald-800/60",
    r"bg-orange-50 hover:bg-orange-100 dark:bg-orange-950/40 dark:hover:bg-orange-900/50 text-orange-700 dark:text-orange-300 font-semibold text-xs border border-orange-200 dark:border-orange-800/60",
    r"bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/40 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 font-semibold text-xs border border-blue-200 dark:border-blue-800/60",
    r"bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs border border-zinc-200 dark:border-zinc-800",
    r"bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/50 text-purple-700 dark:text-purple-300 font-semibold text-xs border border-purple-200 dark:border-purple-800/60"
]

share_replacement = "bg-white dark:bg-[#000000] hover:bg-zinc-50 dark:hover:bg-zinc-900 text-zinc-700 dark:text-zinc-300 font-semibold text-xs border border-zinc-200 dark:border-zinc-800 shadow-sm"

for sc in share_classes_to_replace:
    content = content.replace(sc, share_replacement)

content = re.sub(r'class="w-3\.5 h-3\.5 fill-current (?:text-emerald-600 dark:text-emerald-400|text-orange-500|text-blue-600|text-zinc-800 dark:text-zinc-100)"', r'class="w-3.5 h-3.5 fill-current text-zinc-600 dark:text-zinc-400"', content)
content = content.replace('text-[15px] text-purple-600 dark:text-purple-400', 'text-[15px] text-zinc-600 dark:text-zinc-400')

# 3. Move "Analitik Pengunjung & Minat Pembeli" above "Sales Trend Chart"
sales_trend_marker = "<!-- Sales Trend Chart: Bulanan & Harian -->"
analitik_marker = "<!-- Section: Analitik Pengunjung & Minat Pembeli (Visitor Traffic, Top Clicked, Top Searches) -->"
main_content_marker = "<!-- Main Content Area: 2 Columns (7:5) -->"

idx_sales = content.find(sales_trend_marker)
idx_analitik = content.find(analitik_marker)
idx_main = content.find(main_content_marker)

if idx_sales != -1 and idx_analitik != -1 and idx_main != -1:
    sales_block = content[idx_sales:idx_analitik]
    analitik_block = content[idx_analitik:idx_main]
    
    # Check if there's any stray closing div/spacing issue
    new_content = content[:idx_sales] + analitik_block + sales_block + content[idx_main:]
    content = new_content

with open('resources/views/tenant/dashboard.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Modification complete.")
