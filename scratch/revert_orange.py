import os
import re

files_to_revert = [
    'resources/views/layouts/public.blade.php',
    'resources/views/welcome.blade.php',
    'resources/views/contact.blade.php',
    'resources/views/products/index.blade.php',
    'resources/views/products/show.blade.php',
    'resources/views/projects/index.blade.php',
    'resources/views/projects/show.blade.php',
    'resources/views/store/show.blade.php',
    'resources/views/store/hybrid.blade.php',
    'resources/views/store/_partials/_appearance_block.blade.php'
]

prefixes = ['text', 'bg', 'border', 'ring', 'shadow', 'from', 'via', 'to', 'decoration', 'accent', 'caret', 'fill', 'stroke']

def replace_orange(filepath):
    if not os.path.exists(filepath):
        print(f"File not found: {filepath}")
        return

    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # We want to replace {prefix}-orange-{weight} with {prefix}-sky-{weight}
    # Be careful not to replace anything else.
    for prefix in prefixes:
        # e.g., text-orange-500 -> text-sky-500
        # also handles text-orange-500/50 -> text-sky-500/50
        pattern = rf"\b{prefix}-orange-([0-9]+)\b"
        content = re.sub(pattern, rf"{prefix}-sky-\1", content)
        
        # also handle text-orange-50, text-orange-950, etc.
        
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"Reverted orange to sky in {filepath}")

for fp in files_to_revert:
    replace_orange(fp)
