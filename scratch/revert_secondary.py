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

def replace_secondary(filepath):
    if not os.path.exists(filepath):
        return

    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Replace occurrences like text-secondary -> text-primary
    for prefix in prefixes:
        # text-secondary, bg-secondary, etc.
        # But wait, there is no "secondary" tailwind scale (e.g. secondary-500) because "secondary" is used as a color itself in tailwind.config
        # Like text-secondary
        pattern = rf"\b{prefix}-secondary\b"
        content = re.sub(pattern, f"{prefix}-primary", content)
        
        # What about text-secondary/50?
        pattern2 = rf"\b{prefix}-secondary/"
        content = re.sub(pattern2, f"{prefix}-primary/", content)

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"Reverted secondary to primary in {filepath}")

for fp in files_to_revert:
    replace_secondary(fp)
